<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
use cylcode\tools\arr\Arr;
use cylcode\manager\Models\WxBuilder as builder;
/**
 * 微信绑定
 */
class WxBuilder extends Base
{

	protected $table = 'wx_builder';
	public function __construct(){
		// builder::fKeywordToBuilder('15220138389');
		$config = [
	    'app_id' => config('manager.wx.appid'),
	    'secret' => config('manager.wx.appsecret'),
	    'token'	 => config('manager.wx.token'),
	];
	$this->app = Factory::officialAccount($config);
	}

	public function lists(){
		return Bear::make('table')
				->setData($this->getData($this->table))
				->addColumn('title','标题')
				->addColumn('return_title','返出title')
				->addColumn('des','描述')
				->addColumn('url','url')
				->addRightBtn('editField','编辑',['class'=>'btn-xs','href'=> route('wx.builder.edit',['id'=>'']).'/{id}'])
				->fetch();

	}
	##所有表
	/**
	 * [edit description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-03T09:24:40+0800
	 * @Example  eg:
	 * @param    Request                  $request   [description]
	 * @return   [type]                              [description]
	 */
	public function edit(Request $request){
		$id = (int)$request->route('id');
		if($id) $data = Arr::objToArray(DB::table($this->table)->where(['id'=>$id])->first());
		if($id&&$data) extract($data);
		// dump(Arr::forMat(self::getTables(),'Name','Name'));
		return Bear::form()
			->addSelectSearch('builder_table','绑定表',Arr::forMat(self::getTables(),'Name','Name'),$builder_table)
			->addText('title','标题描述',$title)
			->addText('sort','排序',$sort)


			->addText('img','绑定图片字段',$img)
			->addText('url','跳转的URL',$url)
			->addTextArea('return_title','返出标题绑定字段',$return_title,['tips'=>'变量请用{name}'])

			->addTextArea('des','返出描述绑定',$des)

			

			->setNav([
				'基本配置'=>['title','builder_table','sort','img_field'],
				'内容配置'=>['return_title','des','url'],
				])
			

			->fetch();
	}
	/**
	 * [getFields description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-06-04T09:14:16+0800
	 * @Example  eg:
	 * @param    [type]                   $table     [description]
	 * @return   [type]                              [description]
	 */
	public function getfield($table='wx_builder'){

		return returnJson(0,'success',Arr::forMat(self::getFields($table),'Field','Field'),false);
	}
}
