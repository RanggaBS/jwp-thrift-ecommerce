<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . '../../../core/db.php';
require_once __DIR__ . '../../../lib/utils.php';

/* -------------------------------------------------------------------------- */
/* Check session                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isAdminLoggedIn()) {
	Util::redirect("../");
}

/* -------------------------------------------------------------------------- */
/* Get data                                                                   */
/* -------------------------------------------------------------------------- */

$userQuery = "SELECT COUNT(*) AS total_user FROM users";
$userResult = $db->query($userQuery);
$totalUser = $userResult->fetch_assoc()["total_user"];

$productQuery = "SELECT COUNT(*) AS total_product FROM products";
$productResult = $db->query($productQuery);
$totalProduct = $productResult->fetch_assoc()["total_product"];

$orderQuery = "SELECT COUNT(*) AS total_order FROM orders";
$orderResult = $db->query($orderQuery);
$totalOrder = $orderResult->fetch_assoc()["total_order"];

$revenueQuery = "SELECT SUM(total_price) AS total_revenue FROM orders";
$revenueResult = $db->query($revenueQuery);
$totalRevenue = $revenueResult->fetch_assoc()["total_revenue"] ?? 0;
// $totalRevenue = $totalRevenue ? $totalRevenue : 0;

?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Thrift | Admin dashboard</title>

	<!-- Development -->
	<link rel="stylesheet" href="../../styles/tw-output.css" />
	<!-- Production -->
	<!-- <link rel="stylesheet" href="../../styles/tw-build.css" /> -->
</head>

