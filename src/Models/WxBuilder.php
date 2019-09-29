<?php
namespace cylcode\manager\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use cylcode\tools\arr\Arr;
class WxBuilder extends Model
{
   public $timestamps = false;
   protected $table = 'wx_builder';
   /**
    * [fKeywordToBuilder 根据关键字，返出相关的所有信息]
    * @Author   Jerry                    (wx621201)
    * @DateTime 2019-06-26T16:20:47+0800
    * @Example  eg:
    * @param    [type]                   $keyword   [description]
    * @return   [type]                              [description]
    */
   public static function fKeywordToBuilder($keyword){
   		$data = self::get()->toArray();
   		if(!$data) return [];
   		$returnData = [];
   		foreach ($data as $key => $v) {
   			##查找值
   			if(!$v['builder_table']) continue;
   			$buildData = DB::table($v['builder_table'])->where([$v['keyword_field']=>$keyword])->get();
   			// dump($buildData);
   			// die;
   		}

   		// dump($data);
   }
   /**
    * [getBuilderInfo description]
    * @Author   Jerry                    (wx621201)
    * @DateTime 2019-06-26T16:25:38+0800
    * @Example  eg:
    * @return   [type]                   [description]
    */
   private static function _getBuilderInfo(){

   }  
}
