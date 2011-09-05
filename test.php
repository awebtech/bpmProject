<?php

	define('CONSOLE_MODE', true);

	require realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'index.php';
			
	/*$milestone = ProjectMilestones::findById(8);
	
	$wso = new MilestoneWso($milestone);
	
	$wso = $wso->getWsoState();
	
	print_r($wso);*/
	
	$user = Users::findById('3');
	
	CompanyWebsite::instance()->logUserIn($user);
	
	//print_r($user);
	
	$wso = new stdClass();
	
	$wso->username = $user->getUsername();
	$wso->url = $user->getAccountUrl();
	$wso->title = $user->getTitle();
	$wso->display_name = $user->getDisplayName();
	$wso->email = $user->getEmail();
	
	print_r($wso);

?>