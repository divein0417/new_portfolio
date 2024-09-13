<?
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


/**************************************
* 자동화 설정 관련
crontab 확인 필요

// 생일 쿠폰
5 0 * * * /usr/bin/curl -s https://www.bontoshop.com/autoexec/auto_birth.php
7 0 * * * /usr/bin/curl -s https://www.bontoshop.com/autoexec/auto_order_del.php
10 0 * * * /usr/bin/curl -s https://www.bontoshop.com/autoexec/auto_free_coupon.php
13 0 * * * /usr/bin/curl -s https://www.bontoshop.com/autoexec/auto_level.php

**************************************/





// 특정아이피만 열어둘때 작업할때
$_SERVICE_OUT = false;
$_agree_ip[] = array();
$_agree_ip[0] = "180.224.124.28";	// 정우
$_agree_ip[1] = "218.55.70.22";		// 사무실

if( $_SERVICE_OUT == true){

	if( in_array( $_SERVER["REMOTE_ADDR"], $_agree_ip) ){
		$block_chk = false;
	}else{
		$block_chk = true;
	}
}

if($block_chk == true){
	echo "<script type='text/javascript'>";
	echo "location.href='/warning_info.php';";
	echo "</script>";
	exit;
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
define("_IT_COM_ADDR",		$row_home_info['addr1']. " " .$row_home_info['addr2']);
define("_IT_BANKS",			$row_home_info['banks']);
define("_IT_FAX",			$row_home_info['fax']);

define("_IT_OWNER",			$row_home_info['com_owner']);
define("_IT_INFO_OWER",		$row_home_info['info_owner']);

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

$_ssl_use = false;

if($_ssl_use){	// SSL 사용 설정일 경우
	if(! isSecureDomain() ){
		$_return_url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
		echo "<script type='text/javascript'>";
		echo "location.href='".$_return_url."';";
		echo "</script>";
	}	
}


// 회원가입시 인증
$_join_chk = "N";	// 사용Y 미사용N

// www 설정
$_domain_dot = "N";

if($_domain_dot == "Y"){
	$_www_use = true;
}else{
	$_www_use = false;
}
$_www_use = false;
if($_www_use){	// SSL 사용 설정일 경우
	
	if( isDotDomain() ){
		$_return_url = $_IT_TOP_PROTOCOL . "www." . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
		echo "<script type='text/javascript'>";
		echo "location.href='".$_return_url."';";
		echo "</script>";
	}
	/*
	if( isDotDomain2() ){
		$_tmp_url = str_replace("www.","",$_SERVER['SERVER_NAME']);
		$_return_url = $_IT_TOP_PROTOCOL . "" . $_tmp_url . $_SERVER['REQUEST_URI'];
		
		
		echo "<script type='text/javascript'>";
		echo "location.href='".$_return_url."';";
		echo "</script>";
		
	}
	*/
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


// 배송 상태관련
$_deli_type['D'] = "결제대기";
$_deli_type['B'] = "결제대기(무통장)";
$_deli_type['E'] = "결제완료";
$_deli_type['R'] = "배송준비중";
$_deli_type['I'] = "배송중";
$_deli_type['M'] = "배송완료";
$_deli_type['P'] = "교환/반품중";
$_deli_type['C'] = "결제취소";

// SMS 문구

$_sms_text['S'] = "[[[_IT_SITE_NAME]]] [[name]] 고객님! [주문번호 : [[order_code]]] 주문해주셔서 감사합니다.";
//$_sms_text['G'] = "[[[_IT_SITE_NAME]]] [[name]] 님 가입을 진심으로 축하드립니다. 쇼핑 적립금 [[JOIN_POINT]]원을 선물로 넣어드렸습니다.본토 많은 애용 부탁드립니다.^^본토바로가기->http://www.bornto.co.kr";

$_sms_text['G'] = "[[[_IT_SITE_NAME]]] [[name]] 님 가입을 진심으로 축하드립니다. 본토 많은 애용 부탁드립니다.^^본토바로가기->http://www.bontoshop.com";


$rid = "0517151365";


// 무통장 입금시 계좌 정보
$_direct_shop_bank = "우리은행";
$_direct_shop_bank_num = "1005-403-623486";

/*
PG사 관련
*/


// PG사 테스트 결제
$_pg_pay_test = "N";	// 테스트 : Y , 실결제 : N


// PG 테스트 결제 금액
$_pg_pay_test_price = "0";	// 가격 입력시 해당 가격으로 결제, 0 또는 음수 입력시 실제 가격



// 결제방법
$_pg_Method = array();
$_pg_Method['Card']			= "신용/체크카드";
$_pg_Method['VCard']			= "신용/체크카드";
$_pg_Method['CARD']			= "신용/체크카드";	// 모바일
$_pg_Method['DirectBank']	= "실시간계좌이체";
$_pg_Method['BANK']			= "실시간계좌이체";	// 모바일
$_pg_Method['Vbank']		= "무통장(가상계좌)";
$_pg_Method['VBank']		= "무통장(가상계좌)";
$_pg_Method['VBANK']		= "무통장(가상계좌)";	// 모바일
$_pg_Method['Dbank']		= "무통장입금";
$_pg_Method['Point']		= "포인트";
$_pg_Method['']				= "";





// 카드정보
$_pg_Card = array();
$_pg_Card['01'] = "하나(외환)";
$_pg_Card['03'] = "롯데";
$_pg_Card['04'] = "현대";
$_pg_Card['06'] = "국민";
$_pg_Card['11'] = "BC";
$_pg_Card['12'] = "삼성";
$_pg_Card['14'] = "신한";
$_pg_Card['15'] = "한미";
$_pg_Card['16'] = "NH";
$_pg_Card['17'] = "하나카드";
$_pg_Card['21'] = "해외 VISA";
$_pg_Card['22'] = "해외마스터";
$_pg_Card['23'] = "해외 JCB";
$_pg_Card['24'] = "해외아멕스";
$_pg_Card['25'] = "해외다이너스";
$_pg_Card['26'] = "중국온련";
$_pg_Card['32'] = "광주";
$_pg_Card['33'] = "전북";
$_pg_Card['34'] = "하나";
$_pg_Card['35'] = "산업카드";
$_pg_Card['41'] = "NH";
$_pg_Card['43'] = "씨티";
$_pg_Card['44'] = "우리";
$_pg_Card['48'] = "신협체크";
$_pg_Card['51'] = "수협";
$_pg_Card['52'] = "제주";
$_pg_Card['54'] = "MG새마을금고체크";
$_pg_Card['55'] = "케이뱅크";
$_pg_Card['56'] = "카카오뱅크";
$_pg_Card['71'] = "우체국체크";
$_pg_Card['95'] = "저축은행체크";



// 은행정보
$_pg_Bank = array();
$_pg_Bank['04'] = "국민은행";
$_pg_Bank['05'] = "하나은행 (구외환)";
$_pg_Bank['06'] = "국민은행 (구 주택)";
$_pg_Bank['07'] = "수협중앙회";
$_pg_Bank['11'] = "농협중앙회";
$_pg_Bank['12'] = "단위농협";
$_pg_Bank['16'] = "축협중앙회";
$_pg_Bank['20'] = "우리은행";
$_pg_Bank['21'] = "신한은행 (조흥은행)";
$_pg_Bank['23'] = "제일은행";
$_pg_Bank['25'] = "하나은행 (서울은행)";
$_pg_Bank['26'] = "신한은행";
$_pg_Bank['27'] = "한국씨티은행 (한미은행)";
$_pg_Bank['31'] = "대구은행";
$_pg_Bank['32'] = "부산은행";
$_pg_Bank['34'] = "광주은행";
$_pg_Bank['35'] = "제주은행";
$_pg_Bank['37'] = "전북은행";
$_pg_Bank['38'] = "강원은행";
$_pg_Bank['39'] = "경남은행";
$_pg_Bank['41'] = "비씨카드";
$_pg_Bank['53'] = "씨티은행";
$_pg_Bank['54'] = "홍콩상하이은행";
$_pg_Bank['71'] = "우체국";
$_pg_Bank['81'] = "하나은행";
$_pg_Bank['83'] = "평화은행";
$_pg_Bank['87'] = "신세계";
$_pg_Bank['88'] = "신한은행(조흥 통합)";
$_pg_Bank['97'] = "카카오 머니";
$_pg_Bank['98'] = "페이코 (포인트 100% 사용)";




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
$_adm_regist['1'] = "신상꺼리";
$_adm_regist['2'] = "본토회원DEAL";
$_adm_regist['3'] = "본토장터";
$_adm_regist['4'] = "9장 DEAL";
$_adm_regist['5'] = "박리다매 DEAL";
$_adm_regist['6'] = "예약판매";


// 과세유형
$_adm_item_tax = array();
$_adm_item_tax['1'] = "과세";
$_adm_item_tax['2'] = "면세";
$_adm_item_tax['3'] = "영세";


// 회원 정보 관련
$_set_member_level = array();
$_set_member_level['1'] = "관리자";
$_set_member_level['2'] = "운영자";
$_set_member_level['6'] = "토박이";
$_set_member_level['7'] = "단골 수";
$_set_member_level['8'] = "단골 우";
$_set_member_level['9'] = "정회원";
$_set_member_level['0'] = "탈퇴회원";

// 회원별 등업 조건 (해당 레벨에 누적 금액이 일치하면 1레벨 상승함
$_set_member_limit = array();
$_set_member_limit['7'] = "10000000";
$_set_member_limit['8'] = "6000000";
$_set_member_limit['9'] = "2000000";


// 회원 등급 별 할인율 (퍼센트)
$_set_member_dcprice['6'] = "2";
$_set_member_dcprice['7'] = "1";
$_set_member_dcprice['8'] = "1";
$_set_member_dcprice['9'] = "0";


// 회원 등급 별 적립금 (퍼센트)
$_set_member_point['6'] = "3";
$_set_member_point['7'] = "2";
$_set_member_point['8'] = "2";
$_set_member_point['9'] = "1";


// 회원가입 축하 쿠폰 (퍼센트)
$_set_member_agree = "5";

// 회원가입 축하 포인트
$_set_member_agree_point = "1000";


// 생일 쿠폰 (퍼센트)
$_set_member_birth = "5";


// 회원별 승급기념 쿠폰 (퍼센트)
$_set_member_upgrade['6'] = "5";
$_set_member_upgrade['7'] = "3";
$_set_member_upgrade['8'] = "2";
$_set_member_upgrade['9'] = "0";


// 무료배송쿠폰 횟수 (월 / 회)
// 6레벨 회원은 무조건 무료
$_set_member_free_baesong['7'] = "2";
$_set_member_free_baesong['8'] = "1";
$_set_member_free_baesong['9'] = "0";


// 웰컴백 쿠폰 (퍼센트)
$_set_member_welcome['6'] = "10";
$_set_member_welcome['7'] = "7";
$_set_member_welcome['8'] = "5";
$_set_member_welcome['9'] = "3";


// 웰컴백 기간 (개월)
$_set_welcome_month = 3;

// 리뷰 적립 포인트
$_review_write_point = "500";

// 추천 적립금
$_reco_point = "3000";

// 추천 가능 횟수
$_reco_cnts = "3";


// 최종 결제 금액의 몇% 이내 포인트를 사용하게 할것인지...
$_last_use_point_per = "50";

// 탈퇴 후 정보 삭제 기간 ( 재가입 가능 시간)
$_mem_info_del_date = "30";


//----- 관리자 메뉴 관련
/*
	관리자 권한 관련 설정
*/

// 고객센터
$_Adm_grant_top_name[0]	   = "고객센터";
$_Adm_grant_code[0][0] = "A1";
$_Adm_grant_code[0][1] = "A2";
$_Adm_grant_code[0][2] = "A3";
$_Adm_grant_name[0][0] = "공지사항";
$_Adm_grant_name[0][1] = "자주묻는질문";
$_Adm_grant_name[0][2] = "고객후기";


// 회원관리
$_Adm_grant_top_name[1]    = "회원관리";
$_Adm_grant_code[1][0] = "B1";
$_Adm_grant_code[1][1] = "B2";
$_Adm_grant_code[1][2] = "B3";
$_Adm_grant_name[1][0] = "회원관리";
$_Adm_grant_name[1][1] = "이메일 관리";
$_Adm_grant_name[1][2] = "SMS 관리";

// 문의
$_Adm_grant_top_name[2]    = "문의";
$_Adm_grant_code[2][0] = "C1";
$_Adm_grant_code[2][1] = "C2";
$_Adm_grant_name[2][0] = "1:1 문의하기";
$_Adm_grant_name[2][1] = "가맹/제휴문의";

// 코드관리
$_Adm_grant_top_name[3]    = "코드관리";
$_Adm_grant_code[3][0] = "D1";
$_Adm_grant_code[3][1] = "D2";
$_Adm_grant_code[3][2] = "D3";
$_Adm_grant_code[3][3] = "D4";
$_Adm_grant_code[3][4] = "D5";
$_Adm_grant_name[3][0] = "카테고리";
$_Adm_grant_name[3][1] = "분류";
$_Adm_grant_name[3][2] = "브랜드";
$_Adm_grant_name[3][3] = "국가";
$_Adm_grant_name[3][4] = "아이콘관리";


// 상품관리
$_Adm_grant_top_name[4]    = "상품관리";
$_Adm_grant_code[4][0] = "E1";
$_Adm_grant_code[4][1] = "E2";
$_Adm_grant_name[4][0] = "상품관리";
$_Adm_grant_name[4][1] = "상품후기";


// 주문관리
$_Adm_grant_top_name[5]    = "주문관리";
$_Adm_grant_code[5][0] = "F1";
$_Adm_grant_name[5][0] = "주문관리";


// 배너관리
$_Adm_grant_top_name[6]    = "배너관리";
$_Adm_grant_code[6][0] = "G1";
$_Adm_grant_code[6][1] = "G2";
$_Adm_grant_code[6][2] = "G3";
$_Adm_grant_code[6][3] = "G4";
$_Adm_grant_name[6][0] = "메인배너";
$_Adm_grant_name[6][1] = "광고배너(상단)";
$_Adm_grant_name[6][2] = "광고배너(하단)";
$_Adm_grant_name[6][3] = "조리영상";


// 기본설정
$_Adm_grant_top_name[7]    = "기본설정";
$_Adm_grant_code[7][0] = "H1";
$_Adm_grant_code[7][1] = "H2";
$_Adm_grant_code[7][2] = "H3";
$_Adm_grant_code[7][3] = "H4";
$_Adm_grant_code[7][4] = "H5";
$_Adm_grant_code[7][5] = "H6";
$_Adm_grant_name[7][0] = "쇼핑몰 기본설정";
$_Adm_grant_name[7][1] = "운영자 계정관리";
$_Adm_grant_name[7][2] = "관리자 접속IP";
$_Adm_grant_name[7][3] = "정책 설정";
$_Adm_grant_name[7][4] = "배송사관리";
$_Adm_grant_name[7][5] = "약관 및 정책";


// 통계
$_Adm_grant_top_name[8]    = "통계";
$_Adm_grant_code[8][0] = "I1";
$_Adm_grant_code[8][1] = "I2";
$_Adm_grant_code[8][2] = "I3";
$_Adm_grant_code[8][3] = "I4";
$_Adm_grant_code[8][4] = "I5";
$_Adm_grant_name[8][0] = "주문분석";
$_Adm_grant_name[8][1] = "매출분석";
$_Adm_grant_name[8][2] = "방문분석";
$_Adm_grant_name[8][3] = "상품분석";
$_Adm_grant_name[8][4] = "회원분석";


// 운영관리
$_Adm_grant_top_name[9]    = "운영관리";
$_Adm_grant_code[9][0] = "J1";
$_Adm_grant_code[9][1] = "J2";
$_Adm_grant_code[9][2] = "J3";
$_Adm_grant_code[9][3] = "J4";
$_Adm_grant_code[9][4] = "J5";
$_Adm_grant_name[9][0] = "쿠폰관리";
$_Adm_grant_name[9][1] = "쿠폰설정";
$_Adm_grant_name[9][2] = "TOP5";
$_Adm_grant_name[9][3] = "인기상품";
$_Adm_grant_name[9][4] = "팝업관리";








// 아이피 차단
$block_chk = false;

$sql_ips = "select * from tbl_block_ip ";
$result_ips = mysqli_query($connect, $sql_ips);
while($row_ips = mysqli_fetch_array($result_ips)){
	$block_ip[$row_ips['idx']] = $row_ips['ips'];
}
	

foreach($block_ip as $keys=>$values){

	$tmp_size = strlen($values);


	if(substr($_SERVER["REMOTE_ADDR"],0,$tmp_size) == $values){
		$block_chk = true;
	}
}

if($block_chk==true){

?>
<script type="text/javascript">
	var block_ip = "<?=$block_chk?>";
	if(block_ip==1){
		alert("차단된 IP입니다.\n관리자에게 문의해주세요.");
		location.href="/main/logout.php";
	}
</script>
<?
}








// 아래에 쓸데 없이 공백이 생기면 에러 남?>