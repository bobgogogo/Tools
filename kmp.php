<?php
$text = 'abaabaabbabaaabaabbabaab';
$t = "速度快放假了卡啊就分开了仨分";
$str = 'abaa';

//STEP 1 求 NEXT 数组

// 1.初始化
$j = 0;//next数组指针位置
$k = -1;//text 匹配数组指针
$next[0] = -1;

// 2.开始求next数组
// $t_a = str_split($text);
$l = strlen($text);
while ($j<$l) {
	if ($k==-1||$text[$j]==$text[$k]) {
		if ($text[$j+1]==$text[$k+1]) {
			$next[++$j] = $next[++$k];	
		} else {
			$next[++$j] = ++$k;
		}
	} else {
		$k = $next[$k];
	}
}
// 3.next数组右移一位

// echo "<pre>";
// var_dump($next);
array_pop($next);
// var_dump($next);
// exit;
// 4.kmp
$p = 0;//主串位置
$q = 0;//模式串位置
$m = strlen($str);
$index = [];
while ($p<$l-$m) {
	while ($q<$m) {
		echo "输入p: $p   q:$q <br/>";
		if ($q==-1 || $text[$p]==$str[$q]) {
			if ($q==-1) {
				echo "跳过 主串：$p=>$text[$p]  模式串：$q=>$str[$q]";
			} else {
				echo "匹配 主串：$p=>$text[$p]  模式串：$q=>$str[$q]";
			}
			$p++;
			$q++;
		} else {
			echo "不匹配 模式串从 ： $q 回退到：";
			$q = $next[$q];
			echo $q;
		}
		echo "<br/>";
	}
	echo "<span style='color:red;'>输出匹配：第 ".($p-$m)." 位</span><br/>";
	$index[] = $p-$m;
	$p = $p++;
	$q = 0;
}
var_dump($index);
