<?php
class myClass {
	static $basical = array(0 => "零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
	static $advanced = array(1 => "拾", "佰", "仟");

	//人民币金额转大写程序PHP版
	public static function ParseNumber($number) {
		$number = trim($number);
		if ($number > 999999999999) {
			return "数字太大，无法处理。抱歉！";
		}

		if ($number == 0) {
			return "零";
		}

		if (strpos($number, '.')) {
			$number = round($number, 2);
			$data = explode(".", $number);
			$data[0] = self::int($data[0]);
			$data[1] = self::dec($data[1]);
			return $data[0] . $data[1];
		} else {
			return self::int($number) . '整';
		}
	}

	public static function int($number) {
		$arr = array_reverse(str_split($number));
		$data = '';
		$zero = false;
		$zero_num = 0;
		foreach ($arr as $k => $v) {
			$_chinese = '';
			$zero = ($v == 0) ? true : false;
			$x = $k % 4;
			if ($x && $zero && $zero_num > 1) {
				continue;
			}

			switch ($x) {
			case 0:
				if ($zero) {
					$zero_num = 0;
				} else {
					$_chinese = self::$basical[$v];
					$zero_num = 1;
				}
				if ($k == 8) {
					$_chinese .= '亿';
				} elseif ($k == 4) {
					$_chinese .= '万';
				}
				break;
			default:
				if ($zero) {
					if ($zero_num == 1) {
						$_chinese = self::$basical[$v];
						$zero_num++;
					}
				} else {
					$_chinese = self::$basical[$v];
					$_chinese .= self::$advanced[$x];
				}
			}
			$data = $_chinese . $data;
		}
		return $data . '元';
	}

	public static function dec($number) {
		if (strlen($number) < 2) {
			$number .= '0';
		}

		$arr = array_reverse(str_split($number));
		$data = '';
		$zero_num = false;
		foreach ($arr as $k => $v) {
			$zero = ($v == 0) ? true : false;
			$_chinese = '';
			if ($k == 0) {
				if (!$zero) {
					$_chinese = self::$basical[$v];
					$_chinese .= '分';
					$zero_num = true;
				}
			} else {
				if ($zero) {
					if ($zero_num) {
						$_chinese = self::$basical[$v];
					}
				} else {
					$_chinese = self::$basical[$v];
					$_chinese .= '角';
				}
			}
			$data = $_chinese . $data;
		}
		return $data;
	}
}
/**
 * 获得提交的json数据
 * @param  Request $request 请求类
 * @param  变量名 $param   [description]
 * @param  默认值 $default 默认null
 * @return 返回结果          [description]
 */
function strtotimeYmd($var) {
	return date('Y-m-d H:i:s', strtotime($var));
}
function jsonData($request, $param = 'id', $default = null) {
	return $request->json()->get($param) ?? $default;
	// php7语法 isset
	// 仅在json content中取值
}
function getAnyData($request, $param = 'id', $default = null) {
	return $request->json()->get($param) ?? ($request->{$param} ?? $default);
	// 在json未找到时 寻找url参数 兼容form 和 url参数
}
/**
 * 获得ID参数
 * @param  string  $id        id,id,id
 * @param  boolean $resArray  是否必然返回数组
 * @param  string  $partition 分隔符
 * @return [type]             第二个参数时True强制返回数组
 */
function getOneOrAll($id, $resArray = false, $partition = ',') {
	// split 比 explode 更有效率
	$ids = array_filter(explode($partition, $id));
	if (count($ids) > 1 or $resArray) {
		return $ids;
	} else {
		return trim($id, $partition);
	}
}
/**
 * 得到所有的数据 方便create
 * @param  [type]  $request     [description]
 * @param  boolean $includeJson 是否需要json数据
 * @return [type]               [description]
 */
function jsonDataAll($request, $includeJson = false, $removeId = true) {
	if ($includeJson) {
		$res = array_merge($request->json()->all(), $request->all());
	} else {
		$res = $request->all();
	}
	if ($removeId) {
		unset($res['id']);
	}
	return $res;
	# code...
}
/**
 * 得到当前登陆用户
 * @param  string $field field
 * @return [type]        [description]
 */
function user($field = '', $guard = 'api') {
	$u = auth()->user();
	switch ($field) {
	case '':
		return $u;
		break;
	case 'object':
		return $u;
		break;
	case 'roles':
		return $u->roles()->get();
		break;

	default:
		return $u->{$field};
		break;
	}
}
/*移动端判断*/
function isMobile($only = '') {
	if ($only == 'wx') {
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'micromessenger') !== false) {
			return true;
		}
	}
	// dd($_SERVER);
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset($_SERVER['HTTP_VIA'])) {
		// 找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	// 脑残法，判断手机发送的客户端标志,兼容性有待提高
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array(
			'micromessenger', //微信
			'nokia',
			'sony',
			'ericsson',
			'mot',
			'samsung',
			'htc',
			'sgh',
			'lg',
			'sharp',
			'sie-',
			'philips',
			'panasonic',
			'alcatel',
			'lenovo',
			'iphone',
			'ipod',
			'blackberry',
			'meizu',
			'android',
			'netfront',
			'symbian',
			'ucweb',
			'windowsce',
			'palm',
			'operamini',
			'operamobi',
			'openwave',
			'nexusone',
			'cldc',
			'midp',
			'wap',
			'mobile',
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	// 协议法，因为有可能不准确，放到最后判断
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}
function get_execution_time() {
	static $microtime_start = null;
	if ($microtime_start === null) {
		$microtime_start = microtime(true);
		return 0.0;
	}
	return microtime(true) - $microtime_start;
}
/*
 * ----------------------------------
 * update batch
 * ----------------------------------
 * multiple update in one query
 *
 * multipleData ( required | array of array )
 * tablename( required | string )
 */
function updateBatch($multipleData = array(), $tableName) {
	// $multipleData = [
	// 	['id' => 1, 'order' => 11],
	// 	['id' => 2, 'order' => 22],
	// 	['id' => 3, 'order' => 33],
	// ];
	if ($tableName && !empty($multipleData)) {
		// column or fields to update
		// dd($multipleData);
		$updateColumn = array_keys($multipleData[0]);
		$referenceColumn = $updateColumn[0]; //e.g id
		unset($updateColumn[0]);
		$whereIn = "";
		$q = "UPDATE `" . $tableName . "` SET ";
		foreach ($updateColumn as $uColumn) {
			$q .= "`" . $uColumn . "` = CASE ";
			foreach ($multipleData as $data) {
				// $upData = $data[$uColumn];
				$upreData = is_array($data[$referenceColumn]) ? serialize($data[$referenceColumn]) : $data[$referenceColumn];
				$upData = is_array($data[$uColumn]) ? serialize($data[$uColumn]) : $data[$uColumn];
				// dump($data[$uColumn]);
				$q .= "WHEN " . $referenceColumn . " = " . $upreData . " THEN '" . $upData . "' ";
			}
			$q .= "ELSE `" . $uColumn . "` END, ";
		}
		foreach ($multipleData as $data) {
			// $upData = is_array($data[$referenceColumn]) ? json_encode($data[$referenceColumn]) : $data[$referenceColumn];
			$upData = $data[$referenceColumn];
			$whereIn .= "'" . $upData . "', ";
		}
		$q = rtrim($q, ", ") . " WHERE " . $referenceColumn . " IN (" . rtrim($whereIn, ', ') . ")";
		// Update
		return \DB::update(DB::raw($q));

	} else {
		return false;
	}
}
function updateBatchZip($multipleData = array(), $tableName, $updateKey = 'id') {
	return updateBatch($multipleData, $tableName);
	$multiData = [];
	$kvalue = (head($multipleData));
	unset($kvalue['updated_at'], $kvalue['created_at']);
	foreach ($kvalue as $key => $value) {
		$multiData[$key] = array_pluck($multipleData, $key);
	}
	// dd($multiData);
	return updateBatch($multiData, $tableName);
}
function setCollection($array) {
	return ($array instanceof Collection) ? $array : collect($array);
}
/**
 * 时间差
 * @param  [非时间戳] $begin_time [计算会转换为时间戳]
 * @param  [非时间戳] $end_time   [计算会转换为时间戳]
 * 日期 时间 时间类型 每周几 剩余排课次数 不排课时间
 * @return [返回间隔天数的数组]             [description]
 */
function timearray($date, $time, $timetype = "0", $week = "0", $days = "1", $unsetdate = []) {
	if ($date[0] < $date[1]) {
		$startdate = $date[0];
		$enddate = $date[1];
	} else {
		$startdate = $date[1];
		$enddate = $date[0];
	}
	if ($time[0] < $time[1]) {
		$starttime = $time[0];
		$endtime = $time[1];
	} else {
		$starttime = $time[1];
		$endtime = $time[0];
	}
	// 两个时间的差
	// 需要的天数
	$num = 0;
	$resDate = [];
	// $timetype = 0;
	// 每周时间 如果第一天不是这个时间则选择属于周X的日期
	if ($timetype == '0') {
		$weekStartDate = date("w", strtotime($startdate));
		// dd($weekStartDate);
		if ($week != $weekStartDate) {
			// 下一个周$week
			$needDays = 7 - abs($weekStartDate - $week);
			// 可以用英文的 不过数字更好计算
			$startdate = date('Y-m-d', strtotime($startdate . ' +' . $needDays . ' days'));
			// dd($weekStartDate, $week, $needDays, $startdate);
		}
	}
	for ($i = 0; $i < $days; $i++) {
		$time = $timetype == '0' ? date("Y-m-d", (strtotime($startdate) + intval(604800 * $i))) : date("Y-m-d", (strtotime($startdate) + intval(86400 * $i)));
		// 每周 或 每天
		// if (str_contains($unsetdate, $time)) {
		if (in_array($time, $unsetdate)) {
			// 新版本存的是数组
			$days++;
			continue;
		}
		$num++;
		$resDate[] = [$time . ' ' . $starttime, $time . ' ' . $endtime, $num];
	}
	return $resDate;
}
/**
 * 查看的金额 传入单位 分[showAmount description]
 * @param  string $value [description]
 * @return [type]        [description]
 */
function showAmount($value = '') {
	return bcdiv($value, 100, 2);
}
/**
 * 支付的金额 传入单位 元[payAmount description]
 * @param  string $value [description]
 * @return [type]        [description]
 */
function payAmount($value = '') {
	return bcmul($value, 100, 2);
}
/**
 * API WITH HEADER TO FIX AJAX NOT USE JSONP
 * @param  [type] $with [description]
 * @return [type]       [description]
 */
function withHeader($with) {
	return $with->header("Access-Control-Allow-Origin", "*");
}
function implode_key($pieces = array(), $glueKey = "=>", $glue = PHP_EOL) {
	$temp = '';
	foreach ($pieces as $key => $value) {
		$temp .= $key . $glueKey . $value . $glue;
	}
	return $temp;
	// $arrK = array_keys($pieces);
	// return implode($glue, $arrK);
}
function cn_str($value, $limit = 100, $end = '...') {
	if (mb_strwidth($value, 'UTF-8') <= $limit) {
		return $value;
	}

	return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
}

//过滤空格，换行
function filter($str) {
	$search = array(" ", "　", "\n", "\r", "\t");
	$replace = array("", "", "", "", "");
	return str_replace($search, $replace, $str);
}
//获得数字
function findNum($str = '') {
	$str = trim($str);
	if (empty($str)) {return '';}
	$result = '';
	for ($i = 0; $i < strlen($str); $i++) {
		if (is_numeric($str[$i])) {
			$result .= $str[$i];
		}
	}
	return $result;
}
/**
 * 指定位置插入字符串
 * @param $str  原字符串
 * @param $i    插入位置
 * @param $substr 插入字符串
 * @return string 处理后的字符串
 */
function insertToStr($str, $i, $substr, $startstr = "") {

	//指定插入位置前的字符串
	for ($j = 0; $j < $i; $j++) {
		$startstr .= $str[$j];
	}

	//指定插入位置后的字符串
	$laststr = "";
	for ($j = $i; $j < strlen($str); $j++) {
		$laststr .= $str[$j];
	}

	//将插入位置前，要插入的，插入位置后三个字符串拼接起来
	$str = $startstr . $substr . $laststr;

	//返回结果
	return $str;
}
// 图片获取
// 返回
// (
//     [id] => "logo"
//     [src] => "http://www.devdo.net/wp-content/uploads/2015/06/2015-06-02.jpg"
//     [alt] => "码农小兵"
//     [title] => "码农小兵logo"
// )
function extract_attrib($html, $pb = 'ph') {
	preg_match_all('/<img[^>]*\>/', $html, $match); //匹配img标签
	$r = array();
	// dd($match);
	foreach ($match[0] as $k => $val) {
		$html = str_replace($val, insertToStr($val, 4, ' onclick="' . $pb . '.open(' . $k . ')"'), $html);
		preg_match_all('/(id|alt|title|src)=("[^"]*")/i', $val, $matches);
		// dd($matches);
		$ret = array();
		foreach ($matches[1] as $i => $v) {
			$ret[$v] = $matches[2][$i];
		}
		$r[$k] = $ret;
	}
	return ['photo' => $r, 'html' => $html];
}
function extract_attrib_2($html) {
	// $html = str_replace('<a href','<a class="gallery-item-hook" href',$html);
	preg_match_all('/<a[^>]*\>/', $html, $matcha); //匹配img标签
	foreach ($matcha[0] as $k => $vala) {
		preg_match_all('/(href)=("[^"]*")/i', $vala, $matchesa);
		$zh = substr(trim($matchesa[2][0], '"'), -3);
		// dd($zh,in_array($zh, ['png','jpg','peg','gif','bmp','et/']));
		if (in_array($zh, ['png', 'jpg', 'peg', 'gif', 'bmp'])) {
			$vala2 = str_insert($vala, 3, ' class="gallery-item-hook"');
			$html = str_replace($vala, $vala2, $html);
		}
		// dd($val,$k,$matches[2][0]);
		// dd($matches);
	}
	preg_match_all('/<img[^>]*\>/', $html, $match); //匹配img标签
	// dd($match);
	foreach ($match[0] as $k => $val) {
		preg_match_all('/(id|alt|title|src)=("[^"]*")/i', $val, $matches);
		$html = str_replace($val, '<a href=' . $matches[2][0] . ' class="gallery-item-hook">' . $val . '</a>', $html);
		// dd($val, $k, $matches[2][0]);
		// dd($matches);
	}
	return ['html' => $html];
}
function check_wap() {
	if (isset($_SERVER['HTTP_VIA'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) {
		return true;
	}

	if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML") > 0) {
		// Check whether the browser/gateway says it accepts WML.
		$br = "WML";
	} else {
		$browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
		if (empty($browser)) {
			return true;
		}

		$mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');

		$mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

		$found_mobile = checkSubstrs($mobile_os_list, $browser) ||
		checkSubstrs($mobile_token_list, $browser);
		if ($found_mobile) {
			$br = "WML";
		} else {
			$br = "WWW";
		}

	}
	if ($br == "WML") {
		return true;
	} else {
		return false;
	}
}

function checkSubstrs($list, $str) {
	$flag = false;
	for ($i = 0; $i < count($list); $i++) {
		if (strpos($str, $list[$i]) > 0) {
			$flag = true;
			break;
		}
	}
	return $flag;
}

function currentDomain($domain = true) {
	if ($domain) {
		return config('app.url');
	}
	$cityCache = cache('Cachecity');
	if (!$cityCache) {
		$service = app(App\Http\Controllers\API\CacheController::class);
		$request = new \Illuminate\Http\Request();
		$service->index($request);
		$cityCache = cache('Cachecity');
	}
	if (request()->citycode) {
		$citycode = request()->citycode;
		$city = $cityCache->where('id', $citycode)->first();
	} else {
		$city = $cityCache->first();
	}
	return $city;
}
function array_cus_sort($array, $keys, $type = 'asc') {
	//$array为要排序的数组,$keys为要用来排序的键名,$type默认为升序排序
	$keysvalue = $new_array = array();

	//提取排序的列
	foreach ($array as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}
	if ($type == 'asc') {
		asort($keysvalue); //升序排列
	} else {
		arsort($keysvalue); //降序排列
	}
	//reset($keysvalue);
	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $array[$k];
	}
	return $new_array;
}
function str_insert($str, $i, $substr) {
	$startstr = $laststr = '';
	for ($j = 0; $j < $i; $j++) {
		$startstr .= $str[$j];
	}
	for ($j = $i; $j < strlen($str); $j++) {
		$laststr .= $str[$j];
	}
	$str = ($startstr . $substr . $laststr);
	return $str;
}
function CloseTags($html) {
	// strip fraction of open or close tag from end (e.g. if we take first x characters, we might cut off a tag at the end!)
	$html = preg_replace('/<[^>]*$/', '', $html); // ending with fraction of open tag
	// put open tags into an array
	preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$opentags = $result[1];
	// put all closed tags into an array
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closetags = $result[1];
	$len_opened = count($opentags);
	// if all tags are closed, we can return
	if (count($closetags) == $len_opened) {
		return $html;
	}
	// close tags in reverse order that they were opened
	$opentags = array_reverse($opentags);
	// self closing tags
	$sc = array('br', 'input', 'img', 'hr', 'meta', 'link');
	// ,'frame','iframe','param','area','base','basefont','col'
	// should not skip tags that can have content inside!
	for ($i = 0; $i < $len_opened; $i++) {
		$ot = strtolower($opentags[$i]);
		if (!in_array($opentags[$i], $closetags) && !in_array($ot, $sc)) {
			$html .= '</' . $opentags[$i] . '>';
		} else {
			unset($closetags[array_search($opentags[$i], $closetags)]);
		}
	}
	return $html;
}
function aversion() {
	$av = \Request::get('aversion');
	return ((int) str_replace('.', '', $av) >= env('AVERSION', 3164)) ? true : false;
}
/**
 * 时间格式化
 * 校验
 */
function formatDate($time, $jy = false) {
	if ($jy) {
		if ($time < 1450000000) {
			return '默认时间';
		}
	}
	$rtime = date("Y-m-d", $time);
	$htime = date("H:i", $time);

	$time = time() - $time;
	if ($time < 0) {
		$str = $rtime;
	} elseif ($time < 60) {
		$str = '刚刚';
	} elseif ($time < 60 * 60) {
		$min = floor($time / 60);
		$str = $min . '分钟前';
	} elseif ($time < 60 * 60 * 24) {
		$h = floor($time / (60 * 60));
		$str = $h . '小时前 ';
	} else {
		$str = $rtime;
	}
	// } elseif ($time < 60 * 60 * 24 * 3) {
	//     $d = floor ( $time / (60 * 60 * 24) );
	//     if ($d == 1)
	//         $str = '昨天 ' . $rtime;
	//     else
	//         $str = '前天 ' . $rtime;
	// } else {
	//     $str = $rtime;
	// }
	return $str;
}
function getToken($value = '') {
	// $tk = JWTAuth::parseToken();
	// dump($tk);
	if (!$value) {
		$value = request()->header('authorization');
	}
	return ltrim($value, 'Bearer ');
}
function returnSuccess($value = '', $code = 200) {
	return response(['message' => $value], $code);
}
function resData($value) {
	return ['data' => $value];
}
/**
 * 导出表格
 *
 * @param  str  $name
 * @param  boole  $random  true 添加随机数 | false 不添加
 * @param  [需要导出的数组]  $data
 * @return str $path
 */
function export($data, $header = null, $name = null, $random = true) {
	set_time_limit(0);
	if ($name == null) {
		$filename = 'Over' . date('d-His');
	} else {
		if ($random) {
			$filename = $name . '_' . date('d-His');
		} else {
			$filename = $name;
		}
	}
	\Excel::create($filename, function ($excel) use ($data, $header) {
		// Set the title
		$excel->setTitle(date('Y-m-d H:i:s'));
		// Chain the setters
		$excel->setCreator('Gtd')
			->setCompany('Gtd Studio');
		// Call them separately
		$excel->setDescription('导出表');
		$excel->sheet('导出表', function ($sheet) use ($data, $header) {
			// dd($data);
			$exports = [];
			foreach ($data as $key => $info) {
				$exports[$key] = [];
				foreach ($info as $kin => $vin) {
					$exports[$key][] = $vin;
				}
			}
			$sheet->fromArray($exports, null, 'A1');
			foreach ($data as $key => $info) {
				foreach ($info as $kin => $vin) {
					$firstline[] = $kin;
				}
				break;
			}
			$firstline = $header ?? $firstline;
			$sheet->appendRow(1, $firstline);
		});
	})->store('xls', storage_path('app/exports'));
	$path = $filename . '.xls';
	return $path;
}
/**
 * 获取缓存
 * @param  str  $name  city|ca|ct等
 * @return [缓存的数组] $nameCache
 */
function getcache($name, $refresh = false) {
	$nameCache = cache('Cache' . $name);
	if (!$nameCache || $refresh) {
		$service = app(App\Http\Controllers\API\CacheController::class);
		$request = new \Illuminate\Http\Request();
		$request->merge([
			'refresh' => 1]);
		$service->index($request);
		$nameCache = cache('Cache' . $name);
	}
	return $nameCache;
}
function getTid($city_id, $getId = true) {
	$tps = getcache('tp')->where('city_id', $city_id);
	if ($getId) {
		return $tps->where('active', 1)->first();
	} else {
		return $tps;
	}
}
function getCampus($city_id, $getId = '') {
	$tps = getcache('ca')->where('city_id', $city_id);
	if ($getId) {
		return $tps->where('id', $getId)->first();
	} else {
		return $tps;
	}
}
/**
 * 返回给定字符串中已知两个字符中间的值[getNeedBetween description]
 * @param  [type] $kwd   [description]
 * @param  [type] $mark1 [description]
 * @param  [type] $mark2 [description]
 * @return [type]        [description]
 */
function getNeedBetween($kwd, $mark1, $mark2 = '') {
	$kw = $kwd;
	$st = stripos($kw, $mark1);
	$startnum = $st + strlen($mark1);
	if ($mark2 === '') {
		return substr($kw, $startnum);
	}
	$ed = stripos($kw, $mark2);
	if (($st === false || $ed === false) || $st >= $ed) {
		return 0;
	}
	if (($ed + 1) === strlen($kwd)) {
		$endnum = $ed - $st - 1 - strlen($mark2);
	} else {
		$endnum = $ed - $st - 1;
	}
	$kw = substr($kw, $startnum, $endnum);
	return $kw;
}
function checkByPhone($phone, $halt) {
	return (strtolower(e($halt)) == strtolower(md5(($phone) . config('app.IMSK'))));
}
/**
 * [sendcrul description]
 * @param  string $url  网易接受地址只需要传关键位置 如https://api.netease.im/sms/sendcode.action 仅需要 sms/sendcod
 * @param  array $data 提交的数据，一般包含accid 数组格式 http://dev.netease.im/docs?doc=server
 * @param  string $bugpath [相对于storage目录地址 记录]
 * @return [type]          [description]
 */
function sendcrul($url = '', $data = [], $bugpath = 'im/func.log') {
	$service = app('\App\Http\Controllers\API\SmsController');
	// dd($url, $data, $bugpath);
	$res = $service->server_send($url, http_build_query($data), $bugpath);
	return $res;
}
// 分页数据改装
function setItem($pagination, $items) {
	$item = $items instanceof Illuminate\Support\Collection ? $items : Illuminate\Support\Collection::make($items);
	$return = $pagination->toArray();
	$count = $item->count();
	$pagination = [
		'total' => $return['total'],
		'count' => $count,
		'per_page' => $return['per_page'],
		'current_page' => $return['current_page'],
		'total_pages' => $return['last_page'],
	];
	$res['meta'] = ['pagination' => $pagination];
	$res['data'] = $item;
	return $res;
}
// 分页数据改装2
function setItemFrompage($pagination) {
	$return = $pagination->toArray();
	$count = count($return['data']);
	$pagination = [
		'total' => $return['total'],
		'count' => $count,
		'per_page' => $return['per_page'],
		'current_page' => $return['current_page'],
		'total_pages' => $return['last_page'],
	];
	$res['data'] = $return['data'];
	$res['meta'] = ['pagination' => $pagination];
	return $res;
}
function serializeClass(&$classinfo) {
	$Options = [
		'campus' => getcache('ca'),
		'project' => getcache('pr'),
		'classtype' => getcache('ct'),
		'grade' => getcache('gr'),
		'trainingplan' => getcache('tp'),
		'classroom' => getcache('cr'),
		'teacher' => getcache('user'),
	];
	$classinfo['campus'] = isset($Options['campus'][$classinfo['campus_id']]) ? $Options['campus'][$classinfo['campus_id']]['title'] : '';
	$classinfo['campusAddress'] = isset($Options['campus'][$classinfo['campus_id']]) ? $Options['campus'][$classinfo['campus_id']]['address'] : '';
	$classinfo['project'] = isset($Options['project'][$classinfo['project_id']]) ? $Options['project'][$classinfo['project_id']]['title'] : '';
	$classinfo['classtype'] = isset($Options['classtype'][$classinfo['classtype_id']]) ? $Options['classtype'][$classinfo['classtype_id']]['title'] : '';
	$gr = '';
	if (is_array($classinfo['grade_id'])) {
		if (count($classinfo['grade_id']) == 1) {
			$gr = $Options['grade'][$classinfo['grade_id'][0]]['title'] ?? '';
		}
		// foreach ($classinfo['grade_id'] as $grade) {
		// 	// dump($grade, $Options['grade']);
		// 	$gr .= ($Options['grade'][$grade]['title'] ?? '') . ',';
		// }
	} else if (is_numeric($classinfo['grade_id'])) {
		$gr = $Options['grade'][$classinfo['grade_id']]['title'] ?? '';
	} else {
		$grs = json_decode($classinfo['grade_id'], true);
		if (count($grs) == 1) {
			$gr = $Options['grade'][$grs[0]]['title'] ?? '';
		}
		// foreach ($grs as $grade) {
		// 	$gr .= ($Options['grade'][$grade]['title'] ?? '') . ',';
		// }
	}
	$classinfo['grade'] = rtrim($gr, ',');
	if (str_contains($classinfo['title'], '测试')) {
		$classinfo['grade'] = '[测试]' . $classinfo['grade'];
	}
	$classinfo['description'] = '';
	$classinfo['trainingplan'] = $Options['trainingplan'][$classinfo['tid']]['title'];
	try {
		$classinfo['teacher'] = $Options['teacher'][$classinfo['teacher_id']]->only(['id', 'realname', 'displayname', 'username', 'phone', 'headimgurl', 'sex']);
	} catch (\Exception $e) {
		$classinfo['teacher'] = ['id' => '', 'realname' => '', 'displayname' => '', 'username' => '', 'phone' => '', 'headimgurl' => '', 'sex' => ''];
	}
	// if (isset($classinfo['kq'])) {
	// 	foreach ($classinfo['kq'] as $key => $valueKq) {
	// 		serializeClassdetail($valueKq, $Options);
	// 	}
	// }
	// if (isset($classinfo['classdetail'])) {
	// 	foreach ($classinfo['classdetail'] as $key => $valueKq) {
	// 		serializeClassdetail($valueKq, $Options);
	// 	}
	// }
	return $classinfo;
}
function serializeCds(&$classinfo) {
	$Options = [
		'campus' => getcache('ca'),
		'project' => getcache('pr'),
		'classtype' => getcache('ct'),
		'grade' => getcache('gr'),
		'trainingplan' => getcache('tp'),
		'classroom' => getcache('cr'),
		'teacher' => getcache('user'),
	];
	foreach ($classinfo['classdetail'] as $key => $valueKq) {
		serializeClassdetail($valueKq, $Options);
	}
}
function serializeKqs(&$classinfo) {
	$Options = [
		'campus' => getcache('ca'),
		'project' => getcache('pr'),
		'classtype' => getcache('ct'),
		'grade' => getcache('gr'),
		'trainingplan' => getcache('tp'),
		'classroom' => getcache('cr'),
		'teacher' => getcache('user'),
	];
	foreach ($classinfo['kq'] as $key => $valueKq) {
		serializeClassdetail($valueKq, $Options);
	}
}
function serializeStatus(&$classes) {
	// 本来可以写一起的 但是 因为是多人合作 还是把函数分开算了。
	if ($classes['limitbmnum'] == 0) {
		$classes['cantreg'] = 1;
		$classes['statusId'] = 3;
		$classes['status'] = '报满';
	} else {
		$classes['statusId'] = $classes['currentman'] < $classes['limitbmnum'] ? (($classes['currentman'] / $classes['limitbmnum']) < 0.5 ? '1' : '2') : '3';
		switch ($classes['statusId']) {
		case '1':
			$classes['status'] = '新增';
			break;
		case '2':
			// 热报不能转班
			$classes['allowzb'] = 0;
			$classes['status'] = '热报';
			break;
		case '3':
			$classes['allowzb'] = 0;
			$classes['cantreg'] = 1;
			$classes['status'] = '报满';
			break;
		default:
			$classes['status'] = '新增';
			break;
		}
	}
	return $classes;
}
function serializeClassdetail(&$kq, $Options) {
	$kq['classroom'] = isset($Options['classroom'][$kq['classroom_id']]) ? $Options['classroom'][$kq['classroom_id']]['title'] : '';
	$kq['campus'] = isset($Options['campus'][$kq['campus_id']]) ? $Options['campus'][$kq['campus_id']]['title'] : '';
	$kq['campusAddress'] = isset($Options['campus'][$kq['campus_id']]) ? $Options['campus'][$kq['campus_id']]['address'] : '';
	if (isset($kq['teacher_id'])) {
		serializeStudentplan($kq, $Options);
	}
	return $kq;
}
function serializeStudentplan(&$kq, $Options) {
	$kq['teacher'] = isset($Options['teacher'][$kq['teacher_id']]) ? $Options['teacher'][$kq['teacher_id']]['displayname'] : '';
	return $kq;
}
function serializeFollows(&$classinfo, $student_id = '', $unset = true) {

	// dump($classinfo['follow'], $student_id);
	$classinfo['isfollow'] = $classinfo['follows']->where('student_id', $student_id)->count();
	// dump($classinfo['follow']->count(), $student_id);
	$classinfo['follow'] = $classinfo['follows']->count();
	if ($unset) {
		unset($classinfo['follows']);
	}
	return $classinfo;
}
function serializeListen($listeninfo) {
	$Options = [
		'campus' => getcache('ca'),
		'project' => getcache('pr'),
		'classtype' => getcache('ct'),
		'grade' => getcache('gr'),
		'trainingplan' => getcache('tp'),
		'classroom' => getcache('cr'),
		'teacher' => getcache('user'),
	];
	$listeninfo['campus'] = $Options['campus'][$listeninfo['campus_id']]['title'];
	$listeninfo['project'] = $Options['project'][$listeninfo['project_id']]['title'];
	$listeninfo['classtype'] = $Options['classtype'][$listeninfo['classtype_id']]['title'];
	$gr = '';
	if (is_array($listeninfo['grade_id'])) {
		if (count($listeninfo['grade_id']) == 1) {
			$gr = $Options['grade'][$listeninfo['grade_id'][0]]['title'] ?? '';
		}
		// foreach ($listeninfo['grade_id'] as $grade) {
		// 	$gr .= ($Options['grade'][$grade]['title'] ?? '') . ',';
		// }
	} else {
		$gr = $Options['grade'][$listeninfo['grade_id']]['title'] ?? '';
	}
	$listeninfo['grade'] = rtrim($gr, ',');
	return $listeninfo;
}
function classOnly($classes) {
	// $class = [];
	$class = $classes->only([
		'tid',
		'project_id',
		'campus',
		'campusAddress',
		'project',
		'classtype',
		'grade',
		'trainingplan',
		'teacher',
		'classdate',
		'classtime',
		'week',
	]);
	return $class;
}
//推送消息
function push($body, $push_id, $id = '0', $type = '0') {
	if (count($push_id) > 500) {
		$push_num = collect($push_id);
		$push_arr = $push_num->chunk(500)->toArray();
		foreach ($push_arr as $key => $value) {
			dispatch(new \App\Jobs\push($body, $push_id, $id, $type));
		}
	} else {
		// $job = (new \App\Jobs\push($body, $push_id, $id, $type))
		//                   ->delay(\Carbon\Carbon::now()->addMinutes(10));
		// dispatch($job);
		dispatch(new \App\Jobs\push($body, $push_id, $id, $type));
	}
	return true;
	/*
		$app_key = env('PUSHER_APP_ID');
		$master_secret = env('PUSHER_APP_SECRET');
		$client = new \JPush\Client($app_key, $master_secret, storage_path('jpush.log'));
		$push = $client->push()->setPlatform(['ios', 'android']);
		// $push->setNotificationAlert($body);
		$push->iosNotification($body, array(
			'title' => '心田花开提醒您！',
			'extras' => array(
				'id' => $id,
				'type' => $type,
			),
		));
		$push->androidNotification($body, array(
			'title' => '心田花开提醒您！',
			'extras' => array(
				'id' => $id,
				'type' => $type,
			),
		));
		// $push->message(array(
		// 	'title' => '心田花开提醒您！',
		// 	'extras' => array(
		// 		'id' => $id,
		// 		'type' => $type,
		// 	),
		// ));
		$push->addRegistrationId($push_id);
		$options = array(
			'apns_production' => true,
		);
		return $push->options($options);
	*/
}
function pass($res) {
	return trim($res);
}
function getMemory_usage() {
	dump(memory_get_usage());
}
function cdnurl($var) {
	return url($var);
}
function num2chinese($value = '') {
	switch ($value) {
	case '1':
		return '一';
		break;
	case '2':
		return '二';
		break;
	case '3':
		return '三';
		break;
	case '4':
		return '四';
		break;
	case '5':
		return '五';
		break;
	case '6':
		return '六';
		break;
	case '7':
		return '日';
		break;

	default:
		return '一';
		break;
	}
}
function chinese2num($value = '') {
	switch ($value) {
	case '周一':
		return 1;
		break;
	case '周二':
		return 2;
		break;
	case '周三':
		return 3;
		break;
	case '周四':
		return 4;
		break;
	case '周五':
		return 5;
		break;
	case '周六':
		return 6;
		break;
	case '周日':
		return 7;
		break;

	default:
		return 1;
		break;
	}
}
function hiddenTeacher($title, $mark = "班", $start = 0) {
	$position = strrpos($title, $mark);
	return substr($title, $start, $position + 3);
}
// 临时函数
function old2city($city = 1) {
	switch ($city) {
	case '1':
		return '001';
		break;
	case '2':
		return '002';
		break;
	default:
		return '001';
		break;
	}
}
function old2tid($id, $city = 1) {
	switch ($city) {
	case '1':
		return '2';
		break;
	case '2':
		return '1';
		break;
	default:
		return '2';
		break;
	}
}
function old2campus($id, $city = 1) {
	$cd = [
		3 => 6,
		5 => 5,
		6 => 7,
		7 => 10,
		8 => 15,
		9 => 14,
		10 => 8,
		12 => 9,
		11 => 12,
		13 => 13,
		14 => 11,
		15 => 16,
	];
	$cq = [
		1 => 2,
		4 => 3,
		5 => 1,
		6 => 4,
	];
	switch ($city) {
	case '1':
		return $cd[$id] ?? 0;
		break;
	case '2':
		return $cq[$id] ?? 0;
		break;
	default:
		return $cd[$id] ?? 0;
		break;
	}
}
function old2classroom($city = 1) {
	switch ($city) {
	case '1':
		return 229;
		break;
	case '2':
		return 230;
		break;
	default:
		return 229;
		break;
	}
}
function old2datetime($value = '') {
	$d = [];
	$dt = explode(" - ", $value);
	foreach ($dt as $keyt => $singelt) {
		$d['d'][$keyt] = substr($singelt, 0, 10);
		$d['h'][$keyt] = substr($singelt, -5);
	}
	return $d;
}
function old2idcard($value = '', $city = 1) {
	$code = old2city($city);
	$start = 5; //strlen('10120');
	$y = substr($value, $start, 2);
	$g = substr($value, 7, 2);
	if ($g > 9) {
		$dm = substr($value, -5, 5) + 5000;
	} else {
		$g = (int) $g;
		$dm = substr($value, -4, 4);
	}
	return ($y . $code . $g . $dm);
}
function old2regclassmark($value = '') {
	switch ($value) {
	case '2':
		return '-3';
		break;
	default:
		return $value;
		break;
	}
}
function old2classmark($value = '') {
	switch ($value) {
	case '3':
		return '2';
		break;
	case '2':
		return '-1';
		break;
	case '-1':
		return '-2';
		break;
	case '9':
		return '10';
		break;
	case '11':
		return '10';
		break;
	default:
		return $value;
		break;
	}
}
// function old2payment($city_id = 1) {

// }
function std2Array($value = '', $isArray = true) {
	return json_decode(json_encode($value), $isArray);
}
function jsonreturn($value = '', $statu = true, $code = 1, $msg = '') {
	$stash = collect(['data' => '', 'statu' => $statu, 'code' => $code, 'msg' => $msg]);
	$return = $stash->merge($value);
	return response()->json($return);
	// 刷卡机专用
	// dd($return,$sta,$r);
}
function expgoons($value, $yz) {
	// static $counts[$i];
	foreach ($yz as $v) {
		$count[$v] = 0;
	}
	// $count = array_flip(array_values($yz));
	// dd($count);
	// dd($yz);
	//count作为统计返回
	$a = explode(',[', $value);
	$k = array_keys($a);
	$c = array_map("mapgoons", $k, $a);
	// dd($c);
	foreach ($c as $key => $vv) {
		// dd(($vv));
		// $r[] = json_decode($value);
		$dv = json_decode($vv);
		if ($dv) {
			foreach ($dv as $val) {
				// dd($count[array_keys($yz,$val)[0]]);
				// dd(array_keys($yz,$val));
				// dd(($yz[$val]));
				if (isset($yz[$val])) {
					$count[$val]++;
				}

			}
		}
	}
	// dd($count);
	$return['count'] = $count;
	$return['all'] = count($c);
	// $return['counts'] = $counts[$i];
	return $return;
	# code...
}
function mapgoons($key, $val) {
	if ($key > 0) {
		return "[" . $val;
	} else {
		return $val;
	}
}
function bfb($val, $dot) {
	$str = round($val, $dot + 2);
	return sprintf("%." . ($dot) . "f", $str * 100) . '%';
}
function object_to_array($obj) {
	// dd($obj);
	$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
	$arr = [];
	foreach ($_arr as $key => $val) {
		$val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	// dd($arr);
	return $arr;
}
function array_to_object($arr) {
	if (gettype($arr) != 'array') {
		return;
	}
	foreach ($arr as $k => $v) {
		if (gettype($v) == 'array' || getType($v) == 'object') {
			$arr[$k] = (object) array_to_object($v);
		}
	}

	return (object) $arr;
}

function detailsByClp($courseDetails) {
	$d = course2Api($courseDetails, false, false);
	// dd($d);
	$d['details'] = [];
	foreach ($courseDetails['classdetail'] as $key => $value) {
		$value = (is_array($value)) ? $value : $value->toArray();
		$details = array_only($value, ['id', 'num', 'askforleave', 'come', 'total', 's_time', 'e_time', 'extends', 'question', 'ans']);
		$d['details'][] = $details;
	}
	return $d;
}
/**
 * 课程时间转换
 * @param  [type]  $classinfo 课程原数据
 * @param  [type]  &$class    返回新数据
 * @param  boolean $count     是否显示人数
 * @param  boolean $teach     是否处理教师头像
 * @return [type]             &$class
 */
function course2Api($classori, $count = true, $teach = true) {
	// dd($Options);
	// dd($classori);
	$classinfo = (is_array($classori)) ? $classori : $classori->toArray();
	// dd($classinfo);
	$class = [];
	$class['id'] = $classinfo['id'];
	$count && $class['regsCount'] = $classinfo['currentman'];
	if ($teach) {
		if ($classinfo['teacherinfo']['gender'] == '男') {
			$class['avatar'] = env('APP_URL', 'http://' . $_SERVER['SERVER_NAME']) . '/m.png';
		} else {
			$class['avatar'] = env('APP_URL', 'http://' . $_SERVER['SERVER_NAME']) . '/f.png';
		}
		$class['teacher'] = $classinfo['teacherinfo']['nickname'];
	}
	// dd($class);
	$class['teacher_id'] = $classinfo['teacher_id'];
	$class['campus'] = getcache('ca')[$classinfo['campus_id']]['title'];
	$class['project'] = getcache('pr')[$classinfo['project_id']]['title'];
	$class['classtype'] = getcache('ct')[$classinfo['classtype_id']]['title'];
	// $gr = json_decode($classinfo['grade_id']);
	$gr = $classinfo['grade_id'];
	foreach ($gr as $key => $value) {
		$class['grade'][] = $classinfo['grade_id'] ? getcache('gr')[$value]['title'] : '无';
	}
	// $class['grade'] = $classinfo['grade_id']?getcache('grade')[$classinfo['grade_id']]['title']:'无';
	$class['description'] = '';
	// dd($classinfo);
	if ($classinfo['timetype']) {
		$times = $classinfo['classtime'];
		// $times = json_decode($classinfo['classtime']);
		// dd($times);
		// $tt = '';
		// foreach ($times as $key => $value) {
		//  $tt .= $key;
		// }
		// $inf = json_decode($classinfo['week']);
		$class['description'] = $classinfo['title'] . $classinfo['week'] . ',共' . $classinfo['classnum'] . '次课';
		$class['studyDate'] = '每周' . $classinfo['week'];
		$class['studyTime'] = count($times) ? $times[0] . '--' . $times[1] : '';
	} else {
		// $info = timetotime($classinfo['timetotime']);
		// $count = count($info);
		// dd($info);
		$class['description'] = getcache('tp')[$classinfo['tid']]['title'] . ',共' . $classinfo['classnum'] . '次课';
		$class['studyDate'] = '';
		$class['studyTime'] = '';
		$dateclass = $classinfo['classdate'];
		$time = $classinfo['classtime'];
		$res_data = $dateclass[0] . ' -- ' . $dateclass[1];
		$res_time = $time[0] . ' -- ' . $time[1];
		$class['studyDate'] = $res_data;
		$class['studyTime'] = $res_time;
		// foreach ($times as $key => $value) {
		$rr = @getcache('cr')[$classinfo['classroom_id']]['title'];
		// }
		$class['classroom'] = $rr;
	}

	return $class;
}
// end 临时函数
/**
 * 将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 * @access public
 * @param string $str 待转换字串
 * @return string $str 处理后字串
 */
function make_semiangle($str) {
	$arr = ['０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z', '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-', '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.', '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|', '”' => '"', '‘' => '`', '｜' => '|', '〃' => '"', '　' => ' '];
	return strtr($str, $arr);
}
/**
 * 工资计算
 * @param  [type] $num    数目
 * @param  [type] $option 公式
 * @return [type]         [description]
 */
function finan($num, $option) {
	$option = str_replace('num', '$num', $option);
	// dd($option);
	$result = eval("return " . htmlspecialchars_decode($option) . ";");
	// eval("return ".$option)
	// if($num>$dot){
	//   $gz = ($dot*20) + ($num-$dot)*$option;
	// }else{
	//   $gz = $num*20;
	// }
	return $result;
}
/**
 * 创建学生 额外的年份开头[makeStudent description]
 * @param  [type] $request [description]
 * @param  [type] $ext     [description]
 * @return [type]          [description]
 */
function makeStudent($request, $ext = null) {
	$service = app('\App\Http\Controllers\API\StudentController');
	// dd($url, $data, $bugpath);
	$res = json_decode($service->dealStore($request, $ext)->getContent(), true);
	// dd($res);
	return $res;
}
function makeOrder($student, $classplan, $amount = 0, $ispay = 1, $from = 'excel', $remark = null) {
	$service = app('\App\Http\Controllers\API\OrderController');
	// dd($url, $data, $bugpath);
	$res = json_decode($service->excelOrder($student, $classplan, $amount, $ispay, $from, $remark)->getContent(), true);
	// dd($res);
	return $res;
}
function downfile($url, $type = 'xlsx', $path = 'file') {
	$url = str_replace("xthk-OSS.xintianhuakai.cn", "b65f42046e57756520932bc648b05bd1.oss-cn-shenzhen-internal.aliyuncs.com", $url);
	$file = file_get_contents($url);
	$filename = '/' . $path . '/' . md5(time() . rand(0, 10000)) . '.' . $type;
	$a = Storage::disk('local')->put($filename, $file);
	return $filename;
}
function studying($stu_id) {
	// 重新设置老生为 在读生
	$tid_now = getcache('tp')->where('active', '1')->where('city_id', \App\EduModels\Student::find($stu_id)->city_id)->first()->id;
	$class_now = \App\EduModels\Registration::where('student_id', $stu_id)->where('tid', $tid_now)->where('classmark', 0)->where('type', 0)->first();
	return $class_now ? 1 : 2; //1在读|2非在读
}
function isOldStu($stu) {
	return $stu->old ?? 2; //1在读|2非在读
}
