<?php

namespace CrossWords;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Words extends Model
{
    protected $table = 'words';
    protected $fillable = ['word', 'length'];

    public static function find(array $letters, array $exclude = [])
    {
    	foreach ($letters as &$letter)
    		$letter = $letter ? $letter : '[a-z]{1}';

    	$letters = implode('', $letters);

        $where = ["words.word REGEXP '^$letters$'"];

        if($exclude) {
            foreach ($exclude as &$item) 
                $item = "'$item'";

            $exclude = implode(',', $exclude);
            $where[] = "words.word not in ($exclude)";
        }

    	$word = DB::table('words')
            ->whereRaw(implode(' and ', $where))
            ->inRandomOrder()
            ->first();

    	if(!$word)
    		return false;

    	return $word->word;
    }

    public static function minLength()
    {
    	return DB::table('words')
	    	->select(DB::raw('MIN(LENGTH(word)) as length'))
	    	->get()
	    	->first()
	    	->length;
    }

    public static function maxLength()
    {
    	return DB::table('words')
	    	->select(DB::raw('MAX(LENGTH(word)) as length'))
	    	->get()
	    	->first()
	    	->length;
    }
}
