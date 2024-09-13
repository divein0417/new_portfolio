<?
$now_device = get_device();
?>
<!--
	기존 팝업페이지보니 한 페이지에 다 있길래 css랑 js 한번에 다 넣어두었습니다. ㅇㅅㅇ(파일분리가 필요하시면 분리해드림)
	해서 모바일 팝업은 이 페이지 내용만 그대로 들고가시면 됩니다.
-->
<!-- 모바일 팝업 css/js -->
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:100,300,400,500,700,900&display=swap&subset=korean" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
				
<style>
/* html.scrollx,body.scrollx{overflow:hidden;} */
	#popup_wrap{display:none;}
	.slick-dots{
		position:absolute;
		bottom:15px;
		left:0;
		width:100%;
		text-align:center;
		z-index:10
	}
	.slick-dots li{
		display:inline-block;
		margin:0 1px 0 5px
	}
	.slick-dots li button{
		display:block;
		font-size:0;
		width:8px;
		height:8px;
		border:1px solid #bbbbbb;
		border-radius:100%;
		background:#efefef
	}
	.slick-dots li.slick-active button{background:#fff}
	@media screen and (max-width: 749px){
		#popup_wrap{display:block;}
	}
</style>
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
	$result = mysqli_query($connect,$sql);
	$ccnt = mysqli_num_rows($result);
?>
<!-- 모바일 팝업 -->
<div id="popup_wrap" style="position:fixed;top:0;bottom:0;left:0;right:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:10000;" onclick="mopop_close()">
	<div id="popup_mbox" style="position:fixed;width:100%;background:#fff;">
		<div id="pop_moslider">
			<div class="pop_mo_slide">
				<?
				$i=0;
				while( $row=mysqli_fetch_array($result) ){
					if($row['ufile']){
						if($_COOKIE["main".$row['idx']] != "done"){
				?>
						<div class="m_p_1"><p class="pop_idx" style="display:none;"><?=$row['idx']?></p><a href="<?=$row['P_MOVEURL']?>"><img src="/data/homepage/<?=$row['ufile']?>" alt=""style="width:100%;"></a></div>
				<?
						$i++;
						}
					}
				}
				?>
			</div>
		</div>
		<ul id="pop_btn" style="clear:both; overflow:hidden; border-top:1px solid #ddd; border-bottom:1px solid #ddd; text-align:center;">
			<li style="float:left;width:50%;"><a href="#!" onclick='javascript:closeWin();' style="padding:15px 0; display:block; background:#efefef; font-size:13px; font-weight:500; color:#333; border-right:1px solid #ddd;">이 이벤트 다시 열지 않기</a></li>
			<li style="float:left;width:50%;"><a href="#!" style="padding:15px 0; display:block; background:#efefef; font-size:13px; font-weight:500; color:#333;" onclick="mopop_close()">닫기</a></li>
		</ul>
	</div>
</div>

<!-- 모바일 팝업 -->
<script>
function getCookie(cookieName){
    var cookieValue=null;
    if(document.cookie){
        var array=document.cookie.split((escape(cookieName)+'='));
        if(array.length >= 2){
            var arraySub=array[1].split(';');
            cookieValue=unescape(arraySub[0]);
        }
    }
    return cookieValue;
}

function setCookie( name, value, expiredays ) {  
		var todayDate = new Date();  
        todayDate.setDate( todayDate.getDate() + expiredays );  
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"  
} 

function closeWin() {
	if($(".m_p_1").hasClass("slick-active")){
		var pop_idx = $(".slick-active").children(".pop_idx").text();
		//document.getElementById('popup_wrap').style.visibility = "hidden";

       	setCookie("main"+pop_idx,"done",1);
	}
}
</script>
<script>
$(document).ready(function(){
	openPopup();
});
	function mopop_close(){
		$('#popup_wrap').hide();
		$('html,body').removeClass('scrollx');
	}
	function mopop_style(){
		$('#popup_mbox').css("top",$(window).height()/2 - $('#popup_mbox').height()/2);
		//$('html,body').addClass('scrollx');
	}
	$(function(){
		$('.pop_mo_slide').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			dots:true,
		});
	});

	$(window).load(function(){
			/*$('.pop_mo_slide').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				fade: true,
				dots:true,
			});*/
			if ($(window).width() <= 750){
				mopop_style();
			}
			
		});
		
		$(window).resize(function(){
			if ($(window).width() <= 750){
				mopop_style();
			}
		});
	function openPopup(){
		<?
			if($i!=0){	
		?>
		document.getElementById('popup_wrap').style.visibility = "visible"; 
		<?
			}else{	
		?>
		document.getElementById('popup_wrap').style.visibility = "hidden"; 
		<?
			}	
		?>
	}
</script>