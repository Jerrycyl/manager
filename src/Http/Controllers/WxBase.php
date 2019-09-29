<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
/**
 * 任务管理
 */
class WxBase extends Base
{
	public $app;
	public function __construct(){
		$config = [
	    'app_id' => config('setting.wx_appid'),
	    'secret' => config('setting.wx_appsecret'),
	    'token'	 => config('setting.wx_token'),
	];
	$this->app = Factory::officialAccount($config);
	}
	
}
