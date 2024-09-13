<?php
@session_start();
@ob_start();
@header("Content-Type: text/html; charset=utf-8");


@extract($_GET);
@extract($_POST);
@extract($_COOKIE);


// 에러 리포팅
//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

$SMTP_CONNECT[HOST] = "smtp.cafe24.com";
$SMTP_CONNECT[ID] = base64_encode("contact@godstagram.com");
$SMTP_CONNECT[PW] = base64_encode("1a082507");

// 문자 발신 번호
define("_SendSmsPhone",	"010-4067-8400");

// DB 커네팅 정보
include $_SERVER['DOCUMENT_ROOT']."/include/db_config.php";

// 환경 정보
include $_SERVER['DOCUMENT_ROOT']."/include/config.php";


// 언어팩 인크루드
foreach (glob($_SERVER['DOCUMENT_ROOT']."/language/*.php", GLOB_BRACE) as $filename) {
    //echo "basename : " . basename($filename) . "<br/>";
	include $_SERVER['DOCUMENT_ROOT']."/language/".basename($filename);
}


$iPod		= stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
$iPhone	= stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
$iPad		= stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
$Android	= stripos($_SERVER['HTTP_USER_AGENT'], "Android");

/*
if($iPod > 0 || $iPhone > 0 || $iPad > 0 || $Android > 0){
	if($_SERVER['REMOTE_ADDR'] != "110.15.164.83"){
?>
<script>
	location.href="/mobile_no.php";
</script>
<?
	}
}
*/

function isSecureDomain() {
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
}

