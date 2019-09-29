<?php

namespace  cylcode\manager\Api\xcx;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use cylcode\tools\arr\Arr;
use Illuminate\Http\Request;
use cylcode\bear\Bear;
use Validator;
use EasyWeChat\Factory;
use cylcode\manager\Http\data\Base as dataBase;
class Base extends dataBase
{
	public $app;
	public function __construct(){
		$config = [
		    'app_id' => config('setting.xcx_appid'),
		    'secret' => config('setting.xcx_appsecret'),
		    'response_type' => 'array',
		    'log' => [
		        'level' => 'debug',
		        'file' => APP_PATH.'storage/logs/wechat.log',
		    ],
		];
		$this->app = Factory::miniProgram($config);
	}

}
