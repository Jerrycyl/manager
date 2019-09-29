<?php 
if (!function_exists('clear_js')) {
    /**
     * 过滤js内容
     * @param string $str 要过滤的字符串
     * @author 
     * @return mixed|string
     */
    function clear_js($str = '')
    {
        $search ="/<script[^>]*?>.*?<\/script>/si";
        $str = preg_replace($search, '', $str);
        return $str;
    }
}
/**
 * 删除网页上看不见的隐藏字符串, 如 Java\0script
 *
 * @param   string
 */
function remove_invisible_characters(&$str, $url_encoded = TRUE)
{
    $non_displayables = array();
    
    // every control character except newline (dec 10)
    // carriage return (dec 13), and horizontal tab (dec 09)
    
    if ($url_encoded)
    {
        $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
    }
    
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127

    do
    {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    }
    while ($count);
}
/**
 * 对数组或字符串进行转义处理，数据可以是字符串或数组及对象
 * @param void $data
 * @return type
 */
function addslashes_d($data) {
    if (is_string($data)) {
        return htmlspecialchars(addslashes($data));
    }
    if (is_numeric($data)) {
        return $data;
    }
    if (is_array($data)) {
        $var = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $var[$k] = addslashes_d($v);
                continue;
            } else {
                $var[$k] = htmlspecialchars(addslashes($v));
            }
        }
        return $var;
    }
}
/**
 * 去除转义
 * @param type $data
 * @return type
 */
function stripslashes_d($data) {
    if (empty($data)) {
        return $data;
    } elseif (is_string($data)) {
        return htmlspecialchars_decode(stripslashes($data));
    } elseif (is_array($data)) {
        $var = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $var[$k] = stripslashes_d($v);
                continue;
            } else {
                $var[$k] = htmlspecialchars_decode(stripslashes($v));
            
            }
        }
        return $var;
    }
}
/**
 * 将数组转为字符串表示形式
 * @param array $array 数组
 * @param int $level 等级不要传参数
 * @return string
 */
function array_to_String($array, $level = 0) {
    if (!is_array($array)) {
        return "'" . $array . "'";
    }
    $space = '';
    //空白
    for ($i = 0; $i <= $level; $i++) {
        $space .= "\t";
    }
    $arr = "Array\n$space(\n";
    $c = $space;
    foreach ($array as $k => $v) {
        $k = is_string($k) ? '\'' . addcslashes($k, '\'\\') . '\'' : $k;
        $v = !is_array($v) && (!preg_match("/^\-?[1-9]\d*$/", $v) || strlen($v) > 12) ? '\'' . addcslashes($v, '\'\\') . '\'' : $v;
        if (is_array($v)) {
            $arr .= "$c$k=>" . array_to_String($v, $level + 1);
        } else {
            $arr .= "$c$k=>$v";
        }
        $c = ",\n$space";
    }
    $arr .= "\n$space)";
    return $arr;
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    if($type!=4) {
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
    }else{
        // 中文随机字
        for($i=0;$i<$len;$i++){
          $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
    }
    return $str;
}
// 随机生成一组字符串
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}
function rand_email(){
    $postfix =['qq.com','sina.com','sina.com.cn','163.cn','wanli.com','163.com','aliyun.com','qqcs.com','gmail.com','arcs.com','qq.com.cn','vip.qq.com','vip.sina.cn','exrs.com','disre.net','sraxw.net','saraxw.org.cn','szp.org.cn','hqds.org.cn','huwei.cn','ares.cc'];
    return rand_string(rand(5,10),rand(0,3))."@".$postfix[rand(0,count($postfix)-1)];
}
function rand_phone(){
    $prefix = [133,138,152,177,182,192,150,131,130,134,137,139,151,153,155,156,158,159,170,171,172,173,174,175,177,179,160,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197];
    return $prefix[rand(0,count($postfix)-1)].rand_string(8,1);
}
function rand_user(){
    return rand_string(rand(3,7),rand(0,3));
}
/**
 * 检查字符串是否是UTF8编码
 * @param string $string 字符串
 * @return Boolean
 */
function is_utf8($string) {
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}
/**
 * php 获取一定范围内不重复的随机数字，在1-10间随机产生5个不重复的值
 * @param int $begin
 * @param int $end
 * @param int $limit
 * @return array
 */
function getRand($begin=0,$end=10,$limit=5){
    $rand_array=range($begin,$end);//把$begin到$end列成一个数组
    shuffle($rand_array);//将数组顺序随机打乱，shuffle是系统的数组随机排列函数
return array_slice($rand_array,0,$limit);//array_slice取该数组中的某一段，这里截取0到$limit个，即前$limit个
}
/**
 * [machUrl 是否为URL]
 * @Author   Jerry
 * @DateTime 2017-11-04T01:16:49+0800
 * @Example  eg:
 * @param    [type]                   $str [description]
 * @return   [type]                        [description]
 */
function matchUrl($str){
    $pattern = '/http:\/\/[0-9a-z\.\/\-]+\/[0-9a-z\.\/\-]+\.([0-9a-z\.\/\-]+)/';
    preg_match_all($pattern,$str,$match); 
    return $match;
}
/**
 * [matchImage 检查字符串中是否有图片]
 * @Author   Jerry
 * @DateTime 2017-11-04T01:09:58+0800
 * @Example  eg:
 * @param    [type]                   $str [description]
 * @return   [type]                        [description]
 */
