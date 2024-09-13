		<ul class="mypage_nav">
			<li><a href="#">홈</a>></li>
			<li><a href="#">MY페이지</a>></li>
			<li><a href="#">MY쇼핑</a>></li>
			<li><a href="#">주문목록</a></li>
		</ul>
		<section id="snb" class="contact_snb">
			<h2 class="mybonto">고객센터</h2>
			<ul>
				<li><a href="contact_pu.php">자주묻는 질문</a></li>
				<li><a href="contact_service.php">회원혜택/서비스</a></li>
				<li><a href="contact_as.php">A/S</a></li>
				<li><a href="contact_bonto_news.php">본토소식</a></li>
				<li><a href="contact_event.php">이벤트</a></li>

			</ul>
			<div class="contact_phone_number">
				<p><img src="../img/ico/ico_phone.png" alt="">고객센터<span>02-6006-0471</span></p>
			</div>
		</section>
		<?

		// 할인쿠폰 수

		$sql_c = " select c.coupon_num, c.user_id, c.regdate, c.enddate, c.usedate, c.status, s.coupon_name, s.dc_type, s.coupon_pe, s.coupon_price, s.dex_price_pe
						 from tbl_coupon c
						 left outer join tbl_coupon_setting s
						   on c.coupon_type = s.idx
					   where c.status = 'N'
						 and c.enddate > curdate()
						 and c.usedate = ''
						 and c.user_id = '".$_SESSION['member']['id']."'
					 ";
		$result_c = mysqli_query($connect, $sql_c) or die (mysql_error());
		$coupon_cnt = mysqli_num_rows($result_c);


		// 찜수

		$sql_z = "
					select g.*
					  from tbl_myzzim m
					  left outer join tbl_goods g
						on m.g_idx = g.g_idx
					  where g.item_state = 'sale'
						and m_idx = '".$_SESSION['member']['idx']."'
					";
		$result_z = mysqli_query($connect, $sql_z) or die (mysql_error($connect));
		$zzim_cnt = mysqli_num_rows($result_z);

		?>