function isDotDomain() {
    return
        (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.');
}

function isDotDomain2() {
    return
        (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.');
}

function imageWaterMaking($ARGimagePath, $ARGwaterMakeSourceImage, $ARGimageQuality = 100){

	#####----- 이미지 정보 가져오기 -----#####
	$getSourceImageInfo = GETIMAGESIZE($ARGimagePath);
	#####----- 원본 이미지 검사 -----#####
	if(!$getSourceImageInfo[0]){
		return ARRAY(0, "!!! 원본 이미지가 존재하지 않습니다. !!!");
	}
	$getwaterMakeSourceImageInfo = GETIMAGESIZE($ARGwaterMakeSourceImage);
	#####----- 워터마크 이미지 검사 -----#####
	if(!$getwaterMakeSourceImageInfo[0]){
		return ARRAY(0, "!!! 워터마크 이미지가 존재하지 않습니다. !!!");
	}

	#####----- 원본 이미지 생성(로드) -----#####
	switch($getSourceImageInfo[2]){
		case 1 :        #####----- GIF 포맷 형식 -----#####
			$sourceImage = IMAGECREATEFROMGIF($ARGimagePath);
			break;
		case 2 :        #####----- JPG 포맷 형식 -----#####
			$sourceImage = IMAGECREATEFROMJPEG($ARGimagePath);
			break;
		case 3 :        #####----- PNG 포맷 형식 -----#####
			$sourceImage = IMAGECREATEFROMPNG($ARGimagePath);
			break;
		default :        #####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
			return array(0, "!!! 원본이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
	}

	#####----- 워터마크 이미지 생성(로드) -----#####
	switch($getwaterMakeSourceImageInfo[2]){
		case 1 :        #####----- GIF 포맷 형식 -----#####
			$waterMakeSourceImage = IMAGECREATEFROMGIF($ARGwaterMakeSourceImage);
			break;
		case 2 :        #####----- JPG 포맷 형식 -----#####
			$waterMakeSourceImage = IMAGECREATEFROMJPEG($ARGwaterMakeSourceImage);
			break;
		case 3 :        #####----- PNG 포맷 형식 -----#####
			$waterMakeSourceImage = IMAGECREATEFROMPNG($ARGwaterMakeSourceImage);
			break;
		default :        #####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
			return array(0, "!!! 워터마크이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
	}


	#####----- 워터마크 위치 구하기(중앙에 워터마크 삽입) -----#####
	$waterMakePositionWidth = ($getSourceImageInfo[0] - $getwaterMakeSourceImageInfo[0]) / 2;
	$waterMakePositionHeight = ($getSourceImageInfo[1] - $getwaterMakeSourceImageInfo[1]) / 2;

	#####----- 이미지 그리기 -----#####
	/**
	 *        $save_image=ImageCreate($save_path_width_size, $save_path_height_size) 부분에 원본이미지로 부터 복사본을 그린다.
	 *        $arg1                :                ImageCreateTrueColor 리턴 인자(붙여넣기 할 이미지)
	 *        $arg2                :                ImageCreateFromXXX 리턴 인자(복사할 이미지)
	 *        $arg3                :                붙여넣기 할 이미지의 X 시작점
	 *        $arg4                :                붙여넣기 할 이미지의 Y 시작점
	 *        $arg5                :                복사할 이미지의 X 시작점
	 *        $arg6                :                복사할 이미지의 Y 시작점
	 *        $arg7                :                붙여넣기 할 이미지의 X 끝점
	 *        $arg8                :                붙여넣기 할 이미지의 Y 끝점
	 *        $arg9                :                복사할 이미지의 X 끝점
	 *        $arg10                :                복사할 이미지의 Y 끝점
	 */
	IMAGECOPYRESIZED($sourceImage, $waterMakeSourceImage, $waterMakePositionWidth, $waterMakePositionHeight, 0, 0, ImageSX($waterMakeSourceImage), ImageSY($waterMakeSourceImage), ImageSX($waterMakeSourceImage), ImageSY($waterMakeSourceImage));

	#####----- 이미지 저장 -----#####
	switch($getSourceImageInfo[2]){
		case 1 :        #####----- GIF 포맷 형식 -----#####
			if(IMAGEGIF($sourceImage, $ARGimagePath, $ARGimageQuality)){
				return ARRAY(1, "GIF 형식 워터마크 이미지가 처리 되었습니다.");
			}else{
				return ARRAY(0, "GIF 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
			}
			break;
		case 2 :        #####----- JPG 포맷 형식 -----#####
			if(IMAGEJPEG($sourceImage, $ARGimagePath, $ARGimageQuality)){
				return ARRAY(1, "JPG 형식 워터마크 이미지가 처리 되었습니다.");
			}else{
				return ARRAY(0, "JPG 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
			}
			break;
		case 3 :        #####----- PNG 포맷 형식 -----#####
			if(IMAGEPNG($sourceImage, $ARGimagePath, $ARGimageQuality)){
				return ARRAY(1, "PNG 형식 워터마크 이미지가 처리 되었습니다.");
			}else{
				return ARRAY(0, "PNG 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
			}
			break;
		default :        #####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
			return ARRAY(0, "!!! 원본마크이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
	}

}



//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
// 시작할때 마이크로 타임 구함
//-------------------------------------------------------------------------------------------------
//$start_time=getmicrotime();

// 현재 일자, 시간 구함
$curdate = date("Y-m-d", time());
$curtime = date("H:i:s", time());
$now     = $curdate . " " . $curtime;


//사용함수
function alert_msg($msg, $url="") {
    global $HTTP_REFERER, $g_dir, $g_homepage_index;

    if ($url == "")
    {
        $url_go = "history.go(-1)";
    }
    elseif ($url == "close"){
     $url_go = "window.close()";
    }

    else{
        //$url_go = "document.location.href = '$url'";
		$url_go = "setTimeout( function() {					";
		$url_go .= "	document.location.href='".$url."';	";
		$url_go .= "}, 1000);								";
    }

    if ($msg != "")
        echo "<script language='javascript'>alert('$msg');$url_go;</script>";
    else
        echo "<script language='javascript'>$url_go;</script>";
    exit;
}

function alert_only($msg) {
    echo "<script language='javascript'>alert('$msg');</script>";
}

function alert_only_reload($msg) {
    echo "<script language='javascript'>alert('$msg');parent.location.reload();</script>";
}


function parent_reload() {
    //echo "<script language='javascript'>parent.location.reload();</script>";
	
	echo "<script language='javascript'>		";
	echo "setTimeout( function() {				";
	echo "	parent.location.reload()';	";
	echo "}, 1000);								";
	echo "</script>								";
}

function parent_location($url) {

	//echo "<script language='javascript'>parent.location.href='".$url."';</script>";

    echo "<script language='javascript'>		";
	echo "setTimeout( function() {				";
	echo "	parent.location.href='".$url."';	";
	echo "}, 1000);								";
	echo "</script>								";
}



// 파일의 확장자 검사
// check_file_ext("파일명", "허용확장자리스트 ;로 구분");
function check_file_ext($filename, $allow_ext)
{
	$filename = strtolower($filename);
	if ($filename == "") return true;
	$ext = get_file_ext($filename);
	$allow_ext = explode(";", $allow_ext);
	$sw_allow_ext = false;
	for ($i=0; $i<count($allow_ext); $i++)
    	if ($ext == $allow_ext[$i]) // 허용하는 확장자라면
    	{
        	$sw_allow_ext = true;
        	break;
        }
    return $sw_allow_ext;
}
function no_file_ext($filename)
{

	 $ext = explode(".", strtolower($filename));
	$chk = "Y";
	 $cnt = count($ext)-1;
	  if($ext[$cnt] === ""){
		 if(@preg_match($ext[$cnt-1], "php|php3|php4|htm|inc|html|xls|exe")){
		$chk = "N";
		 }
	  } else if(@preg_match($ext[$cnt], "php|php3|php4|htm|inc|html|xls|exe")){
		$chk = "N";
	  }

	 return $chk;
  }
//-------------------------------------------------------------------------------------------------
function upload_file($srcfile, $destfile, $dir)
{
	if ($destfile == "") return false;
    // 업로드 한후 , 퍼미션을 변경함
	@move_uploaded_file($srcfile, "$dir/$destfile");
	@chmod("$dir/$destfile", 0666);
	return true;
}
//-------------------------------------------------------------------------------------------------
function get_file_ext($filename)
{
	if ($filename == "") return "";
	$type = explode(".", $filename);
	$ext = strtolower($type[count($type)-1]);
	return $ext;
}
//-------------------------------------------------------------------------------------------------
// HTML 특수문자 변환 htmlspecialchars
function htmlspecialchars2($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}
//----------------------------------------------------------------------------------------------------------------------------

// mailer
function mailer($nameFrom, $mailFrom, $mailTo, $subject, $content, $code) {
	global $connect;
	$title = $subject;

    $nameTo  = "회원";

    $charset = "UTF-8";
    $nameFrom   = "=?$charset?B?".base64_encode($nameFrom)."?=";
    $nameTo   = "=?$charset?B?".base64_encode($nameTo)."?=";
    $subject = "=?$charset?B?".base64_encode($subject)."?=";

    $header  = "Content-Type: text/html; charset=utf-8\r\n";
    $header .= "MIME-Version: 1.0\r\n";

    $header .= "Return-Path: <". $mailFrom .">\r\n";
    $header .= "From: ". $nameFrom ." <". $mailFrom .">\r\n";
    $header .= "Reply-To: <". $mailFrom .">\r\n";

    //@mail($mailTo, $subject, $content, $header, $mailFrom);
	@mail($mailTo, $subject, $content, $header);
	//@mail($mailTo, $subject, $content);

	$fsql = " insert into tbl_email_log set
				  code		= '".$code."'
				, title		= '".$title."'
				, content	= '".updateSQ($content)."'
				, tomail	= '".$mailTo."'
				, regdate	= now()
			 ";
	mysqli_query($connect, $fsql) or die (mysqli_error($connect));

}
//-------------------------------------------------------------------------------------------------------------------
//글자 자르기.
function cutstr($str, $size)
{
  $substr = substr($str, 0, $size*2);
  $multi_size = preg_match_all('/[\x80-\xff]/', $substr, $multi_chars);

  if($multi_size >0)
   $size = $size + intval($multi_size/3)-1;

  if(strlen($str)>$size)
  {
   $str = substr($str, 0, $size);
   $str = preg_replace('/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str);
   $str .= '...';
  }

  return $str;
}



function cutstr1($msg, $cut_size, $tail="") {
  if ($cut_size<=0) return $msg;

  // 계속이어쓰는 문자열을 자른다.
  $max_len = 70;
  if(strlen($msg) > $max_len)
    if(!eregi(" ", $msg))
      $msg = substr($msg,0,$max_len);

  for($i=0;$i<$cut_size;$i++)
    if(@ord($msg[$i])>127) $han++;
    else $eng++;

  $cut_size=$cut_size+(int)$han*0.6;

  //echo $cut_size; exit;
  $snow=1;
  for ($i=0;$i<strlen($msg);$i++) {
    if ($snow>$cut_size) { return $snowtmp.$tail;}
    if (ord($msg[$i])<=127) {
      $snowtmp.= $msg[$i];
      if ($snow%$cut_size==0) { return $snowtmp.$tail; }
    } else {
      if ($snow%$cut_size==0) { return $snowtmp.$tail; }
      $snowtmp.=$msg[$i].$msg[++$i];
      $snow++;
    }
    $snow++;
  }
  return $snowtmp;
}
//-------------------------------------------------------------------------------------------------
// 공란없이 이어지는 문자 자르기
function continue_cut_str($str, $len=80)
{
        $pattern = "[^ \n<>]{".$len."}";
    return eregi_replace($pattern, "\\0\n", $str);
}
//-------------------------------------------------------------------------------------------------
// 일자 시간 (mm-dd hh:ii) 표시
function fdatetime($dt) {
  $s = substr($dt,5,11);
  if($s=="00-00 00:00") $s = "&nbsp;";
  return $s;
}
//-------------------------------------------------------------------------------------------------

$mouseover = "class=mout onmouseout=this.className='mout' onmouseover=this.className='mover'";


//------------------------------------------------------------------------------------------------------------------------------
function gotourl($url) {
    echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
    exit;
}
//------------------------------------------------------------------------------------------------
function get_skin_dir($val)
{
    global $g_rel_dir;
    $result_array = array();

    $dirname = "$g_rel_dir/$val/";
    $handle = opendir($dirname);
    while ($file = readdir($handle)) {

        if($file == "."||$file == "..") {
            continue;
        }

        if (is_dir($dirname.$file)) {
            $result_array[] = $file;
        }
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}



function pagelisting($cur_page, $total_page, $n, $url) {
  $retValue = "<div id='btnpage'><ul>";
  if ($cur_page > 1) {
    $retValue .= "<li><a href='" . $url . "1'>&lt;&lt; 처음</a></li>";
    $retValue .= "<li> <a href='" . $url . ($cur_page-1) . "'>&lt; 이전</a></li>";
  } else {
    $retValue .= "<li><a href='" . $url . "1'>&lt;&lt; 처음</a></li>";
    $retValue .= "<li><a href='#'>&lt; 이전</a></li>";
  }
  $retValue .= "";
  $start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
  $end_page = $start_page + 9;
  if ($end_page >= $total_page) $end_page = $total_page;
  if ($total_page >= 1)
    for ($k=$start_page;$k<=$end_page;$k++)
      if ($cur_page != $k) $retValue .= " <li class='number'><a href='$url$k'>$k</a></li>";
      else $retValue .= " <li class='number'><a href='#' class='now'>$k</a></li>";
//  if ($total_page > $end_page) $retValue .= "<a href='" . $url . ($end_page+1) . "'>...</a> ";
  $retValue .= "";
  if ($cur_page < $total_page) {
    $retValue .= "<li><a href='$url" . ($cur_page+1) . "'>다음 &gt;</a></li>";
    $retValue .= "<li><a href='$url$total_page'>맨끝 &gt;&gt;</a></td>";
  } else {
    $retValue .= "<li><a href='#'>다음 &gt;</a></li>";
    $retValue .= "<li><a href='#'>맨끝 &gt;&gt;</a></li>";
  }
  $retValue .= "</ul></div>";
  return $retValue;
}

function apagelisting($cur_page, $total_page, $n, $url) {
  $retValue = "<div class='pagination' style='margin:0 auto;text-align:center'><ul>";
  if ($cur_page > 1) {
    $retValue .= "<li><a href='" . $url . "1'><<</a></li>";
    $retValue .= "<li><a href='" . $url . ($cur_page-1) . "'><</a></li>";
  } else {
    $retValue .= "<li><a href='" . $url . "1'><<</a></li>";
    $retValue .= "<li><a href='#'><</a></li>";
  }
  $retValue .= "";
  $start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
  //echo (int)(($cur_page - 1) / 10);
  $end_page = $start_page + 9;
  if ($end_page >= $total_page) $end_page = $total_page;
//  if ($start_page > 1) $retValue .= " <a href='" . $url . ($start_page-1) . "'>...</a> ";
  if ($total_page >= 1)
    for ($k=$start_page;$k<=$end_page;$k++)
      if ($cur_page != $k) $retValue .= " <li><a href='$url$k'>$k</a></li> ";
      else $retValue .= " <li class='active'><A href='#'>$k</a></li> ";
//  if ($total_page > $end_page) $retValue .= "<a href='" . $url . ($end_page+1) . "'>...</a> ";
  $retValue .= "";
  if ($cur_page < $total_page) {
    $retValue .= "<li><a href='$url" . ($cur_page+1) . "'>></a></li>";
    $retValue .= "<li><a href='$url$total_page'>>></a></li>";
  } else {
    $retValue .= "<li><a href='#'>></a></li>";
    $retValue .= "<li><a href='#'>>></a></li>";
  }
  $retValue .= "</ul></div>";
  return $retValue;
}


function ajaxlisting($cur_page, $total_page, $n, $url) {
	global $g_list_rows;
	$pbgn = $cur_page - (($cur_page-1) % $g_list_rows) ;
	$pend = $cur_page + 10 - (($cur_page-1) % 10) -1;

	$retValue = "<div class='paging'>";
	if ($pend > 10) {
	$intl=$pend-10;
		$retValue .= " <a href='javascript:javascript:product_page_it($intl)'><img src='/kor/images/common/btn_p_first.gif' alt='처음으로' /></a> ";
		$retValue .= " <a href='javascript:javascript:product_page_it(" . ($cur_page-1) . ")'><img src='/kor/images/common/btn_p_prev.gif' alt='이전으로' /></a> ";
		$retValue .= " <span>";
		$retValue .= " <a href='javascript:javascript:product_page_it(1)'>1</a>... ";
	} else {
		$retValue .= " <a href='#'><img src='/kor/images/common/btn_p_first.gif' alt='처음으로' /></a> ";
		if ($cur_page == 1) {
		$retValue .= " <a href='#'><img src='/kor/images/common/btn_p_prev.gif' alt='이전으로' /></a> ";
		} else {
		$retValue .= " <a href='javascript:product_page_it("  . ($cur_page-1).")'><img src='/kor/images/common/btn_p_prev.gif' alt='이전으로' /></a> ";
		}
		$retValue .= " <span>";
	}
	$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
	$end_page = $start_page + 10-1;
	$intl=$pbgn;
	if ($end_page >= $total_page) $end_page = $total_page;
	if ($total_page >= 1)
	for ($k=$start_page;$k<=$end_page;$k++)
	if ($cur_page != $k) $retValue .= " <a href='javascript:product_page_it($k)'>$k</a> ";
	else $retValue .= " <strong>$k</strong> ";

	$intl = $pend+1;
	if ($intl < $total_page) {
		$retValue .= " ...<a href='javascript:product_page_it(".($total_page).")'>$total_page</a>";
		$retValue .= " </span>";
		$retValue .= " <a href='javascript:product_page_it("  . ($cur_page+1).")'><img src='/kor/images/common/btn_p_next.gif' alt='다음으로' /></a> ";
		$retValue .= " <a href='javascript:product_page_it("  . ($intl).")'><img src='/kor/images/common/btn_p_last.gif' alt='마지막으로' /></a> ";
	} else {
		$retValue .= "</span>";
		if ($cur_page == $total_page) {
		$retValue .= " <a href='#'><img src='/kor/images/common/btn_p_next.gif' alt='다음으로' /></a> ";
		} else {
		$retValue .= " <a href='javascript:product_page_it(".($cur_page+1).")'><img src='/kor/images/common/btn_p_next.gif' alt='다음으로' /></a> ";
		}
		$retValue .= " <a href='#'><img src='/kor/images/common/btn_p_last.gif' alt='마지막으로' /></a> ";
	}
	$retValue .= "</div>";
	return $retValue;
}


function ajax_listing($cur_page, $total_page) {
  
  global $g_list_rows;

  $retValue = "<div class='board_num_list'>";

  if ($cur_page > 1) {
    $retValue .= "<a href='#!' class='prev_all' pgs='1' ></a>";
    $retValue .= "<a href='#!' class='prev' pgs='".($cur_page-1)."' ></a></li>";
  } else {
    $retValue .= "<a href='#!' class='prev_all' pgs='1' ></a>";
    $retValue .= "<a href='#!' class='prev' pgs='1' ></a>";
  }




  $retValue .= "";
  $start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
  //echo (int)(($cur_page - 1) / 10);
  $end_page = $start_page + 9;

  if ($end_page >= $total_page) $end_page = $total_page;
//  if ($start_page > 1) $retValue .= " <a href='" . $url . ($start_page-1) . "'>...</a> ";
  if ($total_page >= 1)
    for ($k=$start_page;$k<=$end_page;$k++)
      if ($cur_page != $k) $retValue .= "<a href='#!' class='num' pgs='".$k."'>".$k."</a>";
      else $retValue .= "<a href='#!' class='num on' pgs='".$k."' >".$k."</a>";
//  if ($total_page > $end_page) $retValue .= "<a href='" . $url . ($end_page+1) . "'>...</a> ";



  $retValue .= "";
  if ($cur_page < $total_page) {
    $retValue .= "<a href='#!' class='next' pgs='".($cur_page+1)."' ></a>";
    $retValue .= "<a href='#!' class='next_all' pgs='".$total_page."' ></a>";
  } else {
    $retValue .= "<a href='#!' class='next' pgs='1' ></a>";
    $retValue .= "<a href='#!' class='next_all' pgs='1' ></a>";
  }

  $retValue .= "</div>";
  return $retValue;
}


function ipagelisting4($cur_page, $total_page, $n, $url) {
	$retValue = "<div class='next_prev_box'><ul>";
	if ($cur_page > 1) {
		$retValue .= "<li ><a href='" . $url . "1' class='first_page' title='Go to next page'></a></li>";
		$retValue .= "<li ><a href='" . $url . ($cur_page-1) . "' class='preview' title='Go to first page'></a></li>";
	} else {
		$retValue .= "<li><a href='javascript:;' class='first_page' title='Go to next page'></a></li>";
		$retValue .= "<li><a href='javascript:;' class='preview' title='Go to first page'></a></li>";
	}
	$retValue .= "";
	$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
	$end_page = $start_page + 9;
	if ($end_page >= $total_page) $end_page = $total_page;
	if ($total_page == 0)
	{
		$retValue .= "<li class='on'><a href='javascript:;' title='Go to 0 page'><strong>1</strong></a></li>";
	} elseif ($total_page >= 1)
	{
		for ($k=$start_page;$k<=$end_page;$k++)
		{
			if ($cur_page != $k)
			{
				$retValue .= "<li><a href='$url$k' title='Go to page $k'>$k</a></li>";
			} else {
				$retValue .= "<li><a href='javascript:;' class='on' title='Go to $k page'><strong>$k</strong></a></li>";
			}
		}
	}
	$retValue .= "";
	if ($cur_page < $total_page) {
		$retValue .= "<li><a href='$url" . ($cur_page+1) . "' class='next' title='Go to next page'></a></li>";
		$retValue .= "<li><a href='$url$total_page' class='last_page' title='Go to last page'></a></li>";
	} else {
		$retValue .= "<li><a href='javascript:;' class='next' title='Go to next page'></a></li>";
		$retValue .= "<li><a href='javascript:;' class='last_page' title='Go to last page'></a></li>";
	}
	$retValue .= "</ul></div>";
	return $retValue;
}


// 파일을 첨부함
function attach_file($filename, $file)
{
    $fp = fopen($file, "r");
    $tmpfile = array(
        "name" => $filename,
        "data" => fread($fp, filesize($file)));
    fclose($fp);
    return $tmpfile;
}


function listNew($term, $reg_date1)
{
	$sub_date=date("Y-m-d H:i:s",mktime(date('H')-$term,date('i'),date('s'),date('m'),date('d'),date('Y')));

//		if(date("Y-m-d H:i:s",$reg_date1 < $sub_date)
	if($reg_date1 < $sub_date)
	{
		$show=1;
	} else {
		$show=0;
	}

	return $show;
}

function strcut_utf8($str, $len, $checkmb=false, $tail='...') {
  // global $str,$len,$checkmb,$tail;
   preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
   $m    = $match[0];
   $slen = strlen($str);  // length of source string
   $tlen = strlen($tail); // length of tail string
   $mlen = count($m);    // length of matched characters
   if ($slen <= $len)
	{return $str; }
   if (!$checkmb && $mlen <= $len) return $str;

   $ret  = array();
   $count = 0;

   for ($i=0; $i < $len; $i++) {
  $count += ($checkmb && strlen($m[$i]) > 1)?2:1;
  if ($count + $tlen > $len) break;
  $ret[] = $m[$i];
   }
   return join('', $ret).$tail;
 }






 function time2date($time) {
  $date=date("Y-m-d H:i:s", $time);
  return $date;
 }

//2011-04-11 20:47:46 형태 의 날짜를 1302522466 형태의 timestamp 로 반환
function date2time($date) {
	$arg=explode(' ',$date); // 날짜 와 시간을 분리
	$ymd=explode('-',$arg[0]); // 날짜 부분
	$hms=explode(':',$arg[1]); // 시간 부분
	$time=mktime($hms[0],$hms[1],$hms[2],$ymd[1],$ymd[2],$ymd[0]);
	return $time;
}

/*================================================================================================*/
/*===================================== 파일 업로드 함수 ===========================================*/
function file_check($ok_filename,$ok_file,$path,$ftype){
	if($ok_filename=="" || $ok_file==""){
		return false;
	}else{
		//한글파일 파일명 대체

    $download=$path;
	$aa=date('YmdHms');
//	$check=explode(".",$ok_filename);

	$ext = substr(strrchr($ok_filename,"."),1);	 //확장자앞 .을 제거하기 위하여 substr()함수를 이용
	$ext = strtolower($ext);			 //확장자를 소문자로 변환

	$check1=$aa;
	$check2=strtolower($ext);

	$ok_filename=$check1.".".$check2;
	$attached=$ok_filename;
	if ($ftype == "I")
	{
		if($check2 !="gif" &&  $check2!="jpg" && $check2!="jpeg" && $check2 !="bmp"){
			echo"<script>alert('이미지 파일만 업로드할수있습니다.');
				  history.back(1);</script>";
				  exit;
		}
	} else
	$attached=$ok_filename;
    $ok_filename=$download . $ok_filename;
        if (file_exists($ok_filename)) {    // 같은 파일 존재
                //$file_splited = explode("\.", $attached, 2);
				$file_splited = explode(".", $attached);
                $i = 0;
                do {
                        $tmp_filename = $file_splited[0] . $i . "." . $file_splited[1];
                        $tmp_filelocation = $download . $tmp_filename;
                        $i++;
                } while (file_exists($tmp_filelocation));
                $ok_filename = $tmp_filelocation;
                $attached = $tmp_filename;
        }
	
	if($check2 == "png"){
		/*
		$wfp = fopen($ok_filename, "wb");

		if ($fp = fopen($ok_file, 'r')) {
		   $contents = '';
		   // 전부 읽을때까지 계속 읽음
		   while ($line = fgets($fp, 1024)) {
			  $contents .= $line;
		   }
		}

		//echo $contents;

		fwrite($wfp,$contents);
		fclose($rfp);
		fclose($wfp);
		*/
		
		copy($ok_file, $ok_filename);
	}else{
		copy($ok_file, $ok_filename);
	}


	//copy($ok_file, $ok_filename[background="255 128 128"]);
	unlink($ok_file);
	//GD2_make_thumb(20000,20000,str_replace("img_","thumb_",$path.$attached),$path.$attached);

	return $attached;
	}
}      //함수의 끝

function file_check_1($ok_filename,$ok_file,$path,$ftype, $addname = "", $addtitle = ""){
	if($ok_filename=="" || $ok_file==""){
		return false;
	}else{
		//한글파일 파일명 대체

    $download=$path;
	$aa=date('YmdHms');
//	$check=explode(".",$ok_filename);

	$ext = substr(strrchr($ok_filename,"."),1);	 //확장자앞 .을 제거하기 위하여 substr()함수를 이용
	$ext = strtolower($ext);			 //확장자를 소문자로 변환

	$check1=$aa;
	$check2=strtolower($ext);

	if ($addtitle) {
		$ok_filename=$addtitle.$addname. ".".$check2;
	} else {
		$ok_filename=$check1.$addname. ".".$check2;
	}
	$attached=$ok_filename;
	if ($ftype == "I")
	{
		if($check2 !="gif" &&  $check2!="jpg" && $check2!="jpeg" && $check2 !="bmp"){
			echo"<script>alert('이미지 파일만 업로드할수있습니다.');
				  history.back(1);</script>";
				  exit;
		}
	} else
	$attached=$ok_filename;
    $ok_filename=$download . $ok_filename;
		if ($addtitle == "") {
			if (file_exists($ok_filename)) {    // 같은 파일 존재
					//$file_splited = explode("\.", $attached, 2);
					$file_splited = explode(".", $attached);
					$i = 0;
					do {
							$tmp_filename = $file_splited[0] . $i . "." . $file_splited[1];
							$tmp_filelocation = $download . $tmp_filename;
							$i++;
					} while (file_exists($tmp_filelocation));
					$ok_filename = $tmp_filelocation;
					$attached = $tmp_filename;
			}
		}
	copy($ok_file, $ok_filename);
	unlink($ok_file);
	GD2_make_thumb(2000,2000,str_replace("img_","thumb_",$path.$attached),$path.$attached);

	return $attached;
	}
}      //함수의 끝
/*================================================================================================*/
/*===================================== 파일 업로드 함수 ===========================================*/
function file_check2($ok_filename,$ok_file,$path,$ftype){
	if($ok_filename=="" || $ok_file==""){
		return false;
	}else{
		//한글파일 파일명 대체

    $download=$path;
//	$aa=date('YmdHms');
	$check=explode(".",$ok_filename);

//	$check[0]="thumb_".$aa;
	$check[1]=strtolower($check[1]);

	$ok_filename=$check[0]."_l.".$check[1];
	$ok_filename2=$check[0]."_S.".$check[1];
	$attached=$ok_filename;
	if ($ftype == "I")
	{
		if($check[1] !="gif" &&  $check[1]!="jpg" && $check[1]!="jpeg" && $check[1] !="bmp"){
			echo"<script>alert('이미지 파일만 업로드할수있습니다.');
				  history.back(1);</script>";
				  exit;
		}
	} else
	$attached=$ok_filename;
    $ok_filename=$download . $ok_filename;
        if (file_exists($ok_filename)) {    // 같은 파일 존재
                $file_splited = split("\.", $attached, 2);
                $i = 0;
                do {
                        $tmp_filename = $file_splited[0] . $i . "." . $file_splited[1];
                        $tmp_filelocation = $download . $tmp_filename;
                        $i++;
                } while (file_exists($tmp_filelocation));
                $ok_filename = $tmp_filelocation;
                $attached = $tmp_filename;
        }
	copy($ok_file, $ok_filename);
	unlink($ok_file);
	$ext = substr(strrchr($attached,"."),1);	//확장자앞 .을 제거하기 위하여 substr()함수를 이용
	$ext = strtolower($ext);					//확장자를 소문자로 변환
	$attached2 = substr(basename($attached, $ext),0,strlen(basename($attached, $ext))-1)."_s.".$ext;

	GD2_make_thumb(130,130,$path.$attached2,$path.$attached);
	return $attached."|||".$attached2;
	}
}      //함수의 끝
/*===================================== 파일 업로드 함수 ===========================================*/
/*================================================================================================*/
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function setBanner($title, $num)
	{
		global $connect;

		$sql = " select * from tbl_banner where idx='".$num."' ";
		$result = mysqli_query($connect, $sql) or die (mysql_error());
		$row=mysqli_fetch_array($result);
	?>
		<form name='frm<?=$row[idx]?>' action='/AdmMaster/ajax/front_banner_ok.php' target="hiddenFrame" method=post enctype="multipart/form-data" >
		<input type=hidden name='idx' value='<?=$row[idx]?>'>
		<tbody>
			<tr>
				<td><?=$title?></td>
				<td><? if ($row[bfile1] != "") { ?><a href="/data/banner/<?=$row[bfile1]?>" class="imgpop"><img src="/data/banner/<?=$row[bfile1]?>" style="max-height:100px;max-width:300px"></a><? } else { ?>&nbsp;<? } ?></td>
				<td class="tal">
					제 목 : <input type="text"  name="subject" value="<?=viewSQ($row[subject])?>"  class="bbs_inputbox_pixel" style="width:90%;" /><br>
					사 진 : <input type="file" name="bfile1" class="bbs_inputbox_pixel" style="width:410px;  margin-bottom:3px;  margin-top:3px; " />
					<span class="bbs_guide">* 이미지 등록, gif 또는 jpg</span><br>
					링 크 : <input type="text" name="link" value='<?=$row[link]?>' class="bbs_inputbox_pixel" style="width:410px; margin-bottom:3px;"/>
					<span class="bbs_guide">* 링크 URL</span> &nbsp;&nbsp;&nbsp;
					<input name="starget" type="radio" value="A" <? if ($row[starget] == "A") {echo "checked";} ?> />
					본창열기 &nbsp;&nbsp;&nbsp;
					<input name="starget" type="radio" value="B" <? if ($row[starget] == "B") {echo "checked";} ?> />
					새창열기 &nbsp;&nbsp;&nbsp;
					<input name="starget" type="radio" value="C" <? if ($row[starget] == "C") {echo "checked";} ?> />
					링크없음
				</td>
				<td>
					<select id="" name="status" class="input_select">
						<option value="Y"  <? if ($row[status] == "Y") {echo "selected";} ?>>사용</option>
						<option value="N"  <? if ($row[status] == "N") {echo "selected";} ?>>미사용</option>
					</select>
				</td>
				<td><a href="javascript:document.frm<?=$row[idx]?>.submit()" class="btn btn-default">수정</a></td>
			</tr>
		</tbody>
		</form>


	<?
}

function getBanner($num)
{
	global $strxhk;
	$strxhk = "N";

	global $connect;

	$fsql = " select * from tbl_banner where idx='$num' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	if ($frow[status] == "Y") { ?>
	<img src="/data/banner/<?=$frow[bfile1]?>"  alt="<?=$frow[subject]?>" <? if ($frow[link] && $frow[starget] != "C") { ?> style="cursor:pointer;" onclick="javascript:<? if ($frow[starget] == "B") { ?>window.open('<?=$frow[link]?>')<? } else { ?>location.href='<?=$frow[link]?>'<? } ?>" <? } ?>/>

<?
	$strxhk = "Y";
	}
}

function getBannerChk($num)
{
	global $strxhk;
	$strxhk = "N";

	global $connect;

	$fsql = " select * from TB_BANNER where idx='$num' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	if ($frow[status] == "Y") {
		$chk = "Y";
	}  else {
		$chk = "N";
	}
	return $chk;
}

function getCountryName($code)
{
	global $connect;

	$fsql = " select country_kr from tbl_country_code where country_code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return $frow[country_kr];
}


function getBoardName($code)
{
	global $connect;

	$fsql = " select board_name from tbl_bbs_config where board_code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	if ($frow[board_name] == "") {
		alert_msg("정상적으로 이용바랍니다.");
		exit();
	}
	return $frow[board_name];
}

function getVacationCnt($userId)
{
	global $connect;

	$fsql = " select ifnull(sum(break_cnt),0) as cnt from tbl_vacation where status = 'S03' and substr(s_date,1,4) = '".date("Y",time())."' and user_id = '".$userId."' and kind in ('A01','A02','A05')";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);

	return $frow[cnt];
}

function isBoardCategory($code)
{
	global $connect;

	$fsql = " select is_category from tbl_bbs_config where board_code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);

	if ($frow[is_category] == "") {
		alert_msg("정상적으로 이용바랍니다.");
		exit();
	}
	return $frow[is_category];
}

function skinname($code)
{
	global $connect;

	$fsql = " select skin from tbl_bbs_config where board_code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	if ($frow[skin] == "") {
		alert_msg("정상적으로 이용바랍니다.");
		exit();
	}
	return $frow[skin];
}

function getFront($code)
{
	global $connect;
	$fsql = " select contents from tbl_info where idx='$code' ";

	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return viewSQ($frow[contents]);
}

function galleryTitle($code)
{
	global $connect;

	$fsql = " select title from tbl_gallery_config where code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return $frow[title];
}

function isBoardReply($code)
{
	global $connect;

	$fsql = " select is_reply from tbl_bbs_config where board_code='$code' ";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return $frow[is_reply];
}

function isSecure($code)
{
	global $connect;

	$fsql = " select is_secure from tbl_bbs_config where board_code='$code' ";

	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return $frow[is_secure];
}

function isNotice($code)
{
	global $connect;

	$fsql = " select is_notice from tbl_bbs_config where board_code='$code' ";

	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$frow=mysqli_fetch_array($fresult);
	return $frow[is_notice];
}

function get_code_name($code_no)
{
	global $connect;

	$fsql		= "select code_name from tbl_code where code_no = '".$code_no."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysql_error());
	$frow		= mysqli_fetch_array($fresult);
	if ($code_no == "")
	{
		$txt = "전체";
	} else {
		$txt = $frow["code_name"];
	}
	return $txt;
}

function get_group_name($code_no)
{
	global $connect;

	$fsql		= "select code_name from tbl_group where code_no = '".$code_no."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysql_error());
	$frow		= mysqli_fetch_array($fresult);
	if ($code_no == "")
	{
		$txt = "전체";
	} else {
		$txt = $frow["code_name"];
	}
	return $txt;
}

function get_category_name($code_no)
{
	global $connect;

	$fsql		= "select code_name from tbl_code where code_no = '".$code_no."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysql_error());
	$frow		= mysqli_fetch_array($fresult);
	if ($code_no == "")
	{
		$txt = "전체";
	} else {
		$txt = $frow["code_name"];
	}
	return $txt;
}

function get_category_depth($code_no)
{
	global $connect;

	$fsql		= "select depth from tbl_code where code_no = '".$code_no."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysql_error());
	$frow		= mysqli_fetch_array($fresult);
	if ($code_no == "")
	{
		$txt = "0";
	} else {
		$txt = $frow["depth"];
	}
	return $txt;
}

 function strcut2($str, $size)
 {
  $substr = substr($str, 0, $size*2);
  $multi_size = preg_match_all('/[\x80-\xff]/', $substr, $multi_chars);

  if($multi_size >0)
   $size = $size + intval($multi_size/3)-1;

  if(strlen($str)>$size)
  {
   $str = substr($str, 0, $size);
   $str = preg_replace('/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str);
   $str .= '...';
  }

  return $str;
 }

function mpagelisting($cur_page, $total_page, $n, $url) {
  $retValue = "<div class='list_paging'><ul>";
  if ($cur_page > 1) {
    $retValue .= "<li> <a href='" . $url . ($cur_page-1) . "'>◀</a></li>";
  } else {
    $retValue .= "<li><a href='#'>◀</a></li>";
  }
  $retValue .= "";
  $start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
  $end_page = $start_page + 9;
  if ($end_page >= $total_page) $end_page = $total_page;
  if ($total_page >= 1)
    for ($k=$start_page;$k<=$end_page;$k++)
      if ($cur_page != $k) $retValue .= " <li><a href='$url$k'>$k</a></li>";
      else $retValue .= " <li class='on'><a href='#'>$k</a></li>";
//  if ($total_page > $end_page) $retValue .= "<a href='" . $url . ($end_page+1) . "'>...</a> ";
  $retValue .= "";
  if ($cur_page < $total_page) {
    $retValue .= "<li><a href='$url" . ($cur_page+1) . "'>▶</a></li>";
  } else {
    $retValue .= "<li><a href='#'>▶</a></li>";
  }
  $retValue .= "</ul></div>";
  return $retValue;
}


function GD2_make_thumb($max_x,$max_y,$dst_name,$src_file) {
	$img_info=@getimagesize($src_file);
	$sx = $img_info[0];
	$sy = $img_info[1];
	//썸네일 보다 큰가?
	if ($sx>=$max_x || $sy>=$max_y) {
			if ($sx>$sy) {
							$thumb_y=ceil(($sy*$max_x)/$sx);
							$thumb_x=$max_x;
			} else {
							$thumb_x=ceil(($sx*$max_y)/$sy);
							$thumb_y=$max_y;
			}
	} else {
			$thumb_y=$sy;
			$thumb_x=$sx;
	}
	// JPG 파일인가?
	if ($img_info[2]=="1") {
			$_dq_tempFile=basename($src_file);                                //파일명 추출
			$_dq_tempDir=str_replace($_dq_tempFile,"",$src_file);        //경로 추출
			$_dq_tempFile=$dst_name;        //경로 + 새 파일명 생성

			$_create_thumb_file = true;
			if (file_exists($_dq_tempFile)) { //섬네일 파일이 이미 존제한다면 이미지의 사이즈 비교
					$old_img=@getimagesize($_dq_tempFile);
					if($old_img[0] != $thumb_x) $_create_thumb_file = true; else $_create_thumb_file = false;
					if($old_img[1] != $thumb_y) $_create_thumb_file = true; else $_create_thumb_file = false;
			}
			if ($_create_thumb_file) {
					// 복제
					$src_img=imagecreatefromgif($src_file);
					$dst_img=ImageCreateTrueColor($thumb_x, $thumb_y);
					ImageCopyResampled($dst_img,$src_img,0,0,0,0,$thumb_x,$thumb_y,$sx,$sy);
					Imagejpeg($dst_img,$_dq_tempFile,100);
					// 메모리 초기화
					ImageDestroy($dst_img);
					ImageDestroy($src_img);
			}
	}
	if ($img_info[2]=="2") {
			$_dq_tempFile=basename($src_file);                                //파일명 추출
			$_dq_tempDir=str_replace($_dq_tempFile,"",$src_file);        //경로 추출
			$_dq_tempFile=$dst_name;        //경로 + 새 파일명 생성

			$_create_thumb_file = true;
			if (file_exists($_dq_tempFile)) { //섬네일 파일이 이미 존제한다면 이미지의 사이즈 비교
					$old_img=@getimagesize($_dq_tempFile);
					if($old_img[0] != $thumb_x) $_create_thumb_file = true; else $_create_thumb_file = false;
					if($old_img[1] != $thumb_y) $_create_thumb_file = true; else $_create_thumb_file = false;
			}
			if ($_create_thumb_file) {
					// 복제
					$src_img=ImageCreateFromjpeg($src_file);
					$dst_img=ImageCreateTrueColor($thumb_x, $thumb_y);
					ImageCopyResampled($dst_img,$src_img,0,0,0,0,$thumb_x,$thumb_y,$sx,$sy);
					Imagejpeg($dst_img,$_dq_tempFile,100);
					// 메모리 초기화
					ImageDestroy($dst_img);
					ImageDestroy($src_img);
			}
	}
	if ($img_info[2]=="3") {
			$_dq_tempFile=basename($src_file);                                //파일명 추출
			$_dq_tempDir=str_replace($_dq_tempFile,"",$src_file);        //경로 추출
			$_dq_tempFile=$dst_name;        //경로 + 새 파일명 생성

			$_create_thumb_file = true;
			if (file_exists($_dq_tempFile)) { //섬네일 파일이 이미 존제한다면 이미지의 사이즈 비교
					$old_img=@getimagesize($_dq_tempFile);
					if($old_img[0] != $thumb_x) $_create_thumb_file = true; else $_create_thumb_file = false;
					if($old_img[1] != $thumb_y) $_create_thumb_file = true; else $_create_thumb_file = false;
			}
			if ($_create_thumb_file) {
					// 복제
					$src_img=imagecreatefrompng($src_file);
					$dst_img=ImageCreateTrueColor($thumb_x, $thumb_y);
					ImageCopyResampled($dst_img,$src_img,0,0,0,0,$thumb_x,$thumb_y,$sx,$sy);
					Imagejpeg($dst_img,$_dq_tempFile,100);
					// 메모리 초기화
					ImageDestroy($dst_img);
					ImageDestroy($src_img);
			}
	}
}

function viewSQ($textToFilter)
{
		$textToFilter = str_replace('&amp;',"&",$textToFilter);
		$textToFilter = str_replace('&#59',";",$textToFilter);
		$textToFilter = str_replace('&gt;',">",$textToFilter);
		$textToFilter = str_replace('&lt;',"<",$textToFilter);
		$textToFilter = str_replace("&#39","'",$textToFilter);
		$textToFilter = str_replace('&#34',"\"",$textToFilter);
		$textToFilter = str_replace('&amp;',"&",$textToFilter);
		return $textToFilter;
}

function updateSQ($textToFilter)
{
	//a = &#97;
    //e = &#101;
    //i = &#105;
    //o = &#111;
    //u  = &#117;

    //A = &#65;
    //E = &#69;
    //I = &#73;
    //O = &#79;
    //U = &#85;
    if ($textToFilter != null)
	{
		$textToFilter = str_replace('insert '	,'ins&#101rt ',$textToFilter);
		$textToFilter = str_replace('select '	,'s&#101lect ',$textToFilter);
		$textToFilter = str_replace('values'	,' valu&#101s',$textToFilter);
		$textToFilter = str_replace(' where '	,' wher&#101 ',$textToFilter);
		$textToFilter = str_replace(' order '	,' ord&#101r ',$textToFilter);
		$textToFilter = str_replace(' into '	,' int&#111 ',$textToFilter);
		$textToFilter = str_replace('drop '		,'dr&#111p ',$textToFilter);
		$textToFilter = str_replace('delete '	,'delet&#101 ',$textToFilter);
		$textToFilter = str_replace('update '	,'updat&#101 ',$textToFilter);
		$textToFilter = str_replace(' set'		,' s&#101t',$textToFilter);
		$textToFilter = str_replace('flush'		,'fl&#117sh',$textToFilter);
		$textToFilter = str_replace("'","&#39",$textToFilter);
		$textToFilter = str_replace('"',"&#34",$textToFilter);
		$textToFilter = str_replace('>',"&gt;",$textToFilter);
		$textToFilter = str_replace('<',"&lt;",$textToFilter);
		$textToFilter = str_replace('script','scr&#105pt',$textToFilter);
	//	$textToFilter = nl2br($textToFilter);
		$filterInputOutput = $textToFilter;
		return trim($filterInputOutput);
	}

}

// 현재페이지,총페이지수,한페이지에 보여줄 목록수,URL
function wpagelisting($cur_page, $total_page, $n, $url) {
	$retValue = "<div class='paginate'>";
	if ($cur_page > 1) {
		$retValue .= "<a href='" . $url . "1' class='ctrl first' title='Go to next page'></a>";
		$retValue .= "<a href='" . $url . ($cur_page-1) . "' class='ctrl prev' title='Go to first page'></a>";
	} else {
		$retValue .= "<a href='javascript:;' class='ctrl first' title='Go to next page'></a>";
		$retValue .= "<a href='javascript:;' class='ctrl prev' title='Go to first page'></a>";
	}
	$retValue .= "";
	$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
	$end_page = $start_page + 9;
	if ($end_page >= $total_page) $end_page = $total_page;
	if ($total_page == 0)
	{
		$retValue .= "<a href='javascript:;' class='active' title='Go to 0 page'>1</a>";
	} elseif ($total_page >= 1)
	{
		for ($k=$start_page;$k<=$end_page;$k++)
		{
			if ($cur_page != $k)
			{
				$retValue .= "<a href='$url$k' title='Go to page $k'>$k</a>";
			} else {
				$retValue .= "<a href='javascript:;' title='Go to $k page' class='active'>$k</a>";
			}
		}
	}
	$retValue .= "";
	if ($cur_page < $total_page) {
		$retValue .= "<a href='$url" . ($cur_page+1) . "'  class='ctrl next' title='Go to next page'></a>";
		$retValue .= "<a href='$url$total_page' class='ctrl last' title='Go to last page'></a>";
	} else {
		$retValue .= "<a href='javascript:;'  class='ctrl next' title='Go to next page'></a>";
		$retValue .= "<a href='javascript:;'  class='ctrl last' title='Go to last page'></a>";
	}
	$retValue .= "</div>";
	return $retValue;
}
/*
function wmpagelisting($cur_page, $total_page, $n, $url) {
	if ($total_page > 0)
	{
		$retValue = "<div class=\"pager_wrap\"><ul>";
		if ($cur_page > 1) {
			$retValue .= "<li class='ic ic_ll'><a href='" . $url . "1' title='Go to first page'>게시판 첫페이지로 이동</a></li>";
			$retValue .= "<li class='ic ic_l'><a href='" . $url . ($cur_page-1) . "' class='pagerDB-prev active' title='Go to previous page'>게시판 이전페이지로 이동</a></li>";
		} else {
			$retValue .= "<li class='ic ic_ll'><a href='javascript:;' title='Go to first page'>게시판 첫페이지로 이동</a></li>";
			$retValue .= "<li class='ic ic_l'><a href='javascript:;' title='Go to previous page'>게시판 이전페이지로 이동</a></li> ";
		}
		$retValue .= "";
		$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
		$end_page = $start_page + 9;
		if ($end_page >= $total_page) $end_page = $total_page;
		if ($total_page >= 1)
		for ($k=$start_page;$k<=$end_page;$k++)
		if ($cur_page != $k) $retValue .= "<li class='num'><a href='$url$k' title='Go to page $k'>$k</a></li>";
		else $retValue .= "<li class='active num'><a href='javascript:;'>$k</a></li>";
		$retValue .= "";
		if ($cur_page < $total_page) {
			$retValue .= "<li class=\"ic ic_r\"><a href='$url" . ($cur_page+1) . "' title='Go to next page'>게시판 다음 페이지로 이동</a></li>";
			$retValue .= "<li class=\"ic ic_rr\"><a href='$url$total_page' title='Go to last page'>게시판 마지막 페이지로 이동</a></li>";
		} else {
			$retValue .= "<li class=\"ic ic_r\"><a href='#' title='Go to next page'>게시판 다음 페이지로 이동</a></li>";
			$retValue .= "<li class=\"ic ic_rr\"><a href='#' title='Go to last page'>게시판 마지막 페이지로 이동</a></li>";
		}
		$retValue .= "</ul></div>";
	}
	return $retValue;
}
*/

function wmpagelisting($cur_page, $total_page, $n, $url) {
	if ($total_page > 0)
	{
		$retValue = "<div class='pager_wrap'><ul>";
		if ($cur_page > 1) {
			$retValue .= "<li class='arrow mar_r5'><a href='" . $url . "1' title='Go to first page'><img src='/img/sub/pager_ll.png' alt='처음 페이지로'></a></li>";
			$retValue .= "<li class='arrow mar_r9'><a href='" . $url . ($cur_page-1) . "' class='pagerDB-prev active' title='Go to previous page'><img src='/img/sub/pager_l.png' alt='이전 페이지로'></a></li>";
		} else {
			$retValue .= "<li class='arrow mar_r5'><a href='javascript:;' title='Go to first page'><img src='/img/sub/pager_ll.png' alt='처음 페이지로'></a></li>";
			$retValue .= "<li class='arrow mar_r9'><a href='javascript:;' title='Go to previous page'><img src='/img/sub/pager_l.png' alt='이전 페이지로'></a></li> ";
		}
		$retValue .= "";
		$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
		$end_page = $start_page + 9;
		if ($end_page >= $total_page) $end_page = $total_page;
		if ($total_page >= 1)
		for ($k=$start_page;$k<=$end_page;$k++)
		if ($cur_page != $k) $retValue .= "<li class='num'><a href='$url$k' title='Go to page $k'>$k</a></li>";
		else $retValue .= "<li class='num active'><a href='javascript:;'>$k</a></li>";
		$retValue .= "";
		if ($cur_page < $total_page) {
			$retValue .= "<li class='arrow mar_l9'><a href='$url" . ($cur_page+1) . "' title='Go to next page'><img src='/img/sub/pager_r.png' alt='다음 페이지로'></a></li>";
			$retValue .= "<li class='arrow mar_l5'><a href='$url$total_page' title='Go to last page'><img src='/img/sub/pager_rr.png' alt='마지막 페이지로'></a></li>";
		} else {
			$retValue .= "<li class='arrow mar_l9'><a href='#' title='Go to next page'><img src='/img/sub/pager_r.png' alt='다음 페이지로'></a></li>";
			$retValue .= "<li class='arrow mar_l5'><a href='#' title='Go to last page'><img src='/img/sub/pager_rr.png' alt='마지막 페이지로'></a></li>";
		}
		$retValue .= "</ul></div>";
	}
	return $retValue;
}


function wmpagelisting_new($cur_page, $total_page, $n, $url) {
	if ($total_page > 0)
	{
		$retValue = "<ul class='page'>";
		if ($cur_page > 1) {
			$retValue .= "<li class='more_prev'><a href='" . $url . "1' title='Go to first page'><img src='../img/btn/left_more.png'></a></li>";
			$retValue .= "<li class='prev'><a href='" . $url . ($cur_page-1) . "' class='pagerDB-prev active' title='Go to previous page'><img src='../img/btn/page_left.png'></a></li>";
		} else {
			$retValue .= "<li class='more_prev'><a href='javascript:;' title='Go to first page'><img src='../img/btn/left_more.png'></a></li>";
			$retValue .= "<li class='prev'><a href='javascript:;' title='Go to previous page'><img src='../img/btn/page_left.png'></a></li> ";
		}
		$retValue .= "";
		$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
		$end_page = $start_page + 9;
		if ($end_page >= $total_page) $end_page = $total_page;
		if ($total_page >= 1)
		for ($k=$start_page;$k<=$end_page;$k++)
		if ($cur_page != $k) $retValue .= "<li class='num'><a href='$url$k' title='Go to page $k'>$k</a></li>";
		else $retValue .= "<li class='num active'><a href='javascript:;'>$k</a></li>";
		$retValue .= "";
		if ($cur_page < $total_page) {
			$retValue .= "<li class='next'><a href='$url" . ($cur_page+1) . "' title='Go to next page'><img src='../img/btn/page_right.png'></a></li>";
			$retValue .= "<li class='more_next'><a href='$url$total_page' title='Go to last page'><img src='../img/btn/right_more.png'></a></li>";
		} else {
			$retValue .= "<li class='next'><a href='#' title='Go to next page'><img src='../img/btn/page_right.png'></a></li>";
			$retValue .= "<li class='more_next'><a href='#' title='Go to last page'><img src='../img/btn/right_more.png'></a></li>";
		}
		$retValue .= "</ul>";
	}
	return $retValue;
}


function wpagelisting_ajax($cur_page, $total_page, $n, $code) {

	$retValue = "<div id='pageing_$code' class=\"paging\"><ul>";
	if ($cur_page > 1) {
		$retValue .= "<li class='first'><a href='javascript:get_list(1, $code)' ><img src='/img_board/btn_page_prev.png' /></a></li>";
	} else {
		$retValue .= "<li class='first'><a href='javascript:get_list(".($cur_page-1).", $code)' ><img src='/img_board/btn_page_prev.png' /></a></li>";
	}

	$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;

	$end_page = $start_page + 9;
	if ($end_page >= $total_page) $end_page = $total_page;

	if ($cur_page > 1){
		$retValue .= "<li class='prev'><a href='javascript:get_list(".($cur_page-1).", $code)'>&lt;</a></li>";
	} else {
		$retValue .= "<li class='prev'><a href='javascript:;'>&lt;</a></li>";
	}

	if ($total_page > 1)
		for ($k=$start_page;$k<=$end_page;$k++)
		{
			if ($cur_page != $k) $retValue .= "<li><a href='javascript:get_list($k, $code)' >$k</a></li>";
			else $retValue .= "<li class='active'><a href='javascript:;'><b>$k</b></a></li>";
		}

	if ($total_page > $cur_page){
		$retValue .= "<li class='next'><a href='javascript:get_list(".($cur_page+1).", $code)' >&gt;</a></li>";
	} else {
		$retValue .= "<li class='next'><a href='javascript:;'>&gt;</a></li>";
	}



	if ($cur_page < $total_page) {
		$retValue .= "<li class='last'><a href='javascript:get_list(".($total_page).", $code)' ><img src='/img_board/btn_page_next.png' /></a></li>";
	} else {
		$retValue .= "<li class='last'><a href='javascript:;'><img src='/img_board/btn_page_next.png' /></a></li>";
	}
	$retValue .= "</ul></div>";
	return $retValue;
}// 현재페이지,총페이지수,한페이지에 보여줄 목록수,URL

function ipagelisting($cur_page, $total_page, $n, $url) {

	$retValue = "<div class='paging mt30'><ul>";
	if ($cur_page > 1) {
		$retValue .= "<li class='first'><a href='" . $url . "1' title='Go to next page'>&lt;&lt;  처음</a></li>";
		$retValue .= "<li class='prev'><a href='" . $url . ($cur_page-1) . "' title='Go to first page'>&lt; 이전</a></li>";
	} else {
		$retValue .= "<li class='first'><a href='javascript:;' title='Go to next page'>&lt;&lt; 처음</a></li>";
		$retValue .= "<li class='prev'><a href='javascript:;' title='Go to first page'>&lt; 이전</a></li>";
	}
	$retValue .= "";
	$start_page = ( ( (int)( ($cur_page - 1 ) / 10 ) ) * 10 ) + 1;
	$end_page = $start_page + 9;
	if ($end_page >= $total_page) $end_page = $total_page;
	if ($total_page == 0)
	{
		$retValue .= "<li class='active'><a href='javascript:;' title='Go to 0 page'><strong>1</strong></a></li>";
	} elseif ($total_page >= 1)
	{
		for ($k=$start_page;$k<=$end_page;$k++)
		{
			if ($cur_page != $k)
			{
				$retValue .= "<li><a href='$url$k' title='Go to page $k'>$k</a></li>";
			} else {
				$retValue .= "<li class='active'><a href='javascript:;' title='Go to $k page'><strong>$k</strong></a></li>";
			}
		}
	}
	$retValue .= "";
	if ($cur_page < $total_page) {
		$retValue .= "<li class='next'><a href='$url" . ($cur_page+1) . "' title='Go to next page'>다음 &gt;</a></li>";
		$retValue .= "<li class='last'><a href='$url$total_page' title='Go to last page'>맨끝 &gt;&gt;</a></li>";
	} else {
		$retValue .= "<li class='next'><a href='javascript:;' title='Go to next page'>다음 &gt;</a></li>";
		$retValue .= "<li class='last'><a href='javascript:;' title='Go to last page'>맨끝 &gt;&gt;</a></li>";
	}
	$retValue .= "</ul></div>";
	return $retValue;
}


function num2kor($num)
{

  $ret = "";

  if(!is_numeric($num))

  {

   return 0;

  }



  $arr_number = strrev($num);

  for($i =strlen($arr_number)-1; $i>=0; $i--)

  {

   /////////////////////////////////////////////////

   // 현재 자리를 구함

   $digit = substr($arr_number, $i, 1);




   ///////////////////////////////////////////////////////////

   // 각 자리 명칭

   switch($digit)

   {

    case '-' : $ret .= "(-) ";

        break;

    case '0' : $ret .= "";

        break;

    case '1' : $ret .= "일";

        break;

    case '2' : $ret .= "이";

        break;

    case '3' : $ret .= "삼";

        break;

    case '4' : $ret .= "사";

        break;

    case '5' : $ret .= "오";

        break;

    case '6' : $ret .= "육";

        break;

    case '7' : $ret .= "칠";

        break;

    case '8' : $ret .= "팔";

        break;

    case '9' : $ret .= "구";

        break;

   }




    if($digit=="-") continue;




    ///////////////////////////////////////////////////////////

    // 4자리 표기법 공통부분

    if($digit != 0)

    {

     if($i % 4 == 1)$ret .= "십";

     else if($i % 4 == 2)$ret .= "백";

     else if($i % 4 == 3)$ret .= "천";

    }



    ///////////////////////////////////////////////////////////

    // 4자리 한자 표기법 단위

    if($i % 4 == 0)

    {

     if( floor($i/ 4) ==0)$ret .= "";

     else if(floor($i / 4)==1)$ret .= "<b>만</b> ";

     else if(floor($i / 4)==2)$ret .= "<b>억</b> ";

     else if(floor($i / 4)==3)$ret .= "<b>조</b> ";

     else if(floor($i / 4)==4)$ret .= "<b>경</b> ";

     else if(floor($i / 4)==5)$ret .= "<b>해</b> ";

     else if(floor($i / 4)==6)$ret .= "<b>자</b> ";

     else if(floor($i / 4)==7)$ret .= "<b>양</b> ";

     else if(floor($i / 4)==8)$ret .= "<b>구</b> ";

     else if(floor($i / 4)==9)$ret .= "<b>간</b> ";

     else if(floor($i / 4)==10)$ret .= "<b>정</b> ";

     else if(floor($i / 4)==11)$ret .= "<b>재</b> ";

     else if(floor($i / 4)==12)$ret .= "<b>극</b> ";

     else if(floor($i / 4)==13)$ret .= "<b>항하사</b> ";

     else if(floor($i / 4)==14)$ret .= "<b>아승기</b> ";

     else if(floor($i / 4)==15)$ret .= "<b>나유타</b> ";

     else if(floor($i / 4)==16)$ret .= "<b>불가사의</b> ";

     else if(floor($i / 4)==16)$ret .= "<b>무량대수</b> ";    }

  }




  return $ret;

}



function fetch_url($theurl) {
	$url_parsed = parse_url($theurl);
	$host = $url_parsed["host"];
	$port = $url_parsed["port"];
	if($port==0) $port = 80;
	$the_path = $url_parsed["path"];

	if(empty($the_path)) $the_path = "/";
	if(empty($host)) return false;

	if($url_parsed["query"] != "") $the_path .= "?".$url_parsed["query"];
	$out = "GET ".$the_path." HTTP/1.0\r\nHost: ".$host."\r\n\r\nUser-Agent: Mozilla/4.0 \r\n";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	usleep(50);
	if($fp) {
	socket_set_timeout($fp, 30);
	fwrite($fp, $out);
	$body = false;
	while(!feof($fp)) {
	$buffer = fgets($fp, 128);
	if($body) $content .= $buffer;
	if($buffer=="\r\n") $body = true;
	}
	fclose($fp);
	}else {
	return false;
	}
	return $content;
}

function get_position($code)
{
	if ($code == "01") {
		$lcode = "사무국";
	} elseif ($code == "02") {
		$lcode = "이사";
	} elseif ($code == "03") {
		$lcode = "고문";
	} elseif ($code == "04") {
		$lcode = "감사";
	} elseif ($code == "05") {
		$lcode = "자문위원";
	} elseif ($code == "06") {
		$lcode = "홍보대사";
	}
	return $lcode;
}

function getStudioCate($strdata, $strtype)
{
	$atitle = explode("|",$strdata);
	$strTitle = "";
	for ($i = 0 ; $i < count($atitle)-1 ;$i++)
	{
		$sql1		= "select title from tbl_code where code='".$atitle[$i]."' and cate='".$strtype."' order by onum asc ";
		$result1	= mysql_query($sql1) or die (mysql_error());
		$row1		= mysql_fetch_array($result1);
		$strTitle	= $row1["title"].", ".$strTitle;
	}
	return substr($strTitle,0,strlen($strTitle)-2);
}

function cut_str($string,$cut_size=0,$tail = '...') {
	if($cut_size<1 || !$string) return $string;

	$chars = Array(12, 4, 3, 5, 7, 7, 11, 8, 4, 5, 5, 6, 6, 4, 6, 4, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 4, 4, 8, 6, 8, 6, 10, 8, 8, 9, 8, 8, 7, 9, 8, 3, 6, 7, 7, 11, 8, 9, 8, 9, 8, 8, 7, 8, 8, 10, 8, 8, 8, 6, 11, 6, 6, 6, 4, 7, 7, 7, 7, 7, 3, 7, 7, 3, 3, 6, 3, 9, 7, 7, 7, 7, 4, 7, 3, 7, 6, 10, 6, 6, 7, 6, 6, 6, 9);
	$max_width = $cut_size*$chars[0]/2;
	$char_width = 0;

	$string_length = strlen($string);
	$char_count = 0;

	$idx = 0;
	while($idx < $string_length && $char_count < $cut_size && $char_width <= $max_width) {
		$c = ord(substr($string, $idx,1));
		$char_count++;
		if($c<128) {
			$char_width += (int)$chars[$c-32];
			$idx++;
		}
		else if (191<$c && $c < 224) {
				  $char_width += $chars[4];
				  $idx += 2;
			}
		else {
			$char_width += $chars[0];
			$idx += 3;
		}
	}
	$output = substr($string,0,$idx);
	if(strlen($output)<$string_length) $output .= $tail;
	return $output;
}


function isBoardRecomm($code)
{
	$fsql = " select is_recomm from tbl_bbs_config where board_code='$code' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[is_recomm];
}

function getProductName($code)
{
	$fsql = " select P_NAME from J_PRODUCT_NEW where P_CODE1='$code' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[P_NAME];
}

function getProductImg($code)
{
	$fsql = " select P_IMAGE_S from J_PRODUCT_NEW where P_CODE1='$code' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[P_IMAGE_S];
}

function getProductImgE($code)
{
	$fsql = " select P_IMAGE_S from J_PRODUCT_ENG where P_CODE1='$code' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[P_IMAGE_S];
}

function getProductImgL($code)
{
	$fsql = " select P_IMAGE_L from J_PRODUCT_NEW where P_CODE1='$code' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[P_IMAGE_L];
}
function getCalCnt($code)
{
	$fsql = "select ifnull(count(*),0) as cnt from tbl_schedule_list where sc_date = '".$code."' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[cnt];
}



 function img_ext()
 {
    return array(
        'gif','jpe','jpg','jpeg','bmp','png','art','ani','bnr','cal',
        'fax','hdp','mac','pbm','pcd','pct','pcx','pgm','png','ppm',
        'psd','ras','tga','tif','tiff','wmf','cdr','cgm','cmk','cut',
        'dcx','dib','drw','dxf','emf','eps','flc','fli','iff',
        'lbm','wpg'
    );
 }

 function link_image_all($str)
 {
    if(!empty($sttr))
    {
        return preg_replace("/&lt;.*?img.*?src=\s*?['\"]http:\/\/([0-9a-z-.\/~_]+\.(" . implode("|", img_ext()) . "))['\"].*?&gt;/i", "<img src=\"http://\\1\" />", $str);
    }
    return false;
 }


function getimage($con,$idx,$hh="") {
	$cnt = preg_match_all('@<img\s[^>]*src\s*=\s*(["\'])?([^\s>]+?)\1@i',stripslashes($con),$output);
		$j = 0;
		for($i = 0; $i < $cnt; $i ++) {
		$cols[$j][] = str_replace('""', '"', ($output[2][$i] != '') ? $output[2][$i] : $output[4][$i]);

		if($output[6][$i] != '')
		$j ++;

			$img = $cols[0][$i];
			echo "<a href='$img' id='".$hh."gallery_".$idx."_".$i."' rel=\"prettyPhoto[".$hh."gallery_$idx]\"></a>";
		}
//	return $img;
}

function getConImg($con) {
	$cnt = preg_match_all('@<img\s[^>]*src\s*=\s*(["\'])?([^\s>]+?)\1@i',stripslashes($con),$output);
		$j = 0;
		for($i = 0; $i < $cnt; $i ++) {
		$cols[$j][] = str_replace('""', '"', ($output[2][$i] != '') ? $output[2][$i] : $output[4][$i]);

		if($output[6][$i] != '')
		$j ++;

			$img = $cols[0][$i];
		}
	return $img;
}


function get_cate($code)
{
	$fsql = " select * from tbl_category where ca_idx ='$code'";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow = mysql_fetch_array($fresult);
	return $frow[ca_name];
}

function get_code_radio($gubun, $val = "")
{
	$fsql = " select * from tbl_code where code_gubun ='$gubun' order by onum desc, code_no asc";
	$fresult = mysql_query($fsql) or die (mysql_error());
	while ($frow = mysql_fetch_array($fresult)) {
	?>
	<li><input type="radio" title="<?=$frow[code_name]?>" class="code_<?=$frow[code_gubun]?>" id="<?=$frow[code_gubun]?>_<?=$frow[code_no]?>" name="<?=$gubun?>" value="<?=$frow[code_no]?>" <? if(strpos($val, $frow[code_no]) !== false) { echo "checked"; } ?>/> <label for="<?=$frow[code_gubun]?>_<?=$frow[code_no]?>"><?=$frow[code_name]?></label></li>
	<? } ?>
<?
}

function get_code_select($gubun,$val = "")
{
	?>
	<select name="<?=$gubun?>" id="<?=$gubun?>" class="code_<?=$gubun?>" style="width: 154px;height: 34px;">
	<option value="">선택하세요</option>
	<?
	$fsql = " select * from tbl_code where code_gubun ='$gubun' order by onum desc, code_no asc";
	$fresult = mysql_query($fsql) or die (mysql_error());
	while ($frow = mysql_fetch_array($fresult)) {
	?>
		<option value="<?=$frow[code_no]?>" <? if(strpos($val, $frow[code_no]) !== false) { echo "selected"; } ?>><?=$frow[code_name]?></option>

	<? } ?>
	</select>
<?
}


function get_img($img, $path, $width, $height, $water = "")
{

	$file_dir = "";
	$thumb_img_path = $_SERVER['DOCUMENT_ROOT'].$path."/thum_".$width."_$height/";
	if(!is_dir($thumb_img_path)){
		@mkdir($thumb_img_path, 0777);
	}
	$thumb_img = $thumb_img_path.$img;
	if(!file_exists($thumb_img))
	{
		@GD2_make_thumb($width,$height,$thumb_img,$_SERVER[DOCUMENT_ROOT]."/".$path."/".$img);
	}
	chmod($_SERVER['DOCUMENT_ROOT'].$path."/thum_".$width."_".$height."/".$img,0777);
//	echo $path."/thum_".$width."_".$height."/".$img;
	return $path."/thum_".$width."_".$height."/".$img;
}

function get_bbs_img($img, $path, $width, $height, $code)
{

	$file_dir = "";
	$thumb_img_path = $_SERVER[DOCUMENT_ROOT].$path."/thum_".$width."_$height/";
	if(!is_dir($thumb_img_path)){
		@mkdir($thumb_img_path, 0777);
	}
	$thumb_img = $thumb_img_path.$img;
	if(!file_exists($thumb_img))
	{
		@GD2_make_thumb($width,$height,$thumb_img,$_SERVER[DOCUMENT_ROOT]."/".$path."/".$img);
		$wimg = "std_2000.png";
//		if ($water == "Y") {
		if ($width > 300) {
			imageWaterMaking($thumb_img_path.$img, $_SERVER[DOCUMENT_ROOT]."/_images/common/".$wimg, 100);
		}
	}
	chmod($_SERVER[DOCUMENT_ROOT]."/data/thum_".$width."_".$height."/".$img,0777);
//	echo $path."/thum_".$width."_".$height."/".$img;
	return $path."/thum_".$width."_".$height."/".$img;
}

$write_sample_img = "/resource/img/sub/pic_info_img.png";



function DateAdd($interval, $number, $date) {

    //getdate()함수를 통해 얻은 배열값을 각각의 변수에 지정합니다.

	$date_time_array = getdate($date);
	$hours = $date_time_array["hours"];
	$minutes = $date_time_array["minutes"];
	$seconds = $date_time_array["seconds"];
	$month = $date_time_array["mon"];
	$day = $date_time_array["mday"];
	$year = $date_time_array["year"];


     //switch()구문을 사용해서 interval에 따라 적용합니다.

     switch ($interval) {
          case "yyyy":
              $year +=$number;
              break;

          case "q":
              $year +=($number*3);
              break;

          case "m":
              $month +=$number;
              break;

          case "y":
          case "d":
          case "w":
              $day+=$number;
              break;

          case "ww":
              $day+=($number*7);
              break;

          case "h":
              $hours+=$number;
              break;

          case "n":
              $minutes+=$number;
              break;

          case "s":
              $seconds+=$number;
              break;

     }


    $timestamp = date("Y-m-d",mktime($hours ,$minutes, $seconds, $month, $day, $year));
	return $timestamp;
}

function DateAddTime($interval, $number, $date) {

    //getdate()함수를 통해 얻은 배열값을 각각의 변수에 지정합니다.

	$date_time_array = getdate($date);
	$hours = $date_time_array["hours"];
	$minutes = $date_time_array["minutes"];
	$seconds = $date_time_array["seconds"];
	$month = $date_time_array["mon"];
	$day = $date_time_array["mday"];
	$year = $date_time_array["year"];


     //switch()구문을 사용해서 interval에 따라 적용합니다.

     switch ($interval) {
          case "yyyy":
              $year +=$number;
              break;

          case "q":
              $year +=($number*3);
              break;

          case "m":
              $month +=$number;
              break;

          case "y":
          case "d":
          case "w":
              $day+=$number;
              break;

          case "ww":
              $day+=($number*7);
              break;

          case "h":
              $hours+=$number;
              break;

          case "n":
              $minutes+=$number;
              break;

          case "s":
              $seconds+=$number;
              break;

     }


    $timestamp = date("YmdHis",mktime($hours ,$minutes, $seconds, $month, $day, $year));
	return $timestamp;
}


function room_view($room_idx)
{
	$fsql = " update tbl_room_mst set hit = hit + 1  where room_idx ='$room_idx' ";
	$fresult = mysql_query($fsql) or die (mysql_error());

	$fsql = "select cnt+1 as cnt from tbl_room_view_log where room_idx = '$room_idx' and ip_address	= '".$_SERVER['REMOTE_ADDR']."' ";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	if ($frow[cnt] == "") {
		$fsql = " insert into tbl_room_view_log set
			ip_address	= '".$_SERVER['REMOTE_ADDR']."'
			, cnt		= '1'
			, room_idx	= '$room_idx'
			, r_date	= now()
		";
		$fresult = mysql_query($fsql) or die (mysql_error());
	} else {
		$fsql = " update tbl_room_view_log set
			 room_idx	= '$room_idx'
			, cnt		= '".$frow[cnt]."'
			, r_date	= now()
			where ip_address	= '".$_SERVER['REMOTE_ADDR']."'
		";
		$fresult = mysql_query($fsql) or die (mysql_error());

	}
}

function ipaddress_to_uint32($ip) {
    list($v4,$v3,$v2,$v1) = explode(".", $ip);
    return ($v4*256 *256*256) + ($v3*256*256) + ($v2*256) + ($v1);
}

function ipaddress_to_country_code($ip) {

    $i = ipaddress_to_uint32($ip);

    $query   = "select * from tbl_geoip where ip32_start<= $i and $i <=ip32_end;";
    $result = mysql_query($query) or die (mysql_error());
    $row = mysql_fetch_array($result);

    return $row['country_code'];
}


function getYoil($strdate)
{
	$yoil = array("일","월","화","수","목","금","토");
	$date= $strdate;

echo $yoil[date('w',strtotime($date))];

}

function get_rand($size)
{
	$feed = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for ($i=0; $i < $size; $i++)
	{
	    $rand_str .= substr($feed, rand(0, strlen($feed)-1), 1);
	}
	return $rand_str;
}

function get_rand_num($size)
{
	$feed = "0123456789";
	for ($i=0; $i < $size; $i++)
	{
	    $rand_str .= substr($feed, rand(0, strlen($feed)-1), 1);
	}
	return $rand_str;
}

function get_home_setting($ths_idx)
{
	$fsql    = "select * from tbl_homepage_setting where ths_idx = $ths_idx";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow	= mysql_fetch_array($fresult);
	return $frow[contents];
}


function getEventNo($program_no)
{
	$fsql    = "select tel_idx from ".TBL_EVENT_LIST." where program_no = '$program_no'";
	$fresult = mysql_query($fsql) or die (mysql_error());
	$frow=mysql_fetch_array($fresult);
	return $frow[tel_idx];
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac'; }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { $bname = 'Internet Explorer'; $ub = "MSIE"; }
    elseif(preg_match('/Firefox/i',$u_agent)) { $bname = 'Mozilla Firefox'; $ub = "Firefox"; }
    elseif(preg_match('/Chrome/i',$u_agent)) { $bname = 'Google Chrome'; $ub = "Chrome"; }
    elseif(preg_match('/Safari/i',$u_agent)) { $bname = 'Apple Safari'; $ub = "Safari"; }
    elseif(preg_match('/Opera/i',$u_agent)) { $bname = 'Opera'; $ub = "Opera"; }
    elseif(preg_match('/Netscape/i',$u_agent)) { $bname = 'Netscape'; $ub = "Netscape"; }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){ $version= $matches['version'][0]; }
        else { $version= $matches['version'][1]; }
    }
    else { $version= $matches['version'][0]; }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array('userAgent'=>$u_agent, 'name'=>$bname, 'version'=>$version, 'platform'=>$platform, 'pattern'=>$pattern);
}
$admin_email = "master@imagestd.com.co.kr";
$new_num = 14;

function sql_password($value)
{
	global $connect;

	//$sql = " select md5('$value') as pass ";
	$sql = " select sha2('$value',512) as pass ";
	$result = mysqli_query($connect, $sql) or die (mysql_error());
	$row=mysqli_fetch_array($result);

    return $row['pass'];
}

function getFacebookInfo($value)
{
	global $connect;

		$sql = " select count(*) as cnts from tbl_myzzim where m_idx = '".$m_idx."' and g_idx = '".$g_idx."' limit 1 ";
		$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row = mysqli_fetch_array($result);

		return $row['cnts'];
}


function alink($data) {

	// http
	$data = preg_replace("/http:\/\/([0-9a-z-.\/@~?&=_]+)/i", "<a href=\"http://\\1\" target='_blank'>http://\\1</a>", $data);

	// ftp
	$data = preg_replace("/ftp:\/\/([0-9a-z-.\/@~?&=_]+)/i", "<a href=\"ftp://\\1\" target='_blank'>ftp://\\1</a>", $data);

	// email
	$data = preg_replace("/([_0-9a-z-]+(\.[_0-9a-z-]+)*)@([0-9a-z-]+(\.[0-9a-z-]+)*)/i", "<a href=\"mailto:\\1@\\3\">\\1@\\3</a>", $data);

	return $data;

}

function right($value, $count){
   $value = substr($value, (strlen($value) - $count), strlen($value));
   return $value;
}

function left($string, $count){
   return substr($string, 0, $count);
}

function get_school($code)
{
	$strs = "";
	if ($code == "01")
	{
		$strs = "초대줄";
	} elseif ($code == "02") {
		$strs = "대줄";
	} elseif ($code == "03") {
		$strs = "학사";
	} elseif ($code == "04") {
		$strs = "석,박사 이상 ";
	}
	return $strs;
}

function get_ex_no()
{
	$fsql		= "select em_no from tbl_exam_member order by r_date desc limit 0, 1";
	$fresult	= mysql_query($fsql) or die (mysql_error());
	$frow		= mysql_fetch_array($fresult);
	if ($frow[em_no] == "")
	{
		$em_number = "1";
	} else {
		$arr_em_number = explode("_",$frow[em_no]);
		$em_number	= $arr_em_number[1];
	}
	return date("Ymd")."_".str_pad((int)$em_number+1, 5, "0", STR_PAD_LEFT);
}

//$head_title = "Uver";

function get_device() {
    // 모바일 기종(배열 순서 중요, 대소문자 구분 안함)
    $ary_m = array("iPhone","iPod","IPad","Android","Blackberry","SymbianOS|SCH-M\d+","Opera Mini","Windows CE","Nokia","Sony","Samsung","LGTelecom","SKT","Mobile","Phone");
	$str = "P";
    for($i=0; $i<count($ary_m); $i++){
        if(preg_match("/$ary_m[$i]/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            //return $ary_m[$i];
			$str = "M";
            break;
        }
    }
    return $str;
}


function fn_edate($s_date, $month){
	$out_date = "";

	$tmp_array = explode("-",$s_date);
	$tmp_year	= $tmp_array[0];
	$tmp_month	= $tmp_array[1];
	$tmp_date	= $tmp_array[2];

	$tmp_year = $tmp_year * 1;
	$tmp_month = $tmp_month * 1;
	$tmp_date = $tmp_date * 1;


	// 계산
	$tmp_month = $tmp_month + $month;
	$tmp_date--;

	if($tmp_month>12){

		$tmp_year = $tmp_year + (floor($tmp_month/12));
		$tmp_month = $tmp_month%12;
		if($tmp_month==0){
			$tmp_month = 12;
			$tmp_year--;
		}
	}

	if($tmp_date>0){
		$tmp_last = date("t",strtotime($tmp_year."-".$tmp_month."-01"));

		if($tmp_last < $tmp_date){
			$tmp_date = $tmp_last;
		}

	}else{
		$tmp_month--;
		if($tmp_month==0){
			$tmp_month = 12;
			$tmp_year--;
		}
		//$tmp_date = date("t",strtotime($s_date));
		$tmp_date = date("t",strtotime($tmp_year."-".$tmp_month."-01"));
	}



	$tmp_month = $tmp_month*1;
	$tmp_date = $tmp_date*1;

	if($tmp_month<10){
		$tmp_month  = "0".$tmp_month;
	}

	if($tmp_date<10){
		$tmp_date  = "0".$tmp_date;
	}

	//$out_date = $tmp_year . "-" . str_pad($tmp_month,2,"0",str_pad_left) . "-" . str_pad($tmp_date,2,"0",str_pad_left);
	$out_date = $tmp_year . "-" . $tmp_month . "-" . $tmp_date;

	return $out_date;

}

function fn_edate_same($s_date, $month){
	$out_date = "";

	$tmp_array = explode("-",$s_date);
	$tmp_year	= $tmp_array[0];
	$tmp_month	= $tmp_array[1];
	$tmp_date	= $tmp_array[2];

	$tmp_year = $tmp_year * 1;
	$tmp_month = $tmp_month * 1;
	$tmp_date = $tmp_date * 1;


	// 계산
	$tmp_month = $tmp_month + $month;
	//$tmp_date--;

	if($tmp_month>12){

		$tmp_year = $tmp_year + (floor($tmp_month/12));
		$tmp_month = $tmp_month%12;
		if($tmp_month==0){
			$tmp_month = 12;
			$tmp_year--;
		}
	}

	if($tmp_date>0){
		$tmp_last = date("t",strtotime($tmp_year."-".$tmp_month."-01"));

		if($tmp_last < $tmp_date){
			$tmp_date = $tmp_last;
		}

	}else{
		$tmp_month--;
		if($tmp_month==0){
			$tmp_month = 12;
			$tmp_year--;
		}
		//$tmp_date = date("t",strtotime($s_date));
		$tmp_date = date("t",strtotime($tmp_year."-".$tmp_month."-01"));
	}



	$tmp_month = $tmp_month*1;
	$tmp_date = $tmp_date*1;

	if($tmp_month<10){
		$tmp_month  = "0".$tmp_month;
	}

	if($tmp_date<10){
		$tmp_date  = "0".$tmp_date;
	}

	//$out_date = $tmp_year . "-" . str_pad($tmp_month,2,"0",str_pad_left) . "-" . str_pad($tmp_date,2,"0",str_pad_left);
	$out_date = $tmp_year . "-" . $tmp_month . "-" . $tmp_date;

	return $out_date;

}


function fn_month_term($s_date, $month){
	$out_date = "";

	$tmp_array = explode("-",$s_date);
	$tmp_year	= $tmp_array[0];
	$tmp_month	= $tmp_array[1];


	$tmp_year = $tmp_year * 1;
	$tmp_month = $tmp_month * 1;



	// 계산
	$tmp_month = $tmp_month + $month;





	if($tmp_month>12){

		$tmp_year = $tmp_year + (floor($tmp_month/12));
		$tmp_month = $tmp_month%12;
	}


	$tmp_month = $tmp_month*1;


	if($tmp_month<10){
		$tmp_month  = "0".$tmp_month;
	}


	$out_date = $tmp_year . "-" . $tmp_month;

	return $out_date;

}


function fn_addDays($day2,$d) {
    $day2 = strtotime(date($day2));
    $day = $day2 + $d*86400;

    $day = date('Y-m-d',$day);


    return $day;
}


function getCodeSlice($_tmp_code, $char = "UTF-8"){
	$_tmp_code = mb_substr($_tmp_code, 1, mb_strlen($_tmp_code) -2, $char );
	return $_tmp_code;
}



function get_cate_text($code)
{
	global $connect;

	$fsql		= "select * from tbl_code where code_no='".$code."' limit 1";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$now_cnt = $frow['depth'];
	$out_txt = $frow['code_name'];
	$parent_code_no	= $frow['parent_code_no'];

	while($now_cnt > 1){
		$now_cnt--;

		$fsql2		= "select * from tbl_code where code_no='".$parent_code_no."' limit 1";
		$fresult2	= mysqli_query($connect, $fsql2) or die (mysqli_error($connect));
		$frow2		= mysqli_fetch_array($fresult2);
		$parent_code_no = $frow2['parent_code_no'];

		$out_txt = $frow2['code_name'] . " &gt; " . $out_txt;

	}


	return $out_txt;
}


//쓰임새 로케이션 정보 출력
/* 기존
function get_group_select_text($code_number)
{
	global $connect;

	$depth_1_no = substr($code_number, 0, 1);
	$fsql		= "select * from tbl_group where depth=1 order by code_no asc";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$out_txt = "<select name=\"\" id=\"\" onchange=\"fncGroupLoc(this.value)\">";
	$sel = "";
	while($frow=mysqli_fetch_array($fresult)){
		
		if($depth_1_no == $frow['code_no']){
			$sel = "selected";
		}else{
			$sel = "";
		}
		$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
	}
	$out_txt = $out_txt. "</select>";

	if(strlen($code_number) >= 3){
		$depth_2_no = substr($code_number, 0, 3);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_group where depth=2 order by code_no asc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncGroupLoc2(this.value)\">";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			if($depth_2_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
		}
		$out_txt = $out_txt. "</select>";	
	}


	if(strlen($code_number) >= 5){
		$depth_3_no = substr($code_number, 0, 5);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_group where depth=3 order by code_no asc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncGroupLoc2(this.value)\">";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			
			if($depth_3_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";			
		}
		$out_txt = $out_txt. "</select>";	
	}
	return $out_txt;
}
*/

function get_group_select_text($code_number)
{
	global $connect;

	$depth_1_no = substr($code_number, 0, 1);
	$fsql		= "select * from tbl_group where status = 'Y' and depth=1 order by onum desc";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$out_txt = "<select name=\"\" id=\"\" onchange=\"fncGroupLoc(this.value)\">";
	$sel = "";
	while($frow=mysqli_fetch_array($fresult)){
		
		if($depth_1_no == $frow['code_no']){
			$sel = "selected";
		}else{
			$sel = "";
		}
		$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
	}
	$out_txt = $out_txt. "</select>";

	if(strlen($code_number) >= 3){
		$depth_2_no = substr($code_number, 0, 3);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_group where status = 'Y' and depth=2 and parent_code_no = '1' order by onum desc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncGroupLoc2(this.value)\">";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			if($depth_2_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
		}
		$out_txt = $out_txt. "</select>";	
	}


	if(strlen($code_number) >= 5){
		$depth_3_no = substr($code_number, 0, 5);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_group where status = 'Y' and depth=3 and parent_code_no = '".substr($code_number,0,3)."' order by onum desc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncGroupLoc2(this.value)\">";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			
			if($depth_3_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";			
		}
		$out_txt = $out_txt. "</select>";	
	}
	return $out_txt;
}


function get_cate_code_select_text($code_number)
{
	global $connect;

	$depth_1_no = substr($code_number, 0, 1);
	$fsql		= "select * from tbl_code where code_gubun = 'goods' and status='Y' and depth=1 order by onum desc";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$out_txt = "<select name=\"\" id=\"\" onchange=\"fncCate_Code_Loc(this.value)\">";
	$out_txt = $out_txt."	<option value='' >전체</option>";
	$sel = "";
	while($frow=mysqli_fetch_array($fresult)){
		
		if($depth_1_no == $frow['code_no']){
			$sel = "selected";
		}else{
			$sel = "";
		}
		$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
	}
	$out_txt = $out_txt. "</select>";

	
	//echo strlen($code_number);

	
	if(strlen($code_number) >= 1 ){
		$depth_2_no = substr($code_number, 0, 3);
		
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_code where code_gubun = 'goods' and status='Y' and  depth=2  and parent_code_no = '".$depth_1_no."'  order by onum desc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncCate_Code_Loc(this.value)\">";
		$out_txt = $out_txt."	<option value=\"".$depth_1_no."\" >선택</option>";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			if($depth_2_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";				
		}
		$out_txt = $out_txt. "</select>";	
	}


	if(strlen($code_number) >= 3 ){
		
		$depth_3_no = substr($code_number, 0, 5);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_code where code_gubun = 'goods' and status='Y' and  depth=3 and parent_code_no = '".$depth_2_no."'  order by onum desc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));

		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncCate_Code_Loc(this.value)\">";
		$out_txt = $out_txt."	<option value=\"".$depth_2_no."\" >선택</option>";
		$sel = "";

		while($frow=mysqli_fetch_array($fresult)){
			
			if($depth_3_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";			
		}
		$out_txt = $out_txt. "</select>";	
	


	}

	if(strlen($code_number) >= 5){
		$depth_4_no = substr($code_number, 0, 7);
		$out_txt = $out_txt. "<span class=\"sl_arrow\">&gt;</span>";
		$fsql		= "select * from tbl_code where code_gubun = 'goods' and status='Y' and  depth=4 and parent_code_no = '".$depth_4_no."'  order by onum desc";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		
		
		$out_txt = $out_txt. "<select name=\"\" id=\"\" onchange=\"fncCate_Code_Loc(this.value)\">";
		$out_txt = $out_txt."	<option value=\"".$depth_3_no."\" >선택</option>";
		$sel = "";
		while($frow=mysqli_fetch_array($fresult)){
			
			if($depth_4_no == $frow['code_no']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$out_txt = $out_txt."<option value=\"".$frow['code_no']."\" ".$sel."   >".$frow['code_name']."</option>";			
		}
		$out_txt = $out_txt. "</select>";	
	}

	return $out_txt;
}


function get_group_text($code)
{
	global $connect;

	$fsql		= "select * from tbl_group where code_no='".$code."' limit 1";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$now_cnt = $frow['depth'];
	$out_txt = $frow['code_name'];
	$parent_code_no	= $frow['parent_code_no'];

	while($now_cnt > 1){
		$now_cnt--;

		$fsql2		= "select * from tbl_group where code_no='".$parent_code_no."' limit 1";
		$fresult2	= mysqli_query($connect, $fsql2) or die (mysqli_error($connect));
		$frow2		= mysqli_fetch_array($fresult2);
		$parent_code_no = $frow2['parent_code_no'];

		$out_txt = $frow2['code_name'] . " &gt; " . $out_txt;

	}


	return $out_txt;
}

function get_group_array($code)
{
	global $connect;

	$fsql		= "select * from tbl_group where code_no='".$code."' limit 1";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$code_array = array();
	$now_cnt = $frow['depth'];
	$parent_code_no	= $frow['parent_code_no'];

	$code_array[$now_cnt] = $frow['code_no'];

	while($now_cnt > 1){
		$now_cnt--;

		$fsql2		= "select * from tbl_group where code_no='".$parent_code_no."' limit 1";
		$fresult2	= mysqli_query($connect, $fsql2) or die (mysqli_error($connect));
		$frow2		= mysqli_fetch_array($fresult2);
		$parent_code_no = $frow2['parent_code_no'];

		$code_array[$now_cnt] = $frow2['code_no'];

		ksort($code_array);
	}

	return $code_array;
}

// 상품 옵션 키값을 받아서 재고량 리턴
function get_option_cnt_idx($g_key)
{
	global $connect;

	$fsql		= "select goods_cnt from tbl_goods_option where idx='".$g_key."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$out_txt = $frow['goods_cnt'];

	return $out_txt;
}


// 상품코드, 색상, 사이즈를 입력 받아서 재고 수량 확인
function get_option_cnt($goods_code, $color_code, $size_code)
{
	global $connect;

	$fsql		= "select goods_cnt from tbl_goods_option where goods_code='".$goods_code."' and goods_color='".$color_code."' and goods_size='".$size_code."' limit 1";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$out_txt = $frow['goods_cnt'];

	return $out_txt;
}

// 상품코드, 색상, 사이즈를 입력 받아서 판매 유무 확인
function get_option_use($goods_code, $color_code, $size_code)
{
	global $connect;

	$fsql		= "select use_yn from tbl_goods_option where goods_code='".$goods_code."' and goods_color='".$color_code."' and goods_size='".$size_code."' limit 1";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$out_txt = $frow['use_yn'];

	return $out_txt;
}


// 상품코드, 색상 입력 받아서 재고 수량 확인
function get_option_color_cnt($goods_code, $color_code)
{
	global $connect;

	$fsql		= "select sum(goods_cnt) as goods_cnt from tbl_goods_option where goods_code='".$goods_code."' and goods_color='".$color_code."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$out_txt = $frow['goods_cnt'];

	return $out_txt;
}


// 상품코드, 사이즈 입력 받아서 재고 수량 확인
function get_option_size_cnt($goods_code, $size_code)
{
	global $connect;

	//$fsql		= "select sum(goods_cnt) as goods_cnt from tbl_goods_option where goods_code='".$goods_code."' and goods_size = '".$size_code."' ";

	$fsql		= "select sum(goods_cnt) as goods_cnt from tbl_goods_option where goods_code='".$goods_code."' and goods_size in (select code_no from tbl_size where type='".$size_code."') ";

	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$out_txt = $frow['goods_cnt'];

	return $out_txt;
}


// 아이디 존재유무 확인
function chk_member_id($userid)
{
	global $connect;

	$fsql		= " select count(*) cnts from tbl_member where user_id = '".$userid."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	return $frow['cnts'];
}


// 아이디 존재유무 확인
function chk_member_adminrator_id($userid)
{
	global $connect;

	$fsql		= " select count(*) cnts from tbl_member_adminrator where user_id = '".$userid."'";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	return $frow['cnts'];
}


function write_log($message){
	 $dir = "./log/";
	$myfile = fopen($dir.date("Ymd").".txt", "a") or die("Unable to open file!");
	$txt = chr(13).chr(10).date("Y.m.d G:i:s")."(".$_SERVER['REMOTE_ADDR']." ".$_SERVER['PHP_SELF']." ) : ".chr(13).chr(10).$message.chr(13).chr(10);
	fwrite($myfile, chr(13).chr(10). $txt.chr(13).chr(10));
	fclose($myfile);

}


function write_log_dir($message,$dir){
	 //$dir = "./log/";
	$myfile = fopen($dir.date("Ymd").".txt", "a") or die("Unable to open file!");
	$txt = chr(13).chr(10).date("Y.m.d G:i:s")."(".$_SERVER['REMOTE_ADDR']." ".$_SERVER['PHP_SELF']." ) : ".chr(13).chr(10).$message.chr(13).chr(10);
	fwrite($myfile, chr(13).chr(10). $txt.chr(13).chr(10));
	fclose($myfile);

}




// 로그인 관련

function goUrl($url="", $msg=""){
	echo "<script type='text/javascript'>";
	if($msg)
	{
	echo "	alert('".$msg."');";
	}
	if($url)
	{

	echo "setTimeout( function() {	";
	echo "	location.href='".$url."';";
	echo "}, 1000);					";
	
	//echo "	location.href='".$url."';";
	}
	echo "</script>";
}



function chkGoods($idx){
	global $connect;

	$fsql		= "select count(*) as goods_cnt from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	return $frow['goods_cnt'];

}

function returnGoods($idx){
	global $connect;

	$fsql		= "select * from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	return $frow;

}

function returnGoodsCols($idx, $cols){
	global $connect;

	$fsql		= "select ".$cols." as outcol from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	return $frow['outcol'];
	//return $fsql;
}

// 상품 실가격 노출하도록 (상품키값이랑 수량을 넘김)
/*
function viewGoodsPay($idx, $cnts=1){
	global $connect;
	global $_set_member_dcprice;
	

	$fsql		= "select * from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	// 기본가격
	$ori_price = $frow['price_se'] * $cnts;
	// 기간할인 가격
	$default_dc = 0;
	// 회원등급별 할인
	$level_dc = 0;

	// 기간할인 사용 유무
	if( $frow['dis_date_use'] == "Y"){
		if( $frow['dis_date_s'] != "" && $frow['dis_date_e'] != "" && $frow['price_ds'] != "" ){

			$today = date('Y-m-d');

			if( $frow['dis_date_s'] <= $today && $frow['dis_date_e'] >= $today){
				$default_dc = $frow['price_ds'];
			}

		}
	}

	// 회원이라면 회원등급에 맞춰서 할인이 됨
	if( $_SESSION['member']['level'] ){
		$level_dc = $ori_price * $_set_member_dcprice[$_SESSION['member']['level']] / 100;
	}

	// 노출 가격은 [기본가격] - [기간할인] - [등급할인]
	$out_price = $ori_price - $default_dc - $level_dc;

	$out_price = round($out_price,-1);

	return $out_price;

}
*/

function viewGoodsPay($idx, $cnts=1){
	global $connect;
	global $_set_member_dcprice;
	

	$fsql		= "select * from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	// 기본가격
	$ori_price = $frow['price_se'] * $cnts;
	// 기간할인 가격
	$default_dc = 0;
	

	// 기간할인 사용 유무
	if( $frow['dis_date_use'] == "Y"){
		if( $frow['dis_date_s'] != "" && $frow['dis_date_e'] != "" && $frow['price_ds'] != "" ){

			$today = date('Y-m-d');

			if( $frow['dis_date_s'] <= $today && $frow['dis_date_e'] >= $today){
				$default_dc = $frow['price_ds'];
			}

		}
	}

	// 노출 가격은 [기본가격] - [기간할인]
	$out_price = $ori_price - $default_dc;

	$out_price = round($out_price,-1);

	return $out_price;

}

// 상품 기간할인 중인지 체크
function chkGoodsLimitDc($idx){
	global $connect;

	$fsql		= "select * from tbl_goods where g_idx='".$idx."' ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);
	
	$dc_chk = "N";

	// 기간할인 사용 유무
	if( $frow['dis_date_use'] == "Y"){
		if( $frow['dis_date_s'] != "" && $frow['dis_date_e'] != "" && $frow['price_ds'] != "" ){

			$today = date('Y-m-d');

			if( $frow['dis_date_s'] <= $today && $frow['dis_date_e'] >= $today){
				$dc_chk = "Y";
			}

		}
	}

	return $dc_chk;

}

function goods_view_log($g_idx){
	global $connect;

	// 추후 회원쪽 작업되면....
	$m_idx = "";
	$ip = $_SERVER['REMOTE_ADDR'];

	if($g_idx){

		$fsql		= "select count(*) as goods_cnt from tbl_goods where g_idx='".$g_idx."' ";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$frow		= mysqli_fetch_array($fresult);

		if( $frow['goods_cnt'] > 0 ){

			// 상품이 존재할 때만 조회 흔적을 남김
			$sql = "
			insert into tbl_goods_pop SET
				 g_idx			= '".$g_idx."'
				,m_idx			= '".$m_idx."'
				,ip				= '".$ip."'
				,regdate		= now()
			";
			mysqli_query($connect, $sql) or die (mysqli_error($connect));

		}

	}

}


function chk_zzim($m_idx, $g_idx){
	global $connect;

	if($g_idx){

		$sql = " select count(*) as cnts from tbl_myzzim where m_idx = '".$m_idx."' and g_idx = '".$g_idx."' limit 1 ";
		$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row = mysqli_fetch_array($result);

		return $row['cnts'];

	}

}


// 회원 포인트 조회 (날짜 지정시 지정 기간만)
function showPoint($user_id, $sdate="", $edate=""){
	global $connect;

	if($user_id){

		$_sql_detail = "";
		$_sql_detail2 = "";

		if($sdate){
			$sdate = substr($sdate,0,10);
			$_sql_detail .= " and regdate >= '$sdate 00:00:00' ";
			$_sql_detail2 .= " and enddate >= '$sdate 00:00:00' ";
		}

		if($edate){
			$edate = substr($edate,0,10);
			$_sql_detail .= " and regdate <= '$edate 23:59:59' ";
			$_sql_detail2 .= " and enddate <= '$edate 23:59:59' ";

		}

		// 전체 쌓인 포인트
		$sql = " select sum(point) as t_point from tbl_point where user_id = '".$user_id."' ".$_sql_detail;
		$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row = mysqli_fetch_array($result);

		// 소멸 포인트 확인
		/*
		$sql2 = " select sum(point) as t_point from tbl_point where user_id = '".$user_id."' and enddate != '' and enddate <= curdate() and point > 0 ".$_sql_detail2;
		$result2 = mysqli_query($connect, $sql2) or die (mysql_error($connect));
		$row2 = mysqli_fetch_array($result2);
		*/

		//$out_point = $row['t_point'] - $row2['t_point'];
		$out_point = $row['t_point'];

		return $out_point;

	}

}


// 회원 포인트 조회 (누적)
function accPoint($user_id, $idx){
	global $connect;

	if($user_id){

		$_sql_detail = "";

		$_sql_detail .= " and idx <= '$idx' ";


		$sql = " select sum(point) as t_point from tbl_point where user_id = '".$user_id."' ".$_sql_detail;
		$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row = mysqli_fetch_array($result);

		/*
		$sql2 = " select sum(point) as t_point from tbl_point where user_id = '".$user_id."' and enddate != '' and enddate <= curdate() and point > 0 ".$_sql_detail;
		$result2 = mysqli_query($connect, $sql2) or die (mysql_error($connect));
		$row2 = mysqli_fetch_array($result2);
		*/


		//$out_point = $row['t_point'] - $row2['t_point'];
		$out_point = $row['t_point'];

		return $out_point;

	}

}


// 포인트 적립 조회
function chkPoint($user_id, $o_idx, $point, $msg ){
	global $connect;

	if($user_id){

		$sql = " select count(*) as cnts from tbl_point where user_id = '".$user_id."' and  o_idx = '".$o_idx."' and  point = '".$point."' and  msg = '".$msg."' ";
		$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row = mysqli_fetch_array($result);

		$out_cnt = $row['cnts'];

		return $out_cnt;
	}

}


function decTohex($nums, $length=0){
	$nums = strtoupper(dechex($nums));

	if($length>0){
		$nums = str_pad($nums, $length, "0", STR_PAD_LEFT);
	}

	return $nums;
}

function hexTodec($nums, $length=0){
	$nums = hexdec($nums);

	if($length>0){
		$nums = str_pad($nums, $length, "0", STR_PAD_LEFT);
	}
	return $nums;
}

function convertChar($nums){
	$char_code = $nums+64;

	return chr($char_code);
}



//------- 쿠폰 관련 세트 ------------

// last_idx 가져오는 함수
function createLastIdx(){

	global $connect;

	$fsql		= " select IFNULL( max(last_idx), 0) + 1 as l_idx from tbl_coupon where left(regdate,10) = curdate() ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);
	$last_idx = $frow['l_idx'];


	//$last_idx = "3"; 중복 테스트할려고 만든 것

	return $last_idx;
}


// 쿠폰번호 생성(중복 확인까진 안함)
function createCouponNum(){
	global $connect;

	/*
	쿠폰번호는 10자리

	날짜(5) + last_idx(3) + 랜덤값(1) + 확인코드(1)

	1. 날짜
	   date('ymd')

	2. last_idx (1일 4,095개 생성, 랜덤값 적용 시 4095 x 26 = 106,470 까지 가능)
	   일일 단위로 idx를 넣어서 적용 예정

	3. 랜덤값
	   A~Z

	4. 확인코드
	   10진수 기준으로 모든 문자를 합친 후 각 자리수들을 하나씩 끊어 합한 후에 27로 나눈 값을 문자로 표현
	   ex)180302 + 10 + 14 -> 1803021014 (문자합계) -> 20 -> convertChar(20) -> T
	*/

	$coupon_txt = "";
	$chk_bit = "";

	// last_idx 값을 먼저 가져오자
	$last_idx = createLastIdx();


	// 1. 날짜
	$date_dec = date('ymd');
	$date_hex = decTohex($date_dec);

	// 2. last_idx
	$idx_dec = $last_idx;
	$tmp_idx_desc = $idx_dec;
	if($tmp_idx_desc > 4095){
		$tmp_idx_desc = $tmp_idx_desc - 4095;
	}
	$idx_hex = decTohex($tmp_idx_desc,3);

	// 3. 랜덤값
	$rand_dec = rand(1,26);
	//$rand_dec  = date('i'); 중복 테스트할려고 만든 거

	$rand_hex = convertChar($rand_dec);


	// 4. 확인코드
	$t_bit = $date_dec. $idx_dec . $rand_dec;
	$t_hap = 0;

	for($i=0;$i<strlen($t_bit);$i++){
		//echo $i . " : " . substr($t_bit,$i,1)  . "<br/>";
		$t_hap += substr($t_bit,$i,1);
	}

	$t_hap = $t_hap % 26;
	$t_hap++;

	$chk_bit = convertChar($t_hap);

	// 쿠폰번호 조합
	$coupon_txt = $date_hex . $idx_hex . $rand_hex . $chk_bit;


	return $coupon_txt;

}

// 쿠폰 존재 확인
function createCouponChk($coupon){
	global $connect;

	$fsql			= "select * from tbl_coupon where coupon_num='".$coupon."' ";
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$nTotalCount	= mysqli_num_rows($fresult);

	return $nTotalCount;
}

// 실제 쿠폰 발급하는 곳
function createCoupon($coupon_type){
	global $connect;

	$fsql			= "select * from tbl_coupon_setting where idx='".$coupon_type."' ";
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$nTotalCount	= mysqli_num_rows($fresult);

	$frow_type		= mysqli_fetch_array($fresult);

	if($nTotalCount == 0){	// 존재하지 않는 쿠폰 타입일 경우
		$message = "존재하지 않는 쿠폰 타입니다 : " . $fsql;
		goUrl("", $message);
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		return "Error";

	}else{


		$_couponNum = createCouponNum();

		while( createCouponChk($_couponNum) >= 1 ){
			$_couponNum = createCouponNum();
		}

		$last_idx = createLastIdx();

		// 테스트 (구현 필요)
		$exp_days = $frow_type['exp_days'] + 1;
		$enddate = fn_addDays(date('Y-m-d'), $exp_days);

		//$enddate = "2018-12-24";

		$fsql = " insert into tbl_coupon set
					  coupon_num	= '".$_couponNum."'
					, coupon_type	= '".$coupon_type."'
					, types			= 'N'
					, status		= 'D'
					, last_idx	= '".$last_idx."'
					, regdate	= now()
					, enddate	= '".$enddate."'
		";

		$message = "쿠폰생성 : " . $fsql;
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		$fresult = mysqli_query($connect, $fsql) or die (mysql_error());

		return $_couponNum;

	}

}



// 메인 쿠폰 등록 ( 쿠폰 존재만 확인하는게 아니라 등록되지 않은 쿠폰만 찾아야함)
function regiCouponChk($coupon){
	global $connect;

	$fsql			= " select * from tbl_coupon where user_id = '' and status = 'D' and enddate >= curdate() and coupon_num = '".$coupon."' ";
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$nTotalCount	= mysqli_num_rows($fresult);

	return $nTotalCount;
}


// 쿠폰 유저에게 매칭
function sendCoupon($coupon_num, $user_id){

	global $connect;

	if( createCouponChk($coupon_num) < 1 ){	// 존재하지 않는 쿠폰 타입일 경우
		$message = "존재하지 않는 쿠폰입니다:" . $coupon_num;
		//goUrl("", $message);
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		return "Error:" . $message;

	}else{
		// 쿠폰 내역 조회
		$fsql			= "select * from tbl_coupon where coupon_num='".$coupon_num."' ";
		$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$frow		= mysqli_fetch_array($fresult);

		if( $frow['status'] != "D" ){
			$message = "이미 발급되었거나 사용된 쿠폰입니다:" . $fsql;
			goUrl("", $message);
			write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

			return "Error:".$message;
		}

		if( $frow['user_id'] != "" ){
			$message = "이미 발급된 쿠폰입니다:" . $fsql;
			goUrl("", $message);
			write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

			return "Error:".$message;
		}

		if( $frow['enddate'] <= date('Y-m-d') ){
			$message = "사용기한이 지난 쿠폰입니다:" . $fsql;
			goUrl("", $message);
			write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

			return "Error:".$message;
		}


		$fsql = " update tbl_coupon set
					  user_id	= '".$user_id."'
					 ,status	= 'N'
					where coupon_num = '".$coupon_num."'
		";

		$message = "쿠폰발급(유저) : " . $fsql;
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		$fresult = mysqli_query($connect, $fsql) or die (mysql_error());

		return "ok";

	}

}



// 쿠폰 사용처리
function spendCoupon($c_idx, $user_id){

	global $connect;


	// 쿠폰 내역 조회
	$fsql			= "select * from tbl_coupon where c_idx='".$c_idx."' ";
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);

	$coupon_num = $frow['coupon_num'];

	if( $frow['status'] != "N" ){
		$message = "이미 사용되었거나 취소된 쿠폰입니다:";	// $row_sub['coupons']
		goUrl("", $message);
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		return "Error:".$message;
	}

	if( $frow['user_id'] != $user_id ){
		$message = "소유자가 일치하지 않는 쿠폰입니다:" . $fsql;
		goUrl("", $message);
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		return "Error:".$message;
	}

	if( $frow['enddate'] <= date('Y-m-d') ){
		$message = "사용기한이 지난 쿠폰입니다:" . $fsql;
		goUrl("", $message);
		write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

		return "Error:".$message;
	}


	$fsql = " update tbl_coupon set
				 status	= 'E'
				where coupon_num = '".$coupon_num."'
	";

	$message = "쿠폰사용(유저) : " . $fsql;
	write_log_dir($message , $_SERVER['DOCUMENT_ROOT']."/AdmMaster/_coupon/log/");

	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());

	return "ok";

	

}










// 관련상품
function showOtherGoods($cates, $cnts=10){
	global $connect;

	$output = "";

	$fsql			= "select * from tbl_goods where product_code like '%".$cates."%' and item_state = 'sale' order by g_idx desc limit 0, ".$cnts;
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	while( $frow = mysqli_fetch_array($fresult) ){

		if($frow['price_mk'] == 0){
			$pers = 0;
		}else{
			$pers = round(($frow['price_mk'] - viewGoodsPay($frow['g_idx']))/$frow['price_mk']*100,1);
		}

		$output .= "<li>";
		$output .= "	<article class='item_box'>";
		$output .= "		<a href='/item/item_view.php?gcode=".$frow['g_idx']."' class='thum_img'>";
		$output .= "			<span class='in_img'><img src='/data/product/".$frow["ufile1"]."' alt='".$frow['goods_name_front']."'></span>";
		$output .= "		</a>";
		$output .= "		<div class='item_content'>";
		$output .= "			<p class='ic_subject'><a href='#'>".$frow['goods_name_front']."</a></p>";
		$output .= "			<span class='ic_pay'><span>".number_format($frow['price_mk'])."</span> / <span class='red'>".$pers."%</span> / <strong>".number_format(viewGoodsPay($frow['g_idx']))."원</strong></span>";
		$output .= "		</div>";
		$output .= "	</article>";
		$output .= "</li>";

	}

	return $output;
}



// last_idx 가져오는 함수
function bookingLastIdx(){

	global $connect;

	$fsql		= " select IFNULL( max(idx), 0) + 1 as l_idx from tbl_mybooking ";
	$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$frow		= mysqli_fetch_array($fresult);
	$last_idx = $frow['l_idx'];

	return "B".$last_idx;
}



// 아이디를 받아 원하는 정보를 가져오자
function chk_member_col($userid, $cols)
{
	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		global $connect;

		$fsql		= " select ".$cols." as outcol from tbl_member where user_id = '".$userid."'";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$frow		= mysqli_fetch_array($fresult);

		return $frow['outcol'];
		//return $fsql;
	}
}



// 아이디를 받아 등업가능한지 확인
function chk_member_limit($userid)
{
	global $connect;
	global $_set_member_limit;

	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		// 레벨 캐치
		$user_level = chk_member_col($userid, "user_level");
		$limit_money = $_set_member_limit[$user_level];

		/*
		구매 테이블 작업 이후에 작업 예정
		$fsql		= " select ".$cols." as outcol from tbl_member where user_id = '".$userid."'";
		$fresult	= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$frow		= mysqli_fetch_array($fresult);

		return $frow['outcol'];
		//return $fsql;
		*/
		// 임시 리턴
		return $limit_money;
	}
}


// 아이디를 받아 할인 금액을 리턴받자
function chk_member_dc($userid)
{
	global $connect;
	global $_set_member_dcprice;

	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		// 레벨 캐치
		$user_level = chk_member_col($userid, "user_level");
		$dc_price = $_set_member_dcprice[$user_level];



		return $dc_price;
	}
}

