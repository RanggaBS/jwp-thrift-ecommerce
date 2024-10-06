<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (Util::isLoggedIn()) {
	Util::redirect("./");
}

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

$errMsg = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$email = Util::sanitize($_POST["email"]);
	$password = Util::sanitize($_POST["password"]);

	$checkQuery = "SELECT id, email, password FROM users WHERE email = '$email'";
	$result = $db->query($checkQuery);

	try {
		if ($result->num_rows <= 0) {
			throw new Exception("Email tidak terdaftar atau kata sandi salah.");
		}

		$user = $result->fetch_assoc();
		if (!password_verify($password, $user["password"])) {
			throw new Exception("Email tidak terdaftar atau kata sandi salah.");
		}

		Util::createSession();
		session_start();
		$_SESSION["userId"] = $user["id"];

		Util::redirect("./");
	} catch (Exception $exception) {
		// $result->close();
		// $stmt->close();

		$errMsg = $exception->getMessage();
	}
}

$db->close();

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thrift | Masuk</title>

  <!-- Development -->
  <link rel="stylesheet" href="styles/tw-output.css" />
  <!-- Production -->
  <!-- <link rel="stylesheet" href="styles/tw-build.css" /> -->
</head>

<body>
  <div id="root" class="grid text-black bg-zinc-50 min-h-dvh place-items-center">
    <a href="./" class="absolute link top-4 left-4">&larr; Kembali</a>
    <!-- <a href="https://ranggabs.github.io/jwp-thrift-ecommerce/" class="absolute link top-4 left-4">&larr; Checklist</a> -->

    <div class="w-full max-w-sm px-6">
      <div class="mb-12 text-center">
        <h1 class="pb-2 text-4xl font-bold">Masuk</h1>

        <p class="text-sm text-zinc-400">Hai Kamu! Selamat datang.</p>
      </div>


      <?php if (isset($errMsg)) { ?>
      <p class="text-sm text-red-500">
        <?= $errMsg ?>
      </p>
      <?php } ?>

      <?php
			$thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);
			?>

      <form action="<?= $thisFile ?>" method="post" id="form" class="mb-8">
        <div class="input-field">
          <label for="email" class="font-medium">Email</label>
          <input type="text" name="email" id="email" placeholder="johndoe@mail.com..." class="input" />
        </div>

        <div class="input-field">
          <label for="password" class="font-medium">Password</label>
          <input type="password" name="password" id="password" placeholder="rahasia..." class="input" />
        </div>

        <button type="submit" class="w-full btn btn-md btn-primary">
          Masuk
        </button>
      </form>

      <p class="text-xs text-center">
        Belum punya akun? <a href="register.php" class="link">Daftar</a>
      </p>
    </div>
  </div>
</body>

</html>