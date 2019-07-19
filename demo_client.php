<?php
/**
 * 客户端用法说明
 * PHP version 7
 *
 * @category Null
 * @package  QueryList4.0
 * @author   Display NAme <chuzhichao@yiihua.com>
 * @license  www.yiihua.com chuzhchao
 * @link     www.yiihua.com
 */

require 'vendor/autoload.php';

use QL\QueryList;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

//采集某页面所有的图片
$data = QueryList::get('http://www.czc123.top?param1=testvalue&params2=somevalue')->find('img')->attrs('src');

//等价于   ---get方法
$data = QueryList::get(
    'http://www.czc123.top',
    [
    'param1' => 'testvalue',
    'params2' => 'somevalue'
    ]
)->find('img')->attrs('src');

//等价于  --- post方法
$data = QueryList::post(
    'http://www.czc123.top',
    [
    'param1' => 'testvalue',
    'params2' => 'somevalue'
    ]
)->find('img')->attrs('src');



/* ****************自定义Header****************** */

$data = QueryList::post(   // get同理
    'http://www.czc123.top',
    [
    'param1' => 'testvalue',
    'params2' => 'somevalue'
    ],
    [
     // 设置代理
    //'proxy' => 'http://222.141.11.17:8118',
    //设置超时时间，单位：秒
    'timeout' => 30,
     'headers' => [
        'Referer' => 'https://querylist.cc/',
        'User-Agent' => 'testing/1.0',
        'Accept'     => 'application/json',
        'X-Foo'      => ['Bar', 'Baz'],
        // 携带cookie
        'Cookie'    => 'abc=111;xxx=222'
     ]
    ]
)->find('img')->attrs('src');



/* *******************连贯操作**********************   */
//post操作和get操作是cookie共享的,意味着你可以先调用post()方法登录，然后get()方法就可以采集所有登录后的页面。
$data = QueryList::post(
    'http://www.czc123.top',
    [
    'username' => 'admin',
    'password' => '123456'
    ]
)->get('http://www.czc123.top/wordpress/?cat=1')->find('img')->attrs('src');

$ret = $data->all();

//print_r($ret);


/* ------------------------------------------------------------------------抓取html----------------------------------------------------------------------------------------------- */

$ql = QueryList::get('http://www.czc123.top');
//echo $ql->getHtml();



/* ------------------------------------------------------------------------捕获HTTP异常----------------------------------------------------------------------------------------------- */

try {
    $ql = QueryList::get('http://www.czc123.top');
} catch (RequestException $e) {
    //print_r($e->getRequest());
    // echo 'Http Error';
}




/* ------------------------------------------------------------------------捕获HTTP响应头----------------------------------------------------------------------------------------------- */

$client = new Client();
$response = $client->get('http://www.czc123.top');
// 获取响应头部信息
$headers = $response->getHeaders();

//print_r($headers);

/* ------------------------------------------------------------------------自定义HTTP客户端----------------------------------------------------------------------------------------------- */


/**
 * 自定义http客户端
 *
 * @param string $url 地址
 *
 * @return array
 */
function getHtml($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$html = getHtml('http://www.czc123.top?param1=testvalue');
// 这种情况下允许你对HTML做一些额外的处理后，然后再把HTML传给QueryList对象
$html = str_replace('xxx', 'yyy', $html);
$ql = QueryList::html($html);
echo $ql->getHtml();
