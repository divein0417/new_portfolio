<!-- 타임특가 -->
<div class="time_sbox only_web">
	<p class="tac">타임특가</p>
	<ul class="ts_list">

	<?


	$fsql    = "select * from tbl_icon where status='Y' order by onum asc, code_idx desc";
	$fresult = mysqli_query($connect, $fsql) or die (mysql_error());
	$i = 0;
	while($frow=mysqli_fetch_array($fresult)){
		$arr_code_no[$i] = $frow["code_no"];
		$arr_img[$i] = $frow["iconimg"];
		$i = $i + 1;
	}

	$total_sql = " select g.* , if(sum_cnt is null , 0, sum_cnt) as sum_cnt
					 from tbl_goods g
					 left outer join
						  (select g_idx as sg_idx, sum(cnts) as sum_cnt
						  from tbl_order_sub
						 group by g_idx) o
					   on g.g_idx = o.sg_idx
					where item_state = 'sale' 
					  and g.goods_dis3='Y' 
				";
	$sql    = $total_sql . " order by g.g_idx desc limit 3 ";

	$result = mysqli_query($connect, $sql) or die (mysql_error($connect));
	
	while($row = mysqli_fetch_array($result)){
		// 브랜드 정보
		$sql_br    = "select * from tbl_brand where code_no='".$row['goods_brand']."'";
		$result_br = mysqli_query($connect, $sql_br) or die (mysql_error());
		$row_br    = mysqli_fetch_array($result_br);

		$product_dbcolor = $row['product_dbcolor'];

		// 찜
		if( chk_zzim($_SESSION[member][idx], $row['g_idx']) > 0 ){
			$zzim_class = " active ";
		}else{
			$zzim_class = " ";
		}

		// vs 부분
		$vs_class = "";
		if( in_array( $row['g_idx'], $_COOKIE["vsGoods"] ) == true ){
			$vs_class = " active ";
		}else{
			$vs_class = " ";
		}
	
	?>

		<li>
			<a href="/item/item_view.php?gcode=<?=$row["g_idx"]?>">
				<div class="">
					<img src="/data/product/<?=$row["ufile1"]?>" alt="">
				</div>
				<div class="ts_con">
					<p class="p_discount"><?=round(($row['price_mk'] - viewGoodsPay($row['g_idx']))/$row['price_mk']*100,1)?>%<p>
					<p class="p_name"><?=$row['goods_name_front']?></p>
					<p class="p_won"><?=number_format(viewGoodsPay($row['g_idx']))?><span>원</span></p>
				</div>
			</a>
		</li>
	<?}?>

		<!--
		<li>
			<a href="#!">
				<div class="">
					<img src="../img/main/time_img02.png" alt="">
				</div>
				<div class="ts_con">
					<p class="p_discount">50%<p>
					<p class="p_name">96700쥬서기/착즙기</p>
					<p class="p_won">890,000<span>원</span></p>
				</div>
			</a>
		</li>
		-->
		
		<!--<li>
			<a href="#!">
				<div class="">
					<img src="../img/main/time_img04.png" alt="">
				</div>
				<div class="ts_con">
					<p class="p_discount">50%<p>
					<p class="p_name">96700쥬서기/착즙기</p>
					<p class="p_won">890,000<span>원</span></p>
				</div>
			</a>
		</li>-->
	</ul>
</div>
<!-- 타임특가 -->

<!-- 퀵메뉴 -->
<div class="quick_link only_web">
	<ul class="quick_list">
		<li>
			<a href="#!">
				<img src="../img/ico/quick_ico01.png" alt="내가 본 상품 아이콘">
				<p>내가본상품</p>
			</a>
		</li>
		<li>
			<a href="/item/cart.php">
				<img src="../img/ico/quick_ico02.png" alt="장바구니 아이콘">
				<p>장바구니</p>
			</a>
		</li>
		<li>
			<a href="/mypage/mypage_book.php">
				<img src="../img/ico/quick_ico03.png" alt="찜한상품 아이콘">
				<p>찜한상품</p>
			</a>
		</li>
		<li>
			<a href="/mypage/mypage_contact.php">
				<img src="../img/ico/quick_ico04.png" alt="견적문의 아이콘">
				<p>견적문의</p>
			</a>
		</li>
	</ul>
	<a href="#wrap" class="top_btn">TOP</a>
</div>
<!-- 퀵메뉴 -->