<?
##############################
//
// + 케이보드 _ 첨부파일_다운로드(download.php)
//
##############################

@session_start();

@extract($_GET);
@extract($_POST);

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

//실제 파일명 또는 경로
$ufile = iconv("utf-8","euc-kr",$ufile);
$file = "../data/$mode/$ufile"; 
$dnfile = iconv("utf-8","euc-kr",$rfile);
$dnfile = str_replace("+"," ",$dnfile);

// 1 이면 다운 0 이면 브라우져가 인식하면 화면에 출력
$dn = "1"; 
$dn_yn = ($dn) ? "attachment" : "inline";

$bin_txt = "1"; 
$bin_txt = ($bin_txt) ? "r" : "rb"; 


if(preg_match("(MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT)) 
{ 
    Header("Content-type: application/octet-stream"); 
    Header("Content-Length: ".filesize("$file"));
    Header("Content-Disposition: $dn_yn; filename=$dnfile");  
    Header("Content-Transfer-Encoding: binary");  
    Header("Pragma: no-cache");  
    Header("Expires: 0");  
}
else 
{ 
    Header("Content-type: file/unknown");     
    Header("Content-Length: ".filesize("$file")); 
    Header("Content-Disposition: $dn_yn; filename=$dnfile"); 
    Header("Content-Description: PHP3 Generated Data");    
    Header("Pragma: no-cache"); 
    Header("Expires: 0"); 
} 

if (is_file("$file")) 
{ 
    $fp = fopen("$file", "$bin_txt");
        if (!fpassthru($fp)) 
        fclose($fp); 
} 
else 
{
    echo "해당 파일이나 경로가 존재하지 않습니다.";
} 


?>
