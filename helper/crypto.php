<?php
/**
 * This file generates password hashes and checks the hashes on the login.
 *
 * author: Christian Högerle
 */

// generates the password hash
function hashPassword($password) {
	return password_hash($password, PASSWORD_BCRYPT);
}

// checks the given password hash
function checkPasswordHash($password, $hash) {
	if(password_verify($password, $hash)) {
		return true;
	} else {
		return false;
	}
}

?>