function matchImage($str){
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/"; 
    preg_match_all($pattern,$str,$match); 
    return $match;
}

/**
 * [GrabImage 保存远程图片至本地]
 * @param [type] $url      [远程图片地址]
 * @param string $filename [保存图片名]
 */
function save_img($url,$path='/public/uploads/images/',$fileName=''){
    if(empty($url)){  
        return false;  
    }  
          //获取图片信息大小  
    $imgSize = getImageSize($url);  
    if(!in_array($imgSize['mime'],array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'),true)){  
        return false;  
    }  
     //获取后缀名  
    $_mime = explode('/', $imgSize['mime']);  
    $_ext = '.'.end($_mime);  
    $fileName = $fileName?$fileName:pathinfo($url, PATHINFO_BASENAME);
      //开始攫取  
    ob_start();  
    readfile($url);  
    $imgInfo = ob_get_contents();  
    ob_end_clean();  
  
    if(!file_exists($path)){  
        mkdir($path,0777,true);  
    }  
    $fp = fopen($path.$fileName, 'a');  
    $imgLen = strlen($imgInfo);    //计算图片源码大小  
    $_inx = 1024;   //每次写入1k  
    $_time = ceil($imgLen/$_inx);  
    for($i=0; $i<$_time; $i++){  
        fwrite($fp,substr($imgInfo, $i*$_inx, $_inx));  
    }  
    fclose($fp);  
  
    return $path.$fileName;  
}
/**
 * 获取url
 * @return [type] [description]
 */
function getUrl(){
  $pageURL = 'http';
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pageURL .= "s";
  }
  $pageURL .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } else {
    $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}
/**
 * 下载
 * @param  [type] $filename [description]
 * @param  string $dir      [description]
 * @return [type]           [description]
 */
function downloads($filename,$dir='./'){
    $filepath = $dir.$filename;
    if (!file_exists($filepath)){
        header("Content-type: text/html; charset=utf-8");
        echo "File not found!";
        exit;
    } else {
        $file = fopen($filepath,"r");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".filesize($filepath));
        Header("Content-Disposition: attachment; filename=".$filename);
        echo fread($file, filesize($filepath));
        fclose($file);
    }
}

/**
 * 打印输出数据
 * @param void $var
 */
function show($var) {
    if (is_bool($var)) {
        var_dump($var);
    } else if (is_null($var)) {
        var_dump(NULL);
    } else {
        echo "<pre style='padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;'>" . print_r($var, true) . "</pre>";
    }
}

if (!function_exists('load_static')) {
    /**
     * 加载静态资源
     * @param string $static 资源名称
     * @param string $type 资源类型
     * @author 
     * @return string
     */
    function load_static($static = '', $type = 'css',$minify='true')
    {
         
        ##开启minify
        $minify = $minify=="false"?false:true;

        ##***因为开启CDN，强制不开启MINIFY 
        $minify = false;

        
        $assets_list = config($static);
        if($minify){
            $assets_list = !is_array($assets_list) ? $assets_list : implode(',', $assets_list);
            $url   = '/public/min/?f=';
            $result = $type=='css'?'<link rel="stylesheet" href="'.$url.$assets_list.'">':'<script src="'.$url.$assets_list.'"></script>';
            $result = $result."\n";
        }else{
        $result = '';
         ##如果设置了CDN域名，
        $pre = getset('cdn_domain');
            foreach ($assets_list as $item) {
                if ($type == 'css') {
                    $result .= '<link rel="stylesheet" href="'.$pre.$item.'">'."\n";
                } else {
                    $result .= '<script src="'.$pre.$item.'"></script>'."\n";
                }
            } 
        }
       
        return $result;
    }
}

if (!function_exists('minify')) {
    /**
     * 合并输出js代码或css代码 需要minify插件支付
     * @param string $type 类型：group-分组，file-单个文件，base-基础目录
     * @param string $files 文件名或分组名
     * @author 
     */
    function minify($type = '',$files = '')
    {
        $files = !is_array($files) ? $files : implode(',', $files);
        $url   = '/public/min/?';

        switch ($type) {
            case 'group':
                $url .= 'g=' . $files;
                break;
            case 'file':
                $url .= 'f=' . $files;
                break;
            case 'base':
                $url .= 'b=' . $files;
                break;
        }
        return  $url;
    }
}
if (!function_exists('get_browser_type')) {
    /**
     * 获取浏览器类型
     * @return string
     */
    function get_browser_type(){
        $agent = $_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent,'MSIE') !== false || strpos($agent,'rv:11.0')) return "ie";
        if(strpos($agent,'Firefox') !== false) return "firefox";
        if(strpos($agent,'Chrome') !== false) return "chrome";
        if(strpos($agent,'Opera') !== false) return 'opera';
        if((strpos($agent,'Chrome') == false) && strpos($agent,'Safari') !== false) return 'safari';
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')) return '360SE';
        return 'unknown';
    }
}
/**
 * 判断是否是合格的手机客户端
 * 
 * @return boolean
 */
