<?php
$a = [3212,12,1,123,32,125,5842,235,74,14,8,69,35,68,235,56,234,4];
$b = [9,2,324,6,85,7,65,34,37,34,63,45,2,98,475,42,46,94,123,3245];
function merge_sort($a,$b)
{
	$a = merge_fen($a);
	$b = merge_fen($b);
	$c = merge_bin($a,$b);
	var_dump($c);
}

function merge_fen($arr)
{
	$length = sizeof($arr);
	if ($length>2) {
		$a = merge_fen(array_slice($arr, 0,ceil($length/2)));
		$b = merge_fen(array_slice($arr, ceil($length/2)));
		return merge_bin($a,$b);
	} else if($length==2) {
		return merge_bin([$arr[0]],[$arr[1]]);
	} else {
		return $arr;
	}
}

function merge_bin($arr1,$arr2)
{
	$k = $j = $l = 0;
	$arr = [];
	while($arr1[$k] && $arr2[$j])
	{
		if ($arr1[$k]<=$arr2[$j]) {
			$arr[$l++] = $arr1[$k++];
		} else {
			$arr[$l++] = $arr2[$j++];
		}
	}
	while ($arr1[$k]) {
		$arr[$l++] = $arr1[$k++];
	}

	while ($arr2[$j]) {
		$arr[$l++] = $arr2[$j++];
	}
	return $arr;
}

merge_sort($a,$b);