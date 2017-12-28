<?php
/** 
 * RENAME THIS FILE TO: path.php
 * 
 * Configuration for: Project root directory
 *
 * PROJECT_ROOT: points to the project root dir (is used for the includes in the php files) [do no change]
 * BASE_URL: 	 points to the URL for this project
*/

define("PROJECT_ROOT",  dirname ( __DIR__,1) . DIRECTORY_SEPARATOR);
define("BASE_URL", "http://127.0.0.1/EventManger/");
?>
