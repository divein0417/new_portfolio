<? include "../include/lib.inc.php"; ?>
<?	
	$sql    = " Select * from tbl_popup where (status = 'B' or (concat(P_STARTDAY, ' ', P_START_HH, ':', P_START_MM ) <= '".date("Y-m-d H:i")."' <= '".$curdate."' and concat(P_ENDDAY, ' ', P_END_HH, ':', P_END_MM ) >= '".$curdate."' and status = 'A' ) ) and  idx='".$idx."' ";
	$result = mysqli_query($connect,$sql) or die (mysqli_error($connect));
	$row=mysqli_fetch_array($result);
?>
<html>

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?=iconv("euc-kr","utf-8",$row[P_SUBJECT])?></title>
</head>
<script language="JavaScript">  
function setCookie( name, value, expiredays ) 
{ 
var todayDate = new Date(); 
todayDate.setDate( todayDate.getDate() + expiredays ); 
document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
} 
 
function closeWin()  {
{ 
if ( document.cnjform.notice.checked )  // 폼네임 cnjform 은 동일해야 합니다.
setCookie("maindivapDiv<?=$row[idx]?>", "no" , 10000);   // 부모창에서 지정한 쿠키네임과 일치 해야 합니다.
} 
top.close();
}
</script> 


<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
	<table width="<?=$row[P_WIN_WIDTH]?>" height="<?=$row[P_WIN_HEIGHT]?>" border="0" cellspacing="0" cellpadding="0" style='font-size:10pt;table-layout:fixed;'>
		<tr <? if ($row[P_MOVEURL]) { ?>style="cursor:pointer;" onclick="javascript:<? if ($row[P_STYLE] == "N") { ?>window.open('<?=$row[P_MOVEURL]?>')<? } else { ?>opener.location.href='<?=$row[P_MOVEURL]?>';window.close();<? } ?>" <? } ?>>
			<td valign=top><?=str_replace("&gt;",">",str_replace("&lt;","<",$row[P_CONTENT]))?></td>
		</tr>
	<form name="cnjform">
		<tr bgcolor=333333 height=25 align=center>
			<td><input type="checkbox" name="notice" onclick="closeWin()"><span class="style1" style="color: #FFFFFF">오늘 하루동안 창 열지 않기</a><a href="javascript:window.close()"><font color=ffffff>[Close]</font></a></td>
		</tr>
	</form>
	</table>
</body>
</html>