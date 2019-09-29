<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
/**
 * 任务管理
 */
class WxUser extends WxBase
{
	/**
	 * [group description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-24T17:33:36+0800
	 * @Example  eg:
	 * @return   [type]                   [description]
	 */
  public function group(){
  	return Bear::make('table')
        ->setData(Arr::objToArray(DB::table('wx_users_groups')->get()))
        ->addColumn('groupid','系统groupid')
        ->addColumn('id','微信分组ID')
        ->addColumn('name','名称')
        ->addColumn('count','用户数')
        // ->addRightBtn('editField','编辑字段',['class'=>' btn-xs','href'=>'/fastsql/editField/{Name}'])
        ->fetch();
  }
  /**
   * [users description]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-24T17:33:34+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function users(){
  	$data = $this->paginate('wx_users');
//  	dump($data);
  	return Bear::make('table')
        ->setData($data['data'])
        ->setSearch(['nickname'=>'昵称'])
        ->addColumn('id','id')
        ->addColumn('subscribe','是否关注微信',['width'=>'150','callback'=>function($data){
        	return $data['subscribe']>0?'是':'否';
        }])
        ->addColumn('nickname','微信名',['width'=>'150'])
        ->addColumn('remark','备注信息')
        ->addColumn('sex','姓别',function($data){
          return $data['sex']==1?'男':'女';
        })
        ->addColumn('headimgurl','头像','img')
        ->setPages($data['total'],$data['per_page'],$data['current_page'],$data['html'])
        ->addTopBtn('scheduling','更新用户',['class'=>' btn-xs','href'=>'/admin/scheduling'])
        ->setTools()
        ->fetch();
  }

}
