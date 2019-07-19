<?php
/**
 * 采集列表
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

echo '---------------------------------------采集IT之家的文章页，代码如下：-----------------------------------------------';
$ql = QueryList::get('https://www.ithome.com/html/discovery/358585.htm');
$rt = [];
// 采集文章标题
$rt['title'] = $ql->find('h1')->text();
// 采集文章作者
$rt['author'] = $ql->find('#author_baidu>strong')->text();
// 采集文章内容
$rt['content'] = $ql->find('.post_content')->html();
print_r($rt);

echo '--------------------------------------下面我们来用rules()函数进一步简化代码：---------------------------------------';

/*
$rules = [
    '规则名1' => ['选择器1','元素属性'],
    '规则名2' => ['选择器2','元素属性'],
    // ...
];
*/
$url = 'https://www.ithome.com/html/discovery/358585.htm';
// 定义采集规则
$rules = [
    // 采集文章标题
    'title' => ['h1','text'],
    // 采集文章作者
    'author' => ['#author_baidu>strong','text'],
    // 采集文章内容
    'content' => ['.post_content','html']
];
$rt = QueryList::get($url)->rules($rules)->query()->getData();

print_r($rt->all());

echo '-----------------------------------------------queryData() 语法糖-------------------------------------------------';
//$rt = QueryList::get($url)->rules($rules)->query()->getData();
//queryData()方法等同于query()->getData()->all()
$rt = QueryList::get($url)->rules($rules)->queryData();
print_r($rt);


echo '-----------------------------------------------列表采集------------------------------------------------';

$url = 'https://it.ithome.com/ityejie/';
// 元数据采集规则
$rules = [
    // 采集文章标题
    'title' => ['h1','text'],
    // 采集链接
    'link' => ['h2>a','href'],
    //  采集缩略图，真正的图片链接在data-original属性上
    'img' => ['.list_thumbnail>img','data-original'],
    //采集文档简介
    'desc' => ['.memo','text']
];
// 切片选择器,跳过第一条广告
$range = '.ulcl>li:gt(0)';
$rt = QueryList::get($url)->rules($rules)->range($range)->query()->getData();
print_r($rt->all());

//get()、rules()和range() 这几个方法都属于QueryList属性设置方法，所以调用顺序可以随意，所以下面这几种写法都是等价的：