// 아이디를 받아 할인 금액 적용된 금액을 리턴함
function chk_member_dcprice($userid, $price)
{
	global $connect;

	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		$dc_per = chk_member_dc($userid);
		$dc_price = $price - ($price * $dc_per / 100);

		$dc_price = round($dc_price,0);



		return $dc_price;
	}
}


// 아이디를 받아 할인 금액 적용된 금액을 리턴함
function chk_member_point($userid, $price)
{
	global $connect;
	global $_set_member_point;

	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		// 레벨 캐치
		$user_level = chk_member_col($userid, "user_level");
		$get_point = $_set_member_point[$user_level];


		return $get_point;
	}
}

// 아이디를 받아 할인 금액 적용된 금액을 리턴함
function chk_member_pointprice($userid, $price)
{
	global $connect;
	global $_set_member_point;

	if( chk_member_id($userid) < 1){

		return "error";

	}else{
		// 레벨 캐치
		$user_level = chk_member_col($userid, "user_level");
		$get_point = $_set_member_point[$user_level] * $price;


		return $get_point;
	}
}

// 임시 회원(비회원) 생성을 위한 함수
function get_createId(){
	return "U".time().rand(1,99);
}

// 임시 회원(비회원)에서 로그인 했을 때에 장바구니 정보를 업데이트 함
function fn_chg_basket($shop_id, $user_id){
	global $connect;

	$sql = " update tbl_basket set userid = '".$user_id."'  where userid ='".$shop_id."' ";
	mysqli_query($connect, $sql) or die (mysqli_error($connect));

}

