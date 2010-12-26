<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more   |
// | Authors: Dietrich Ayala <dietrich@ganx4.com> Original Author         |
// +----------------------------------------------------------------------+
//
// $Id: server_Round2Base.php,v 1.1 2010/01/22 18:09:25 acio Exp $
//
require_once 'SOAP/Server.php';
require_once 'interop_Round2Base.php';

$base =& new SOAP_Interop_Base();
$server =& new SOAP_Server;
$server->_auto_translation = true;

$server->addObjectMap($base,'http://soapinterop.org/');
$server->service(isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:NULL);

$test = '<?xml version="1.0" encoding="UTF-8"?>

<SOAP-ENV:Envelope  xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
 xmlns:ns4="http://soapinterop.org/"
 SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>

<ns4:echoStruct>
<inputStruct>
<varString xsi:type="xsd:string">arg</varString>
<varInt xsi:type="xsd:int">34</varInt>
<varFloat xsi:type="xsd:float">325.325</varFloat></inputStruct></ns4:echoStruct>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';
#$server->service($test,'',TRUE);

?>
