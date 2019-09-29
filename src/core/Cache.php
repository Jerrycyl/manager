<?php
namespace cylcode\manager\core;
use Illuminate\Support\Facades\Cache as c;

class Cache 
{

     
     public static $scence = 'system';
     // public $key;
     // public function __construct(){

     // }
     /**
      * [set 设置缓存]
      * @Author   Jerry                    (wx621201)
      * @DateTime 2019-06-18T09:23:44+0800
      * @Example  eg:
      */
     public static function set($key,$value=null,$minutes='60'){
     	$key = self::$scence.':'.$key;
     	return c::add(self::_getKey(), $value, $minutes);
     }
     /**
      * [get 获取缓存]
      * @Author   Jerry                    (wx621201)
      * @DateTime 2019-06-18T09:34:38+0800
      * @Example  eg:
      * @param    [type]                   $key       [description]
      * @param    string                   $default   [description]
      * @return   [type]                              [description]
      */
     public static function get($key,$default=''){
     	// $key = 
     	return c::get(self::_getKey(), $default);
     }
     /**
      * [_getKey description]
      * @Author   Jerry                    (wx621201)
      * @DateTime 2019-06-18T09:37:45+0800
      * @Example  eg:
      * @param    [type]                   $key       [description]
      * @return   [type]                              [description]
      */
     private static function _getKey($key){
     	return self::$scence.':'.$key;
     }
}
