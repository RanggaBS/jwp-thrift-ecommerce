<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */

if (!Util::isAdminLoggedIn()) {
  Util::redirect("./");
}

/* -------------------------------------------------------------------------- */

$productListQuery = "
  SELECT
    products.*,
    product_images.image_url
  FROM
    products
  LEFT JOIN product_images ON products.id = product_images.product_id
";
$productResult = $db->query($productListQuery);

/* -------------------------------------------------------------------------- */

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Admin - product list</title>
  <link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body class="flex min-h-dvh">

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
            class="lucide lucide-log-out mr-2">
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
        <div class="section-wrapper py-6">
          <table cellpadding="12" class="border-collapse border border-black">
            <tr class="border border-black">
              <th>No</th>
              <th>ID</th>
              <th>Gambar</th>
              <th>Nama produk</th>
              <th>Deskripsi</th>
              <th>Stok</th>
              <th>Harga</th>
              <th>Aksi</th>
            </tr>

            <?php if ($productResult->num_rows > 0) { ?>
              <?php $index = 0; ?>
              <?php while ($product = $productResult->fetch_assoc()) { ?>
                <tr class="border border-black">
                  <td>
                    <?= $index + 1 ?>
                  </td>
                  <td>
                    <?= $product["id"] ?>
                  </td>
                  <td>
                    <img src="../img/<?= $product["image_url"] ?>" alt="" class="w-32">
                  </td>
                  <td>
                    <?= $product["name"] ?>
                  </td>
                  <td>
                    <?= $product["description"] ?>
                  </td>
                  <td>
                    <?= $product["stock"] ?>
                  </td>
                  <td>
                    Rp <?= Util::formatRupiahTanpaKoma($product["price"]) ?>
                  </td>
                  <td>
                    <a href="./edit-product.php?id=<?= $product["id"] ?>" class="inline-block btn btn-sm btn-icon">
                      Edit
                    </a>
                    <a href="./delete-product.php?id=<?= $product["id"] ?>" class="inline-block btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus produk ini?');">
                      Hapus
                    </a>
                  </td>
                </tr>

                <?php $index += 1; ?>

              <?php } ?>
            <?php } ?>
          </table>
        </div>
      </section>

      <section>
        <div class="section-wrapper">
          <ul class="flex gap-4">
            <li>
              <a href="./add-product.php" class="inline-block btn btn-sm btn-primary">
                Tambah produk
              </a>
            </li>
            <!-- <li>
              <a href="./product-list.php" class="inline-block btn btn-sm btn-primary">
                Daftar produk
              </a>
            </li> -->
          </ul>
        </div>
      </section>
    </main>
  </div>

</body>

</html>