<body class="flex bg-zinc-50 min-h-dvh">
	<!-- ---------------------------------------------------------------------- -->
	<!-- Sidebar                                                                -->
	<!-- ---------------------------------------------------------------------- -->

	<aside class="hidden w-full border-r md:block border-zinc-300 max-w-64">
		<div class="grid h-16 border-b place-items-center border-zinc-300">
			<a href="#" class="inline-block px-4 py-3 text-2xl font-semibold whitespace-nowrap">
				ThriftStore Admin
			</a>
		</div>

		<h2 class="px-4 pt-4 text-lg font-semibold">Dashboard</h2>

		<ul class="flex flex-col gap-1 px-4 pt-2">
			<!-- ------------------------------------------------------------------- -->
			<!-- Overview                                                            -->
			<!-- ------------------------------------------------------------------- -->

			<li>
				<a href="#" class="flex items-center w-full gap-2 btn btn-sm btn-ghost">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-chart-no-axes-column-increasing">
						<line x1="12" x2="12" y1="20" y2="10" />
						<line x1="18" x2="18" y1="20" y2="4" />
						<line x1="6" x2="6" y1="20" y2="16" />
					</svg>

					Ikhtisar
				</a>
			</li>

			<!-- ------------------------------------------------------------------- -->
			<!-- Products                                                            -->
			<!-- ------------------------------------------------------------------- -->

			<li>
				<a href="#" class="flex items-center w-full gap-2 btn btn-sm btn-ghost">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-package">
						<path d="m7.5 4.27 9 5.15" />
						<path
							d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
						<path d="m3.3 7 8.7 5 8.7-5" />
						<path d="M12 22V12" />
					</svg>

					Produk
				</a>
			</li>

			<!-- ------------------------------------------------------------------- -->
			<!-- Orders                                                              -->
			<!-- ------------------------------------------------------------------- -->

			<li>
				<a href="#" class="flex items-center w-full gap-2 btn btn-sm btn-ghost">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-shopping-cart">
						<circle cx="8" cy="21" r="1" />
						<circle cx="19" cy="21" r="1" />
						<path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
					</svg>

					Pesanan
				</a>
			</li>

			<!-- ------------------------------------------------------------------- -->
			<!-- Customers                                                           -->
			<!-- ------------------------------------------------------------------- -->

			<li>
				<a href="#" class="flex items-center w-full gap-2 btn btn-sm btn-ghost">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-users">
						<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
						<circle cx="9" cy="7" r="4" />
						<path d="M22 21v-2a4 4 0 0 0-3-3.87" />
						<path d="M16 3.13a4 4 0 0 1 0 7.75" />
					</svg>

					Pelanggan
				</a>
			</li>
		</ul>
	</aside>

	<!-- ---------------------------------------------------------------------- -->
	<!-- Main Content                                                           -->
	<!-- ---------------------------------------------------------------------- -->

	<div class="w-full">
		<!-- --------------------------------------------------------------------- -->
		<!-- Top Navbar                                                            -->
		<!-- --------------------------------------------------------------------- -->

		<nav id="top-navbar" class="border-b border-zinc-300">
			<div class="flex items-center justify-between px-6 py-3">
				<button type="button" class="btn btn-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-menu">
						<line x1="4" x2="20" y1="12" y2="12" />
						<line x1="4" x2="20" y1="6" y2="6" />
						<line x1="4" x2="20" y1="18" y2="18" />
					</svg>
				</button>

				<a href="../logout.php" class="flex items-center rounded-md btn btn-sm btn-danger">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="mr-2 lucide lucide-log-out">
						<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
						<polyline points="16 17 21 12 16 7" />
						<line x1="21" x2="9" y1="12" y2="12" />
					</svg>

					Keluar
				</a>
			</div>
		</nav>

		<main>
			<section>
				<div class="py-6 section-wrapper">
					<h1 class="pb-6 text-3xl font-bold">Ikhtisar</h1>

					<ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
						<!-- ---------------------------------------------------------------- -->
						<!-- Total User                                                       -->
						<!-- ---------------------------------------------------------------- -->

						<li>
							<div class="px-6 py-5 border rounded-md border-zinc-300">
								<div class="flex items-center justify-between pb-4">
									<h2 class="text-sm font-medium">Jumlah Pengguna</h2>

									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="lucide lucide-users">
										<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
										<circle cx="9" cy="7" r="4" />
										<path d="M22 21v-2a4 4 0 0 0-3-3.87" />
										<path d="M16 3.13a4 4 0 0 1 0 7.75" />
									</svg>
								</div>

								<p class="text-2xl font-bold">
									<?= $totalUser ?>
								</p>
							</div>
						</li>

						<!-- ---------------------------------------------------------------- -->
						<!-- Total Product                                                    -->
						<!-- ---------------------------------------------------------------- -->

						<li>
							<div class="px-6 py-5 border rounded-md border-zinc-300">
								<div class="flex items-center justify-between pb-4">
									<h2 class="text-sm font-medium">Jumlah Produk</h2>

									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="lucide lucide-package">
										<path d="m7.5 4.27 9 5.15" />
										<path
											d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
										<path d="m3.3 7 8.7 5 8.7-5" />
										<path d="M12 22V12" />
									</svg>
								</div>

								<p class="text-2xl font-bold">
									<?= $totalProduct ?>
								</p>
							</div>
						</li>

						<!-- ---------------------------------------------------------------- -->
						<!-- Total Order                                                      -->
						<!-- ---------------------------------------------------------------- -->

						<li>
							<div class="px-6 py-5 border rounded-md border-zinc-300">
								<div class="flex items-center justify-between pb-4">
									<h2 class="text-sm font-medium">Jumlah Pesanan</h2>

									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="lucide lucide-shopping-cart">
										<circle cx="8" cy="21" r="1" />
										<circle cx="19" cy="21" r="1" />
										<path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
									</svg>
								</div>

								<p class="text-2xl font-bold">
									<?= $totalOrder ?>
								</p>
							</div>
						</li>

						<!-- ---------------------------------------------------------------- -->
						<!-- Total Revenue                                                    -->
						<!-- ---------------------------------------------------------------- -->

						<li>
							<div class="px-6 py-5 border rounded-md border-zinc-300">
								<div class="flex items-center justify-between pb-4">
									<h2 class="text-sm font-medium">Jumlah Pendapatan</h2>

									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="lucide lucide-dollar-sign">
										<line x1="12" x2="12" y1="2" y2="22" />
										<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
									</svg>
								</div>

								<p class="text-2xl font-bold">
									Rp <?= number_format($totalRevenue, 0, '', '.') ?>
								</p>
							</div>
						</li>
					</ul>
				</div>
			</section>

			<section>
				<div class="section-wrapper">
					<ul class="flex gap-4">
						<li>
							<a href="../add-product.php" class="inline-block btn btn-sm btn-primary">
								Tambah produk
							</a>
						</li>
						<li>
							<a href="../product-list.php" class="inline-block btn btn-sm btn-primary">
								Daftar produk
							</a>
						</li>
					</ul>
				</div>
			</section>
		</main>
	</div>
</body>

</html>