// shop_id를 전달 받아서 장바구니 수량을 표시 (이건 회원이 아니라 비회원 겸용)
function fn_basket_cnt($user_id){

	global $connect;

	if($user_id == ""){
		$re_cnts = 0;
	}else{
	
		$fsql = " select count(*) as cnts from tbl_basket where order_code = '' and status = '' and userid = '".$user_id."' ";
		$fresult = mysqli_query($connect, $fsql) or die (mysqli_error($connect));
		$frow = mysqli_fetch_array($fresult);

		$re_cnts = $frow['cnts'];
	}

	return $re_cnts;

}

function use_coupon_dc_price($g_idx, $cnts, $c_idx, $user_id, $c_option){
	
	global $connect;
	global $_set_member_agree;
	global $_set_member_birth;
	global $_set_member_upgrade;
	global $_set_member_welcome;

	$_return_txt = "";

	if($user_id == ""){
		$_return_txt = "Error : 아이디가 없습니다.";

	}else if($g_idx == ""){
		$_return_txt = "Error : 상품이 없습니다.";

	}else if($c_idx == ""){
		$_return_txt = "Error : 쿠폰정보가 없습니다.";

	}else if($c_option == ""){
		$_return_txt = "Error : 옵션정보가 없습니다.";

	}else{
	
		if( chkGoods($g_idx) < 1 ){
			$_return_txt = "Error : 상품이 존재하지 않습니다.";
		}

		if( createCouponChk($c_idx) < 1 ){
			$_return_txt = "Error : 쿠폰이 존재하지 않습니다.";
		}

		// 상품 정보를 리턴 받음
		$good_row = returnGoods($g_idx);

		// 판매가
		$price_se = viewGoodsPay($g_idx);



		// 옵션별 내역 확인

		$sql_o			= " select * from tbl_goods_option where idx = '".$c_option."'";
		$result_o		= mysqli_query($connect, $sql_o) or die (mysql_error($connect));
		$row_o			= mysqli_fetch_array($result_o);


		// 필수옵션에만 쿠폰을 쓸 수 있음
		if($row_o['option_type']=="M"){
			$_option_first_price = $price_se + $row_o['goods_price'];
			$_total_first_price += ($_option_first_price * $_option_cnt);

		}else if($row_o['option_type']=="S"){
			$_option_first_price = $row_o['goods_price'];
			$_total_first_price += ($_option_first_price * $_option_cnt);
		}


		


		// 쿠폰 정보 조회

		$sql		= " select c.c_idx, c.coupon_num, c.user_id, c.regdate, c.enddate, c.usedate, c.status, c.types, s.coupon_name, s.dc_type, s.coupon_pe, s.coupon_price, s.dex_price_pe
					 from tbl_coupon c
					 left outer join tbl_coupon_setting s
					   on c.coupon_type = s.idx
				   where c.enddate > curdate()
					 and c.usedate = ''
					 and c.user_id = '".$user_id."'
					 and c.c_idx = '".$c_idx."'
				 ";
		$result		= mysqli_query($connect, $sql) or die (mysql_error($connect));
		$row		= mysqli_fetch_array($result);

		$types		= $row['types'];
		$dc_type	= $row['dc_type'];
		$dc_price	= "";

		// 할인전 가격
		$_tmp_per_se = $_option_first_price * $cnts;


		

		if($dc_type == "P"){
			$dcs = $row['coupon_pe'];
			$dc_price =  $_tmp_per_se * $dcs / 100;

		}else if($dc_type == "D"){
			$dcs = $row['coupon_price'];
			$dc_price = $dcs;

		}


	


		if($c_idx == 0 || $types == "F"){
			$dc_price = 0;
		}

		$dc_price = round($dc_price,0);

		$_return_txt = $dc_price;
	
	}

	return $_return_txt;
}

