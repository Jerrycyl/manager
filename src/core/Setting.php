<?php
namespace cylcode\manager\core;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache as c;
use cylcode\tools\arr\Arr as arr;
class Setting 
{

     private static $tag = 'system::setting';
     /**
      * [get 获取后台配置项]
      * @Author   Jerry                    (wx621201)
      * @DateTime 2019-06-18T09:23:44+0800
      * @Example  eg:
      */
     public static  function get($key){
      $minutes = 500;
      // self::clean();
      if(!$key) throw new Exception("key can not be null or empty", 1);
        if (!c::has($key)) {
            $setting = c::remember(self::$tag, $minutes, function () {
                $data = arr::objToArray(DB::table('setting')->get());
                $newData = [];
                foreach ($data as  $v) {
                  $newData[$v['key']] = $v['value'];
                }
                return $newData;
            });
        }else{
            $setting = c::get(self::$tag);
        }
       return $setting[$key];

     }
     /**
      * [clean 清掉配置项]
      * @Author   Jerry                    (wx621201)
      * @DateTime 2019-06-18T09:49:51+0800
      * @Example  eg:
      * @return   [type]                   [description]
      */
     public static function clean(){
        return c::forget(self::$tag);
     }
}
