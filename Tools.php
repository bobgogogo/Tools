<?php
/**
* 工具类
*/
class Tools 
{
	/**
	 * 大写 => _小写 
	 * @example AdminUserLog => _admin_user_log
	 * @param $name String 需要转换的字符串
	 */
	public function Upper2Under($string){
		$string = preg_replace_callback("/[A-Z]/", function($match){
			    return "/".strtolower($match[0]);
			}, $string);
		return $string;
	}
}


 