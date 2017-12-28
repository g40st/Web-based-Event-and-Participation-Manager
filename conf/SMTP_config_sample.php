<?php
/** 
 * RENAME THIS FILE TO: SMTP_config.php
 *
 * Configuration for: SMTP
 *
 * SMTP_DEBUG: 		enable verbose debug output (in production use "0")
 * SMTP_HOST: 		specify main and backup SMTP servers
 * SMTP_USERNAME: 	SMTP username
 * SMTP_PASSWORD: 	SMTP password
 * SMTP_SECURE: 	enable TLS encryption, `ssl` also accepted
 * SMTP_PORT: 		TCP port to connect to
 *
 * ADMIN_EMAIL: specify the mail address of the admin
*/

define("SMTP_DEBUG", 0);
define("SMTP_HOST", "smtp.example.com");
define("SMTP_USERNAME", "example@example.com");
define("SMTP_PASSWORD", "password");
define("SMTP_SECURE", "tls");
define("SMTP_PORT", 587);

define("ADMIN_EMAIL", 'admin@example.com');

?>
