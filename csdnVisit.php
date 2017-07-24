<?php
#! /usr/bin/php
/**
* CSDN Blog's 刷访问量脚本 (CSDN通过cookie来统计访问量)
* 首先遍历获取文章列表，提取每篇博客的地址id
* 再通过file_get_contents函数访问这些地址
* 将博客中所有文章访问一遍，从而达到刷访问量的目的
* http://blog.csdn.net/ITYang_/article/details/74740974
* http://blog.csdn.net/ITYang_
* @date: 2017年7月24日 下午4:34:52
* @author: ityangs<ityangs@163.com>
* 执行方式：php csdn.php;【cli模式下执行】
*/
header("Content-type: text/html; charset=utf-8");
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set('PRC'); // 切换到中国的时间

/* * * * * * 需要修改的地方* * * * * *  */
$username="ITYang_";//文章列表页的用户标识
$page_count=5;//文章的总页数
$visit_count=10; //每篇文章访问次数
/* * * * * * 需要修改的地方* * * * *  */

$csdn="http://blog.csdn.net/";//csdn网址
echo "Start URLs...".PHP_EOL;
$list_id=get_list_id($username, $csdn, $page_count);
if (count($list_id)==0){
    echo "NO articles!";
    exit();
}
print_r($list_id);
echo "grep URLs finshed. Total URL numbers: ".count($list_id).PHP_EOL;
echo "Start Visits...".PHP_EOL;;
get_visits($username, $csdn, $list_id,$visit_count);







/**
 * 用来提取页面中的博客地址的id
 * @param unknown $username 文章列表页的用户标识
 * @param unknown $csdn csdn网址
 * @param unknown $page_count 文章的总页数
 */
function get_list_id($username,$csdn,$page_count){
    //正则，用来提取页面中的博客地址
    $pattern1='/\<h3 class="list_c_t"\>\<a href="\/'.$username.'\/article\/details\/(\d{7,8})"\>/';//博客皮肤：极客世界 列表样式一
    $pattern2='/\<span class="link_title"\>\<a href="\/'.$username.'\/article\/details\/(\d{7,8})"\>/';//列表样式二
    //循环遍历所文章列表，提取文章URL，循环次数为博客实际的分页数
    $list_id=[];
    for($i=1;$i<=$page_count;$i++) {
        $list_url=$csdn.$username."/article/list/$i";
        $html = curl_get_contents($list_url);
        if(preg_match_all($pattern1, $html, $arr)>0){
            if($i==1) {
                $list_id=$arr[1];
            } else {
                //将每个分页中提取的URL合并到一个大数组中，方便处理
                $list_id = array_merge($list_id,$arr[1]);
            }  
        }elseif (preg_match_all($pattern2, $html, $arr)>0){
            if($i==1) {
                $list_id=$arr[1];
            } else {
                //将每个分页中提取的URL合并到一个大数组中，方便处理
                $list_id = array_merge($list_id,$arr[1]);
            }
        }else 
            break;
    }
    return $list_id;
}

/**
 * 循环访问次数
 * @param unknown $username 文章列表页的用户标识
 * @param unknown $csdn csdn网址
 * @param unknown $list_id 页面中的所有博客地址的id
 * @param number $visit_count 每篇文章访问次数
 */
function get_visits($username,$csdn,$list_id,$visit_count=10){
    //循环访问次数
    $article_url=$csdn.$username.'/article/details/';
    for($i=1;$i<=$visit_count;$i++) {
        foreach($list_id as $value) {
            curl_get_contents($article_url.$value);
        }
        echo "loop times: $i".PHP_EOL;
    }

}

/**
 * curl远程获取页面
 * @param unknown $url URL地址
 * @return mixed
 */
function curl_get_contents($url) {
    $headers = get_rand_ip();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_USERAGENT,  "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0");      //模拟浏览器类型
    curl_setopt($curl, CURLOPT_TIMEOUT, 100);                               // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0);                                  // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                          // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }
    return $tmpInfo;
}

/**
 * 随机提供了国内的IP地址
 * @return multitype:string
 */
function get_rand_ip(){
    $ip_long = array(
        array('607649792', '608174079'), //36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
    );
    $rand_key = mt_rand(0, 9);
    $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
    $headers['CLIENT-IP'] = $ip;
    $headers['X-FORWARDED-FOR'] = $ip;

    $headerArr = array();
    foreach( $headers as $n => $v ) {
        $headerArr[] = $n .':' . $v;
    }
    return $headerArr;
}