function is_mobile()
{
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    
    if (preg_match('/playstation/i', $user_agent) OR preg_match('/ipad/i', $user_agent) OR preg_match('/ucweb/i', $user_agent))
    {
        return false;
    }
    
    if (preg_match('/iemobile/i', $user_agent) OR preg_match('/mobile\ssafari/i', $user_agent) OR preg_match('/iphone\sos/i', $user_agent) OR preg_match('/android/i', $user_agent) OR preg_match('/symbian/i', $user_agent) OR preg_match('/series40/i', $user_agent))
    {
        return true;
    }
    
    return false;
    // return true;
}
/**
 * 判断是否处于微信内置浏览器中
 * 
 * @return boolean
 */
function in_weixin()
{
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    
    if (preg_match('/micromessenger/i', $user_agent))
    {
        return true;
    }
    
    return false;
}
if (!function_exists('generate_rand_str')) {
    /**
     * 生成随机字符串
     * @param int $length 生成长度
     * @param int $type 生成类型：0-小写字母+数字，1-小写字母，2-大写字母，3-数字，4-小写+大写字母，5-小写+大写+数字
     * @author 蔡伟明 <314013107@qq.com>
     * @return string
     */
    function generate_rand_str($length = 8, $type = 0) {
        $a = 'abcdefghijklmnopqrstuvwxyz';
        $A = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $n = '0123456789';

        switch ($type) {
            case 1: $chars = $a; break;
            case 2: $chars = $A; break;
            case 3: $chars = $n; break;
            case 4: $chars = $a.$A; break;
            case 5: $chars = $a.$A.$n; break;
            default: $chars = $a.$n;
        }

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }
}
if (!function_exists('get_server_ip')) {
    /**
     * 获取服务器端IP地址
     * @return array|false|string
     */
    function get_server_ip(){
        if(isset($_SERVER)){
            if($_SERVER['SERVER_ADDR']){
                $server_ip = $_SERVER['SERVER_ADDR'];
            }else{
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        }else{
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }
}
/**
 * [getIp 此方法相对比较好]
 * @return [type] [description]
 */
function fetch_ip(){  
  $realip = '';  
  $unknown = 'unknown';  
  if (isset($_SERVER)){  
      if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
          $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
          foreach($arr as $ip){  
              $ip = trim($ip);  
              if ($ip != 'unknown'){  
                  $realip = $ip;  
                  break;  
              }  
          }  
      }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
          $realip = $_SERVER['HTTP_CLIENT_IP'];  
      }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
          $realip = $_SERVER['REMOTE_ADDR'];  
      }else{  
          $realip = $unknown;  
      }  
  }else{  
      if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
          $realip = getenv("HTTP_X_FORWARDED_FOR");  
      }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
          $realip = getenv("HTTP_CLIENT_IP");  
      }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
          $realip = getenv("REMOTE_ADDR");  
      }else{  
          $realip = $unknown;  
      }  
  }  
  $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
  return $realip;  
}  
/**
 * 验证 IP 地址是否为内网 IP
 * 
 * @param string
 * @return string
 */
function valid_internal_ip($ip)
{ 

    $ip_address = explode('.', $ip);
    
    if ($ip_address[0] == 10)
    {
        return true;
    }
    
    if ($ip_address[0] == 172 and $ip_address[1] > 15 and $ip_address[1] < 32)
    {
        return true;
    }
    
    if ($ip_address[0] == 192 and $ip_address[1] == 168)
    {
        return true;
    } 
    
    return false;
}
/**
 * 兼容性转码
 * 
 * 系统转换编码调用此函数, 会自动根据当前环境采用 iconv 或 MB String 处理
 * 
 * @param  string
 * @param  string
 * @param  string 
 * @return string
 */
function convert_encoding($string, $from_encoding = 'GBK', $target_encoding = 'UTF-8')
{
    if (function_exists('mb_convert_encoding'))
    {
        return mb_convert_encoding($string, str_replace('//IGNORE', '', strtoupper($target_encoding)), $from_encoding);
    }
    else
    {
        if (strtoupper($from_encoding) == 'UTF-16')
        {
            $from_encoding = 'UTF-16BE';
        }
        
        if (strtoupper($target_encoding) == 'UTF-16')
        {
            $target_encoding = 'UTF-16BE';
        }
        
        if (strtoupper($target_encoding) == 'GB2312' or strtoupper($target_encoding) == 'GBK')
        {
            $target_encoding .= '//IGNORE';
        }
        
        return iconv($from_encoding, $target_encoding, $string);
    }
}
/**
 * 兼容性转码 (数组)
 * 
 * 系统转换编码调用此函数, 会自动根据当前环境采用 iconv 或 MB String 处理, 支持多维数组转码
 * 
 * @param  array
 * @param  string
 * @param  string 
 * @return array
 */
function convert_encoding_array($data, $from_encoding = 'GBK', $target_encoding = 'UTF-8')
{
    return eval('return ' . convert_encoding(var_export($data, true) . ';', $from_encoding, $target_encoding));    
}
/**
 * 字符串截取，支持中文和其他编码
 * @param  [string]  $str     [字符串]
 * @param  integer $start   [起始位置]
 * @param  integer $length  [截取长度]
 * @param  string  $charset [字符串编码]
 * @param  boolean $suffix  [是否有省略号]
 * @return [type]           [description]
 */