// 주문번호 생성
function createOrderNum(){
	global $connect;

	$_return_txt = "";

	/*
	타임스탬프값(10) + 랜덤값(1)
	*/
	
	$_time_stamp = time();
	$_rand_num	= rand(0,9);
	
	/* 중복체크 값	
	$_time_stamp = "1522130604";
	$_rand_num	= rand(1,2);
	*/
	$_return_txt = $_time_stamp . $_rand_num;

	return $_return_txt;

}

// 주문번호 존재 확인
function createOrderChk($order_code){
	global $connect;

	$fsql			= "select * from tbl_order where order_code='".$order_code."' ";
	$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
	$nTotalCount	= mysqli_num_rows($fresult);

	return $nTotalCount;
}

// 실제 주문번호 반환해주는 함수
function newOrderNum(){
	
	$_orderNum = createOrderNum();

	while( createOrderChk($_orderNum) >= 1 ){
		$_orderNum = createOrderNum();
	}


	return $_orderNum;
}


// 적립 예정 금액 노출
function addAbleCash($price){
	global $connect;
	global $_set_member_point;

	$out_price = 0;

	// 회원이라면 회원등급에 맞춰서 할인이 됨
	if( $_SESSION['member']['level'] ){
		$out_price = $price * $_set_member_point[$_SESSION['member']['level']] / 100;
	}

	$out_price = round($out_price,0);

	return $out_price;

}

