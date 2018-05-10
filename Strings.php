<?php
/**
 * 字符串处理类
 * @author  swb
 * @date    2018-05-08
 */
class Strings {

	/**
	 * 过滤字符串俩边空格
	 *
	 * @param string $str
	 * @return string
	 */
	public static function filterBlank($str) {
		mb_regex_encoding('utf-8');
		$str = mb_ereg_replace('　', '', $str);
		return trim($str);
	}


	/**
	 * 过滤javascript
	 *
	 * @param string $str
	 * @return string
	 */
	public static function filterJs($str) {
		$str = str_replace("<script", "&lt;script", $str);
		$str = str_replace("</script>", "&lt;/script&gt;", $str);

		return $str;
	}

	/**
	 * 过滤字符串中的html代码
	 *
	 * @param string $str
	 * @return string
	 */
	public static function filterHtml($str) {
		$str = preg_replace("|<\/*[^<>]*>|isU", "", $str);
		$str = htmlspecialchars($str, ENT_QUOTES);
		return $str;
	}

	/**
	 * 过滤字符串中的表情
	 *
	 * @param string $str
	 * @return string
	 */
	public static function filterSmile($str) {
		return preg_replace("/\[em(\d{1,3})\]/isU", "", $str);
	}


	/**
	 * 过滤字符串中的回车(\r\n)
	 *
	 * @param string $str
	 * @return string
	 */
	public static function filterEnter($str) {
		$tarr = array("|\r|isU", "|\n|isU");
		$str = preg_replace($tarr, " ", $str);
		return $str;
	}

	/**
	 * 转义引号
	 *
	 * @param array $array
	 */
	public static function filterAddslashes(&$array){
		foreach($array as $key => $value){
			if(!is_array($value)){
				$array[$key] = addslashes($value);
			}else{
				MoshFilter::filterAddslashes($array[$key]);
			}
		}
	}

