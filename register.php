<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (Util::isLoggedIn()) {
	header("Location: ./");
	exit;
}

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

$errMsg = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$name = Util::sanitize($_POST["name"]);
	$email = Util::sanitize($_POST["email"]);
	$password = password_hash(Util::sanitize($_POST["password"]), PASSWORD_DEFAULT);

	try {
		$insertQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
		$stmt = $db->prepare($insertQuery);
		$stmt->bind_param("sss", $name, $email, $password);
		$success = $stmt->execute();
		if (!$success) {
			// if (strpos($stmt->error, "Duplicate") !== false) {
			if (Util::str_contains($stmt->error, "Duplicate")) {
				throw new Exception("Email tersebut sudah terdaftar.");
			}
			throw new Exception("Daftar gagal.");
		}

		Util::createSession();
		$_SESSION["userId"] = $stmt->insert_id;

		Util::redirect("./");
	} catch (Exception $exception) {
		$stmt->close();

		$errMsg = $exception->getMessage();

		// if (strpos($errMsg, "Duplicate") !== false) {
		if (Util::str_contains($errMsg, "Duplicate")) {
			$errMsg = "Email tersebut sudah terdaftar.";
		}
	}
}

$db->close();

?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Thrift | Daftar</title>

	<!-- Development -->
	<link rel="stylesheet" href="styles/tw-output.css" />
	<!-- Production -->
	<!-- <link rel="stylesheet" href="styles/tw-build.css" /> -->
</head>

<body>
	<div id="root" class="grid text-black bg-zinc-50 min-h-dvh place-items-center">
		<a href="../" class="absolute link top-4 left-4">&larr; Kembali</a>
		<!-- <a href="https://ranggabs.github.io/jwp-thrift-ecommerce/" class="absolute link top-4 left-4">&larr; Checklist</a> -->

		<div class="w-full max-w-sm px-6">
			<div class="mb-12 text-center">
				<h1 class="pb-2 text-4xl font-bold">Buat Akun</h1>

				<p class="text-sm text-zinc-400">Isi formulir di bawah ini.</p>
			</div>

			<?php if (isset($errMsg)) { ?>
				<p class="text-sm text-red-500">
					<?= $errMsg ?>
				</p>
			<?php } ?>

			<form action="" method="post" id="form" class="mb-8">
				<div class="input-field">
					<label for="name" class="font-medium">Nama</label>
					<input type="text" name="name" id="name" placeholder="John Doe..." class="input" />
				</div>
				<div class="input-field">
					<label for="email" class="font-medium">Email</label>
					<input type="text" name="email" id="email" placeholder="johndoe@mail.com..." class="input" />
				</div>
				<div class="input-field">
					<label for="password" class="font-medium">Password</label>
					<input type="password" name="password" id="password" placeholder="rahasia..." class="input" />
				</div>

				<button type="submit" class="w-full btn btn-md btn-primary">
					Daftar
				</button>
			</form>

			<p class="text-xs text-center">
				Sudah punya akun? <a href="login.php" class="link">Masuk</a>
			</p>
		</div>
	</div>
</body>

</html>
