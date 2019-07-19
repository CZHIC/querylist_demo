<?php
/**
 * 单元采集
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

$html = <<<STR
<div id="one">
    <div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.com/1.jpg" alt="这是图片" abc="这是一个自定义属性">
        <img class="second_pic" src="http://querylist.com/2.jpg" alt="这是图片2">
        <a href="http://doc.querylist.cc">QueryList文档</a>
    </div>
    <span>其它的<b>一些</b>文本</span>
</div>        
STR;

$ql = QueryList::html($html);

echo' ---------------------------------------------- 获取第一张图片的属性 ---------------------------------------------- ';

$rt = [];
//获取第一张图片的链接地址
//下面四种写法完全等价
$rt[] = $ql->find('img')->attr('src');
$rt[] = $ql->find('img')->src;
$rt[] = $ql->find('img:eq(0)')->src;
$rt[] = $ql->find('img')->eq(0)->src;

//获取第一张图片的alt属性
$rt[] = $ql->find('img')->alt;
//获取第一张图片的abc属性，注意这里获取定义属性的写法与普通属性的写法是一样的
$rt[] = $ql->find('img')->abc;

print_r($rt);


echo' ---------------------------------------------- 获取第er张图片的属性 ---------------------------------------------- ';

$rt = [];
//获取第二张图片的alt属性
$rt[] = $ql->find('img')->eq(1)->alt;
//等价下面这句话
$rt[] = $ql->find('img:eq(1)')->alt;
//也等价下面这句话，通过class选择图片
$rt[] = $ql->find('.second_pic')->alt;

print_r($rt);

echo' ---------------------------------------------- 获取元素的所有属性 ---------------------------------------------- ';

//属性匹配支持通配符*,表示匹配当前元素的所有属性。
$rt = [];
$rt[] = $ql->find('img:eq(0)')->attr('*');
$rt[] = $ql->find('a:eq(1)')->attr('*');

print_r($rt);

echo' ---------------------------------------------- 获取元素内的html内容或text内容 ---------------------------------------------- ';

//text内容与html内容的区别是，text内容中去掉了所有html标签，只剩下纯文本。
$rt = [];
// 获取元素下的HTML内容
$rt[] = $ql->find('#one>.two')->html();
// 获取元素下的text内容
$rt[] = $ql->find('.two')->text();

print_r($rt);


echo' ---------------------------------------------- 获取多个元素的单个属性 ---------------------------------------------- ';

//map()方法用于遍历多个元素的集合，find()方法返回的其实是多个元素的集合，这一点与jQuery也是一致的。

//获取class为two的元素下的所有图片的alt属性

$data1 = $ql->find('.two img')->map(
    function ($item) {
        return $item->alt;
    }
);
// 等价下面这句话
$data2 = $ql->find('.two img')->attrs('alt');

print_r($data1->all());
print_r($data2->all());


//获取选中元素的所有html内容和text内容
$texts = $ql->find('.two>a')->texts();
$htmls = $ql->find('#one span')->htmls();

print_r($texts->all());
print_r($htmls->all());

echo' ----------------------------------------------  如图采集IT之家文章页的：文章标题、作者和正文内容。 ---------------------------------------------- ';
$ql = QueryList::get('https://www.ithome.com/html/discovery/358585.htm');

$rt = [];
// 采集文章标题
$rt['title'] = $ql->find('h1')->text();
// 采集文章作者
$rt['author'] = $ql->find('#author_baidu>strong')->text();
// 采集文章内容
$rt['content'] = $ql->find('.post_content')->html();

print_r($rt);


