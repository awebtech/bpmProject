<?php
header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<definitions name="InteropTest" targetNamespace="http://soapinterop.org/" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://soapinterop.org/" xmlns:s="http://soapinterop.org/xsd" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">

	<import location="http://www.whitemesa.com/interop/InteropTest.wsdl" namespace="http://soapinterop.org/"/>
	<import location="http://www.whitemesa.com/interop/InteropTest.wsdl" namespace="http://soapinterop.org/xsd"/>
	<import location="http://www.whitemesa.com/interop/InteropTestC.wsdl" namespace="http://soapinterop.org/"/>

	<service name="interopLabEchoHeader">

  		<port name="interopPortEchoHdr" binding="tns:InteropEchoHeaderBinding">
			<soap:address location="http://<?php echo $_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"];?>/soap_interop/server_Round2GroupC.php"/>
  		</port>

	</service>

</definitions>
