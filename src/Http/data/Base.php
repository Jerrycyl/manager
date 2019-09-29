<?php

namespace  cylcode\manager\Http\data;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use cylcode\tools\arr\Arr;
use Illuminate\Http\Request;
use cylcode\bear\Bear;
use Validator;
class Base extends Controller
{
	
	public function save($post){
		$where = $post['where'];
		$table = $post['do_table'];
		$post['create_time'] = time();
		$post['update_time'] = time();
		$post  = $this->fileterField($post,$table);##过滤掉没有数据字段的内容
		$post  = $this->doValidate($post,$table);##规则验证
		$post  = $this->beforeInsert($post,$table);##入库前的数据处理
		if($where){
			if(DB::table($table)->where($where)->count()){
				if(isset($post['create_time'])) unset($post['create_time']);##更新数据，不处理创建时间
				return DB::table($table)->where($where)->update($post);
			}else{
				if(isset($post['update_time'])) unset($post['update_time']);##新建数据，不处理更新时间
				return DB::table($table)->insertGetId($post);
			}
		}else{
			return DB::table($table)->insertGetId($post);
		}
		
		
	}
	/**
	 * [paginate 分页处理]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-21T10:36:55+0800
	 * @Example  eg:
	 * @param    [type]                   $table     [description]
	 * @param    integer                  $limit     [description]
	 * @return   [type]                              [description]
	 */
	public function paginate($table,$limit=50){
		$where = Bear::table()->getSearch();
	     $query = DB::table($table)->select('*');
	     if($where){
	      foreach ($where as $k => $v) {
	            $query = $query->orWhere($v[0],$v[1],$v[2]);
	      }
	     }
	     $request = $query->select('*')->orderBy($this->getPk($table),'desc')->paginate($limit);
	     $data = Arr::objToArray($request);
	     $data['html'] = $request;
	     return $data;
	}
	/**
	 * [validate 验证规则]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-30T09:08:18+0800
	 * @Example  eg:
	 * @param    [type]                   $post      [description]
	 * @param    [type]                   $table     [description]
	 * @return   [type]                              [description]
	 */
	public function doValidate($post,$table){
		if(!$post) return $post;
		$role = Arr::objToArray(DB::table('manager_fastsql')->where(['table'=>$table])->where('role','!=','')->select('alias_name','role','field')->get());
		if(!$role) return $post;
		$roleData = [];
		$attrbute = [];
		foreach ($role as $v) {
			$roleData[$v['field']] = $v['role'];
			$attrbute[$v['field']] = $v['alias_name'];
		}
		$validator = Validator::make($post, $roleData,[],$attrbute);
		if($validator->fails()){
			errorJson(-1,$validator->errors()->first());
		}
		return $post;
	}
	/**
	 * [beforeInsert 数据插入前处理]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-29T09:58:38+0800
	 * @Example  eg:
	 * @param    [type]                   $post      [description]
	 * @return   [type]                              [description]
	 */
	public function beforeInsert($post,$table){
		if(!$post) return $post;
		##当前表的规则
		$fieldsInfo = Arr::objToArray(DB::table('manager_fastsql')->where(['table'=>$table])->where('insert_before','!=','')->whereIn('field',array_keys($post))->select('insert_before','field')->get());
		if(empty($fieldsInfo)||!$fieldsInfo) return $post;
		foreach ($fieldsInfo as $key => $v) {
				$post[$v['field']] = $this->_doFunc($v['insert_before'],$post[$v['field']]);
		}
		// dump($fieldsInfo);
		return $post;
	}
	/**
	 * [_doFunc 数据行函数处理]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-30T10:50:14+0800
	 * @Example  eg:
	 * @param    [type]                   $strFunc [函数串，多个用,号隔开]
	 * @param    [type]                   $string        [需要处理的字符串]
	 * @return   [type]                                  [description]
	 */
	private function _doFunc($strFunc,$string){
		if(!$string) return $string;
		$funs = explode(',', $strFunc);
		foreach ($funs as $vfun) {
			##检查函数是否存在
			if(function_exists($vfun)){
				$string = $vfun($string);
			}else{
				throw new \Exception("函数{$vfun}不存在，请先创建函数",1);
				
			}
		}
		return $string;
	}
	/**
	 * [afterSelect 查出数据处理]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-29T09:58:29+0800
	 * @Example  eg:
	 * @param    [type]                   $data      [description]
	 * @return   [type]                              [description]
	 */
	public function afterSelect($data){

	}
	// public function _db($table){
		// return DB::table($table);
	// }
	/**
	 * [fileterField 过滤掉多余的字段]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-15T17:12:40+0800
	 * @Example  eg:
	 * @param    [type]                   $post      [description]
	 * @return   [type]                              [description]
	 */
	public function fileterField($post,$table){
		##当前表的字段
		$sql = "SHOW FULL COLUMNS FROM {$table}";
		$desc = DB::select($sql);
	    $fieldInfo = [];
	    ##查询字段信息
	    $columns =  collect($desc)->map(function ($item) {
	        return (array)$item;
	    });
	    $fields = [];
	    foreach ($columns as  $v) {
	    	$fields[] = $v['Field'];
	    }
	    ##获取主键
	    $pk = $this->getPk($table);
	    ##如果主键不为真。UNSET掉
	    if($pk&&!$post[$pk]) unset($post[$pk]);
	    $data = [];
	    ##过滤掉数据库不存在的字段
	    foreach ($post as $k => $v) {
	    	if(in_array($k, $fields)){
	    		$v = $this->csrfStr($v);
	    		$data[$k] = $v;
	    	}
	    }
	    return $data;
	}
	/**
	 * [csrfStr 转义]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-19T14:19:17+0800
	 * @Example  eg:
	 * @param    [type]                   $str       [description]
	 * @return   [type]                              [description]
	 */
	public function csrfStr($str){

		return htmlspecialchars(htmlentities(trim($str)));

	}
	/**
	 * [csrfStrDecode 反转]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-19T14:19:49+0800
	 * @Example  eg:
	 * @param    [type]                   $str       [description]
	 * @return   [type]                              [description]
	 */
	public function csrfStrDecode($str){

		return html_entity_decode(htmlspecialchars_decode($str));

	}
	/**
	 * [getPk 获得主键]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-16T17:18:48+0800
	 * @Example  eg:
	 * @param    [type]                   $table     [description]
	 * @return   [type]                              [description]
	 */
	public function getPk($table){
		$sql = "SELECT column_name FROM INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` WHERE table_name='".$table."' AND constraint_name='PRIMARY'";
		$pkinfo = DB::select($sql);
		 $pkinfo =  collect($pkinfo)->map(function ($item) {
	        return (array)$item;
	    });
		return $pkinfo[0]['column_name'];
	}

