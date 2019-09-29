<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="row">
            <div class="col-sm-12">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>所有项目</h5>
                        <div class="ibox-tools">
                            <a href="/tools/task/edit" class="btn btn-primary btn-xs">创建新任务</a>
                        </div>
                    </div>
                    <div class="wrapper wrapper-content  animated fadeInRight">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <h3>任务列表</h3>
                                        <!-- <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p> -->

                                        <div class="input-group">
                                            <input type="text" placeholder="添加新任务" class="input input-sm form-control">
                                            <span class="input-group-btn">
                                                        <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-plus"></i> 添加</button>
                                                </span>
                                        </div>

                                        <ul class="sortable-list connectList agile-list">
                                         {{<foreach $data as $v>}}
                                            {{<if $v.status==0>}}
                                            <li class="info-element">
                                                {{<$v.name>}}
                                                <div class="agile-detail">
                                                    <a href="/tools/task/edit/{{<$v.id>}}" class="pull-right btn btn-xs btn-white">编辑</a>
                                                    <i class="fa fa-clock-o"></i> {{<date('Y-m-d H:i:s',$v.create_time)>}}
                                                </div>
                                            </li>
                                            {{</if>}}
                                        {{</foreach>}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <h3>进行中</h3>
                                        <!-- <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p> -->
                                        <ul class="sortable-list connectList agile-list">
                                            {{<if $v.status==1>}}
                                            <li class="warning-element">
                                                {{<$v.name>}}
                                                <div class="agile-detail">
                                                    <a href="/tools/task/edit/{{<$v.id>}}" class="pull-right btn btn-xs btn-white">编辑</a>
                                                    <i class="fa fa-clock-o"></i> {{<date('Y-m-d H:i:s',$v.create_time)>}}
                                                </div>
                                            </li>
                                            {{</if>}}
                                           
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <h3>已完成</h3>
                                        <!-- <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p> -->
                                        <ul class="sortable-list connectList agile-list">
                                            {{<if $v.status==2>}}
                                            <li class="success-element">
                                                {{<$v.name>}}
                                                <div class="agile-detail">
                                                    <a href="/tools/task/edit/{{<$v.id>}}" class="pull-right btn btn-xs btn-white">编辑</a>
                                                    <i class="fa fa-clock-o"></i> {{<date('Y-m-d H:i:s',$v.create_time)>}}
                                                </div>
                                            </li>
                                            {{</if>}}

                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>

            </div>
        </div>
</div>