function _microtime() { return array_sum(explode(' ',microtime())); }

function createSessionTime(){
	$f = _microtime();
	$s1 = rand(1,333);
	$s2 = random_int(1,333);
	$s3 = mt_rand(1,333);
	
	$sa = $s1 + $s2 + $s3;
	return $f.$sa;
}

function sms_send($to, $send, $msg, $opt2="", $opt3="", $opt4="")
{
	$sms_id = createSessionTime();
		

	//global $connect;
if(isset($_SERVER["HTTPS"])) {  
	$_IT_TOP_PROTOCOL = "https://";
	$_sms_link = "4";
}else{
	$_IT_TOP_PROTOCOL = "http://";
	$_sms_link = "2";
}

$send = "0260060471";


$context=<<<EOF
<script>
var _mnq = _mnq || [];
_mnq.push(['_setUid', 'MN-1397195605-2297']);
(function(s,o,m,a,g) {a=s.createElement(o),g=s.getElementsByTagName(o)[0];a.async = 1;a.src = '{$_IT_TOP_PROTOCOL}'+m+'/API/mn{$_sms_link}.js';g.parentNode.insertBefore(a, g);})
(document,'script','www.munjanote.com');


_mnq.push(['_send', {
msg:"{$msg}", 
phone:"{$to}", 
callback:"{$send}", 
reservation:"", 
encode:"", 
image:"" 
}]);



function MUNJANOTE_CallBack(obj) {
	if (obj.rslt=="true") { // 발송성공!!
		
	} else {
		var failMsg = (obj.msg)?obj.msg:"";
		// 실패시 처리를 넣어 주세요.
	}
}
</script>
EOF;
echo $context;

}