	/**
	 * 检查字符串长度是否超过限制
	 *
	 * @param string $str 原有字符串
	 * @param int $length 要检查的长度范围
	 * @param int $strlen 原字符串长度
	 * @return bool 是否超过限制
	 */
	public static function checkStrlenLength($str, $length, &$strlen = '') {
		$strlen = mb_strlen($str, 'utf8');
		if($strlen > $length) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 得到字符串长度
	 *
	 * @param string $str
	 * @return int
	 */
	public static function strlen($str, $checknl = false) {
		if($checknl) {
			$str = str_replace("\n", "", $str);
			$str = str_replace(" ", "", $str);
			$str = str_replace("　","", $str);
		}
		$strlen = mb_strlen($str, 'utf8');
		return $strlen;
	}


	/**
	 * 检查是否为email
	 *
	 * @param checkEmail $str 原有字符串
	 * @return bool 是否是email格式
	 */
	public static function checkEmail($str) {
		$isEmail = filter_var($str, FILTER_VALIDATE_EMAIL);
		if($isEmail <> false) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 检查是否为url连接
	 *
	 * @param string $str 原有字符串
	 * @return bool 是否是url格式
	 */
	public static function checkUrl($str) {
		$isUrl = filter_var($str, FILTER_VALIDATE_URL);
		if($isUrl <> false) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 检查是否为字母和数字
	 *
	 * @param string $str 原有字符串
	 * @return bool 是否是字母和数字格式
	 */
	public static function checkAlnum($str) {
		$isAlnum = ctype_alnum($str);
		return $isAlnum;
	}

	/**
	 * 检查是否为字母和数字和下划线
	 *
	 * @param string $str 原有字符串
	 * @return bool 是否是字母和数字格式
	 */
	public static function checkNum_Al($str) {
		$isAlnum = ereg("^[0-9a-zA-Z_]{1,}$", $str);
		return $isAlnum;
	}

	/**
	 * 检查手机号是否合法
	 *
	 * @param string $str 原有字符串
	 * @return bool 是否是字母和数字格式
	 */
	public static function checkMobile($str) {
		if(($str >= 13000000000 && $str <= 13999999999) || ($str >= 14700000000 && $str <= 15999999999) || ($str >= 18000000000 && $str <= 18999999999)) {
			return true;
		}
		return false;
	}

	/**
	 * 截取字符串
	 *
	 * @param string $str 原有字符串
	 * @param int $start 开始位置
	 * @param int $length 结束位置
	 * @param bool $end 是否结尾
	 * @param bool $isextend 是否扩展,默认为true
	 * @return string 返回字符串
	 */
	public static function substr($str, $start, $length, $end = false, $isextend = true) {
        if(empty($str)) {
            return false;
        }
		$totalLength = mb_strlen($str);
		$cutNum = $length -1 ;
		if($isextend) {//是否扩展,默认为true
			if($totalLength>$length) {
				$capital = 0;//大写字母
				for($i=0;$i<$length;$i++) {
					$singleOrd = ord($str[$i]);
					if((32 <= $singleOrd && $singleOrd <= 47)||(58 <= $singleOrd && $singleOrd <= 64)||(91 <= $singleOrd && $singleOrd <= 126)) {
						$cutNum++;
					} elseif((65 <= $singleOrd && $singleOrd <= 90)||(48 <= $singleOrd && $singleOrd <= 57)) {//大写字母
						$capital++;
						if($capital!=0&&$capital%2==0) {//现在视两个大写字母多一个
							$cutNum++;
						}
					}
				}
			}
		}
		$subStr = mb_substr($str,$start,$cutNum,'utf8');
		$sub_str = strstr(substr($subStr,-13),'[');
		if($sub_str){
			$cutNum = $cutNum -strlen($sub_str);
			$subStr = mb_substr($str,$start,$cutNum,'utf8');
		}
		if($end){
			if(strlen($subStr) < strlen($str)){
				$subStr = $subStr.'...';
			}
		}
		return $subStr;
	}

	//
	public static function titleSubstr($str, $start, $length, $end = false, $isextend = true) {
		$str = str_replace('&nbsp;',' ',$str);
		$totalLength = mb_strlen($str);
		$cutNum = $length;
		if($isextend) {//是否扩展,默认为true
			if($totalLength>$length) {
				$capital = 0;//大写字母
				for($i=0;$i<$length;$i++) {
					$singleOrd = ord($str[$i]);
					if((32 <= $singleOrd && $singleOrd <= 47)||(58 <= $singleOrd && $singleOrd <= 64)||(91 <= $singleOrd && $singleOrd <= 126)) {
						$cutNum++;
					} elseif((65 <= $singleOrd && $singleOrd <= 90)||(48 <= $singleOrd && $singleOrd <= 57)) {//大写字母
						$capital++;
						if($capital!=0&&$capital%2==0) {//现在视两个大写字母多一个
							$cutNum++;
						}
					}
				}
			}
		}
		$subStr = mb_substr($str,$start,$cutNum,'utf8');
		$subStr = str_replace(' ','&nbsp;',$subStr);
		if($end){
			if(strlen($subStr) < strlen($str)){
				$subStr = $subStr.'...';
			}
		}
		return $subStr;
	}


	/**
	 * 截取字符串,备份,等确认无误后删除
	 *
	 * @param string $str 原有字符串
	 * @param int $start 开始位置
	 * @param int $length 结束位置
	 * @return string 返回字符串
	 */
	public static function substr_bak($str, $start, $length, $end = false) {
		$strs = "";
		if($end) {
			$strs = '...';
		}
		if(self::strlen($str) < $length) {
			$strs = "";
		}

		return mb_substr($str, $start, $length, 'utf8') . $strs;
	}



	/**
	 * 判断是否有非法字符,有则返回true
	 * @param string $str
	 */
	public static function isHaveNonlicet($str) {
		if(preg_match("/[ '.,:;*\?~`\!@#\$%\^&\+=\)\(<>\{\}]|\]|\[|\/|\\\|\"|\】|\【|\|/",$str)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 过滤lucene中的非法字符
	 * @param string $str
	 */
	public static function luceneFilter($str) {
		$str = preg_replace("/['.,:;*\?~`\!@#\$%\^&\+=\)\(<>\{\}\]|]|\[|\/|\\\|\"|\】|\【|\|/", '', $str);
		$str = trim($str);
		return $str;
	}


	/**
	 * 补全时对于有url的资源的过滤
	 *
	 * @param string $str 原有字符串
	 * @return string 返回补全字符串
	 */
	public static function splitstr($str){
		$preg = "/<(img|embed)[^>].*[^>]+$/isU";
		$str = preg_replace($preg, "", $str);
		$str = MoshString::htmlFix($str);
		return $str;
	}

	/**
	 * 拆分字符串按照字符个数
	 *
	 * @param string $str 原有字符串
	 * @param count 个数
	 */
	public static function mb_split($str, $count = 1) {
		mb_internal_encoding("UTF-8");
		while(strlen($str)) {
			$mb_strcut = mb_strcut($str, 0, $count);
			$offset = strlen($mb_strcut);
			$str = mb_strcut($str, $offset);
			$return[] = $mb_strcut;
		}
		return $return;
	}



	/**
	 * tidy补全html
	 *
	 * @param string $html 原有字符串
	 * @return string 返回补全字符串
	 */
	public static function htmlFix($html){
		if(!function_exists('tidy_repair_string')) return $html;
		$str = tidy_repair_string($html, array('output-xhtml'=>true),'utf8');
		$str = tidy_parse_string($str,array('output-xhtml'=>true),'utf8');
		$s = '';
		$nodes = tidy_get_body($str)->child;

		if(!is_array($nodes)){
			$returnVal = 0;
			return $s;
		}
		foreach($nodes as $n){
			$s .= $n->value;
		}
		return $s;
	}



	/**
	 * 格式化字符串将, 、等转换成数组
	 *
	 * @param string $tags 原有字符串
	 * @return string 返回拆分数组
	 */
	public static function formatTags($tags) {

		$tags = str_replace("，", " ", $tags);
		$tags = str_replace(", ", " ", $tags);
		$tags = str_replace(",", " ", $tags);
		$tags = str_replace("、", " ", $tags);
		$tags = str_replace("　", " ", $tags);
		$tags = str_replace("\n", " ", $tags);

		$info = explode(" ", $tags);
		for ($i = 0; $i < count($info); $i++){
			$str = trim($info[$i]);
			if (strlen($str) > 0) {
				$temp[] = $str;
			}
		}

		if (is_array($temp)) {
			return $temp;
		}
		return NULL;
	}


	/**
	 * 过滤flash中的&#等特殊符号
	 *
	 * @param string $str 原有字符串
	 * @return string 返回拆分数组
	 */
	public static function replaceFlashMark($str){
		$arr = array("|'|isU", "|\"|isU", "|&([^#\d])|isU");
		$arrre = array("&#039;", "&#034;", "\\1");
		return preg_replace($arr, $arrre, $str);
	}

	/**
	 * 显示笑脸
	 *
	 * @param string $text 原有字符串
	 * @return string 返回补全字符串
	 */
	public static function changeSmile($text) {
		$text = preg_replace("/\[em(\d{1,3})\]/isU", "<img src='" . STATIC_HOST . "images/face/em\\1.gif' /> ", $text);
		$text = preg_replace("/\[([a-zA-Z]{4,9})\.([a-zA-Z]{2,4})(\d{1,3})\]/isU", "<img src='" . EDITOR_HOST . "editor/images/smiley/\\1/\\2\\3.gif' /> ", $text);
		return $text;
	}


	/**
	 * 将图片替换为指定字符
	 *
	 * @param string $text 原有字符串
	 * @return string 返回补全字符串
	 */
	public static function changeImg($text) {
		$text = preg_replace('|<img([^/]+?)src=\"' . STATIC_HOST . 'images\/face\/em(\d{1,3})\.gif\"(.*) />|Usi', "[em\\2]", $text);
		$text = preg_replace('|<img([^/]+?)src=\"' . EDITOR_HOST . 'editor\/images\/smiley\/(.*)\/(.*)(\d{1,3})\.gif\"(.*) />|Usi', "[\\2.\\3\\4]", $text);

		$text = preg_replace('|(<img([^/]+?)src=(.*)/>)|Usi', "[图片]", $text);
		return $text;
	}
	/**
	 * HTML文本的入库
	 *
	 * @param string $str 原有字符串
	 * @param int $maxlen 截取长度，为0则不截取
	 * @return string 返回字符串
	 */
	public static function filterHtmlSave($str, $maxlen=0) {
		if($maxlen > 0) {
			$str = mb_substr($str, 0, $maxlen, 'utf-8');
		}
		$str = self::htmlFix($str);		//fix html

		$pregfind = array(
		"/<script.*>.*<\/script>/siU",
		"/<style.*>.*<\/style>/siU",
		);
		$pregreplace = array(
		'',
		'',
		);
		$str = preg_replace($pregfind, $pregreplace, $str);	//filter script/style entirely

		$if = new Lib_Filterinput_InputFilter(array(), array(), 1, 1);
		$str = $if->process($str);		//filter html

		//临时提供运营使用style功能 by cinless.qu
		$stylefind = array(
		"/{{moshmystyle}}/siU",
		"/{{\/moshmystyle}}/siU",
		);
		$styleplace = array(
		'<style type="text/css">',
		'</style>',
		);
		$str = preg_replace($stylefind, $styleplace, $str);

		return $str;
	}

	/**
	 * 纯文本内容的入库处理
	 *
	 * @param string $str
	 * @param int $maxlen 截取长度，为0则不截取
	 * @return string
	 */
	public static function filterTextSave($str, $maxlen=0) {
		if($maxlen > 0) {
			$str = mb_substr($str, 0, $maxlen, 'utf-8');
		}
		$str = htmlspecialchars($str, ENT_QUOTES);
		//$str = str_replace(' ', '&nbsp;', $str);
		return self::htmlFix(nl2br($str));
	}

	/**
	 * 名称入库处理（圈子名、用户名等）
	 *
	 * @param string $str 原有字符串
	 * @param int $maxlen 截取长度，为0则不截取
	 * @return bool 返回正确或错误
	 */
	public static function filterNameSave($str, $maxlen=0) {
		if($maxlen > 0) {
			$str = mb_substr($str, 0, $maxlen, 'utf-8');
		}
	//	$str = preg_replace('/\s/isU', '', $str);
		return htmlspecialchars(trim($str), ENT_QUOTES);
	}

	//自定义url的格式
	public static function filterUrlSave($text) {
		if(!preg_match("|[0-9a-z]|isU", $text)) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * html文本的出库显示处理
	 *
	 * @param string $str 原文本
	 * @param int $brief_len 摘要长度，为0则为全部
	 * @param string $ending 补充结束字符
	 * @param string $brief_allow_tag 允许的标签，例如：'<p><br>'，为null则保留全部
	 * @return string
	 */
	public static function filterHtmlDisplay($str, $brief_len=0, $ending='...', $brief_allow_tag=null) {
		if($brief_len <= 0) {
			return $str;
		} else {
			if(!($brief_allow_tag === null)) {
				$str = strip_tags($str, $brief_allow_tag);
			}
			return Lib_Filterinput_Truncate($str, $brief_len, $ending, true, true);
		}
	}
	/**
	 * 纯文本内容的出库显示处理
	 *
	 * @param string $str 原有字符串
	 * @param int $brief_len 摘要长度，0为全部
	 * @param string $ending 结束补充字符
	 * @return string 返回字符串
	 */
	public static function filterTextDisplay($str, $brief_len=0, $ending='...') {
		if($brief_len <= 0) {
			return $str;
		} else {
			return Lib_Filterinput_Truncate($str, $brief_len, $ending, true, true);
		}
	}


	/**
	 * 名称的出库显示处理
	 *
	 * @param string $str 原文本
	 * @param int $brief_len 摘要长度
	 * @param string $ending 结束补充字符
	 * @return string
	 */
	public static function filterNameDisplay($str, $brief_len=0, $ending='') {
		return self::filterTextDisplay($str, $brief_len, $ending);
	}


	/**
	 * 过滤音乐和object
	 *
	 * @param string $str 原文本
	 * @return string
	 */

	public static function filterObjectDisplay($str) {
		$array1 = array(
			"/<script[^>]*?>(.*?<\/script>)?/si",
			"/<object[^>]*?>(.*?<\/object>)?/si",
			"/<embed[^>]*?>(.*?<\/embed>)?/si",
		);
		$array2 = array("", "", "");
		$str = preg_replace($array1, $array2, $str);
		return $str;
	}


	/**
	 * 转换时间为星期
	 *
	 * @param string/int $time 时间
	 * @return string
	 */
	public static function changeTimeToWeek($time) {
		$oldTime = $time;
		$time = intval($time);
		if(strlen($time) != 10) {
			$time = strtotime($oldTime);
		}
		if(empty($time)) {
			return true;
		}
		$timeArr = array('日','一','二','三','四','五','六');
		$date = date('w', $time);
		if($date >= 0 && $date <= 6) {
			return "星期" . $timeArr[$date];
		} else {
			return false;
		}
	}

	/**
	 * 修改文本中的连接在新的窗口打开
	 *
	 * @param unknown_type $str
	 * @return unknown
	 */
	public static function change_href_blank($str) {
		 $str = preg_replace ("/target=([_a-zA-Z]+)/i","",$str);
		 $str = preg_replace("/<a([^>]+)>/i","<a target='_blank' $1 >", $str);
		 return $str;
	}

	/**
	 * 修改url过滤最后的html
	 *
	 * @param string $uri
	 * @return string
	 */
	public static function trimUriExtend($uri) {
		$val = preg_replace("|^(.*)".URI_EXTEND."$|isU",'\\1',$uri);
		return $val;
	}
	/**
	 * 判断是否是合法rul
	 *
	 * @param string $str
	 * @return string
	 */
	public static function check_url($str){
		return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $str);
	}
	/**
	 * 修改图片显示尺寸
	 *
	 * @param string $str
	 * @param int $w
	 * @param int $h
	 * @return string
	 */
	public static function change_img_size($str,$w=80,$h=80) {
		 $str = preg_replace("/<img(.+?)src=('|\")?([^\s]+?)('|\"|\/|'\/|\"\/)?(\s|>).+>/is","<img width='{$w}' height='{$h}' src=$3 />", $str);
		 return $str;
	}
	/**
	 * nl2br 相反
	 *
	 * @param 转换前字符串 $text
	 * @return 转换后字符串
	 */
	public static function br2nl($text) {
		$text = preg_replace('/<br\\s*?\/??>/i', "\n", $text);
		$text = str_replace("\n\n", "\n", $text);
		return $text;
	}

	public static function getdesc($str, $start = 0, $length = 300) {
		return self::filterTextDisplay(trim(strip_tags($str), "&nbsp;\n"), $start, $length);
	}

	/**
	 * 转义
	 *
	 * @param str $text
	 * @return str
	 */
	public static function mhtmlentities($text) {
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
		return $text;
	}


	//unicode 码转换为utf-8编码
	public static function  wapdecoder($str) {
		$str = rawurldecode($str);
		preg_match_all("/(?:%u.{4})|.{4};|&#\d+;|.+/U",$str,$r);
		$ar = $r[0];

		foreach($ar as $k=>$v) {
			if(substr($v,0,2) == "%u") {
				$ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));
			} elseif(substr($v,0,3) == "") {
				$ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,3,-1)));
			} elseif(substr($v,0,2) == "&#") {
				$ar[$k] = mb_convert_encoding(pack("n",substr($v,2,-1)), "UTF-8","UCS-2");
			}
		}
		return join("",$ar);
	}
	
