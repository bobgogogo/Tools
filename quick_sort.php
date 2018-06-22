<?php
// 快速排序

$arr = [];

//生产随机纯数字数组
function generateArr(&$arr)
{
	for ($i=0; $i < 20; $i++) { 
		$arr[] = mt_rand(1,100);
	}
	return $arr;
}


function quickSort($preArr)
{
	if (count($preArr)<=1) {
		return $preArr;
	}
	$key = $preArr[0];//取第一个数作为key值
	$left = [];
	$right = [];
	$count = count($preArr);
	for ($i=1; $i < $count; $i++) { 
		if ($preArr[$i]<=$key) {
			$left[] = $preArr[$i];
		} else if ($preArr[$i]>$key) {
			$right[] = $preArr[$i];
		}
	}
	$left_arr = quickSort($left);
	$right_arr = quickSort($right);
	return array_merge_recursive($left_arr,[$key],$right_arr);
}

$preArr = generateArr($arr);
var_dump(quickSort($preArr));