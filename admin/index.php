<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/constants.php";
require_once __DIR__ . "../../lib/utils.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (Util::isAdminLoggedIn()) {
	header("Location: ./dashboard/");
	exit;
}

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

/**
 * @var ?string
 */
$errMsg = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$username = strtolower(Util::sanitize($_POST["username"]));
	$password = strtolower(Util::sanitize($_POST["password"]));

	try {
		if ($username !== ADMIN_USERNAME || $password !== ADMIN_PASSWORD) {
			throw new Exception("Username atau password salah.");
		}

		Util::createAdminSession();
		header("Location: ./dashboard/");
	} catch (Exception $exception) {
		$errMsg = $exception->getMessage();
	}
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thrift | Admin login</title>
	<!-- Development -->
	<link rel="stylesheet" href="../styles/tw-output.css" />
	<!-- Production -->
	<!-- <link rel="stylesheet" href="../styles/tw-build.css" /> -->
</head>

<body>
	<a href="../" class="link">&larr; Back</a>

	<div id="root" class="grid bg-zinc-50 min-h-dvh place-items-center">
		<div class="w-full max-w-sm px-6">

			<div class="pb-8 text-center">
				<h1 class="pb-2 text-4xl font-bold">Admin</h1>

				<p class="text-sm text-zinc-400">Masuk untuk dapat melakukan operasi CRUD.</p>
			</div>

			<?php if (isset($errMsg)) { ?>
				<p class="py-4 text-sm text-red-500">
					<?= $errMsg ?>
				</p>
			<?php } ?>

			<?php
			$thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);
			?>

			<form action="<?= $thisFile ?>" method="post">
				<div class="input-field">
					<label for="username" class="font-medium">Nama pengguna</label>
					<input type="text" name="username" id="username" placeholder="johndoe..." class="input">
				</div>

				<div class="input-field">
					<label for="password" class="font-medium">Kata sandi</label>
					<input type="password" name="password" id="password" placeholder="rahasia..." class="input">
				</div>

				<button type="submit" class="w-full btn btn-md btn-primary">Masuk</button>
			</form>
		</div>
	</div>
</body>

</html>
