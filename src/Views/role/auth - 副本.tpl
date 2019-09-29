

  <link rel="stylesheet" href="{{<$static_host>}}plus/zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery.ztree.core.js"></script>
  <script type="text/javascript" src="{{<$static_host>}}plus/zTree_v3/js/jquery.ztree.excheck.js"></script>

<div class="zTreeDemoBackground left">
    <ul id="auth" class="ztree"></ul>
  </div>

<SCRIPT type="text/javascript">
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
    var code;
    

 
    
    $(document).ready(function(){
      $.fn.zTree.init($("#auth"), setting, zNodes);


       $("#btn_GetCheckedAll").click(GetCheckedAll);
       // 
        // $("#btn_CheckAllNodes").click(CheckAllNodes);
        // $("#btn_CancelAllNodes").click(CancelAllNodes);
        // $("#btn_AssignCheck").click(AssignCheck);
        // $("#btn_Disabled1").click(Disabled1);
        // $("#btn_Disabled2").click(Disabled2);

        // $("#btn_Add").click(Add);
        // $("#btn_AddChild").click(AddChild);
        // $("#btn_Update").click(Update);
        // $("#btn_Delete").click(Delete);
        // $("#btn_DeleteAll").click(DeleteAll);
        


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
    function GetCheckedAll() {
        var treeObj = $.fn.zTree.getZTreeObj("auth");
        var nodes = treeObj.getCheckedNodes(true);
        var msg = "name--id--pid\n";
        for (var i = 0; i < nodes.length; i++) {
            msg += nodes[i].name+"--"+nodes[i].id+"--"+nodes[i].pId+"\n";
        }
        $("#msg").val();
        $("#msg").val(msg);
    }    
    //-->
  </SCRIPT>


  <form action="/Ajax/Ucent/authorize.html" method="post" class="cmzForm"> 
   



    <input type="hidden" name="ucat" value="1" />
   <a class="btn btn-primary  btn-outline " >授权</a>

  </form>
