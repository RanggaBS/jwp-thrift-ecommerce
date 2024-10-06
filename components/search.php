<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

// require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Incoming Request                                                           */
/* -------------------------------------------------------------------------- */

// if ($_SERVER["REQUEST_METHOD"] === "GET") {
//   $keyword = $_GET["search"];

//   $searchQuery = "
//     SELECT * FROM products
//       WHERE name LIKE '%$keyword%'
//       OR description LIKE '%$keyword%'
//   ";
//   $result = $db->query($searchQuery);
// }

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["search"])) {
  $keyword = Util::sanitize($_GET["search"]);

  header("Location: ../../search.php?search=$keyword");
  //   header("Location: asdsadsadsadd.php?search=$keyword");
  //   header("Location: asdsadsadsadd");
  exit;
}

?>

<?php
// $thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);
// $thisFile = htmlspecialchars(__FILE__);
?>

<form action="/search.php" method="get" class="relative">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
    class="absolute text-transparent -translate-y-1/2 pointer-events-none top-1/2 left-4">
    <path
      d="M20 20L15.8033 15.8033M18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18C14.6421 18 18 14.6421 18 10.5Z"
      stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
  </svg>

  <input type="text" name="search" id="search" placeholder="Cari..."
    class="py-2 pl-12 pr-4 text-sm transition border rounded-full max-w-36 sm:max-w-48 md:max-w-56 border-zinc-300 hover:border-primary-hover focus-within:border-2 outline-0 focus-within:border-primary" />
</form>