function msubstr($str, $start=0, $length=50, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr")) {
          if($suffix&&mb_strlen($str,'UTF-8')>$length){
            return mb_substr($str, $start, $length, $charset)."...";
          }else{
            return mb_substr($str, $start, $length, $charset);
          }
        
    } elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));

    if($suffix) {
        return $slice."......";
    }
    return $slice;
}
/**
 * 递归创建目录
 * 
 * 与 mkdir 不同之处在于支持一次性多级创建, 比如 /dir/sub/dir/
 * 
 * @param  string
 * @param  int
 * @return boolean
 */
function make_dir($dir, $permission = 0777)
{
    $dir = rtrim($dir, '/') . '/';
    
    if (is_dir($dir))
    {
        return TRUE;
    }
    
    if (! make_dir(dirname($dir), $permission))
    {
        return FALSE;
    }
    
    return @mkdir($dir, $permission);
}
/**
 * jQuery jsonp 调用函数
 * 
 * 用法同 json_encode
 * 
 * @param  array
 * @param  string
 * @return string
 */
function jsonp_encode($json = array(), $callback = 'jsoncallback')
{
    if ($_GET[$callback])
    {
        return $_GET[$callback] . '(' . json_encode($json) . ')';
    }
    
    return json_encode($json);
}
/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffDate ($second1, $second2)
{
   $second1 = count(explode("-", $second1))>2?strtotime($second1):$second1;
   $second2 = count(explode("-", $second2))>2?strtotime($second2):$second2;
 return abs(ceil(($second1 - $second2) / 86400));
    // $second1 = strtotime(date('Y-m-d',$second1));
    // $second2 = strtotime(date('Y-m-d',$second2));
    
 //  if ($second1 < $second2) {
 //    $tmp = $second2;
 //    $second2 = $second1;
 //    $second1 = $tmp;
 //  }

 //  return ceil(($second1 - $second2) / 86400);
}
/**
 * 时间友好型提示风格化（即微博中的XXX小时前、昨天等等）
 * 
 * 即微博中的 XXX 小时前、昨天等等, 时间超过 $time_limit 后返回按 out_format 的设定风格化时间戳
 * 
 * @param  int
 * @param  int
 * @param  string
 * @param  array
 * @param  int
 * @return string
 */
function date_friendly($timestamp, $time_limit = 604800, $out_format = 'Y-m-d H:i', $formats = null, $time_now = null)
{
    // if (get_setting('time_style') == 'N')
    // {
    //  return date($out_format, $timestamp);
    // }
    
    if ($formats == null)
    {
        $formats = array('YEAR' => '%s 年前', 'MONTH' => '%s 月前', 'DAY' => '%s 天前', 'HOUR' => '%s 小时前', 'MINUTE' => '%s 分钟前', 'SECOND' => '%s 秒前');
    }
    
    $time_now = $time_now == null ? time() : $time_now;
    $seconds = $time_now - $timestamp;
    
    if ($seconds == 0)
    {
        $seconds = 1;
    }
    
    if ($time_limit != null && $seconds > $time_limit)
    {
        return date($out_format, $timestamp);
    }
    
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $days = floor($hours / 24);
    $months = floor($days / 30);
    $years = floor($months / 12);
    
    if ($years > 0)
    {
        $diffFormat = 'YEAR';
    }
    else
    {
        if ($months > 0)
        {
            $diffFormat = 'MONTH';
        }
        else
        {
            if ($days > 0)
            {
                $diffFormat = 'DAY';
            }
            else
            {
                if ($hours > 0)
                {
                    $diffFormat = 'HOUR';
                }
                else
                {
                    $diffFormat = ($minutes > 0) ? 'MINUTE' : 'SECOND';
                }
            }
        }
    }
    
    $dateDiff = null;
    
    switch ($diffFormat)
    {
        case 'YEAR' :
            $dateDiff = sprintf($formats[$diffFormat], $years);
            break;
        case 'MONTH' :
            $dateDiff = sprintf($formats[$diffFormat], $months);
            break;
        case 'DAY' :
            $dateDiff = sprintf($formats[$diffFormat], $days);
            break;
        case 'HOUR' :
            $dateDiff = sprintf($formats[$diffFormat], $hours);
            break;
        case 'MINUTE' :
            $dateDiff = sprintf($formats[$diffFormat], $minutes);
            break;
        case 'SECOND' :
            $dateDiff = sprintf($formats[$diffFormat], $seconds);
            break;
    }
    
    return $dateDiff;
}
/**
 * 时间差计算
 *
 * @param Timestamp $time
 * @return String Time Elapsed
 * @author Shelley Shyan
 * @copyright http://phparch.cn (Professional PHP Architecture)
 */
function time2Units ($time)
{
   $year   = floor($time / 60 / 60 / 24 / 365);
   $time  -= $year * 60 * 60 * 24 * 365;
   $month  = floor($time / 60 / 60 / 24 / 30);
   $time  -= $month * 60 * 60 * 24 * 30;
   $week   = floor($time / 60 / 60 / 24 / 7);
   $time  -= $week * 60 * 60 * 24 * 7;
   $day    = floor($time / 60 / 60 / 24);
   $time  -= $day * 60 * 60 * 24;
   $hour   = floor($time / 60 / 60);
   $time  -= $hour * 60 * 60;
   $minute = floor($time / 60);
   $time  -= $minute * 60;
   $second = $time;
   $elapse = '';

   $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
                    '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
                    );

   foreach ( $unitArr as $cn => $u )
   {
       if ( $$u > 0 )
       {
           $elapse = $$u . $cn;
           break;
       }
   }

   return $elapse;
}

