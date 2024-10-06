<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

// require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (Util::isLoggedIn()) {
  echo "<script>alert('Anda sudah login!');</script>";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Sudah masuk</title>
</head>

<body>
  <h1>Anda sudah masuk.</h1>
</body>

</html>

<?php
header("Location: ./");
exit;
?>
