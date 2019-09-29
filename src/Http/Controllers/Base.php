<?php

namespace  cylcode\manager\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use cylcode\tools\arr\Arr;
use Illuminate\Http\Request;
use cylcode\bear\Bear;
use Validator;
use cylcode\manager\Http\data\Base as dataBase;
class Base extends dataBase
{
	protected $table = '';
	
}
