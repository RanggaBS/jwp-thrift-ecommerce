<?php

// echo "<pre>";
// var_export($_POST);
// echo "<hr class='border-black w-dvw' />";
// var_export($_FILES);
// echo "</pre>";

// echo "<hr class='border-black w-dvw' />";

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . '../../core/db.php';
require_once __DIR__ . '../../lib/utils.php';
require_once __DIR__ . '../../lib/constants.php';

// Util::prettyVarDump($_POST);
// Util::prettyVarDump($_FILES);

/* -------------------------------------------------------------------------- */
/* Check session                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isAdminLoggedIn()) {
	// Util::redirect("/admin"); // Ga work juga??

	echo "<script>
		window.location.href = './';
	</script>";
}

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

/**
 * @var ?string
 */
$errMsg = null;

$isProductSuccessfullyAdded = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$productName = Util::sanitize($_POST["product-name"]);
	$productDesc = Util::sanitize($_POST["product-description"]);
	$productPrice = Util::sanitize($_POST["product-price"]);
	$productCat = Util::sanitize($_POST["product-category"]);
	$productImgs = $_FILES["product-images"];
	$productStock = isset($_POST["product-stock"])
		? Util::sanitize($_POST["product-stock"])
		: 1;
	// $productImgs = Util::reArrayFiles($_FILES["product-images"]);

	// TODO (DONE ✅)
	// fix img url "../img/*.*", must contains only filename with the extension
	// ❌ ../img/image1.jpg
	// ✅ image1.jpg

	$movedImages = [];
	$imageNames = [];

	try {
		// if (str_starts_with($productPrice, '0')) {
		if (Util::str_starts_with($productPrice, '0')) {
			throw new Exception("Harga tidak valid!");
		}
		if (Util::str_starts_with($productStock, '0')) {
			throw new Exception("Stok tidak valid!");
		}

		// echo "<pre>";
		// var_export(Util::reArrayFiles($_FILES["product-images"]));
		// echo "</pre>";

		$uploadDir = "../img/";
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir);
		}

		foreach ($productImgs["name"] as $index => $name) {
			$fileExtension = pathinfo($name, PATHINFO_EXTENSION);

			$uniqueFileName = uniqid() . '.' . $fileExtension;
			$targetFilePath = $uploadDir . $uniqueFileName;

			$allowedExts = ["gif", "jpg", "jpeg", "png", "webp"];
			if (!in_array(strtolower($fileExtension), $allowedExts)) {
				throw new Exception("Extensi file '$name' tersebut bukan gambar.");
			}

			if (
				!move_uploaded_file(
					$productImgs["tmp_name"][$index],
					$targetFilePath
				)
			) {
				throw new Exception(
					"Gagal mengunggah file '" . htmlspecialchars($name) . "'."
				);
			}

			$movedImages[] = $targetFilePath;
			$imageNames[] = $uniqueFileName;
		}

		/* ------------------------------------------------------------------------ */

		$insertQuery = "
			INSERT INTO products (category_id, name, description, price, stock)
			VALUES ($productCat, '$productName', '$productDesc', $productPrice, $productStock)
		";
		if (!$db->query($insertQuery)) {
			throw new Exception("Gagal menambahkan produk.");
		}

		$lastId = $db->insert_id;

		foreach ($imageNames as $imageName) {
			$insertImgQuery = "
				INSERT INTO product_images (product_id, image_url, alt_text)
				VALUES ($lastId, '$imageName', '$productName')
			";
			if (!$db->query($insertImgQuery)) {
				throw new Exception("Gagal menambahkan produk.");
			}
		}

		$isProductSuccessfullyAdded = true;
	} catch (Exception $exception) {
		// Delete the uploaded files if there's an error
		foreach ($movedImages as $movedImage) {
			unlink($movedImage);
		}

		$errMsg = $exception->getMessage();
	}
}

/* -------------------------------------------------------------------------- */
/* Get Categories                                                             */
/* -------------------------------------------------------------------------- */

$COL_ID = "id";
$COL_NAME = "name";
$TBL_NAME = "categories";

$categories = [];

$getCatQuery = "SELECT $COL_ID, $COL_NAME FROM $TBL_NAME ORDER BY $COL_NAME ASC";
$result = $db->query($getCatQuery);
while ($row = $result->fetch_assoc()) {
	$categories[] = [
		"id" => $row[$COL_ID],
		"name" => $row[$COL_NAME]
	];
}

/* -------------------------------------------------------------------------- */
/* Close connection                                                           */
/* -------------------------------------------------------------------------- */

$db->close();

?>

<!-- ----------------------------------------------------------------------- -->
<!-- HTML                                                                    -->
<!-- ----------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thrift | Admin Add Product</title>

	<!-- Development -->
	<link rel="stylesheet" href="../styles/tw-output.css" />
	<!-- Production -->
	<!-- <link rel="stylesheet" href="../styles/tw-build.css" /> -->
</head>

<body class="grid bg-zinc-50 min-h-dvh place-items-center">
	<a href="./dashboard/index.php" class="absolute link top-4 left-4">&larr; Dashboard</a>

	<div class="w-full p-6 mx-auto max-w-screen-xs">
		<h1 class="pb-8 text-3xl font-bold text-center">Tambah Produk</h1>

		<?php
		$thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);
		?>

		<?php if ($isProductSuccessfullyAdded) { ?>
			<p class="pb-4 text-sm text-center text-lime-500">Produk berhasil ditambahkan.</p>
		<?php } ?>

		<?php if (isset($errMsg)) { ?>
			<p class="pb-4 text-sm text-center text-red-500">
				<?= $errMsg ?>
			</p>
		<?php } ?>

		<form action="<?= $thisFile ?>" method="post" enctype="multipart/form-data">
			<div class="input-field">
				<label for="product-name">Nama Produk*</label>
				<input type="text" name="product-name" id="product-name" class="input" required>
			</div>

			<div class="input-field">
				<label for="product-description">Deskripsi</label>
				<!-- <input type="text" name="product-description" id="product-description" class="input"> -->
				<textarea name="product-description" id="product-description" class="input"></textarea>
			</div>

			<div class="input-field">
				<label for="product-price">Harga*</label>
				<input type="number" name="product-price" id="product-price" min="0" class="input" required>
			</div>

			<div class="input-field">
				<label for="product-category">Kategori*</label>
				<select name="product-category" id="product-category" class="input">
					<option value="Pilih kategori" disabled selected>Pilih Kategori</option>

					<?php foreach ($categories as $category) { ?>
						<option value="<?= $category["id"] ?>">
							<?= $category["name"] ?>
						</option>
					<?php } ?>

				</select>
			</div>

			<div class="input-field">
				<label for="product-images">Gambar*</label>
				<input type="file" name="product-images[]" id="product-images" class="input" required multiple>
			</div>

			<div class="input-field">
				<label for="product-stock">Stok</label>
				<input type="number" name="product-stock" id="product-stock" min="1" class="input">
			</div>

			<button type="submit" class="w-full btn btn-md btn-primary">Tambah</button>
		</form>
	</div>
</body>

</html>
