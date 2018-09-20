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
	public static function Upper2Under($string){
		$string = preg_replace_callback("/[A-Z]/", function($match){
			    return "_".strtolower($match[0]);
			}, $string);
		return $string;
	}

	//通过二进制流 读取文件后缀信息
    public static function getImagePostFix($filename) {
        if (empty($filename))
            return false;
        $file = fopen($filename, "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strinfo = @unpack("c2chars", $bin);
        $typecode = intval($strinfo['chars1'] . $strinfo['chars2']);
        $filetype = "";
        switch ($typecode) {
            case 7790: $filetype = 'exe';
                break;
            case 7784: $filetype = 'midi';
                break;
            case 8297: $filetype = 'rar';
                break;
            case 255216:$filetype = 'jpg';
                break;
            case 7173: $filetype = 'gif';
                break;
            case 6677: $filetype = 'bmp';
                break;
            case 13780: $filetype = 'png';
                break;
            case 239187:$filetype = 'txt';
                break;
            case 48 : $filetype = 'p12';
                break;
            case 8075 : $filetype = 'zip';
                break;
            default: $filetype = 'unknown' . $typecode;
        }
        if ($strinfo['chars1'] == '-1' && $strinfo['chars2'] == '-40')
            return 'jpg';
        if ($strinfo['chars1'] == '-119' && $strinfo['chars2'] == '80')
            return 'png';
        return $filetype;
    }

    /**
     * 格式化数字
     * @param $num
     * @return array|bool|string
     */
    public static function numberFormat($num) {
        if (!is_numeric($num) || $num == 0) {
            return '---';
        }
        $rvalue = '';
        $num = explode('.', $num); //把整数和小数分开
        $rl = !isset($num['1']) ? '' : $num['1']; //小数部分的值
        $j = strlen($num[0]) % 3; //整数有多少位
        $sl = substr($num[0], 0, $j); //前面不满三位的数取出来
        $sr = substr($num[0], $j); //后面的满三位的数取出来
        $i = 0;
        while ($i <= strlen($sr)) {
            $rvalue = $rvalue . ',' . substr($sr, $i, 3); //三位三位取出再合并，按逗号隔开
            $i = $i + 3;
        }
        $rvalue = $sl . $rvalue;
        $rvalue = substr($rvalue, 0, strlen($rvalue) - 1); //去掉最后一个逗号
        $rvalue = explode(',', $rvalue); //分解成数组
        if ($rvalue[0] == 0) {
            array_shift($rvalue); //如果第一个元素为0，删除第一个元素
        }
        $rv = $rvalue[0]; //前面不满三位的数
        for ($i = 1; $i < count($rvalue); $i++) {
            $rv = $rv . ',' . $rvalue[$i];
        }
        if (!empty($rl)) {
            $rvalue = $rv . '.' . $rl; //小数不为空，整数和小数合并
        } else {
            $rvalue = $rv; //小数为空，只有整数
        }
        return $rvalue;
    }

    /**
     * 获取当前日期往前推 一周的日期列表
     */
    public static function getOneWeekDate() {
        $list = [];
        for ($i = 7; $i >= 1; $i--) {
            $list[] = date('m-d', strtotime('-' . $i . 'days'));
        }
        return $list;
    }

        public static function RemoveXSS($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);
        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        return $val;
    }

    /**
     * 判断多维数组是否存在某个值
     */
    public static function _deep_in_array($value, $array) {
        foreach ($array as $item) {
            if (!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }
            if (in_array($value, $item)) {
                return true;
            } else if (self::_deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param array $data  include 'url','params','header','http_method'
     * @param array $options
     */
    public static function curlMulti($data,$options = [])
    {
        $curly = array();
        $result = array();
    
        $mh = curl_multi_init();
    
        foreach ($data as $id => $item) {
            $curly[$id] = curl_init();
            if (is_string($item)) {
                curl_setopt($curly[$id], CURLOPT_POST, 0);
                curl_setopt($curly[$id], CURLOPT_URL,$item);
                curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
            }elseif (is_array($item)){
                if (empty($item['url'])) {
                    throw new Exception('Wrong curl data which mush contain a url field');
                }
                if (!empty($item['header'])) {
                    curl_setopt($curly[$id], CURLOPT_HTTPHEADER, $item['header']);
                }
                $http_method = !empty($item['http_method']) && in_array($item['http_method'], ['POST','GET'])?$item['http_method']:'GET';
                if ('POST' == $http_method) {
                    curl_setopt($curly[$id], CURLOPT_POST, 1);
                    if (!empty($item['params'])) {
                        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $item['params']);
                    }
                }
                if ('GET' == $http_method) {
                    curl_setopt($curly[$id], CURLOPT_POST, 0);
                    if (strpos($item['url'], '?') === false) {
                        $item['url'] .= '?';
                    }else{
                        $item['url'] .= '&';
                    }
                    if (!empty($item['params'])) {
                        $item['url'] .= http_build_query($item['params'],'','&',PHP_QUERY_RFC3986);
                    }
                }
                curl_setopt($curly[$id], CURLOPT_URL,$item['url']);
                curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
                if (!empty($options)) {
                    curl_setopt_array($curly[$id], $options);
                }
            }else{
                throw new Exception('Wrong curl data');
            }
            curl_multi_add_handle($mh, $curly[$id]);
        }
        $running = null;
        do {
            curl_multi_select($mh,10);
            curl_multi_exec($mh, $running);
        } while($running > 0);
    
        foreach($curly as $id => $c) {
            $result[$id] = curl_multi_getcontent($c);
            curl_multi_remove_handle($mh, $c);
        }
        curl_multi_close($mh);
    
        return $result;
    }

    /**
     * 将数组转为关联数组,暂时只支持二维数组
     * 主要用于将数据库查询的结果转变为以主键为Key的关联数组,和将其中某个值提取出来,
     * @param  array $array 二维数组
     * @param  string $key  二维数组中的关联key
     * @param  bool         为true时只返回包含指定key的值的一维数组
     * @return array        以指定key对应的值作为key的关联数组
     */
    public static function arrayToAssoc($array, $key = '', $onlyKey = false) {
        $map = array();
        if (!is_array($array)) {
            return $map;
        }

        if (isset($array[$key]) && !is_array($array[$key]) && !is_object($array[$key])) {
            return $array[$key];
        }

        foreach ($array as $value) {
            if (isset($value[$key]) && !is_array($value[$key]) && !is_object($value[$key])) {
                if ($onlyKey) {
                    $map[] = $value[$key];
                } else {
                    $map[$value[$key]] = $value;
                }
            } else {
                continue;
            }
        }
        return $map;
    }

    /**
     * 对象转为数组
     * @param object  $models 要转换的对象
     * @return array
     */
    public static function objToArr($obj,$field="attributes") {
        $res = array();
        foreach ($obj as $key => $value) {
            if(isset($value->$field)){
                $res[$key] = $value->$field;
            }else{
                $res[$key] = $value;
            }
        }
        return $res;
    }

    /*
     * todo: 导出excl方法
     *
     * $fileName  文件名 不需要带后缀
     * $title   标题数组    例： $title = array('姓名','年龄','性别');
     * $dataList   数据体     例： $dataList = [['张三','18','男'],['张三','18','男']];
     */
    public static function exportExcel($fileName = 'excl_1',$title = array(),$dataList = array()){
        $phpExcel = new PHPExcel();

        //最多支持26列数据
        $columnName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        if(count($title) > count($columnName)){
            //输出文档列数超过限制
            return false;
        }
        //设置表头
        foreach ($title as $key => $value){
            $phpExcel->getActiveSheet()->getColumnDimension($columnName[$key])->setWidth(20);
            $phpExcel->getActiveSheet()->setCellValue($columnName[$key].'1',$value);
            $phpExcel->getActiveSheet()->getStyle($columnName[$key].'1')->getFont()->setBold(true);
        }
        //填充excel数据 ,从第二行开始
        $line = 2;
        foreach ($dataList as $data){
            $column = 0;
            foreach ($data as $key => $value){
                $phpExcel->getActiveSheet()->setCellValue("$columnName[$column]$line","$value");
                $column ++;
            }
            $line ++;
        }

        $filename = $fileName.'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $objWriter->save('php://output');

    }

  	//是否节假日  0是工作日1是节假日
    public static function isHoliday(){
        $url='http://api.goseek.cn/Tools/holiday?date='.date("Ymd");
        @$urlday = file_get_contents($url);
        if($urlday){
            $holiday= json_decode($urlday,true);
            $isholiday = $holiday['data'];
        }else{
            if(date("w") >=1 && date("w") <=5){
                $isholiday =  0;
            }else{
                $isholiday =  1;
            }
        }
        return $isholiday;
    }

    /**
     * 渲染为标准JSON格式
     * @param  mixed $data 如果为数组或对象,
     *                         如果只有2个值, 且第一个为数字第二个字符串将分别作为code和msg,
     *                         否则将除code和msg的值赋值给data
     *                     如果为整数作为code
     *                     如果为字符串作为msg
     * @param  array $param 将参数附加到一维数组
     * @return json  返回必带code和msg可能带data的json标准字符串
     */
    public function renderJson($data, $param = [])
    {

        $json = is_array($param) ? $param : [];

        if (is_object($data)) {
            $data = self::objToArr($data);
        }
        if (is_array($data)) {
            if (count($data) == 2 && is_numeric($data[0]) && is_string($data[1])) {
                $json['code'] = $data[0];
                $json['msg'] = $data[1];
                unset($data);
            }

            if (isset($data['code'])) {
                $json['code'] = $data['code'];
                unset($data['code']);
            }

            if (isset($data['msg'])) {
                $json['msg'] = $data['msg'];
                unset($data['msg']);
            }

            if (!empty($data)) {
                $json['data'] = $data;
            }
        } elseif (is_int($data)) {
            $json['code'] = $data;
        } elseif (is_string($data)) {
            $json['msg'] = $data;
        }

        $json['code'] = isset($json['code']) ? $json['code'] : 0;
        $json['msg'] = isset($json['msg']) ? $json['msg'] : 'ok';
        echo json_encode($json);
    }
}


 