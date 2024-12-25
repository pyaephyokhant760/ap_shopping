<?php include('header.php') ?>
<?php

require 'config/config.php';

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
	$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
	$stmt->execute();
	$raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
	$total_pagenu = ceil(count($raw_result) / $numOfrecs);

	$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrecs ");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
} else {
	$searchKey = isset($_POST["search"]) ? $_POST["search"] : (isset($_COOKIE["search"]) ? $_COOKIE["search"] : '');
	$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
	$stmt->execute();
	$raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
	$total_pagenu = ceil(count($raw_result) / $numOfrecs);


	$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
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
							<a data-toggle="collapse"><span class="lnr lnr-arrow-right"></span><?php echo $data['name']; ?></a>
						<?php } ?>

					</li>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pagenu=1" class='active'>Frist</a>
					<a <?php if ($pagenu <= 1) {
							echo 'disabled';
						} ?> href="<?php if ($pagenu <= 1) {
																				echo "";
																			} else {
																				echo '?pagenu=' . ($pagenu - 1);
																			} ?>" class="next-arrow  "><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="" class='active'><?php echo $pagenu; ?></a>
					<a <?php if ($pagenu >= $total_pagenu) {
							echo 'disabled';
						} ?> href="<?php if ($pagenu >= $total_pagenu) {
																							echo "";
																						} else {
																							echo '?pagenu=' . ($pagenu + 1);
																						} ?>" class="next-arrow "><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
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
									<img class="img-fluid" src="admin/image/<?php echo escape($value['image']); ?>" style="height: 220px">
									<div class="product-details">
										<h6><?php echo escape($value['name']); ?></h6>
										<div class="price">
											<h6><?php echo escape($value['price']); ?></h6>
										</div>
										<div class="prd-bottom">

											<a href="" class="social-info">
												<span class="ti-bag"></span>
												<p class="hover-text">add to bag</p>
											</a>
											<a href="" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
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
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>
						document.write(new Date().getFullYear());
					</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
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