<?php

namespace CrossWords\Http\Controllers;

use Illuminate\Http\Request;

class Page extends Controller
{
    public function home()
    {	
    	$crossword = new CrossWord( 18, 10, 1, isset($_GET['states']) ? $_GET['states'] : 0 );
    	
    	$crossword->fill();

    	return View('crossword', ['crosswords' => $crossword->get(), 'states' => $crossword->state]);
    }
}
