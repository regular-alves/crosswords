<?php

namespace CrossWords;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = 'states';
    protected $fillable = ['width', 'heigth', 'difficulty', 'vector', 'empty', 'word_num', 'cursor', 'cursor_new', 'direction', 'list'];
}
