

  <link rel="stylesheet" href="{{<$static_host>}}plus/zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery.ztree.core.js"></script>
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery.ztree.excheck.js"></script>




<div style="padding:30px;">
   <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="8"> 
    <thead> 
     <tr> 
      <th>  <a href="javascript:;" data-id="412" class="check_all" checked="checked">全选中</a>/ <a href="javascript:;" data-id="412" class="reverse_all">全取消<i class="fa fa-share-alt-square" aria-hidden="true"></i></a></th> 
     </tr> 
    </thead> 
    <td>
      <div class="zTreeDemoBackground left">
      <ul id="auth" class="ztree"></ul>
    </div>
    </td>
      
   </table>

    <input type="hidden" name="ucat" value="1" />
   <div style="margin:0 auto;padding:10px;">
     <a class="btn btn-primary  btn-outline btn-block" id="do-auth">授权</a>

   </div>
 </div> 


  <script type="text/javascript">
    <!--
    var setting = {
      check: {
        enable: true
      },
      data: {
        simpleData: {
          enable: true
        }
      }
    };

    // var zNodes =[
    //   { id:1, pId:0, name:"随意勾选 1", open:true},
    //   { id:11, pId:1, name:"随意勾选 1-1", open:true},
    //   { id:111, pId:11, name:"随意勾选 1-1-1"},
    //   { id:112, pId:11, name:"随意勾选 1-1-2"},
    // ];
    
    var zNodes = {{<$json>}}
    $(document).ready(function(){
      $.fn.zTree.init($("#auth"), setting, zNodes);
      $("#do-auth").click(auth);
      $(".check_all").click(CheckAllNodes);
      $(".reverse_all").click(CancelAllNodes);

    });

      //全选
    function CheckAllNodes() {
        var treeObj = $.fn.zTree.getZTreeObj("auth");
        treeObj.checkAllNodes(true);
    }

    //全取消
    function CancelAllNodes() {
        var treeObj = $.fn.zTree.getZTreeObj("auth");
        treeObj.checkAllNodes(false);
    }
    
     //获取所有选中节点的值
    function auth() {
        var treeObj = $.fn.zTree.getZTreeObj("auth");
        var nodes = treeObj.getCheckedNodes(true);
        var ids = new Array();
        for (var i = 0; i < nodes.length; i++) {
            ids[i] = nodes[i].id;
            // msg += nodes[i].name+"--"+nodes[i].id+"--"+nodes[i].pId+"\n";
        }
        $.ajax({
          url: '',
          type: 'post',
          dataType: 'json',
          data: {ids: ids,'role_id':'{{<$role_id>}}'},
          success:function(res){
              if(res.status==0){
                layer.confirm(res.msg, {
                        btn: ['确定'] //按钮
                    }, function(){
                      layer.close(layer.index);
                      parent.location.reload(); 
                            return false;
                         
                    });   
              }else{
                 layer.confirm(res.msg, {
                        btn: ['确定'] //按钮
                    }, function(){
                      
                    });  
              }   
          }
        })
        
        
       
    }    

  </script>