	private function _validate($data){
		$this->validate($data, [
		    'title' => 'bail|required|unique:posts|max:255',
		    'body' => 'required',
		]);
	}
	/**
	 * [getData 获得数据列表]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-31T16:27:47+0800
	 * @Example  eg:
	 * @param    array                    $where     [description]
	 * @return   [type]                              [description]
	 */
	public  function getData($table,$where=[]){
		return Arr::objToArray(DB::table($table)->where($where)->get());
	}
	/**
	 * [table 请求SQL]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-27T11:25:43+0800
	 * @Example  eg:
	 * @param    [type]                   $table     [description]
	 * @return   [type]                              [description]
	 */
	public function table($table){
		return DB::table($table);
	}
	/**
	 * [toArray 转成数组]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-27T11:26:04+0800
	 * @Example  eg:
	 * @param    [type]                   $obj       [description]
	 * @return   [type]                              [description]
	 */
	public function toArray($obj){

		return Arr::objToArray($obj);
	}
	/**
	 * 
	 */
	public function getOne($table,$where=[]){
		return Arr::objToArray(DB::table($table)->where($where)->first());
	}
	/**
	 * [getValue description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-27T10:38:36+0800
	 * @Example  eg:
	 * @param    [type]                   $value     [description]
	 * @param    array                    $where     [description]
	 * @return   [type]                              [description]
	 */
	public function getValue($table,$value,$where=[]){
		return DB::table($table)->where($where)->value($value);
	}
	/**
	 * [getTables 获得当前库的所有表信息]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-31T17:48:56+0800
	 * @Example  eg:
	 * @return   [type]                   [description]
	 */
	public static function getTables(){
		return Arr::objToArray(DB::select('show table status'));
	}
	/**
	 * [getFields 获得表的所有字段]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-31T17:50:01+0800
	 * @Example  eg:
	 * @param    [type]                   $table     [description]
	 * @return   [type]                              [description]
	 */
	public static function getFields($table){
		return Arr::objToArray( DB::select("SHOW FULL COLUMNS FROM {$table}"));
	}
}
