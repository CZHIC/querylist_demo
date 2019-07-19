<?php
/**
 * 结果处理
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
    <div class="xx">
        <img data-src="/path/to/1.jpg" alt="">
    </div>
    <div class="xx">
        <img data-src="/path/to/2.jpg" alt="">
    </div>
    <div class="xx">
        <img data-src="/path/to/3.jpg" alt="">
    </div>
STR;

$routs =  array(
        'image' => array('.xx>img','data-src')
    );


echo "------------------------------------------------------基本二维数组---------------------------------------------\n";
$data = QueryList::html($html)->rules($routs)->query()->getData(
    function ($item) {
        return $item;
    }
);
print_r($data->all());

echo "------------------------------------------------------简化数据---------------------------------------------\n";
//可以使用flatten()方法将多维集合转为一维的，对上面的采集结果data进行处理:
$rt = $data->flatten()->all();
print_r($rt);


echo "------------------------------------------------------截取数据---------------------------------------------\n";
//take() 方法返回给定数量项目的新集合，对最初的采集结果data进行处理:
$rt = $data->flatten()->take(2)->all();
$rt = $data->flatten()->take(-2)->all();
print_r($rt);

echo "------------------------------------------------------翻转数据顺序---------------------------------------------\n";
$rt = $data->reverse()->all();
print_r($rt);

echo "------------------------------------------------------过滤数据---------------------------------------------\n";
//filter()方法用于按条件过滤数据，只保留满足条件的数据。
$rt = $data->filter(
    function ($item) {
        return $item['image'] != '/path/to/2.jpg';
    }
)->all();
print_r($rt);

echo "------------------------------------------------------遍历数据，依次处理每一项数据---------------------------------------------\n";
//map() 方法遍历集合并将每一个值传入给定的回调。该回调可以任意修改项目并返回，从而形成新的被修改过项目的集合
$rt = $data->map(
    function ($item) {
        $item['image'] = 'http://xxx.com'.$item['image'];
        return $item;
    }
)->all();
print_r($rt);