/**
 * 获得几天前，几小时前，几月前
 * @param int $time 时间戳
 * @param array $unit 时间单位
 * @return bool|string
 */
function date_before($time, $unit = null) {
    $time = intval($time);
    $unit = is_null($unit) ? array("年", "月", "星期", "天", "小时", "分钟", "秒") : $unit;
    switch (true) {
        case $time < (NOW - 31536000) :
            return floor((NOW - $time) / 31536000) . $unit[0] . '前';
        case $time < (NOW - 2592000) :
            return floor((NOW - $time) / 2592000) . $unit[1] . '前';
        case $time < (NOW - 604800) :
            return floor((NOW - $time) / 604800) . $unit[2] . '前';
        case $time < (NOW - 86400) :
            return floor((NOW - $time) / 86400) . $unit[3] . '前';
        case $time < (NOW - 3600) :
            return floor((NOW - $time) / 3600) . $unit[4] . '前';
        case $time < (NOW - 60) :
            return floor((NOW - $time) / 60) . $unit[5] . '前';
        default :
            return floor(NOW - $time) . $unit[6] . '前';
    }
}
/**
 * 根据一个时间戳得到详细信息
 * @param  [type] $time [时间戳]
 * @return [type]      
 * @author [yangsheng@yahoo.com]
 */
function getDateInfo($time){
    $day_of_week_cn=array("日","一","二","三","四","五","六"); //中文星期
    $week_of_month_cn = array('','第1周','第2周','第3周','第4周','第5周','第6周');#本月第几周
    $tenDays= getTenDays(date('j',$time)); #获得旬
    $quarter = getQuarter(date('n',$time),date('Y',$time));# 获取季度
     
    $dimDate = array(
        'date_key' => strtotime(date('Y-m-d',$time)), #日期时间戳
        'date_day' => date('Y-m-d',$time), #日期YYYY-MM-DD
        'current_year' => date('Y',$time),#数字年
        'current_quarter' => $quarter['current_quarter'], #季度
        'quarter_cn' =>$quarter['quarter_cn'],
        'current_month' =>date('n',$time),#月
        'month_cn' =>date('Y-m',$time), #月份
        'tenday_of_month' =>$tenDays['tenday_of_month'],#数字旬
        'tenday_cn' =>$tenDays['tenday_cn'],#中文旬
        'week_of_month' =>ceil(date('j',$time)/7), #本月第几周
        'week_of_month_cn' =>$week_of_month_cn[ceil(date('j',$time)/7)],#中文当月第几周
        'day_of_year' =>date('z',$time)+1,  #年份中的第几天
        'day_of_month' =>date('j',$time),#得到几号
        'day_of_week' =>date('w',$time)>0 ? date('w',$time):7,#星期几
        'day_of_week_cn' =>'星期'.$day_of_week_cn[date('w',$time)],
     );
    return $dimDate;
}
/**
 * 获得日期是上中下旬
 * @param  [int] $j [几号]
 * @return [array]    [description]
 * @author [yangsheng@yahoo.com]
 */
function getTenDays($j)
{  
    $j = intval($j);
     if($j < 1 || $j > 31){
        return false;#不是日期
    }
   $tenDays = ceil($j/10);
    switch ($tenDays) {
        case 1:#上旬
            return array('tenday_of_month'=>1,'tenday_cn'=>'上旬',);
            break;
        case 2:#中旬
             return array('tenday_of_month'=>2,'tenday_cn'=>'中旬',);
            break;       
        default:#下旬
            return array('tenday_of_month'=>3,'tenday_cn'=>'下旬',);
            break;
    }
    return false;
}
/**
 * 根据月份获得当前第几季度
 * @param  [int] $n [月份]
 * @param  [int] $y [年]
 * @return [array]    [description]
 */
function getQuarter($n,$y=null){
     $n = intval($n);
    if($n < 1 || $n > 12){
        return false;   #不是月份
    }
    $quarter = ceil($n/3);
    switch ($quarter) {
        case 1: #第一季度
            return array('current_quarter' => 1, 'quarter_cn'=>$y?$y.'-Q1':'Q1');
            break;
        case 2: #第二季度
            return array('current_quarter' => 2, 'quarter_cn'=>$y?$y.'-Q2':'Q2');
            break;
         case 3: #第三季度
            return array('current_quarter' => 3, 'quarter_cn'=>$y?$y.'-Q3':'Q3');
            break;
         case 4: #第四季度
            return array('current_quarter' => 4, 'quarter_cn'=>$y?$y.'-Q4':'Q4');
            break;
    }
     return false;
}


/**
 * 判断文件或目录是否可写
 * 
 * @param  string
 * @return boolean
 */
function is_really_writable($file)
{
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DIRECTORY_SEPARATOR == '/' and @ini_get('safe_mode') == FALSE)
    {
        return is_writable($file);
    }
    
    // For windows servers and safe_mode "on" installations we'll actually
    // write a file then read it.  Bah...
    if (is_dir($file))
    {
        $file = rtrim($file, '/') . '/is_really_writable_' . md5(rand(1, 100));
        
        if (! @file_put_contents($file, 'is_really_writable() test file'))
        {
            return FALSE;
        }
        else
        {
            @unlink($file);
        }
        
        return TRUE;
    }
    else if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
    {
        return FALSE;
    }
    
    return TRUE;
}

