<?php
//最大子数组
$origin = [1,-2,3,200,-4,-201,5,-6,-7,8,9,10,-11,-2,1000,12];

//划分为左右数量均等的两个数组，继续求最大子数组，
//最大子数组取跨中点的最大数组返回

function max_array($a)
{
	$count = count($a);
	// var_dump(array_slice($a, 0,ceil($count/2)),array_slice($a, ceil($count/2)));exit;
	if (count($a)<2) {
		return $a[0];
	}
	$left_sum = max_array(array_slice($a, 0,ceil($count/2)));
	$right_sum = max_array(array_slice($a, ceil($count/2)));

	$this_sum = max_sum($a);

	$max_sum = ($left_sum>$right_sum?$left_sum:$right_sum)>$this_sum?($left_sum>$right_sum?$left_sum:$right_sum):$this_sum;
	return $max_sum;
}

function max_sum($a)
{
	$count = count($a);
	$mid = (int)$count/2;
	// var_dump($count,$left_length,$right_length);exit;
	$left = 0;
	$left_sum = 0;
	$right = 0;
	$right_sum = 0;
	for ($i=$mid-1; $i >=0; $i--) { 
		$left_sum += $a[$i];
		if ($left_sum>$left) {
			$left = $left_sum;
		}
	}
	for ($j=$mid; $j < $count; $j++) { 
		$right_sum += $a[$j];
		if ($right_sum>$right) {
			$right = $right_sum;
		}
	}
	return $left+$right;
}

$sum = max_array($origin);
echo $sum;