/*
function sms_send($to, $send, $msg, $opt2="", $opt3="", $opt4="")
{
	//global $connect;
if(isset($_SERVER["HTTPS"])) {  
	$_IT_TOP_PROTOCOL = "https://";
	$_sms_link = "4";
}else{
	$_IT_TOP_PROTOCOL = "http://";
	$_sms_link = "2";
}

$send = "01088258148";

	
$context=<<<EOF
<script>
var _mnq = _mnq || [];
_mnq.push(['_setUid', 'MN-1529891562-8465']);
(function(s,o,m,a,g) {a=s.createElement(o),g=s.getElementsByTagName(o)[0];a.async = 1;a.src = '{$_IT_TOP_PROTOCOL}'+m+'/API/mn{$_sms_link}.js';g.parentNode.insertBefore(a, g);})
(document,'script','www.munjanote.com');


_mnq.push(['_send', {
msg:"{$msg}", 
phone:"{$to}", 
callback:"{$send}", 
reservation:"", 
encode:"", 
image:"" 
}]);
</script>
EOF;
echo $context;


}
*/


function pg_app_convert($applDate, $applTime){
	$apps = "";
	$apps .= substr($applDate,0,4) . "-" . substr($applDate,4,2) . "-" . substr($applDate,6,2);
	$apps .= " ".substr($applTime,0,2) . ":" . substr($applTime,2,2) . ":" . substr($applTime,4,2);

	return $apps;
}


function get_invo_corp($to, $send, $msg, $opt2="", $opt3="", $opt4="")
{
	global $connect;
	/*
	$conn = mysql_connect("localhost", "emma", "dpvmzlffk");
	mysql_select_db("emma_db", $conn);
	mysql_query("set names utf8");
	*/

	$site	= $_SERVER['SERVER_NAME'];
	$query = "INSERT INTO SC_TRAN (tr_senddate, tr_sendstat, tr_phone, tr_callback ,tr_msg, tr_etc1, tr_etc2,tr_etc3,tr_etc4) VALUES (";
	$query.= "NOW(), '0', '".$to."', '".$send."', '".$msg."', '".$site."', '".$opt2."','".$opt3."','".$opt4."')";
	mysqli_query($connect, $query) or die (mysqli_error($connect));
}


function adminBlock($matching){

	global $connect;

	$_folder = $_SERVER['REQUEST_URI'];

	$output = "";

	if($_folder){
		$_folder_arr = explode("/", $_folder);
		$chk_fol = $_folder_arr[1];

		if($matching == $chk_fol){

			$fsql			= "select count(*) as cnts from tbl_adminIP where useYN = 'Y' and ip = '".$_SERVER['REMOTE_ADDR']."'  ";
			$fresult		= mysqli_query($connect, $fsql) or die (mysqli_error($connect));
			$frow = mysqli_fetch_array($fresult);
			
			$output = $frow['cnts'];

			if($output<1){
				write_log_dir($matching . " 접속 시도 - 아이피가 차단되었습니다 : ",$_SERVER['DOCUMENT_ROOT']."/AdmMaster/log/");
				alert_msg("허가되지 않은 IP입니다.","/");
			}
		}


	}

	return $output;
}


function autoEmail($code, $user_mail, $replace_text){
	
	global $connect;

	$total_sql = " select * from tbl_auto_mail_skin where code = '".$code."'  ";
	$result = mysqli_query($connect, $total_sql) or die (mysql_error());
	$row = mysqli_fetch_array($result);

	// 해당 코드가 자동 발송이 가능한가?
	if($row['autosend'] != "Y"){
		return false;
		exit;
	}

	// 메일 보낼 내역이 없다면 
	if($row['content'] == ""){
		return false;
		exit;
	}

	// 메일 보낼 내역
	$_tmp_content = viewSQ($row['content']);
	$subject = $row['mail_title'];

	//$_tmp_content = "[[name]] 고객님 안녕하세요. 가입하신 아이디는 [[id]] 입니다.[[name]] 고객님 안녕하세요. 가입하신 아이디는 [[id]] 입니다.";


	$_tmp_fir_array = explode("|||", $replace_text);

	for($i=1; $i<sizeof($_tmp_fir_array); $i++){
		//echo $_tmp_fir_array[$i] . "<br/>";
		$_tmp_sec_array = explode(":::", $_tmp_fir_array[$i]);

		$_f_txt = $_tmp_sec_array[0];
		$_s_txt = $_tmp_sec_array[1];

		$_tmp_content = str_replace($_f_txt,$_s_txt,$_tmp_content);
	}
	
	
	$nameFrom = "몬스타그램";
	$mailFrom = "contact@godstagram.com";
	$mailTo = $user_mail;
	$to_name = $user_mail;
	$to_email = $user_mail;

	//mailer($nameFrom, $mailFrom, $mailTo, $subject, $_tmp_content);
	
	$err = send_mail($nameFrom, $mailFrom, $to_name, $to_email, $subject, $_tmp_content);
}



function autoSms($code, $to_phone, $replace_text){

	global $connect;

	$total_sql = " select * from tbl_auto_sms_skin where code = '".$code."'  ";
	$result = mysqli_query($connect, $total_sql) or die (mysql_error());
	$row = mysqli_fetch_array($result);

	// 해당 코드가 자동 발송이 가능한가?
	if($row['autosend'] != "Y"){
		return false;
		exit;
	}

	// 문자 보낼 내역이 없다면 
	if($row['content'] == ""){
		return false;
		exit;
	}

	// 문자 보낼 내역
	$_tmp_content = viewSQ($row['content']);

	//$_tmp_content = "[[name]] 고객님 안녕하세요. 가입하신 아이디는 [[id]] 입니다.[[name]] 고객님 안녕하세요. 가입하신 아이디는 [[id]] 입니다.";


	$_tmp_fir_array = explode("|||", $replace_text);

	for($i=1; $i<sizeof($_tmp_fir_array); $i++){
		//echo $_tmp_fir_array[$i] . "<br/>";
		$_tmp_sec_array = explode(":::", $_tmp_fir_array[$i]);

		$_f_txt = $_tmp_sec_array[0];
		$_s_txt = $_tmp_sec_array[1];

		$_tmp_content = str_replace($_f_txt,$_s_txt,$_tmp_content);
	}


	
	
	$send = _IT_SMS_PHONE;
	
	//echo ($to_phone .", ". $send .", ". $_tmp_content);
	sms_send($to_phone, $send, $_tmp_content);
	sleep(1);
}

function getColorName($code){
	global $connect;

	$code = getCodeSlice($code);

	$_tmp_code_array = explode("||", $code);

	$out_text = "";

	for($i=0; $i<sizeof($_tmp_code_array); $i++){

		$total_sql = " select code_name from tbl_dbcolor where color_code = '".$_tmp_code_array[$i]."'  ";
		$result = mysqli_query($connect, $total_sql) or die (mysql_error());
		$row = mysqli_fetch_array($result);

		if($out_text != ""){
			$out_text .= ", ";
		}
		$out_text .= $row['code_name'];
		
		
	}

	return $out_text;
}

function getSizeName($code){
	global $connect;

	

	$out_text = "";



	$total_sql = " select code_name from tbl_size where code_no = '".$code."'  ";
	$result = mysqli_query($connect, $total_sql) or die (mysql_error());
	$row = mysqli_fetch_array($result);

	
	$out_text .= $row['code_name'];
	
		
	

	return $out_text;
}


function phone_chk($to_phone){

	$_chk_no = mt_rand(1000,9999);
	$_SESSION['member']['phone_chk'] = $_chk_no;

	$_tmp_content = "인증번호 : {{NO}} 입니다.";
	$_tmp_content = str_replace("{{NO}}",$_chk_no,$_tmp_content);
	
	$send = _IT_SMS_PHONE;
	

	//sms_send($to_phone, $send, $_tmp_content);
	
	return send_sms(_SendSmsPhone, $_tmp_content, $to_phone, $title );
}

function phone_chk_ok($chkNum){

	
	if($_SESSION['member']['phone_chk'] == ""){
		alert_only("인증 시간이 만료되었거나, 발급되지 않았습니다. 다시 발급해주세요.");
		echo "N";
	}

	if($chkNum == $_SESSION['member']['phone_chk']){
		$_SESSION['member']['phone_chk'] = "";
		echo "Y";
	}else{
		echo "N";
	}
	


}




//로그인 시 아이피 로그 기록
function getLoginIPChk(){
	global $connect;

	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	
	$gTime = time() + 86400; //하루 24시간 	
	$cookieValue = "user_ip_".str_replace(".","", $REMOTE_ADDR);

	$cookieVal = $_COOKIE[$cookieValue];
	
	if($cookieVal == ""){

		$sql = " select * from tbl_login_ip where loginIP = '".$REMOTE_ADDR."' ";

		$result = mysqli_query($connect, $sql) or die (mysql_error());
		$row = mysqli_fetch_array($result);
		$loginIP = $row['loginIP'];
		$loginCnt = $row['loginCnt'];	
	
		if($loginIP == ""){
			$sql = " insert into tbl_login_ip set loginIP = '".$REMOTE_ADDR."', loginCnt = 1";						 
			mysqli_query($connect, $sql) or die (mysqli_error($conect));
		}else{				
				$loginCnt = $loginCnt + 1;
				$sql = "
					update tbl_login_ip set loginCnt = ".$loginCnt." where loginIP = '".$REMOTE_ADDR."'
				";
				mysqli_query($connect, $sql) or die (mysqli_error($conect));			
		}
	}

	setcookie($cookieValue, $cookieValue, $gTime);	

	$out_text = "";			
	return $out_text;
}



