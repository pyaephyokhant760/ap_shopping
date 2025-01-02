<?php include('header.php') ?>
<?php

require 'config/config.php';
if($_SESSION['role'] == 0) {
	header('Location: login.php');
  } elseif(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
	header('Location: login.php');
  }

if (isset($_POST["search"])) {
	setcookie("search", $_POST["search"], time() + (86400 * 30), "/");
} else {
	if (empty($_GET["pagenu"])) {
		unset($_COOKIE["search"]);
		setcookie("search", "", time() - 3600, "/");
	}
}

if (!empty($_GET['pagenu'])) {
	$pagenu = $_GET['pagenu'];
} else {
	$pagenu = 1;
}
$numOfrecs = 6;
$offset = ($pagenu - 1) * $numOfrecs;

if (empty($_POST['search']) && empty($_COOKIE['search'])) {
	if(!empty($_GET['category_id'])) {
		$categoryId = $_GET['category_id'];
		$stmt = $conn->prepare("SELECT * FROM products WHERE category_id=$categoryId AND quantity > 0 ORDER BY id DESC");
		$stmt->execute();
		$raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
		$total_pagenu = ceil(count($raw_result) / $numOfrecs);

		$stmt = $conn->prepare("SELECT * FROM products WHERE category_id=$categoryId AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs ");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
	}else{
		$stmt = $conn->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC");
		$stmt->execute();
		$raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
		$total_pagenu = ceil(count($raw_result) / $numOfrecs);

		$stmt = $conn->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs ");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
	}
	
} else {
	$searchKey = isset($_POST["search"]) ? $_POST["search"] : (isset($_COOKIE["search"]) ? $_COOKIE["search"] : '');
	$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity > 0 ORDER BY id DESC");
	$stmt->execute();
	$raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
	$total_pagenu = ceil(count($raw_result) / $numOfrecs);


	$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs ");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
}
?>
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<li class="main-nav-list">
						<?php
						$catstmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
						$catstmt->execute();
						$catResult = $catstmt->fetchAll();
						?>

						<?php foreach ($catResult as $data) { ?>
							<a href="index.php?category_id=<?php echo $data['id'] ?>"><span class="lnr lnr-arrow-right"></span><?php echo $data['name']; ?></a>
						<?php } ?>

					</li>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pagenu=1" class='active'>Frist</a>
					<a <?php if ($pagenu <= 1) {echo 'disabled';} ?> href="<?php if ($pagenu <= 1) {echo "";} else {echo '?pagenu=' . ($pagenu - 1);} ?>" class="next-arrow  "><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="" class='active'><?php echo $pagenu; ?></a>
					<a <?php if ($pagenu >= $total_pagenu) {echo 'disabled';} ?> href="<?php if ($pagenu >= $total_pagenu) {echo "";} else {echo '?pagenu=' . ($pagenu + 1);} ?>" class="next-arrow "><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a href="?pagenu=<?php echo $total_pagenu ?>" class='active'>Last</a>
				</div>
			</div>
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<?php
					if ($result) {
						foreach ($result as $value) { ?>
							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<a href="project_detail.php?id=<?php echo $value['id'] ?>">
										<img class="img-fluid" src="admin/image/<?php echo escape($value['image']); ?>" style="height: 220px">
									</a>
									<div class="product-details">
										<h6><?php echo escape($value['name']); ?></h6>
										<div class="price">
											<h6><?php echo escape($value['price']); ?></h6>
										</div>
										<div class="prd-bottom">
											<form action="addtocart.php" method="post">
											<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
											<input type="hidden" name="id" value="<?php echo escape($value['id'])?>">
											<input type="hidden" name="qty" value="1">
											<div class="social-info">
											<button style="display: contents" class="social-info"><span class="ti-bag"></span>
											<p class="hover-text" style="left: 20px">add to bag</p></button>
											</div>
											
											<a href="project_detail.php?id=<?php echo $value['id'] ?>" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
											</form>
										</div>
									</div>
								</div>
							</div>
					<?php
						}
					}
					?>
					<!-- single product -->

				</div>
			</section>
		</div>
	</div>



	<!-- start footer Area -->
<footer class="footer-area section_gap">
<div class="container">
<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
</div>
</div>
</footer>
<!-- End footer Area -->

<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
crossorigin="anonymous"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/jquery.ajaxchimp.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/nouislider.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<!--gmaps Js-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
<script src="js/gmaps.min.js"></script>
<script src="js/main.js"></script>
</body>

</html>