/**
 * 生成密码种子
 * 
 * @param  integer
 * @return string
 */
function fetch_salt($length = 4)
{
    for ($i = 0; $i < $length; $i++)
    {
        $salt .= chr(rand(97, 122));
    }
    
    return $salt;
}

/**
 * 根据 salt 混淆密码
 *
 * @param  string
 * @param  string
 * @return string
 */
function compile_password($password, $salt)
{
    // md5 password...
    if (strlen($password) == 32)
    {
        return md5($password . $salt);
    }
    
    $password = md5(md5($password) . $salt);
    
    return $password;
}
/**
 * 获取数组中随机一条数据
 * 
 * @param  array
 * @return mixed
 */
function array_random($arr)
{
    shuffle($arr);
    
    return end($arr);
}
/**
 * 递归读取文件夹的文件列表
 * 
 * 读取的目录路径可以是相对路径, 也可以是绝对路径, $file_type 为指定读取的文件后缀, 不设置则读取文件夹内所有的文件
 * 
 * @param  string
 * @param  string
 * @return array
 */
function fetch_file_lists($dir, $file_type = null)
{
    if ($file_type)
    {
        if (substr($file_type, 0, 1) == '.')
        {
            $file_type = substr($file_type, 1);
        }
    }
    
    $base_dir = realpath($dir);
    $dir_handle = opendir($base_dir);
    
    $files_list = array();
    
    while (($file = readdir($dir_handle)) !== false)
    {       
        if (substr($file, 0, 1) != '.' AND !is_dir($base_dir . '/' . $file))
        {
            if (($file_type AND H::get_file_ext($file, false) == $file_type) OR !$file_type)
            {
                $files_list[] = $base_dir . '/' . $file;
            }
        }
        else if (substr($file, 0, 1) != '.' AND is_dir($base_dir . '/' . $file))
        {
            if ($sub_dir_lists = fetch_file_lists($base_dir . '/' . $file, $file_type))
            {
                $files_list = array_merge($files_list, $sub_dir_lists);
            }   
        } 
    }
    
    return $files_list;
}
/**
 * CURL 获取文件内容
 * 
 * 用法同 file_get_contents
 * 
 * @param string
 * @param integerr
 * @return string
 */
function curl_get_contents($url, $timeout = 10)
{
    if (!function_exists('curl_init'))
    {
        throw new Zend_Exception('CURL not support');
    }

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    if (substr($url, 0, 8) == 'https://')
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    
    $result = curl_exec($curl);
    
    curl_close($curl);
    
    return $result;
}

/**
 * CURL 获取文件内容
 * 
 * 用法同 curl_post_contents
 *  $url = "http://localhost/web_services.php";
 *  $post_data = array ("username" => "bob","key" => "12345");
 * @param string
 * @param integerr
 * @return string
 */
