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
  <link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body>
  <div id="root" class="bg-zinc-50 min-h-dvh">
    <?php
    include_once __DIR__ . '../../components/navbar-without-search.php';
    ?>

    <main>
      <section>
        <div class="section-wrapper">
          <h1 class="pb-4 text-4xl font-bold">Akun</h1>

          <ul class="space-y-4">
            <li>
              <a href="./profile.php"
                class="flex items-center justify-between px-4 py-3 rounded-md bg-zinc-200 hover:bg-zinc-300">
                <span class="flex items-center gap-4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>

                  Profil
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chevron-right">
                  <path d="m9 18 6-6-6-6" />
                </svg>
              </a>
            </li>
            <li>
              <a href="./order-history.php"
                class="flex items-center justify-between px-4 py-3 rounded-md bg-zinc-200 hover:bg-zinc-300">
                <span class="flex items-center gap-4">

                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-history">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                    <path d="M12 7v5l4 2" />
                  </svg>

                  Riwayat pesanan
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chevron-right">
                  <path d="m9 18 6-6-6-6" />
                </svg>
              </a>
            </li>
            <li>
              <a href="/logout.php" class="inline-block btn btn-md btn-danger">
                Keluar
              </a>
            </li>
          </ul>
        </div>
      </section>
    </main>

    <?php
    include_once __DIR__ . '../../components/mobile-bottombar.php';
    ?>
  </div>
</body>

</html>
