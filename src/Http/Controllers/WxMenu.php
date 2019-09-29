<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
/**
 * 微信菜单
 */
class WxMenu extends Base
{
	public $app;
	public function __construct(){
		$config = [
	    'app_id' => config('manager.wx.appid'),
	    'secret' => config('manager.wx.appsecret'),
	    'token'	 => config('manager.wx.token'),
	];
	$this->app = Factory::officialAccount($config);
	}

	public function message(){
		
		return Bear::make('layout')->wxmenu();

	}
	
}
