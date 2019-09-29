<?php
    Route::group(['prefix'=>'manager','middleware'=>'web'],function(){
         Route::match(['GET','POST'],'login','\cylcode\manager\Http\Controllers\Out@login')->name('manager.login');
         Route::match(['GET','POST'],'lock','\cylcode\manager\Http\Controllers\Out@lock')->name('manager.lock');
         Route::get('loginout','\cylcode\manager\Http\Controllers\Out@loginout')->name('manager.loginout');
        
    });
// Route::resource('manager', cylcode\manager\Http\Controllers\Index::class);
// Route::resource('fastsql', cylcode\manager\Http\Controllers\Fastsql::class);
Route::group(['prefix'=>'manager','middleware'=>['web','manager.auth']],function(){
    
    Route::get('/','\cylcode\manager\Http\Controllers\Index@index')->name('manager.index');
    Route::get('notice','\cylcode\manager\Http\Controllers\Index@notice')->name('manager.notice');
    Route::get('welcome','\cylcode\manager\Http\Controllers\Index@welcome');


  
    // $router->get('logs/{file}', 'Encore\Admin\LogViewer\LogController@index')->name('log-viewer-file');
    // $router->get('logs/{file}/tail', 'Encore\Admin\LogViewer\LogController@tail')->name('log-viewer-tail');
    

    
    // Route::get('/{id}','\cylcode\manager\Http\Controllers\Index@index');
    ##用户管理
    Route::group(['prefix'=>'user'],function(){

        Route::get('/index','\cylcode\manager\Http\Controllers\User@index')->name('manager.user.index');
        Route::match(['GET','POST'],'create','\cylcode\manager\Http\Controllers\User@create')->name('manager.user.create');
        Route::match(['GET','POST'],'changepwd','\cylcode\manager\Http\Controllers\User@changepwd')->name('manager.user.changepwd');
        // Route::match(['GET','POST'],'changepwd','\cylcode\manager\Http\Controllers\User@edit')->name('manager.user.edit');
        

    });
    ##菜单
     Route::group(['prefix'=>'menu'],function(){

        Route::get('/index','\cylcode\manager\Http\Controllers\Menu@index')->name('manager.menu.index');
        Route::match(['GET','POST'],'edit','\cylcode\manager\Http\Controllers\Menu@edit')->name('manager.menu.edit');
        

    });
     ##给用户授权
     Route::group(['prefix'=>'userrole'],function(){
        Route::match(['GET','POST'],'auth','\cylcode\manager\Http\Controllers\UserRole@auth')->name('manager.userRole.auth');
        Route::get('group','\cylcode\manager\Http\Controllers\RoleGroup@lists')->name('manager.roleGroup.lists');
        Route::match(['GET','POST'],'edit','\cylcode\manager\Http\Controllers\RoleGroup@edit')->name('manager.roleGroup.edit');
        

    });

     ##图片相关
      Route::group(['prefix'=>'image/synthesis'],function(){
        Route::match(['GET','POST'],'edit','\cylcode\manager\Http\Controllers\ImageSynthesis@edit')->name('manager.ImageSynthesis.edit');
        Route::get('index','\cylcode\manager\Http\Controllers\ImageSynthesis@index')->name('manager.ImageSynthesis.index');
        Route::match(['GET','POST'],'paramedit','\cylcode\manager\Http\Controllers\ImageSynthesisList@edit')->name('manager.ImageSynthesisList.edit');
        Route::get('lists','\cylcode\manager\Http\Controllers\ImageSynthesisList@index')->name('manager.ImageSynthesisList.index');
        // Route::post('domarge','\cylcode\manager\Http\Controllers\ImageSynthesis@doMarge')->name('manager.ImageSynthesis.doMarge');

    });

    // 
    //任务管理
    Route::get('/task/index','\cylcode\manager\Http\Controllers\Task@index');
    Route::match(array('GET','POST'),'/task/edit/{id?}', '\cylcode\manager\Http\Controllers\Task@edit');

    ## 日志 
    // Route::get('/log/index','\cylcode\manager\Http\Controllers\LogController@index');

    Route::group(['prefix'=>'admin'],function(){
        
        // Route::get('/editField/{table}','\cylcode\manager\Http\Controllers\Fastsql@editField');
        // Route::get('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField');
        // Route::post('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField');

    });
    /**
     * 管理表结构
     */
    Route::group(['prefix'=>'fastsql'],function(){
        Route::get('/index','\cylcode\manager\Http\Controllers\Fastsql@index');
        Route::get('/editField/{table}','\cylcode\manager\Http\Controllers\Fastsql@editField')->name('fastsql.edit');
        Route::get('/BtnList/{table}','\cylcode\manager\Http\Controllers\FastsqlBtn@index')->name('fastsql.btn');
        Route::match(['GET','POST'],'/btnEdit/{table}','\cylcode\manager\Http\Controllers\FastsqlBtn@edit')->name('fastsql.btnadd');
        Route::match(['GET','POST'],'/btnEdit/{table}/{id}','\cylcode\manager\Http\Controllers\FastsqlBtn@edit')->name('fastsql.btnedit');
        Route::post('/btnDelete','\cylcode\manager\Http\Controllers\FastsqlBtn@delete')->name('fastsql.btndelete');
        Route::get('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField')->name('fastsql.editDetail');
        Route::post('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField');

    });

    /**
     * 快速管理表数据
     */
    Route::group(['prefix'=>'fastpost'],function(){
        Route::get('/message/{table}','\cylcode\manager\Http\Controllers\Fastpost@message')->name('fastpost.message');
        Route::match(['GET','POST'],'/edit/{table}','\cylcode\manager\Http\Controllers\Fastpost@edit')->name('fastpost.add');
        Route::match(['GET','POST'],'/edit/{table}/{id}','\cylcode\manager\Http\Controllers\Fastpost@edit')->name('fastpost.edit');
        Route::post('/delete/{table}','\cylcode\manager\Http\Controllers\Fastpost@delete')->name('fastpost.delete');
    });
    
    
    ##上传
    Route::group(['prefix'=>'attachment','namespace'=>'\cylcode\manager\Http\Controllers'],function(){
        // Route::post('upload','Attachment@imgUpload');
         Route::match(['GET','POST'],'upload','Attachment@imgUpload');
        // Route::get('/editField/{table}','\cylcode\manager\Http\Controllers\Fastsql@editField');
        // Route::get('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField');
        // Route::post('/editField/{table}/{field}','\cylcode\manager\Http\Controllers\Fastsql@editField');

    });


    #微信
    Route::group(['prefix'=>'wx','namespace'=>'\cylcode\manager\Http\Controllers'],function(){
        Route::get('users','WxUser@users');
        Route::get('group','WxUser@group');
        Route::match(['GET','POST'],'menu','WxMenu@message');
        Route::match(['GET','POST'],'builder','WxBuilder@lists')->name('wx.builder');
        Route::match(['GET','POST'],'builder/{id}','WxBuilder@edit')->name('wx.builder.edit');
        Route::match(['GET','POST'],'getfield','WxBuilder@getfield')->name('wx.builder.getfield');
        Route::post('sendmsg','WxtemplateMsg@sendmsg')->name('wx.template.sendmsg');
        Route::match(['GET','POST'],'sendmsg','WxtemplateMsg@sendmsg')->name('wx.template.sendmsg');
    });


    ##crond scheduling 管理
    Route::group(['prefix'=>'scheduling','namespace'=>'\cylcode\manager\Http\Controllers'],function(){
        Route::get('index','Scheduling@index');
    });

    Route::group(['prefix'=>'routes','namespace'=>'\cylcode\manager\Http\Controllers'],function(){
        Route::get('index','Routes@index');
    });

    ## setting 设置

    ##crond scheduling 管理
    Route::match(['GET','POST'],'setting/index','\cylcode\manager\Http\Controllers\Setting@index');


});








