<?php

	define('CONSOLE_MODE', true);

	require realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'index.php';
	
	$milestone = ProjectMilestones::findById(8);
	
	$wso = new MilestoneWso($milestone);
	
	$wso = $wso->getWsoState();
	
	print_r($wso);

?>