<?php
/**
 * 表格采集
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
        <table>
            <tr>
                <td>姓名</td>
                <td>年龄</td>
                <td>职位</td>
            </tr>
            <tr>
                <td>Rae</td>
                <td>29</td>
                <td>医生</td>
            </tr>
            <tr>
                <td>Marsh</td>
                <td>56</td>
                <td>牧师</td>
            </tr>
            <tr>
                <td>Solomon</td>
                <td>18</td>
                <td>作家</td>
            </tr>
        </table>
    </div>
STR;
$table = QueryList::html($html)->find('table');
// 采集表头
$tableHeader = $table->find('tr:eq(0)')->find('td')->texts();
// 采集表的每行内容
$tableRows = $table->find('tr:gt(0)')->map(
    function ($row) {
        return $row->find('td')->texts()->all();
    }
);

print_r($tableHeader->all());
print_r($tableRows->all());
