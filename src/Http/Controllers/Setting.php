<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
use Illuminate\Support\Facades\Cookie;
use cylcode\manager\core\Setting as s;
/**
 * 任务管理
 */
class Setting extends WxBase
{
	/**
	 * [index description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-24T17:33:36+0800
	 * @Example  eg:
	 * @return   [type]                   [description]
	 */
  public function index(){
    if(isPost()){
      // dump($_POST);
      foreach ($_POST as $key => $v) {
        if(is_array($v)) continue;
        $data['do_table'] = 'setting';
        $data['where'] = ['key'=>$key];
        $data['key'] = $key;
        $data['value'] = $v;
        $this->save($data);
        ##清掉SETTING 缓存
        s::clean();
      }
      return returnJson(0,'配置成功',['href'=>'']);
    }
    return Bear::make('form')
      ->addImg('base_logo','网站logo',setting('base_logo'))
      ->addText('base_email','邮箱',setting('base_email'))
      ->addRadio('open_notic_windows','是否开户右侧弹窗',['否','是'],setting('open_notic_windows'))
      ->addUeditor('notice','弹窗公告',$this->csrfStrDecode(setting('notice')))

      ->addText('wx_token','微信token',setting('wx_token'))
      ->addText('wx_encodingaeskey','微信encodingaeskey',setting('wx_encodingaeskey'))
      ->addText('wx_appid','微信appid',setting('wx_appid'))
      ->addText('wx_appsecret','微信appsecret',setting('wx_appsecret'))
      ->addText('xcx_appid','小程序appid',setting('xcx_appid'))
      ->addText('xcx_appsecret','小程序appsecret',setting('xcx_appsecret'))

      ->setNav('基本设置',['base_logo','base_email','open_notic_windows','notice'])
      ->setNav('微信设置',['wx_token','wx_encodingaeskey','wx_appid','wx_appsecret'])
      ->setNav('小程序设置',['xcx_appid','xcx_appsecret'])
      ->addText('aliyun_accessKeyId','accessKeyId',setting('aliyun_accessKeyId'))
      ->addText('aliyun_accessKeySecret','accessKeySecret',setting('aliyun_accessKeySecret'))
      ->addText('aliyun_endpoint','endpoint',setting('aliyun_endpoint'))
      ->addText('aliyun_bucket','bucket',setting('aliyun_bucket'))
      ->addText('aliyun_host','host',setting('aliyun_host'))
      ->setNav('OSS配置',['aliyun_accessKeyId','aliyun_accessKeySecret','aliyun_endpoint','aliyun_bucket','aliyun_host'])

      ->addText('mail_host','host',setting('mail_host'))
      ->addText('mail_port','端口',setting('mail_port'),['tips'=>'一般默认值是25，但如果设置SMTP使用SSL加密，该值为465'])
      ->addText('mail_encryption','加密类型',setting('mail_encryption'),['tips'=>'可以设置为null表示不使用任何加密，也可以设置为tls或ssl'])
      ->addText('mail_username','用户名',setting('mail_username'))
      ->addText('mail_password ','密码',setting('mail_password'))
      ->setNav('邮箱配置',['mail_host','mail_port','mail_encryption','mail_username','mail_password'])

      ->addText('seo_title','title',setting('seo_title'))
      ->addText('seo_keywords','keywords',setting('seo_keywords'))
      ->addText('seo_description','description',setting('seo_description'))
      ->setNav('seo配置',['seo_title','seo_keywords','seo_description'])


      ->addRadio('system_upload_mold','上传方式',['oss'=>'oss','local'=>'本地'],setting('system_upload_mold'))
      ->addText('system_upload_exts','上传附件后缀',setting('system_upload_exts'),['tips'=>'eg:jpg,jpeg,gif,png,zip,rar,doc,txt,pem'])
      ->setNav('上传配置',['system_upload_mold','system_upload_exts'])



      ->fetch();
  }


}
