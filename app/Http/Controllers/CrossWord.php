<?php

namespace CrossWords\Http\Controllers;

use Illuminate\Http\Request;
use CrossWords\Words;
use CrossWords\States;

class CrossWord extends Controller
{
    private $width;
	private $heigth;
	private $difficulty;
	private $crossword;
	private $dao;

	private $empty;
	private $word_num;
	private $cursor;
	private $cursor_new;
	private $direction;
	private $last_direction;
	private $list;
	
	public $state;

	function __construct( $width = 12, $heigth = 16, $difficulty = 0, $state = false )
	{
		if($state) {
			$state = States::where('id', $state)->first();

			$this->setWidth( $state['width'] );
			$this->setHeigth( $state['heigth'] );
			$this->setDifficulty( $state['difficulty'] );
			$this->set_crossword( json_decode($state['vector']) );

			$this->empty = $state['empty'];
			$this->word_num = $state['word_num'];
			$this->cursor = json_decode($state['cursor']);
			$this->direction = $state['direction'];
			$this->list = json_decode($state['list']);
			$this->state = $state['id'];
		}else{
			$this->setWidth( $width );
			$this->setHeigth( $heigth );
			$this->setDifficulty( $difficulty );	
			$this->set_crossword( $this->create_vector() );

			$this->empty = $width * $heigth;
			$this->word_num = 0;
			$this->list = [];
			$this->cursor = $this->cursor_new = json_decode( json_encode( [ 'vertical' => 0, 'horizontal' => 0 ] ) );
		}
	}

	public function getWidth() : int
	{
		return $this->width;
	}

	public function getHeight() : int
	{
		return $this->heigth;
	}

	private function randWordLength() 
	{
		$max_space = $this->direction ? $this->heigth : $this->width;
		$max_available = Words::maxLength();

		$length = rand( Words::minLength(), $max_space<$max_available ? $max_space : $max_available );

		if($this->isVertical() && ($this->heigth - $this->cursor->vertical - $length)<0)
			$length = $this->heigth - $this->cursor->vertical;

		elseif(!$this->isVertical() && ($this->width - $this->cursor->horizontal - $length)<0)
			$length = $this->width - $this->cursor->horizontal;

		$this->word_length = $length;
	}

	private function pointDirection() 
	{
		$this->last_direction = $this->direction;
		$this->direction = rand( 0, 1 );
	}

	private function isVertical()
	{
		return $this->direction==1;
	}

	private function length() {
		return rand( Words::minLength(), Words::maxLength() );
	}

	private function setWidth( int $width ) 
	{
		if( !is_numeric( $width ) )
			return false;

		if( $width<0 )
			return false;

		$this->width = $width;
	}

	private function setHeigth( $heigth ) 
	{
		if( !is_numeric( $heigth ) )
			return false;

		if( $heigth<0 )
			return false;

		$this->heigth = $heigth;
	}

	private function setDifficulty( $difficulty ) 
	{
		if( !is_numeric( $difficulty ) )
			return false;

		if( $difficulty<0 )
			return false;

		$this->difficulty = $difficulty;
	}

	private function set_crossword( array $crossword ) 
	{
		$this->crossword = $crossword;
	}

	private function set_empty( int $empty ) 
	{
		$this->empty = $empty;
	}

	private function create_vector() 
	{
		return array_fill( 0, $this->heigth, array_fill( 0, $this->width, '') );
	}

	public function get_vector() 
	{
		return $this->crossword;
	}

	private function get_empty( int $empty ) 
	{
		return $this->empty;
	}

	private function getFields( int $v, int $h, int $v_inc, int $h_inc ) {		
		$letters = [];

		for( $i=0; $i<$this->word_length; $i++ ) {
			if(isset($this->crossword[ $v ][ $h ]))
				$letters[] = $this->crossword[ $v ][ $h ];
			else
				dd(['$v' => $v, '$h' => $h, '$v_inc' => $v_inc, '$h_inc' => $h_inc]);

			$v += $v_inc;
			$h += $h_inc;
		}

		return $letters;
	}

	private function setFields( int $v, int $h, int $v_inc, int $h_inc, string $word ) {
		for( $i=0; $i<$this->word_length; $i++ ) {
			$letter = substr( $word, $i, 1 );
			$this->crossword[ $v ][ $h ] = $letter;

			$v += $v_inc;
			$h += $h_inc;
		}

		return true;
	}

