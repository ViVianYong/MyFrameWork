<?php
$arr = array(
                array('name' => 'tdy','age'  => 18),
                array('name' => 'ygsz','age' => 15),
                array('name' => 'gggges','age'  => 20)
        );
$newArr = arraySort($arr,'name');
var_dump($newArr);

/**
 * 二维数组按指定的键值排序
 * $array 数组
 * $key排序键值
 * $type排序方式
 */
function arraySort($arr, $keys, $type = 'asc') {
    $keysvalue = $new_array = array();
    if (empty($arr)) {
        return $new_array;
    }
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[] = $arr[$k];
    }
    return $new_array;
}
