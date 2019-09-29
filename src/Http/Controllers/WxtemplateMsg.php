<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
use cylcode\manager\Http\data\Data;
use cylcode\tools\code\Code;
/**
 * 微信模板消息
 */
class WxtemplateMsg extends WxBase
{
  /**
   * [sendmsg description]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-06-28T17:55:48+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
	public function sendmsg(Request $request){
    $table = decode($request->input('table'));
    $ids = $request->input('id');
    $templateId = (int)$request->input('templateId');
    if(!$table||!$templateId) return returnJson(-1,'数据异常');
    if(!$ids) return returnJson(-1,'请选选择数据');
    $templateData = Data::getOne('wx_template_message');
    if(!$templateData) return returnJson(-1,'请先配置消息模块信息');
    $data = Arr::objToArray(Data::table($table)->whereIn('id',$ids)->get());
    if(!$data) return returnJson(-1,'未查到用户数据');
    ##循环发送
    $success = 0;
    $error['number'] = 0;
    $error['infos'] = "";
    foreach ($data as  $v) {
       $msgData  = $this->_formatData($templateData,$v);
       $msgData['touser'] = Data::getValue('wx_users','openid',['id'=>$v['wx_userid']]);
       if($msgData['touser']){
          $re = $this->app->template_message->send($msgData);
          if($re['errcode']>0){
            $error['infos'] .= "<br/> id号:{$v['id']}:<span style='color:red'>{$re['errmsg']}</span><br/>";
            $error['number']++;
          }else{
            $success++;
          }
          
       }else{
          $error['infos'] .= "<br/> id号:{$v['id']}:<span style='color:red'>openid错误</span><br/>";
          $error['number']++;
              
       }
       

    }
    return returnJson(0,"本次发送成功{$success}条,错误{$error['number']}条,{$error['infos']}",['href'=>'']);
    
  }
  /**
   * [_formatData description]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-06-29T09:36:39+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  private function _formatData($templateData,$data){
    $msgData = [];
    $formatData = explode("\n", $templateData['data']);
    if($formatData){
      foreach ($formatData as $k => $v) {
          $msgData['keyword'.($k+1)] = [ "value"=>trim(Code::strToVars($v,$data),"\r")];
      }
    }
    $msgData['first'] = [ "value"=>trim(Code::strToVars($templateData['first'],$data),"\r") ];
    $msgData['remark'] = [ "value"=>trim(Code::strToVars($templateData['remark'],$data),"\r") ];
    return [
          'template_id'   => $templateData['template_id'],
          'url'           => trim(Code::strToVars($templateData['url'],$data),"\r"),
          'topcolor'      => '#FF0000',
          'msgtype'       => 'news',
          'data'          => $msgData,

    ];

  }
}
