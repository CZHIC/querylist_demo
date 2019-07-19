<?php
/**
 * 内容过滤
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

$html =<<<STR
<div>
    <p>这是内容</p>
</div>
STR;
$rule = [
    'content' => ['div>p:last','text']
];

echo "----------------------------------------------使用编码转换插件，设置输入输出编码--------------------------------\n";

$data = QueryList::html($html)->rules($rule)->encoding('UTF-8', 'GB2312')->query()->getData();
echo  $data;


echo "----------------------------------------------设置输入输出编码,并移除html头部--------------------------------\n";
//如果设置输入输出参数仍然无法解决乱码，那就使用 removeHead()方法移除html头部
$data = QueryList::html($html)->rules($rule)->removeHead()->query()->getData();
//或者
$data = QueryList::html($html)->rules($rule)->encoding('UTF-8', 'GB2312')->removeHead()->query()->getData();


echo "----------------------------------------------自己手动转码页面，然后再把页面传给QueryList-------------------------------\n";
$url = 'http://www.czc123.top';
//手动转码
$html = iconv('GBK', 'UTF-8', file_get_contents($url));
$data = QueryList::html($html)->rules(
    [
    "text" => [".title a","text"]
    ]
)->query()->getData();
print_r($data);
