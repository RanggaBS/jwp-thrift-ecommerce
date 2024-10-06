<?php

final class Util
{
	/* ------------------------------------------------------------------------- */
	/* Attributes                                                                */
	/* ------------------------------------------------------------------------- */

	/**
	 * @var bool
	 */
	private $IS_PRODUCTION;

	/* ------------------------------------------------------------------------- */
	/* Methods                                                                   */
	/* ------------------------------------------------------------------------- */

	/* ------------------------------------------------------------------------- */
	/* Forms                                                                     */
	/* ------------------------------------------------------------------------- */

	/**
	 * @param string $str
	 * @return string
	 */
	public static function sanitize(string $str): string
	{
		return htmlspecialchars(stripslashes(trim($str)));
	}

	/* ------------------------------------------------------------------------- */
	/* Sessions                                                                  */
	/* ------------------------------------------------------------------------- */

	public static function createSession()
	{
		session_start();
		$_SESSION["isLoggedIn"] = true;
	}

	public static function clearSession()
	{
		session_start();
		session_destroy();
	}

	/**
	 * @return ?bool
	 */
	public static function isLoggedIn(): bool
	{
		session_start();
		return isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"];
	}

	// Admin session

	public static function createAdminSession()
	{
		session_start();
		$_SESSION["isAdminLoggedIn"] = true;
	}

	/**
	 * @return bool
	 */
	public static function isAdminLoggedIn(): bool
	{
		session_start();
		return isset($_SESSION["isAdminLoggedIn"]) && $_SESSION["isAdminLoggedIn"];
	}

	/* ------------------------------------------------------------------------- */
	/* Headers                                                                   */
	/* ------------------------------------------------------------------------- */

	/**
	 * Redirect and terminate the script.
	 * @param string $location
	 * @return never
	 */
	public static function redirect(string $location)
	{
		header("Location: $location");
		exit;
	}

	/* ------------------------------------------------------------------------- */
	/* Files                                                                     */
	/* ------------------------------------------------------------------------- */

	/**
	 * Pass the `$_FILES['name']`.
	 * See [source](https://www.php.net/manual/en/features.file-upload.multiple.php#53240)
	 * @param array $_files
	 * @return array
	 */
	public static function reArrayFiles(array $_files): array
	{
		$files = [];
		$fileCount = count($_files["name"]);
		$fileKeys = array_keys($_files);

		for ($i = 0; $i < $fileCount; $i++) {
			foreach ($fileKeys as $key) {
				$files[$i][$key] = $_files[$key][$i];
			}
		}

		return $files;
	}

	/* -------------------------------------------------------------------------- */
	/* Unavailable functions in older PHP version                                 */
	/* -------------------------------------------------------------------------- */

	/**
	 * @param string $haystack
	 * @param string $needle
	 * @return bool
	 */
	public static function str_starts_with($haystack, $needle)
	{
		//str_starts_with(string $haystack, string $needle): bool

		$strlen_needle = mb_strlen($needle);
		if (mb_substr($haystack, 0, $strlen_needle) == $needle) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $haystack
	 * @param string $needle
	 * @return bool|int
	 */
	public static function str_contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== false;
	}

	/* -------------------------------------------------------------------------- */
	/* Currency                                                                   */
	/* -------------------------------------------------------------------------- */

	/**
	 * @param float $num
	 * @return string
	 */
	public static function formatRupiah(float $num): string
	{
		return number_format($num, 2, ',', '.');
	}

	/**
	 * @param float $num
	 * @return string
	 */
	public static function formatRupiahTanpaKoma(float $num): string
	{
		return number_format($num, 0, ',', '.');
	}

	/* ------------------------------------------------------------------------- */
	/* URLs                                                                      */
	/* ------------------------------------------------------------------------- */

	/**
	 * @return string
	 */
	public static function getCurrentPageName(): string
	{
		return substr(
			$_SERVER["SCRIPT_NAME"],
			strrpos($_SERVER["SCRIPT_NAME"], '/') + 1
		);
	}

	// /**
	//  * @return string
	//  */
	// public static function getFullURL(): string
	// {
	// 	return substr(
	// 		$_SERVER["SCRIPT_NAME"],
	// 		strrpos($_SERVER["SCRIPT_NAME"], '/') + 1
	// 	);
	// }

	/* ------------------------------------------------------------------------- */
	/* Debug                                                                     */
	/* ------------------------------------------------------------------------- */

	public static function prettyVarDump($variable)
	{
		echo "<pre>";
		var_dump($variable);
		echo "</pre>";
	}

	public static function prettyVarExport($variable)
	{
		echo "<pre>";
		var_export($variable);
		echo "</pre>";
	}

	/* ------------------------------------------------------------------------- */
	/* Environment                                                               */
	/* ------------------------------------------------------------------------- */

	public static function initEnv()
	{
		$ENV_CONST_NAME = "ENV";

		if (!defined($ENV_CONST_NAME) && !isset(self::$IS_PRODUCTION)) {
			$isProduction = Util::str_contains(
				$_SERVER["SERVER_NAME"],
				// "thriftstore.jwp-p2bn.site"
				"thriftstore.sispensaitis.my.id"
			);

			define($ENV_CONST_NAME, $isProduction ? "PRODUCTION" : "DEVELOPMENT");
		}
	}
}

Util::initEnv();