function curl_post_contents($url = '', $param = ''){
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        show($data);
       //　print_r($output);
}
function curl_multi($url){
     if (!is_array($urls) or count($urls) == 0) {
        return false;
    }
    $curl = $text = array();
    $handle = curl_multi_init();
    foreach($urls as $k => $v) {
        $nurl[$k]= preg_replace('~([^:\/\.]+)~ei', "rawurlencode('\\1')", $v);
        $curl[$k] = curl_init($nurl[$k]);
        curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl[$k], CURLOPT_HEADER, 0);
        curl_multi_add_handle ($handle, $curl[$k]);
    }
    $active = null;
    do {
        $mrc = curl_multi_exec($handle, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($handle) != -1) {
            do {
                $mrc = curl_multi_exec($handle, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    foreach ($curl as $k => $v) {
        if (curl_error($curl[$k]) == "") {
        $text[$k] = (string) curl_multi_getcontent($curl[$k]);
        }
        curl_multi_remove_handle($handle, $curl[$k]);
        curl_close($curl[$k]);
    }
    curl_multi_close($handle);
    return $text;

}
/**
 * “抽奖”函数
 *
 * @param integer $first    起始编号
 * @param integer $last     结束编号
 * @param integer $total    获奖人数
 *
 * @return string
 *
*/
function isWinner($first, $last, $total)
{
    $winner = array();
    for ($i=0;;$i++)
    {
        $number = mt_rand($first, $last);
        if (!in_array($number, $winner))
            $winner[] = $number;    // 如果数组中没有该数，将其加入到数组
        if (count($winner) == $total)   break;
    }
    return implode(' ', $winner);
}
 /*********************************************************************
    $token = encrypt($id, 'E', 'qingdou');
    echo '加密:'.encrypt($id, 'E', 'qingdou');
    echo '<br />';
    echo '解密：'.encrypt($token, 'D', 'qingdou');
    函数名称:encrypt
    函数作用:加密解密字符串
    使用方法:
    加密     :encrypt('str','E','qingdou');
    解密     :encrypt('被加密过的字符串','D','qingdou');
    参数说明:
    $string   :需要加密解密的字符串
    $operation:判断是加密还是解密:E:加密   D:解密
    $key      :加密的钥匙(密匙);
    *********************************************************************/
    function encrypt($string,$operation,$key='')
    {
        $src  = array("/","+","=");
        $dist = array("_a","_b","_c");
        if($operation=='D'){$string  = str_replace($dist,$src,$string);}
        $key=md5($key);
        $key_length=strlen($key);
        $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
        $string_length=strlen($string);
        $rndkey=$box=array();
        $result='';
        for($i=0;$i<=255;$i++)
        {
            $rndkey[$i]=ord($key[$i%$key_length]);
            $box[$i]=$i;
        }
        for($j=$i=0;$i<256;$i++)
        {
            $j=($j+$box[$i]+$rndkey[$i])%256;
            $tmp=$box[$i];
            $box[$i]=$box[$j];
            $box[$j]=$tmp;
        }
        for($a=$j=$i=0;$i<$string_length;$i++)
        {
            $a=($a+1)%256;
            $j=($j+$box[$a])%256;
            $tmp=$box[$a];
            $box[$a]=$box[$j];
            $box[$j]=$tmp;
            $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
        }
        if($operation=='D')
        {
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8))
            {
                return substr($result,8);
            }
            else
            {
                return'';
            }
        }
        else
        {
            $rdate  = str_replace('=','',base64_encode($result));
            $rdate  = str_replace($src,$dist,$rdate);
            return $rdate;
        }
    }

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String 加密后的字符串
 * @author Anyon Zou <cxphp@qq.com>
 */
function encode($string = '', $skey = '6f918e') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        $key < $strCount && $strArr[$key].=$value;
    }
    return str_replace('=', '6f918e', join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String 解密后的字符串
 * @author Anyon Zou <cxphp@qq.com>
 */
function decode($string = '', $skey = '6f918e') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(str_replace('6f918e', '=', $string), 2);
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        if ($key < $strCount && $strArr[$key][1] === $value) {
            $strArr[$key] = $strArr[$key][0];
        } else {
            break;
        }
    }
    return base64_decode(join('', $strArr));
}
//=============================插件类 核心方法=====================================
/**
 * 1、电信手机访问，HTTP头会有手机号码值，移动、联通则无。
 *2、文中所提到的插入代码即可获取，纯属子虚乌有，文中的功能是一些做移动网络服务的公司，先向电信、移动、联通官方购买查询接口，该接口是以类似统计代码形式插入到你的网站，然后会有个后台统计系统。最后向其他公司贩卖会员，按数据条数收钱（重复也算），奇贵无比，每次最少续费三万。
 *3、只有移动网络有效（电信手机、移动、联通），其他方式访问无效。
 *（2013-8-16 10:43:10 核总补充：手机型号则是使用 HTTP 头 User-Agent 判断的，非常简单的“技术”，和普通网站程序判断浏览器型号及系统类型的方法一摸一样。）
 *该思路、系统最出自于医疗行业，未来移动互联网是发展方向，估计会扩展到其他行业。
 * [getPhoneNumber 获取访问的手机号码]
 * @return [type] [description]
 */
function getPhoneNumber()

