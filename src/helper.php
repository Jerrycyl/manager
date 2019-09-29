<?php
use cylcode\manager\core\Setting as s;
use Illuminate\Support\Facades\DB;
use cylcode\tools\arr\Arr;
if(!function_exists('setting')){
	/**
	 * [setting 基本配置信息]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-21T15:08:55+0800
	 * @Example  eg:
	 * @param    [type]                   $key       [description]
	 * @return   [type]                              [description]
	 */
	function setting($key){
		return s::get($key);
	}
}

if(!function_exists('checkVar')){
	/**
	 * [checkVar 检测变量是否存在]
	 * @Author   Jerry                    (c84133883)
	 * @DateTime 2019-08-28T09:26:56+0800
	 * @Example  eg:
	 * @param    [type]                   $var        [description]
	 * @return   [type]                               [description]
	 */
	function checkVar($var){
		return isset($$var)?$$var:'';
	}
}


if(!function_exists('getAttachment')){
	/**
	 * [getAttachment 获得附件信息]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-21T15:11:53+0800
	 * @Example  eg:
	 * @param    [type]                   $id        [description]
	 * @return   [type]                              [description]
	 */
	function getAttachment($id,$field=null){
		if(!is_numeric($id)) return $id;
		$data = Arr::objToArray(DB::table('admin_attachment')->where(['id'=>$id])->first());
		$data['path'] = ($data['mold']=='local'?'':setting('aliyun_host')).'/'.$data['path'];
		if($field=='path')return $data['path'];
		return $data;
		
	}
}

if(!function_exists('sql')){
	/**
	 * [sql 执行SQL语句]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-21T15:11:53+0800
	 * @Example  eg:
	 * @param    [type]                   $id        [description]
	 * @return   [type]                              [description]
	 */
	function sql($sql){
		if(!$sql) return; 
		$data = DB::select($sql);
		if(!$data) return [];
		$data = Arr::selectSearchForMat(Arr::objToArray($data));

		return $data;
		
	}
}

if(!function_exists('arr')){
	/**
	 * [arr 数组处理]
	 * @Author   Jerry                    (c84133883)
	 * @DateTime 2019-08-08T17:22:32+0800
	 * @Example  eg:
	 * @return   [type]                   [description]
	 * eg 	jijing=基金账务,juan=捐款信息
	 */
	function arr($str){
		if(!$str) return [];
		##全角转半角
		$str = sbc2Dbc($str);
		$formate = explode(",", $str);
		$arr = [];
		foreach ($formate as $k=> $v) {
			$vFormat = explode("=", $v);
			$arr[($vFormat[0]?$vFormat[0]:$k)] = ($vFormat[1]?$vFormat[1]:'');
		}
		// die;
		return $arr;
	}
}

if(!function_exists('sbc2Dbc')){
	/**
	 * [sbc2Dbc 全角转半角]
	 * @Author   Jerry                    (c84133883)
	 * @DateTime 2019-08-08T17:27:34+0800
	 * @Example  eg:
	 * @param    [type]                   $str        [description]
	 * @return   [type]                               [description]
	 */
    function sbc2Dbc($str){
    	 $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4','５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E','Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O','Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T','Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y','Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd','ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i','ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n','ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z','（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[','】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']','‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<','》' => '>','％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-','：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',     '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|', '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"','　' => ' ');  
    	 
     return strtr($str, $arr);  
    	 
    }
}

if(!function_exists('msubstr')){

	/**
 * 字符串截取，支持中文和其他编码
 * @param  [string]  $str     [字符串]
 * @param  integer $start   [起始位置]
 * @param  integer $length  [截取长度]
 * @param  string  $charset [字符串编码]
 * @param  boolean $suffix  [是否有省略号]
 * @return [type]           [description]
 */
function msubstr($str, $start=0, $length=50, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr")) {
          if($suffix&&mb_strlen($str,'UTF-8')>$length){
            return mb_substr($str, $start, $length, $charset)."...";
          }else{
            return mb_substr($str, $start, $length, $charset);
          }
        
    } elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));

    if($suffix) {
        return $slice."......";
    }
    return $slice;
}

}


/**
 * [xunSearch xunSearch处理]
 * @Author   Jerry
 * @DateTime 2018-05-30T11:34:04+0800
 * @Example  eg:
 * @return   [type]                   [description]
 */
function xunSearch($keyword,$index='blog'){
    if(!$keyword) return;
    require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
    $xs = new \XS($index);    // demo  为项目名称，配置文件是：$sdk/app/demo.in i
    // $index = $xs->index;   //  获取索引对象
    $search = $xs->search;   //  获取搜索对象
    $search->setLimit(20); 
    $docs = $search->setQuery()->search($keyword);
    // show($docs);
    $info = [];
    foreach ($docs as  $doc) {
            $info[$doc->id]['is_top'] = $doc->is_top;
            $info[$doc->id]['password'] = $doc->password;
            $info[$doc->id]['title'] = $search->highlight($doc->title);//高亮处理标题 
            $info[$doc->id]['href'] = '/b_d@'.encode($doc->c_id).'@'.encode($doc->id).'.html';//高亮处理标题 
            $info[$doc->id]['message'] = $search->highlight(strip_tags(htmlspecialchars_decode($doc->message)));//高亮处理标题 
       }
  return $info;
}

/**
 * [self_word  PHP分词 lizhichao/VicWord]
 * @Author   Jerry                    (c84133883)
 * @DateTime 2019-09-09T17:44:08+0800
 * @Example  eg:
 * @param    [type]                   $keyword    [description]
 * @return   [type]                               [description]
 */
function vic_word($keyword){
// 	getWord 长度优先切分 。最快
// getShortWord 细粒度切分。比最快慢一点点
// getAutoWord 自动切分 (在相邻词做了递归) 。效果最好
	$fc = new \Lizhichao\Word\VicWord('igb');
	return $fc->getAutoWord($keyword);

}