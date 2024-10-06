<?php

require_once __DIR__ . "/lib/utils.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Thrift | Homepage</title>
	<link rel="stylesheet" href="./styles/tw-output.css" />

	<!-- Swiper -->
	<link rel="stylesheet" href="./lib/swiper/swiper-bundle.min.css" />
</head>

<body>
	<!-- <a href="./logout.php" class="rounded btn btn-sm btn-danger">Logout</a>
	<a href="./login.php" class="link">Login</a>
	<a href="./register.php" class="link">Register</a> -->

	<div id="root" class="text-black bg-zinc-50 min-h-dvh">
		<?php
		include_once __DIR__ . '/components/navbar.php';
		?>

		<main>
			<!-- ------------------------------------------------------------------- -->
			<!-- Hero                                                                -->
			<!-- ------------------------------------------------------------------- -->

			<section id="hero">
				<div class="section-wrapper">
					<div id="swiper-hero" class="rounded-lg swiper">
						<ul class="swiper-wrapper">

							<?php for ($i = 0; $i < 3; $i++) { ?>
								<li class="swiper-slide">
									<div class="relative block rounded-lg max-h-48 xs:max-h-64 group">
										<img src="assets/images/hero-1.png" alt="A man worn black t-shirt"
											class="block object-cover object-top w-full rounded-lg max-h-48 xs:max-h-64" />
										<p class="absolute text-xl font-bold text-white bottom-4 left-4 group-hover:text-3xl transition-all">
											Essential Cotton<br />Black T-Shirt <?= $i + 1 ?>
										</p>
									</div>
								</li>
							<?php } ?>

						</ul>
						<div id="swiper-hero-pagination" class="swiper-pagination"></div>
					</div>
				</div>
			</section>

			<section id="category">
				<div class="mt-6 section-wrapper">
					<h2 class="pb-4 text-2xl font-bold">Kategori</h2>

					<?php
					include_once __DIR__ . '/components/category-homepage.php';
					?>

				</div>
			</section>

			<section id="featured">
				<div class="mt-6 section-wrapper">
					<h2 class="pb-4 text-2xl font-bold">Unggulan</h2>

					<?php
					include_once __DIR__ . '/components/all-product-grid.php';
					?>

					<p class="py-6 text-sm text-center">Kamu sudah mencapai akhir daftar.</p>
				</div>
			</section>
		</main>
	</div>

	<!-- An empty div with the height same as mobile bottom navbar -->
	<div class="h-6 2xs:h-10 xs:h-12"></div>

	<?php
	include_once __DIR__ . '/components/mobile-bottombar.php';
	?>

	<div class="w-1 h-1"></div>

	<!-- Swiper -->
	<!-- <script src="./lib/swiper/swiper-element-bundle.min.js"></script> -->
	<script src="./lib/swiper/swiper-bundle.min.js"></script>
	<script>
		const swiperHero = new Swiper("#swiper-hero", {
			autoplay: {
				delay: 3000,
			},
			speed: 1500,
			spaceBetween: 24,
			pagination: {
				enabled: true,
				el: "#swiper-hero-pagination",
				// bulletClass: "!inline-block bg-white !w-1 !h-1 mx-[2px] rounded-full",
				// bulletActiveClass: "inline-block w-1 h-1 !bg-primary",
				// renderBullet: function (index, className) {
				// 	return `<span class="${className}"></span>`;
				// },
			},
			loop: true,
		});

		// slides-per-view="auto" space-between="16" navigation="true"
	</script>
</body>

</html>
