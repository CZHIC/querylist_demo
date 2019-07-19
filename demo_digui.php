<?php
/**
 * 递归多级采集
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

//获取每个li里面的h3标签内容，和class为item的元素内容

$html =<<<STR
    <div id="demo">
        <ul>
            <li>
              <h3>xxx</h3>
              <div class="list">
                <div class="item">item1</div>
                <div class="item">item2</div>
              </div>
            </li>
             <li>
              <h3>xxx2</h3>
              <div class="list">
                <div class="item">item12</div>
                <div class="item">item22</div>
              </div>
            </li>
        </ul>
    </div>
STR;
$routs = array(
        'title' => array('h3','text'),
        'list' => array('.list','html')
    );

$data = QueryList::html($html)->rules($routs)->range('#demo li')->queryData(
    function ($item) {
        // 注意这里的QueryList对象与上面的QueryList对象是同一个对象
        // 所以这里要重置range()参数，否则会共用前面的range()参数，导致出现采集不到结果的诡异现象
        $item['list'] = QueryList::html($item['list'])->rules(
            array(
                 'item' => array('.item','text')
            )
        )->range('')->queryData();
        return $item;
    }
);
print_r($data);
