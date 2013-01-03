<?php
$DS = DIRECTORY_SEPARATOR;
/**
 * Load up the Aura Auto Loader
 *
 * @author Johnathan Pulos
 */
$PHPToolboxDirectory =
    __DIR__ . $DS . ".." .
    $DS . "Vendor" . $DS .
    "PHPToolbox" . $DS .
    "src" . $DS .
    "PHPToolbox" . $DS;
require_once($PHPToolboxDirectory . "CachedRequest" . $DS . "CurlUtility.php");
require_once($PHPToolboxDirectory . "CachedRequest" . $DS . "CachedRequest.php");
require_once("Tests" . $DS . "Support" . $DS . "HelperFunctions.php");
require_once("App" . $DS . "Includes" . $DS . "CommonFunctions.php");
/**
 * preload source files
 *
 * @author Johnathan Pulos
 */
/**
 * autoload test files
 *
 * @package default
 * @author Johnathan Pulos
 */
spl_autoload_register(
    function ($class) {
        $file = dirname(__DIR__). DIRECTORY_SEPARATOR
              . 'tests' . DIRECTORY_SEPARATOR
              . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
);
