<?
$now_device = get_device();
?>
<style>
    .apDiv{
        max-width: 606px;
    }
</style>
<script language="JavaScript"> 
<!-- 
// 이부분부터  수정할 필요 없습니다. 
function getCookie(name) { 
	var Found = false 
	var start, end 
	var i = 0 
	 
	while(i <= document.cookie.length) { 
		start = i 
		end = start + name.length 
		 
		if(document.cookie.substring(start, end) == name) { 
			Found = true 
			break 
		} 
		i++ 
	} 
	 
	if(Found == true) {
		start = end + 1
		end = document.cookie.indexOf(";", start) 
		if(end < start) 
			end = document.cookie.length 
		return document.cookie.substring(start, end) 
	} 
	return "" 
} 


function setCookie( name, value, expiredays ) {
    var todayDate = new Date();  
	todayDate.setDate( todayDate.getDate() + expiredays );  
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"  
}  


function closeWin(Divpop) {
	document.getElementById('apDiv'+Divpop).style.visibility = "hidden";   
	//if(document.getElementById('Pnotice'+Divpop).checked){
		setCookie("maindivapDiv"+Divpop,"done",1);
	//}
}

 
function closeWin2() {
    if (document.getElementById("popchk").checked ){  
        setCookie( "popclose", "done" , 1 );  
    }  
	$('.layerBg').fadeOut('300');
	$(".layerPop").hide();
}
 

function openPopup(){
	cookiedata = document.cookie;
	<?
	$sql    = " 
			SELECT * 
			  FROM 
			  (
				SELECT * 
				  FROM tbl_popup 
				 WHERE is_mobile='".$now_device."'  -- 접속 기기
				   AND status = 'A'					-- 일정별 노출
				   AND concat(P_STARTDAY, ' ', P_START_HH, ':', P_START_MM ) <= '".date("Y-m-d H:i")."' 
				   AND concat(P_ENDDAY, ' ', P_END_HH, ':', P_END_MM ) >= '".date("Y-m-d H:i")."' 
				 
				 UNION

				 SELECT * 
				  FROM tbl_popup 
				 WHERE is_mobile='".$now_device."'  -- 접속 기기
				   AND status = 'B'					-- 강제노출
			  ) x

			 ORDER BY idx DESC 
		  ";
	$result = mysqli_query($connect,$sql) or die (mysql_error());
	while($row=mysqli_fetch_array($result)){
		$fileUploadPath = "../data/popup/".$row['ufile'];
		$imgSize = GETIMAGESIZE($fileUploadPath);
		$imgWidth = $imgSize[0];
		$imgHeight = $imgSize[1];
	?>
	if ( cookiedata.indexOf("maindivapDiv<?=$row[idx]?>=done") < 0 ){
		<? if ($row[P_CATE] == "L") { ?>
	    document.getElementById('apDiv<?=$row[idx]?>').style.visibility = "visible"; 
		<? } else { ?>
		if (getCookie("maindivapDiv<?=$row[idx]?>") != "no")
		{
			window.open('/include/popup.php?idx=<?=$row[idx]?>','pop<?=$row[idx]?>','width=<?=$imgWidth?>,height=<?=$imgHeight+24?>,top=<?=$row[P_WIN_TOP]?>,left=<?=$row[P_WIN_LEFT]?>,resizable=no,scrollbars=no,location=no'); 
		}
		<?	} ?>
	}else { 
	    document.getElementById('apDiv<?=$row[idx]?>').style.visibility = "hidden";  
	}
	<?
	}
	?>
}

//-->   
</script>

<?
$sql    = " 
			SELECT * 
			  FROM 
			  (
				SELECT * 
				  FROM tbl_popup 
				 WHERE is_mobile='".$now_device."'  -- 접속 기기
				   AND status = 'A'					-- 일정별 노출
				   AND concat(P_STARTDAY, ' ', P_START_HH, ':', P_START_MM ) <= '".date("Y-m-d H:i")."' 
				   AND concat(P_ENDDAY, ' ', P_END_HH, ':', P_END_MM ) >= '".date("Y-m-d H:i")."' 
				 
				 UNION

				 SELECT * 
				  FROM tbl_popup 
				 WHERE is_mobile='".$now_device."'  -- 접속 기기
				   AND status = 'B'					-- 강제노출
			  ) x

			 ORDER BY idx DESC 
		  ";
//echo $sql;
$result = mysqli_query($connect,$sql) or die (mysql_error());
while($row=mysqli_fetch_array($result)){
	$fileUploadPath = "../data/popup/".$row['ufile'];
	$imgSize = GETIMAGESIZE($fileUploadPath);
	$imgWidth = $imgSize[0];
	$imgHeight = $imgSize[1];
?>
<div class="apDiv" id="apDiv<?=$row[idx]?>" style="position:absolute; left:<?=$row[P_WIN_LEFT]?>px; top:<?=$row[P_WIN_TOP]?>px; width:<?=$imgWidth?>px; height:<?=$imgHeight?>px; z-index:999999; visibility: hidden;">	<table border="0" cellspacing="0" cellpadding="0"  bgcolor='ffffff' style="width:auto;">
		<tr <? if ($row[P_MOVEURL]) { ?> onclick="javascript:<? if ($row[P_STYLE] == "N") { ?>window.open('<?=$row[P_MOVEURL]?>')<? } else { ?>location.href='<?=$row[P_MOVEURL]?>'<? } ?>" style="cursor:pointer;" <? } ?>>
			<td colspan="2" ><div><a href="<?=$row['P_MOVEURL']?>"><img src="/data/homepage/<?=$row['ufile']?>" alt=""style="width:100%;"></a></div></td>
		</tr>
		<form name="frm" id="frm<?=$row[idx]?>" style="margin:0px">
            <!-- <tr bgcolor=333333 height=25 align=center>
				<td><input type="checkbox" name="Pnotice<?=$row[idx]?>" id="Pnotice<?=$row[idx]?>" onClick="closeWin('<?=$row[idx]?>');" />
					<span class="style1" style="color: #FFFFFF">오늘 하루동안 창 열지 않기<a onClick="javascript:closeWin('<?=$row[idx]?>')" href="#"><font color=ffffff>[Close]</font></a></td>
			</tr> -->
           
		</form>
	</table>
    <ul id="pop_btn" style="clear:both; overflow:hidden; border-top:1px solid #ddd; border-bottom:1px solid #ddd; text-align:center;">
        <li style="float:left;width:50%;"><a href="#!" onclick="javascript:closeWin('<?=$row[idx]?>');" style="padding:15px 0; display:block; background:#efefef; font-size:13px; font-weight:500; color:#333; border-right:1px solid #ddd;">오늘 하루 열지 않기</a></li>
        <li style="float:left;width:50%;"><a href="#!" style="padding:15px 0; display:block; background:#efefef; font-size:13px; font-weight:500; color:#333;" onclick="mopop_close(<?=$row['idx']?>)">닫기</a></li>
    </ul>
</div>
<?
}

?>
<script> 
openPopup();
if ($(window).width() <= 750){
	$('.apDiv').hide();
}
$(window).resize(function(){
	if ($(window).width() <= 750){
		$('.apDiv').hide();
	}
});

function mopop_close(idx){
    $('#apDiv'+idx).hide();
}
</script>