//회원 로그인시 로그 
function getLoginDeviceUserChk($user_id){
	
	global $connect;

	$device_type = get_device();
	$gTime = time() + 86400; //하루 24시간 	
	$cookieValue = "user_id_".$user_id;
		
	if($user_id != ""){
		
		$cookieVal = $_COOKIE[$cookieValue];

		if($cookieVal == ""){
			$sql = " select * from tbl_login_device where DATE(regdate) = DATE(now())";			
			$result = mysqli_query($connect, $sql) or die (mysql_error());
			$row = mysqli_fetch_array($result);
			$login_type_P = $row['login_type_P'];
			$login_type_M = $row['login_type_M'];

			if($login_type_P == ""){

				if($device_type == "P"){
					$login_type_P = 1;
					$login_type_M = 0;
				}else if($device_type == "M"){
					$login_type_P = 0;
					$login_type_M = 1;
				}
									
				$sql = " insert into tbl_login_device set regdate = now()
					, login_type_P = ".$login_type_P."
					, login_type_M = ".$login_type_M."
					, itemCnt_P = 0
					, itemCnt_M = 0
					";						 
					
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
				
			}else{

				if($device_type == "P"){
					$login_type_P = $login_type_P + 1;	
					$sSQl = " login_type_P = ".$login_type_P;
				}else if($device_type == "M"){					
					$login_type_M = $login_type_M + 1;
					$sSQl = " login_type_M = ".$login_type_M;
				}

				$sql = " update tbl_login_device set ".$sSQl." where DATE(regdate) = DATE(now())";
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
			}	
		}
		
		setcookie($cookieValue, $cookieValue, $gTime);	
	}

	$out_text = "";
			
	return $out_text;
}

//상품 페이지 로그 
function getLoginDeviceItemChk($g_idx){

	global $connect;

	$device_type = get_device();

	$gTime = time() + 86400; //하루 24시간 	
	$cookieValue = "item_".$g_idx;
				
	if($g_idx != ""){
		
		$cookieVal = $_COOKIE[$cookieValue];

		if($cookieVal == ""){
			$sql = " select * from tbl_login_device where DATE(regdate) = DATE(now())";			
			$result = mysqli_query($connect, $sql) or die (mysql_error());
			$row = mysqli_fetch_array($result);
			$itemCnt_P = $row['itemCnt_P'];
			$itemCnt_M = $row['itemCnt_M'];

			if($itemCnt_P == ""){

				if($device_type == "P"){
					$itemCnt_P = 1;
					$itemCnt_M = 0;
				}else if($device_type == "M"){
					$itemCnt_P = 0;
					$itemCnt_M = 1;
				}
									
				$sql = " insert into tbl_login_device set regdate = now()
					, login_type_P=0
					, login_type_M=0
					, itemCnt_P=".$itemCnt_P."
					, itemCnt_M=".$itemCnt_M."
					";						 
					
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
				
			}else{

				if($device_type == "P"){
					$itemCnt_P = $itemCnt_P + 1;	
					$sSQl = " itemCnt_P = ".$itemCnt_P;
				}else if($device_type == "M"){					
					$itemCnt_M = $itemCnt_M + 1;
					$sSQl = " itemCnt_M = ".$itemCnt_M;
				}

				$sql = " update tbl_login_device set ".$sSQl." where DATE(regdate) = DATE(now())";
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
			}	
		}
		
		setcookie($cookieValue, $cookieValue, $gTime);	
	}

	$out_text = "";
			
	return $out_text;
}


//홈페이지 진입 경로 기록 저장 
function getLocationUrl(){
	
	global $connect;

	$location = $_SERVER["HTTP_REFERER"];

	$gTime = time() + 86400; //하루 24시간 	
	$cookieValue = "HTTP_REFERER_".$location;
	
	$cookieValue = str_replace(":","",$cookieValue);
	$cookieValue = str_replace("/","",$cookieValue);
	$cookieValue = str_replace(".","",$cookieValue);
	$cookieValue = str_replace("&","",$cookieValue);
	$cookieValue = str_replace("=","",$cookieValue);
	$cookieValue = str_replace("_","",$cookieValue);
//echo $cookieValue;


	if(strpos($location, "bontoshop.com") !== false) {  
	   $location = "";
	} 
		
	if($location != ""){		

		$cookieVal = $_COOKIE[$cookieValue];
		
		if($cookieVal == ""){
			if(strpos($location, "http://search.naver.com/search.naver") !== false || strpos($location, "https://search.naver.com/search.naver") !== false) {  
			   $location = "http://search.naver.com";
			} 
			if(strpos($location, "http://m.search.naver.com/search.naver") !== false || strpos($location, "https://m.search.naver.com/search.naver") !== false) { 
			   $location = "http://m.search.naver.com";
			} 

			$sql = " select cnt from tbl_location_addr where location='".$location."'";
			$result = mysqli_query($connect, $sql) or die (mysql_error());
			$row=mysqli_fetch_array($result);
			$cnt = $row[cnt];
			
			if($cnt == ""){
				$cnt = 1;
				$sql = " insert into tbl_location_addr SET location= '".$location."',cnt=".$cnt." ";		
				$db = mysqli_query($connect, $sql) or die (mysqli_error($connect));
			}else{
				$cnt = $cnt + 1;
				$sql = " update tbl_location_addr
								  set cnt = ".$cnt."
								where location='".$location."'
							 ";
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
			}
					
		}

		setcookie($cookieValue, $cookieValue, $gTime);	
	}
	
	$out_text = "";
			
	return $out_text;
}

//검색어 순위 
function getSearchKeywordLog($keyword){
	global $connect;

	$gTime = time() + 86400; //하루 24시간 	
	$cookieValue = "keyword_".$keyword;
	$cookieVal = $_COOKIE[$cookieValue];

	if($cookieVal == ""){
		$sql = " select keyword, cnt from tbl_keyword where keyword='".$keyword."'";
		$result = mysqli_query($connect, $sql) or die (mysql_error());
		$row=mysqli_fetch_array($result);
		$cnt = $row[cnt];	
		
		if($cnt == ""){
			$sql = " insert into tbl_keyword SET keyword= '".$keyword."',cnt=1 ";		
			$db = mysqli_query($connect, $sql) or die (mysqli_error($connect));
		}else{
			$cnt = $cnt + 1;
			$sql = " update tbl_keyword
							  set cnt = ".$cnt."
							where keyword='".$keyword."'
						 ";
			mysqli_query($connect, $sql) or die (mysqli_error($conect));
		}		
	}

	setcookie($cookieValue, $cookieValue, $gTime);	

	$out_text = "";			
	return $out_text;
}



//상품별 페이지 로그 
function getGoodsPageView($g_idx){

	global $connect;

	$gTime = time() + 86400; //하루 24시간 
	$cookieValue = "goodsPageView_".$g_idx;
				
	if($g_idx != ""){
		
		$cookieVal = $_COOKIE[$cookieValue];

		if($cookieVal == ""){
			$sql = " select * from tbl_goods_view_cnt where g_idx = ".$g_idx."";			
			$result = mysqli_query($connect, $sql) or die (mysql_error());
			$row = mysqli_fetch_array($result);
			$cnt = $row['cnt'];
		
			if($cnt == ""){				
				$sql = " insert into tbl_goods_view_cnt set g_idx = ".$g_idx.", cnt=1";						 					
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
				
			}else{
				$cnt = $cnt +1;
				$sql = " update tbl_goods_view_cnt set cnt=".$cnt." where g_idx= ".$g_idx."";
				mysqli_query($connect, $sql) or die (mysqli_error($conect));
			}	
		}
		
		setcookie($cookieValue, $cookieValue, $gTime);	
	}

	$out_text = "";
			
	return $out_text;
}

//홈페이지 진입 경로 기록 저장 
getLocationUrl();



function smtp_email($from_email, $to_email, $mail_data){
	global $SMTP_CONNECT;

	$host = $SMTP_CONNECT[HOST];
	$id = $SMTP_CONNECT[ID];
	$pw = $SMTP_CONNECT[PW];

	//var_dump($SMTP_CONNECT);

	$from_email = trim($from_email);
	$to_email = trim($to_email);

	//if($host == "") return "Error:NO host";

	if($host == "") $host = "localhost";
	if($port == "") $port = 587; // 기본이 25 업체 SMTP PORT 설정에 따라서 변경
	if($limit == "") $limit = 30;


	if(!$socket = @fsockopen($host, $port, $errno, $errstr, $limit))
		return "Error:fsockopen ($errno : $errstr)";


	// SMTP 연결 확인
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 4) != "220 ")
		return "Error:connect - ".$response;

	// helo xxxx
	fwrite($socket, "ehlo ".$host."\r\n");
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 3) != "250")
		return "Error:ehlo - ".$response;

	if($id != "" && $pw != ""){
		// auth login
		fwrite($socket, "auth login \r\n");
		//$response = fgets($socket, 1024);
		$response = fread($socket, 1024);
		//echo $response."<br>";
		if(substr($response, 0, 3) != "250")
			return "Error:auth login - ".$response;

		// id xxxx
		fwrite($socket, $id."\r\n");
		$response = fgets($socket, 1024);
		//echo $response."<br>";
		if(substr($response, 0, 3) != "334")
			return "Error:id - ".$response;

		// pw xxxx
		fwrite($socket, $pw."\r\n");
		$response = fgets($socket, 1024);
		//echo $response."<br>";
		if(substr($response, 0, 3) != "235")
			return "Error:pw - ".$response;
	}

	// mail from:<nobody@jnkmw.com>
	fwrite($socket, "mail from:<".$from_email.">\r\n");
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 3) != "250")
		return "Error:mail from - ".$response;

	// rcpt to:<user1@jnkmw.com>
	fwrite($socket, "rcpt to:<".$to_email.">\r\n");
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 3) != "250")
		return "Error:rcpt to - ".$response;

	// data
	fwrite($socket, "data\r\n");
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 3) != "354")
		return "Error:data - ".$response;

	// escape Ending '.'
	$mail_data = str_replace("\r\n.\r\n", "\r\n . \r\n", $mail_data);
	$mail_data = str_replace("\r\n.\r\n", "\r\n . \r\n", $mail_data);

	// 메일내용 (메일헤더 + '\n' + 내용 + '\n.')
	fwrite($socket, $mail_data."\r\n".".\r\n");
	$response = fgets($socket, 1024);
	//echo $response."<br>";
	if(substr($response, 0, 3) != "250")
		return "Error:mail_data - ".$response;

	return "";
}




function send_mail($from_name, $from_email, $to_name, $to_email, $subject, $message, $ext_header=""){
	// 추가 설정
	if($ext_header != ""){
		$arr = explode("\n", $ext_header);
		$cnt = count($arr);
		$param_arr = array();
		$item = "";
		for($i=0; $i < $cnt; $i++){
			$str = $arr[$i];
			if(substr($str, 0, 1) == " "){ // TAB -> 이전 항목에 연결
				if($item != "") $param_arr[$item] .= $str;
				continue;
			}
			else{
				if(!$pos = strpos($str, ":")){
					if($item != "") $param_arr[$item] .= $str;
					continue;
				}

				$item = "";
				$_item = trim(substr($str, 0, $pos));
				$_value = trim(substr($str, $pos + 1));
				if($_item != "" && $_value != ""){
					$param_arr[$_item] = $_value;
					$item = $_item;
				}
			}
		}

		// 보내는 사람 = 답변 받을 사람
		if($param_arr["Reply-To"] != "" && $param_arr["Sender"] == "")
			$param_arr["Sender"] = $param_arr["Reply-To"];
		else if($param_arr["Sender"] != "" && $param_arr["Reply-To"] == "")
			$param_arr["Reply-To"] = $param_arr["Sender"];
	}

	$from_name = trim($from_name);
	$from_email = trim($from_email);

	$to_name = trim($to_name);
	$to_email = trim($to_email);

	$subject = trim($subject);
	$message = trim($message);

	if($from_email == "") return;
	if($to_email == "") return;
	if($subject == "") return;
	if($message == "") return;

	// from
	$from_name = "=?UTF-8?B?".base64_encode($from_name)."?=";
	$from = "\"".$from_name."\" <".$from_email.">";

	// to
	$to_name = "=?UTF-8?B?".base64_encode($to_name)."?=";
	$to = "\"".$to_name."\" <".$to_email.">";

	// subject
	$subject = "=?UTF-8?B?".base64_encode($subject)."?=";

	// --------------------------------------------
	// 1차 발송....SMTP 서버 지정 발송

	// 메일 헤더
	$header = "";
	$header .= "Message-ID: <".microtime(true)."_".uniqid()."@".$_SERVER['SERVER_NAME'].">\n";
	$header .= "Date: ".date("D, j M Y H:i:s +0900")."\n";
	$header .= "From: ".$from."\n";
	$header .= "To: ".$to."\n";
	$header .= "Subject: ".$subject."\n";
	$header .= "Organization: ".(($param_arr["Organization"] != "") ? $param_arr["Organization"] : $_SERVER['SERVER_NAME'])."\n";
	if($param_arr["Sender"] != "") $header .= "Sender: ".$param_arr["Sender"]."\n";
	if($param_arr["Reply-To"] != "") $header .= "Reply-To: ".$param_arr["Reply-To"]."\n";
	if($param_arr["Errors-To"] != "") $header .= "Errors-To: ".$param_arr["Errors-To"]."\n";
	if($param_arr["X-Priority"] != "") $header .= "X-Priority: ".$param_arr["X-Priority"]."\n";
	$header .= "X-Originating-IP: ".$_SERVER['REMOTE_ADDR']."\n";
	$header .= "X-Sender-IP: ".$_SERVER['REMOTE_ADDR']."\n";
	$header .= "X-Sender-ID: ".$auth_id." [".$_SERVER['SERVER_NAME']."]\n";
	$header .= "X-Mailer: Excom21-Mailer\n";
	$header .= "MIME-Version: 1.0\n";
	$header .= "Content-Type: TEXT/HTML; charset=UTF-8\n";
	$header .= "Content-Transfer-Encoding: 8BIT\n";

	$mail_data = $header."\n\n".$message;
	$mail_data = str_replace("\r\n", "\n", $mail_data); // 1. \r\n -> \n
	$mail_data = str_replace("\r", "\n", $mail_data);   // 2. \r   -> \n
	$mail_data = str_replace("\n", "\r\n", $mail_data); // 3. \n   -> \r\n

	// 메일 발송
	$err = smtp_email($from_email, $to_email, $mail_data);
	//$this->log_input("******************* smtp_email (err) : ".$err, "guinee");
	return $err;

	// --------------------------------------------
	// 1차 발송 실패시....자체 발송 (localhost)
	/*if($err != "")
	{
	// ext_header
	if($ext_header == "")
	$ext_header = "From: ".$from."\nX-Mailer: JK-Mailer2\nContent-Type: text/html; charset=UTF-8";
	else
	$ext_header = "From: ".$from."\n".$ext_header;

	// 메일 발송
	$err = !mail($to, $subject, $message, $ext_header);
	//$this->log_input("******************* mail (err) : ".$err, "guinee");
	}*/

	// 메일 발송 결과
	if(!$err)
		return true;
}
function send_sms($sendSmsPhone , $msg, $rphone, $title = "")
{
	$arr_phone = explode("-",$sendSmsPhone);
	$sphone1 = $arr_phone[0];
	$sphone2 = $arr_phone[1];
	$sphone3 = $arr_phone[2];
//	$rphone	= $rphone.",01012345678,01023456789";
//	$rphone	= $rphone.",01000000431";

	$testflag = "";
	$returnurl = "";

   /******************** 인증정보 ********************/
	//$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
	$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	$sms['user_id'] = base64_encode("sungtaekbest"); //SMS 아이디.
	$sms['secure'] = base64_encode("1a6a565f8468c5f9758b3889424dbafd") ;//인증키
	$sms['msg'] = base64_encode(stripslashes($msg));

	$sms['rphone'] = base64_encode($rphone);
	$sms['sphone1'] = base64_encode($sphone1);
	$sms['sphone2'] = base64_encode($sphone2);
	$sms['sphone3'] = base64_encode($sphone3);
	$sms['rdate'] = base64_encode("");
	$sms['rtime'] = base64_encode("");
	$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
	$sms['returnurl'] = base64_encode($returnurl);
	$sms['testflag'] = base64_encode($testflag);
	$sms['destination'] = strtr(base64_encode(""), '+/=', '-,');
	$sms['repeatFlag'] = base64_encode("N");
	$sms['repeatNum'] = base64_encode("1");
	$sms['repeatTime'] = base64_encode("0");
	if (mb_strwidth($msg,'UTF-8') > 90)
	{
		$sms['subject'] = base64_encode(stripslashes($title));; // LMS일경우 L
		$sms['smsType'] = base64_encode("L"); // LMS일경우 L
	} else {
		$sms['smsType'] = base64_encode("S"); // LMS일경우 L
	}
	$nointeractive = "1"; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

	$host_info = explode("/", $sms_url);
	$host = $host_info[2];
	$path = $host_info[3]."/".$host_info[4];

	srand((double)microtime()*1000000);
	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	//print_r($sms);

	// 헤더 생성
	$header = "POST /".$path ." HTTP/1.0\r\n";
	$header .= "Host: ".$host."\r\n";
	$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	// 본문 생성
	foreach($sms AS $index => $value){
		$data .="--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		$data .= "\r\n".$value."\r\n";
		$data .="--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

	$fp = fsockopen($host, 80);

	if ($fp) {
		fputs($fp, $header.$data);
		$rsp = '';
		while(!feof($fp)) {
			$rsp .= fgets($fp,8192);
		}
		fclose($fp);
		$msg = explode("\r\n\r\n",trim($rsp));
		$rMsg = explode(",", $msg[1]);
		$Result= $rMsg[0]; //발송결과
		$Count= $rMsg[1]; //잔여건수

		//발송결과 알림
		if($Result=="success") {
			$alert = "OK";
			//$alert .= " 잔여건수는 ".$Count."건 입니다.";
		}
		else if($Result=="reserved") {
			$alert = "성공적으로 예약되었습니다.";
			$alert .= " 잔여건수는 ".$Count."건 입니다.";
		}
		else if($Result=="3205") {
			$alert = "잘못된 번호형식입니다.";
		}

		else if($Result=="0044") {
			$alert = "스팸문자는발송되지 않습니다.";
		}

		else {
			$alert = "[Error]".$Result;
		}
	}
	else {
		$alert = "Connection Failed";
	}

	/*
	if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
		echo "<script>alert('".$alert ."')</script>";
	}
	else if($nointeractive!="1") {
		echo "<script>alert('".$alert ."')</script>";
	}
	*/
	return $alert;
}
include $_SERVER['DOCUMENT_ROOT']."/include/lib.inc_tmp.php";

//아래에 쓸데 없이 공백이 생기면 에러 남?>