	//按字节截取文字
	public static function st_substr($string, $length, $dot = '') {
	    if (strlen($string) <= $length) {
	        return $string;
	    }
	    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	    $strcut = '';
	    $n = $tn = $noc = 0;
	    while ($n < strlen($string)) {
	        $t = ord($string[$n]);
	        if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
	            $tn = 1;
	            $n++;
	            $noc++;
	        } elseif (194 <= $t && $t <= 223) {
	            $tn = 2;
	            $n += 2;
	            $noc += 2;
	        } elseif (224 <= $t && $t <= 239) {
	            $tn = 3;
	            $n += 3;
	            $noc += 2;
	        } elseif (240 <= $t && $t <= 247) {
	            $tn = 4;
	            $n += 4;
	            $noc += 2;
	        } elseif (248 <= $t && $t <= 251) {
	            $tn = 5;
	            $n += 5;
	            $noc += 2;
	        } elseif ($t == 252 || $t == 253) {
	            $tn = 6;
	            $n += 6;
	            $noc += 2;
	        } else {
	            $n++;
	        }
	        if ($noc >= $length) {
	            break;
	        }
	    }
	    if ($noc > $length) {
	        $n -= $tn;
	    }
	    $strcut = substr($string, 0, $n);
	    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	    return $strcut . $dot;
	}

	/**
	 * Returns the trailing name component of a path.
	 * This method is similar to the php function `basename()` except that it will
	 * treat both \ and / as directory separators, independent of the operating system.
	 * This method was mainly created to work on php namespaces. When working with real
	 * file paths, php's `basename()` should work fine for you.
	 * Note: this method is not aware of the actual filesystem, or path components such as "..".
	 *
	 * @param string $path A path string.
	 * @param string $suffix If the name component ends in suffix this will also be cut off.
	 * @return string the trailing name component of the given path.
	 * @see http://www.php.net/manual/en/function.basename.php
	 */
	public static function basename($path, $suffix = '')
	{
	    if (($len = mb_strlen($suffix)) > 0 && mb_substr($path, -$len) == $suffix) {
	        $path = mb_substr($path, 0, -$len);
	    }
	    $path = rtrim(str_replace('\\', '/', $path), '/\\');
	    if (($pos = mb_strrpos($path, '/')) !== false) {
	        return mb_substr($path, $pos + 1);
	    }
	
	    return $path;
	}
}
?>
