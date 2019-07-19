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

echo '----------------------------------------------------单元素采集场景--------------------------------------------------------';

$html =<<<STR
    <div id="content">

        <span class="tt">作者：xxx</span>

        这是正文内容段落1.....

        <span>这是正文内容段落2</span>

        <p>这是正文内容段落3......</p>

        <span>这是广告</span>
        <p>这是版权声明！</p>
    </div>
STR;
// 采集正文内容
$eles = QueryList::html($html)->find('#content');
// 选择正文内容中要移除的元素，并移除
$eles->find('.tt,span:last,p:last')->remove();
//获取纯净的正文内容
$content = $eles->html();

print_r($content);


echo '----------------------------------------------------列表采集场景--------------------------------------------------------';
//在前面的列表采集篇章中有讲解到rules()这个方法，它的参数是接收一个二维数组的采集规则，我们前面学到的采集规则形态是下面这样的:
/*
$rules = [
    '规则名1' => ['选择器1','元素属性','内容过滤选择器'],
    '规则名2' => ['选择器2','元素属性','内容过滤选择器'],
    // ...
];
*/


// 采集规则
$rules = [
     //设置了内容过滤选择器
    'content' => ['#content','html','-.tt -span:last -p:last']
];
$rt = QueryList::rules($rules)->html($html)->query()->getData();
print_r($rt->all());





$html =<<<STR
    <div id="content">

        <span class="tt">作者：xxx</span>

        这是正文内容段落1.....

        <span>这是正文内容段落2</span>

        <p>这是正文内容段落3......</p>

        <a href="http://querylist.cc">QueryList官网</a>

        <span>这是广告</span>
        <p>这是版权声明！</p>
    </div>
STR;
$rules = [
    // 移除内容中所有的超链接，但保留超链接的内容，并移除内容中所有p标签，但保留p标签的内容
    'content_html' => ['#content','html','a p'],
    // 保留内容中的超链接，以及保留p标签及内容
    'content_text' => ['#content','text','a p'],
];

$rt = QueryList::rules($rules)->html($html)->query()->getData();

print_r($rt->all());

echo "----------------------------------------------------第二种方式：结合remove()方法--------------------------------------------------------\n";
//QueryList的getData()方法接收一个回调函数作为参数，这个回调函数用于遍历采集结果，并对结果进行处理，我们可以利用这个回调函数来过滤内容。
$html =<<<STR
    <div id="content">

        <span class="tt">作者：xxx</span>

        这是正文内容段落1.....

        <span>这是正文内容段落2</span>

        <p>这是正文内容段落3......</p>

        <span>这是广告</span>
        <p>这是版权声明！</p>
    </div>
STR;

$rules = [
    'content' => ['#content','html']
];

$rt = QueryList::rules($rules)
    ->html($html)
    ->query()
    ->getData(
        function ($item) {
            $ql = QueryList::html($item['content']);
            $ql->find('.tt,span:last,p:last')->remove();
            $item['content'] = $ql->find('')->html();
            return $item;
        }
    );

print_r($rt->all());