	private function getOpositeCursor( $v = false, $h = false, $use_word_length = true ) 
	{
		return json_decode( json_encode( [ 
			'vertical' => ($this->heigth - 1) - ( $v!==false ? $v : $this->cursor->vertical ) - ( $use_word_length ? ($this->isVertical() ? $this->word_length - 1 : 0) : 0), 
			'horizontal' => ($this->width - 1) - ( $h!==false ? $h : $this->cursor->horizontal ) - ( $use_word_length ? ($this->isVertical() ? 0 : $this->word_length - 1) : 0)
		] ) );
	}

	private function defineCursor()
	{
		foreach ( $this->crossword as $rn => $row ) {
			foreach ($row as $cn => $col) {
				if( $col!==false && empty( $col ) ) {
					return $this->cursor = json_decode( json_encode( [ 'vertical' => $rn, 'horizontal' => $cn ] ) );
				}
			}
		}
	}

	private function setEmptyValue()
	{
		$oposite = $this->getOpositeCursor( false, false, false );
		$this->crossword[ $this->cursor->vertical ][ $this->cursor->horizontal ] = false;
		$this->crossword[ $oposite->vertical ][ $oposite->horizontal ] = false;
	}

	private function sortEmpty() 
	{
		if(rand(0, 100)>=75) {
			$this->setEmptyValue();
			$this->defineCursor();
		}
	}

	private function getFirstPossible($v, $h, $v_dec, $h_dec, $retry) {
		$col_value = false;

		do {
			$v -= $v_dec;
			$h -= $h_dec;

			$col_value = $this->crossword[ $v ][ $h ] ?? false;
		}while($col_value!==false);

		$v += $v_dec;
		$h += $h_dec;

		if($v_dec)
			$v += $retry;

		if($h_dec)
			$h += $retry;


		return json_decode( json_encode( [ 'vertical' => $v, 'horizontal' => $h ] ) );
	}

	private function placeAWord()
	{
		$this->pointDirection();
		$this->randWordLength();

		$success = false;
		$retry = 0;

		while (!$success) {
			$current = $this->getFirstPossible(
				$this->cursor->vertical,
				$this->cursor->horizontal,
				$this->isVertical() ? 1 : 0,
				$this->isVertical() ? 0 : 1,
				$retry++
			);

			if($retry>1 && $current==$this->cursor)
				return;

			$oposite = $this->getOpositeCursor(
				$current->vertical,
				$current->horizontal
			);

			if($current->vertical<0 || $current->horizontal<0 || $oposite->vertical<0 || $oposite->horizontal<0) {
				$this->randWordLength();
				continue;
			}

			$fields = $this->getFields( 
				$current->vertical, 
				$current->horizontal,
				$this->isVertical() ? 1 : 0,
				$this->isVertical() ? 0 : 1
			);

			$oposite_fields = $this->getFields( 
				$oposite->vertical, 
				$oposite->horizontal,
				$this->isVertical() ? 1 : 0,
				$this->isVertical() ? 0 : 1
			);

			$word = Words::find( $fields, $this->list );
			$oposite_word = Words::find( $oposite_fields, $this->list );
			$success = $word && $oposite_word;

			if($success) {
				$this->setFields(  
					$current->vertical, 
					$current->horizontal,
					$this->isVertical() ? 1 : 0,
					$this->isVertical() ? 0 : 1, 
					$word 
				);

				$this->setFields(  
					$oposite->vertical, 
					$oposite->horizontal,
					$this->isVertical() ? 1 : 0,
					$this->isVertical() ? 0 : 1, 
					$oposite_word
				);
			}
		}

	}

	public function fill() 
	{
		// for ($i=0; $i < 2; $i++) { 
			$this->defineCursor();
			$this->sortEmpty();

			$this->placeAWord();

			$states = new States();
			$states->fill([
				'width' => $this->width,
				'heigth' => $this->heigth,
				'difficulty' => $this->difficulty,
				'vector' => json_encode($this->crossword),
				'empty' => $this->empty,
				'word_num' => $this->word_num,
				'cursor' => json_encode($this->cursor),
				'cursor_new' => json_encode( [ 'vertical' => 0, 'horizontal' => 0 ] ),
				'direction' => $this->direction,
				'list' => json_encode($this->list),
			]);

			$states->save();
			$this->state = $states['id'];
		// }
	}

	public function get()
	{
		return $this->crossword;
	}
}
