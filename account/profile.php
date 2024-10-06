<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
	Util::redirect("../login.php");
}

// Get user's id
$userId = $_SESSION["userId"];

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

$isUpdating = false;


$user = $db->query(
	"SELECT name, email, password FROM users WHERE id = $userId"
)->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$name = $_POST["name"];
	$email = $_POST["email"];
	$oldPassword = $_POST["password-old"];
	$newPassword = $_POST["password-new"];
	$newPasswordConfirm = $_POST["password-new-confirm"];
	
	$errorMsg = '';
	$successMsg = '';
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Email tidak valid<br>";	    
	}
	
// 	if (
// 	    !empty($oldPassword)
// 	    && !password_verify($oldPassword, $user["password"])
// 	) {
// 	    $errorMsg .= "Password lama tidak valid";
// 	}
	
	if (!password_verify($oldPassword, $user["password"])) {
	    $errorMsg .= "Password lama tidak valid";
	} else {
	    $updateQuery = '';
	    
	   // Update profil tanpa ubah password dengan yang baru
	    if (empty($newPassword) || empty($newPassword)) {
	       $updateQuery = "UPDATE users
            SET
                name = '$name',
                email = '$email'
            WHERE id = '$userId'";
	       
            global $isUpdating;
	        $isUpdating = true;
	       
	   // Update profil dengan password yang baru
	    } else {
	        if ($newPassword !== $newPasswordConfirm) {
	            $errorMsg .= "Password baru dan konfirmasi password tidak cocok";
	        } else {
	            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users
                SET
                    name = '$name',
                    email = '$email',
                    password = '$hashedPassword'
                WHERE id = '$userId'";
                
                global $isUpdating;
	            $isUpdating = true;
	        }
	    }
	    
	    if ($db->query($updateQuery)) {
	        global $isUpdating;
	        $isUpdating = true;
	    }
	}
	
// 	$updateQuery = "
// 		UPDATE users
// 		SET
// 			name = '$name',
// 			email = '$email'
// 		WHERE id = $userId
// 	";
	
// 	$isSuccess = $db->query($updateQuery);
	
// 	if ($isSuccess) {
// 	    $isUpdating = true;
// 	}
}

// var_dump($isUpdating);


?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thrift | Profil</title>
	<link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body class="bg-zinc-50 min-h-dvh">

	<?php
	include_once __DIR__ . '../../components/navbar-without-search.php';
	?>

	<main>
		<div class="section-wrapper">
			<a href="./" class="inline-block mb-6 btn btn-md btn-primary">&larr; Kembali</a>

			<h1 class="pb-6 text-3xl font-bold">Profil</h1>

			<?php if ($isUpdating) { ?>
				<p class="flex items-center gap-4 px-4 py-3 mb-4 text-white rounded-md bg-lime-500">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-check">
						<path d="M20 6 9 17l-5-5" />
					</svg>

					Profil berhasil diperbarui
				</p>
			<?php } ?>

			<?php $thisFile = htmlspecialchars($_SERVER["PHP_SELF"]); ?>

			<form action="<?= $thisFile ?>" method="post">
				<div class="input-field">
					<label for="name">Nama</label>
					<input type="text" name="name" id="name" value="<?= $user["name"] ?>" class="input">
				</div>

				<div class="input-field">
					<label for="email">Email</label>
					<input type="text" name="email" id="email" value="<?= $user["email"] ?>" class="input">
				</div>

				<!-- Old and new password input fields -->
				
				<div class="input-field">
				    <label for="password-old">Password</label>
				    <input type="password" name="password-old" id="password-old" required class="input">
				</div>
				
				<div class="input-field">
				    <label for="password-new">Password baru</label>
				    <input type="password" name="password-new" id="password-new" class="input">
				</div>
				
				<div class="input-field">
				    <label for="password-new-confirm">Konfirmasi password baru</label>
				    <input type="password" name="password-new-confirm" id="password-new-confirm" class="input">
				</div>

				<button type="submit" class="btn btn-md btn-primary">Perbarui</button>
			</form>
		</div>
	</main>

</body>

</html>
