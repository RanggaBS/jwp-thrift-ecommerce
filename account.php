<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
  header("Location: ./login.php");
  exit;
}

$userId = $_SESSION["userId"];
$userQuery = "SELECT * FROM users WHERE id = $userId";
$user = $db->query($userQuery)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Account</title>
  <link rel="stylesheet" href="./styles/tw-output.css">
</head>

<body>
  <div id="root" class="bg-zinc-50 min-h-dvh">
    <?php
    include_once __DIR__ . '/components/navbar-without-search.php';
    ?>

    <main>
      <section>
        <div class="section-wrapper">
          <h1 class="pb-4 text-4xl font-bold">Akun</h1>
          
           <p>
            Nama: <?= $user["name"] ?>
          </p>

          <p>
            Email: <?= $user["email"] ?>
          </p>

          <a href="./logout.php" class="btn btn-md btn-danger inline-block">
            Keluar
          </a>
        </div>
      </section>
    </main>

    <?php
    include_once __DIR__ . '/components/mobile-bottombar.php';
    ?>
  </div>
</body>

</html>