{
       if (isset($_SERVER['HTTP_X_NETWORK_INFO']))
       {
         $str1 = $_SERVER['HTTP_X_NETWORK_INFO'];
         $getstr1 = preg_replace('/(.*,)(13[\d]{9})(,.*)/i','\\2',$str1);
         return $getstr1;
       }
       elseif (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID']))
       {
         $getstr2 = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
         return $getstr2;
       }
       elseif (isset($_SERVER['HTTP_X_UP_SUBNO']))
       {
         $str3 = $_SERVER['HTTP_X_UP_SUBNO'];
         $getstr3 = preg_replace('/(.*)(13[\d]{9})(.*)/i','\\2',$str3);
         return $getstr3;
       }
       elseif (isset($_SERVER['DEVICEID']))
       {
         return $_SERVER['DEVICEID'];
       }
       else
       {
         return false;

       }

}

/**
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec=2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
         $size /= 1024;
           $pos++;
    }
    return round($size,$dec)." ".$a[$pos];
}

/**
 * [get_csv_contents 读取CSV文件，EXCEL处理]
 * @param  [type] $file_target [description]
 * @return [type]              [description]
 */
function get_csv_contents( $file_target ){
    $zou = array();
    $ExcelArr = array();
    $handle  = fopen( $file_target, 'r');
  fwrite($handle,chr(0xEF).chr(0xBB).chr(0xBF));
 while ($data = fgetcsv($handle, "", ",")) {
 
  $num = count($data);
  $str = "";
  $row++;
  for ($c=0; $c < $num; $c++) {
        $str .= iconv("GBK", 'UTF-8', $data[$c]) .',';
        if($c==2){
            include_once(COMMON_LIB_PATH."Ip/IpLocation.class.php");

            $Ip = new \IpLocation(); // 实例化类
            $location = $Ip->getlocation(trim($data[$c])); // 获取某个IP地址所在的位置
          $str .= ",".$location['country'].$location['area'].",";
        }


  }

    return $str

 }

 fclose($handle);
}
/**
 * [outputCsv 导出CSV]
 * @param  [type] $data [字符型]
 * @return [type]       [description]
 * $data .= i($result[$i]['name']).','.i($result[$i]['option'])."\n";  换行用\n
 */
function outputCsv($str){
        $filename = date('YmdHis').".csv";
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $str;

 }
/**
 * [val PHP自带的验证规则]
 * @param  [type] $value [description]
 * @return [type]        [description]
 * FILTER_CALLBACK  调用用户自定义函数来过滤数据。
*FILTER_SANITIZE_STRING     去除标签，去除或编码特殊字符。
*FILTER_SANITIZE_STRIPPED   “string” 过滤器的别名。
*FILTER_SANITIZE_ENCODED    URL-encode 字符串，去除或编码特殊字符。
*FILTER_SANITIZE_SPECIAL_CHARS  HTML 转义字符 ‘”<>& 以及 ASCII 值小于 32 的字符。
*FILTER_SANITIZE_EMAIL  删除所有字符，除了字母、数字以及 !#$%&’*+-/=?^_`{|}~@.[]
*FILTER_SANITIZE_URL    删除所有字符，除了字母、数字以及 $-_.+!*’(),{}|\\^~[]`<>#%”;/?:@&=
*FILTER_SANITIZE_NUMBER_INT     删除所有字符，除了数字和 +-
*FILTER_SANITIZE_NUMBER_FLOAT   删除所有字符，除了数字、+- 以及 .,eE。
*FILTER_SANITIZE_MAGIC_QUOTES   应用 addslashes()。
*FILTER_UNSAFE_RAW  不进行任何过滤，去除或编码特殊字符。
*FILTER_VALIDATE_INT    在指定的范围以整数验证值。
*FILTER_VALIDATE_BOOLEAN    如果是 “1″, “true”, “on” 以及 “yes”，则返回 true，如果是 “0″, “false”, “off”, “no” 以及 “”，则返回 false。否则返回 NULL。
*FILTER_VALIDATE_FLOAT  以浮点数验证值。
*FILTER_VALIDATE_REGEXP     根据 regexp，兼容 Perl 的正则表达式来验证值。
*FILTER_VALIDATE_URL    把值作为 URL 来验证。
*FILTER_VALIDATE_EMAIL  把值作为 e-mail 来验证。
*FILTER_VALIDATE_IP     把值作为 IP 地址来验证。
 */
function filter($str,$type){
    switch ($type) {
        case 'email':
            $filter = FILTER_VALIDATE_EMAIL;
            break;
        case 'url':
            $filter = FILTER_VALIDATE_URL;
            break;
        
        case 'boolean':
            $filter = FILTER_VALIDATE_BOOLEAN;
            break;
        case 'float':
            $filter = FILTER_VALIDATE_FLOAT;
            break;
        case 'preg':
            $filter = FILTER_VALIDATE_REGEXP;
            break;
        
        default:
            $filter = FILTER_VALIDATE_EMAIL;
            break;
    }
    return filter_var($str, $filter);
}
/**
 * [num_to_rmb 数据转成大写]
 * @Author   Jerry
 * @DateTime 2018-01-22T16:22:44+0800
 * @Example  eg:
 * @param    [type]                   $num [description]
 * @return   [type]                        [description]
 */
function num_to_rmb($num){
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    //精确到分后面就不要了，所以只留两个小数位
    $num = round($num, 2); 
    //将数字转化为整数
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "金额太大，请检查";
    } 
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            //获取最后一位数字
            $n = substr($num, strlen($num)-1, 1);
        } else {
            $n = $num % 10;
        }
        //每次将最后一位数字转化为中文
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        //去掉数字最后一位了
        $num = $num / 10;
        $num = (int)$num;
        //结束循环
        if ($num == 0) {
            break;
        } 
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        //utf8一个汉字相当3个字符
        $m = substr($c, $j, 6);
        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j-3;
            $slen = $slen-3;
        } 
        $j = $j + 3;
    } 
    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c)-3, 3) == '零') {
        $c = substr($c, 0, strlen($c)-3);
    }
    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    }else{
        return $c . "整";
    }
}
/**
 * [hidecard 隐藏敏感信息内容]
 * @Author   Jerry
 * @DateTime 2017-12-27T11:40:37+0800
 * @Example  eg:
 * @param    [type]                   $phone [description]
 * @return   [type]                          [description]
 */
function hidecard($cardnum, $type = 1, $default = "")
{
    if (empty($cardnum)) {
        return $default;
    }
    if ($type == 1) {
        //身份证
        $cardnum = substr($cardnum, 0, 3) . str_repeat("*", 12) . substr($cardnum, strlen($cardnum) - 3);
    }
    elseif ($type == 2) {
        //手机号
        $cardnum = substr($cardnum, 0, 3) . str_repeat("*", 5) . substr($cardnum, strlen($cardnum) - 3);
    }
    elseif ($type == 3) {
        //银行卡
        $cardnum = str_repeat("*", strlen($cardnum) - 4) . substr($cardnum, strlen($cardnum) - 4);
    }
    elseif ($type == 4)
    //用户名
    {
        $strlen = mb_strlen($cardnum, 'utf-8');
        $firstStr = mb_substr($cardnum, 0, 1, 'utf-8');
        $lastStr = mb_substr($cardnum, -1, 1, 'utf-8');
        $cardnum = $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($cardnum, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;;
    }
    return $cardnum;
}