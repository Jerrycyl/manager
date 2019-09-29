<?php

namespace  cylcode\manager\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use cylcode\attachment\file\File;
use cylcode\tools\oss\Oss;
class Attachment extends Base
{
    protected $mold;##存储方式 
    public function __construct(){
      $this->mold = setting('system_upload_mold');
    }
    /**
     * [imgUpload 图片上传]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-05-20T14:39:02+0800
     * @Example  eg:
     * @param    Content                  $content   [description]
     * @return   [type]                              [description]
     */
    public function imgUpload()
    {
      if(!isPost()) return ;
       $md5File   = isset($_FILES['file']['tmp_name'])?md5_file($_FILES['file']['tmp_name']):'';
       $sha1File  = isset($_FILES['file']['tmp_name'])?sha1_file($_FILES['file']['tmp_name']):'';
       File::type(setting('system_upload_exts'));##设置允许上传的后缀
      switch ($this->mold) {
        case 'local':
            // $attachment = (array)DB::table('admin_attachment')->select('*')->where(['md5'=>$md5File])->first();
            // if($attachment){
              // return $this->_returnUpload($attachment);
            // }
          $info = current(File::upload());
            if(!File::getError()){
              ##写入数据
              $info['do_table'] = 'admin_attachment';
              if($info['id'] = $this->save($info)){
                  return $this->_returnUpload($info);
                }else{
                   returnJson(-1,'上传保存出错');
                }
              
            }else{
              returnJson(-1,File::getError());
            }
          break;
        
        case 'oss':
          $info = File::getInfo();
           if(!File::getError()){
            $filesInfo = pathinfo(current($info)['name']);
            $object = File::createFileName().".".$filesInfo['extension'];
            Oss::config([
              'accessId'      => setting('aliyun_accessKeyId'),
              'accessKey'     => setting('aliyun_accessKeySecret'),
              'bucket'        => setting('aliyun_bucket'),
              'endpoint'      => setting('aliyun_endpoint'),
              'host'          => setting('aliyun_accessKeyId'),

              ]);
            // dump(current($_FILES)['tmp_name']);
            $res      = Oss::uploadFile($object, current($_FILES)['tmp_name']);
            // dump($res);
            if(isset($res['oss-request-url'])){
              $info = [
                'filename'  => $filesInfo['filename'],
                'type'      => $res['oss-requestheaders']['Content-Type'],
                'size'      => filesize(current($_FILES)['tmp_name']),
                'basename'  => $filesInfo['basename'],
                'path'  => basename($res['oss-request-url']),
                'uptime'  => time(),
                'image'  => 1,
                'mold'  => 'oss',
                'md5'  => $md5File,
                'sha1'  => $sha1File,
                'ext'  => $filesInfo['extension'],

                'do_table' => 'admin_attachment',
              ];
              ##兼容markDown信息
             
                if($info['id'] = $this->save($info)){
                    return $this->_returnUpload($info);
                }else{
                  
                   return returnJson(-1,'上传保存出错');
                }
              }else{
                   return returnJson(-1,'上传出错');
              }

           }
          break;
      }
          

    }
    /**
     * [_returnUpload description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-05-21T18:48:42+0800
     * @Example  eg:
     * @param    [type]                   $data      [description]
     * @return   [type]                              [description]
     */
    public function _returnUpload($info,$status=0){
      $from = isset($_GET['from'])?htmlspecialchars($_GET['from']):'ueditor';
      $data = $info;
      $data['path'] = '/'.ltrim($info['path'],'/');##单上传，多上传图片用
      $data['src'] = '/'.ltrim($info['path'],'/');##layedit用
      $data['title'] = $info['filename'];##layedit用
      switch ($from) {
        case 'wangeditor':
          return json_encode([
            'errno'  =>0,##layedit用
            'code'    =>0,
            'data'    =>[
              setting('aliyun_host').$data['path'],


            ]
          ]);
          break;
        case 'mdeditor':
        // {"success":0,"message":"\u6ca1\u6709\u5f00\u542f\u56fe\u7247\u4e0a\u4f20\u529f\u80fd"}
        // {"success":1,"message":"ok","alt":"0822.PNG","url":"http:\/\/127.0.0.1:87\/uploads\/201909\/5d844a35c1872_5d844a35.PNG"}
        // {"status":0,"code":0,"data":{"filename":"0822","type":"image\/png","size":21684,"basename":"0822.PNG","path":"\/1568953625bcf0b737.PNG","uptime":1568953625,"image":1,"mold":"oss","md5":"","sha1":"","ext":"PNG","do_table":"admin_attachment","id":4153,"src":"\/1568953625bcf0b737.PNG","title":"0822"}}
        if($status!=0){
            return json_encode([
            'success'  =>0,
            'message'    =>$info,
          ]);
        }else{
            return json_encode([
            'success'  =>1,
            'message'    =>'ok',
            'url'    =>setting('aliyun_host').$data['path'],
            'alt'    =>$data['filename'],
          ]);
        }
        

          break;
        case 'kindeditor':
        return json_encode([
            'error'  =>0,##layedit用
            'url'    =>setting('aliyun_host').$data['path'],
          ]);

          break;
        default:
          return json_encode([
            'status'  =>0,##layedit用
            'code'    =>0,
            'data'    =>$data
          ]);
          break;
      }
      

      
    }
}
