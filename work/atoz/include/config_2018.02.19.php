<?
/// 무슨 작업 이전 백업입니다.
/*
@ 언어팩 세팅
 0 : ko : 국문
 1 : en : 영문
 2 : ch : 중문
 3 : jp : 일문
*/

$_lang = "";
$_lang = $_SESSION['lang'];
if($_lang==""){
	$_lang = 0;
}

if($_SERVER['REMOTE_ADDR'] == "175.113.170.138"){
	//$_lang = 0;
}





// 홈페이지 정보 설정

$use_skin = "sample1";

// 각종 경로 세팅을 하장!!!
define("_IT_SKIN_ROOT",		$_SERVER['DOCUMENT_ROOT']."/skin/".$use_skin."/");
define("_IT_SKIN_ROOT2",	"/skin/".$use_skin."/");

define("_IT_SKIN_INC",	_IT_SKIN_ROOT."inc/");
define("_IT_SKIN_CSS",	_IT_SKIN_ROOT2."css/");
define("_IT_SKIN_IMG",	_IT_SKIN_ROOT2."img/");
define("_IT_SKIN_JS",	_IT_SKIN_ROOT2."js/");



// 홈페이지 정보를 미리 빼두장!
$sql_home_info = " select * from tbl_homeset where idx='1' ";
$result_home_info = mysqli_query($connect, $sql_home_info) or die (mysqli_error($connect));
$row_home_info=mysqli_fetch_array($result_home_info);

define("_IT_SITE_NAME",		$row_home_info['site_name']);
define("_IT_BROWSER_TITLE",	$row_home_info['browser_title']);
define("_IT_META_TAG",		$row_home_info['meta_tag']);
define("_IT_META_KEYWORD",	$row_home_info['meta_keyword']);
define("_IT_HOME_NAME",		$row_home_info['home_name']);
define("_IT_ADDRESS",		$row_home_info['address']);
define("_IT_TOURNUM",		$row_home_info['tournum']);
define("_IT_COMNUM",		$row_home_info['comnum']);
define("_IT_MALL_ORDER",	$row_home_info['mall_order']);
define("_IT_CUSTOM_PHONE",	$row_home_info['custom_phone']);
define("_IT_SMS_PHONE",		$row_home_info['sms_phone']);
define("_IT_EMAIL",			$row_home_info['email']);
define("_IT_MUNNOTE_CODE",	$row_home_info['munnote_code']);
define("_IT_LOGOS",			"/data/home/".$row_home_info['logos']);
define("_IT_COM_OWNER",		$row_home_info['com_owner']);



define("_IT_BANKS",	$row_home_info['banks']);
define("_IT_FAX",	$row_home_info['fax']);

// 도메인 정보
if(isset($_SERVER["HTTPS"])) {  
	$_IT_TOP_PROTOCOL = "https://";
}else{
	$_IT_TOP_PROTOCOL = "http://";
}

// ssl 설정

if($row_home_info['ssl_chk']=="Y"){
	$_ssl_use = true;
}else{
	$_ssl_use = false;
}

if($_ssl_use){	// SSL 사용 설정일 경우
	if(! isSecureDomain() ){
		$_return_url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
		echo "<script type='text/javascript'>";
		echo "location.href='".$_return_url."';";
		echo "</script>";
	}	
}


define("_IT_TOP_DOMAIN",	$_IT_TOP_PROTOCOL.$_SERVER["SERVER_NAME"]);





/*

셀렉트 관련

*/

//-- 언어별 셀렉트명
$_sel_lang['0'] = "ko";
$_sel_lang['1'] = "en";
$_sel_lang['2'] = "ch";
$_sel_lang['3'] = "jp";

$_sel_lang_re['ko'] = '0';
$_sel_lang_re['en'] = '1';
$_sel_lang_re['ch'] = '2';
$_sel_lang_re['jp'] = '3';





/*
관리자 기본 설정 관련
*/

//---- 상품등록

// 시즌
$_adm_season = array();
$_adm_season['1'] = "봄";
$_adm_season['2'] = "여름";
$_adm_season['3'] = "가을";
$_adm_season['4'] = "겨울";
$_adm_season['5'] = "사계절";

// 성별
$_adm_gender = array();
$_adm_gender['1'] = "남성용";
$_adm_gender['2'] = "여성용";
$_adm_gender['3'] = "남여공용";

// 등록구분
$_adm_regist = array();
$_adm_regist['1'] = "신상품";
$_adm_regist['2'] = "땡처리";
$_adm_regist['3'] = "게릴라";


// 과세유형
$_adm_item_tax = array();
$_adm_item_tax['1'] = "과세";
$_adm_item_tax['2'] = "면세";
$_adm_item_tax['3'] = "영세";










// 아래에 쓸데 없이 공백이 생기면 에러 남?>