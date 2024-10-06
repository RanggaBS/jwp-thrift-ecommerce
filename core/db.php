<?php

require_once __DIR__ . "../../lib/utils.php";

if (ENV === "PRODUCTION") {
	define("HOSTNAME", "localhost");
	define("DB_USERNAME", "sist2824_user_thriftstore");
	define("DB_USERPASSWORD", "thriftstorethriftstore");
	define("DB_NAME", "sist2824_db_thriftstore");
} else {
	define("HOSTNAME", "localhost");
	define("DB_USERNAME", "root");
	define("DB_USERPASSWORD", "");
	define("DB_NAME", "thrift_ecommerce");
}

$db = new mysqli(HOSTNAME, DB_USERNAME, DB_USERPASSWORD, DB_NAME);

if ($db->connect_error) {
	exit("Koneksi ke database gagal!");
}

// echo "Koneksi ke database berhasil!";
