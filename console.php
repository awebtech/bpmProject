<?php
chdir(dirname(__FILE__));
define("CONSOLE_MODE", true);
define('PUBLIC_FOLDER', 'public');
include "init.php";

function err($msg) {
	fwrite(STDOUT, "ERROR: " . $msg . "\n");
	die(1);
}

function console_create_user($args) {
	$fname = array_shift($args);
	$lname = array_shift($args);
	$email = array_shift($args);
	$admin = array_shift($args) == 'true';
	if (is_null($fname) || is_null($lname) || is_null($email)) {
		throw new Exception('create_user: Missing arguments. Expected: (fname, lname, email, admin)');
	}
	$display_name = $fname . " " . $lname;
	$username = str_replace(" ", "_", strtolower($display_name));
	$user_data = array(
		'username' => $username,
		'display_name' => $display_name,
		'email' => $email,
		'password_generator' => 'random',
		'timezone' => 0,
		'autodetect_time_zone' => 1,
		'create_contact' => false,
		'company_id' => owner_company()->getId(),
		'send_email_notification' => true,
		'personal_project' => 0,
	); // array
	try {
		DB::beginWork();
		$user = create_user($user_data, $admin, '');
		if (!$user->getContact() instanceof Contact) {
			$contact = new Contact();
			$contact->setFirstName($fname);
			$contact->setLastName($lname);
			$contact->setEmail($email);
			$contact->setUserId($user->getId());
			$contact->save();
		}
		DB::commit();
	} catch (Exception $e) {
		DB::rollback();
		throw $e;
	}
}

session_commit(); // we don't need sessions
@set_time_limit(0); // don't limit execution if possible

if(!isset($argv) || !is_array($argv)) {
	err('There is no input arguments');
} // if

array_shift($argv);
$function = "console_" . array_shift($argv);
if ($function && function_exists($function)) {
	try {
		$function($argv);
	} catch (Exception $e) {
		err($e->getMessage());
	}
} else {
	err('Function inexistent');
}

?>