<?php

/**
 * wsdl.php
 */

$path="localhost:81/bbsp-rest";

// custom class dir
define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();

require_once "include/collections.inc";
header('Content-Type:text/xml');

echo "<?xml version='1.0' encoding='UTF-8' ?>
<definitions name='CSSA'
targetNamespace='urn:CSWebServices'
xmlns:tns='urn:CSWebServices'
xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
xmlns:xsd='http://www.w3.org/2001/XMLSchema'
xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
xmlns='http://schemas.xmlsoap.org/wsdl/'>";

foreach ($collections as $key => $value) {
    echo "<message name='{$key}Request'>
    <part name='args' type='xsd:string'/>
</message>
<message name='{$key}Response'>
    <part name='Result' type='xsd:string'/>
</message>";
}

echo "<portType name='CSSAPortType'>";

foreach ($collections as $key => $value) {
    echo "<operation name='{$key}'>
    <input message='tns:{$key}Request'/>
    <output message='tns:{$key}Response'/>
</operation>";
}

echo "</portType>";

echo "<binding name='CSSABinding' type='tns:CSSAPortType'>
<soap:binding style='rpc'
transport='http://schemas.xmlsoap.org/soap/http'/>";

foreach ($collections as $key => $value) {
    echo "<operation name='{$key}'>
    <soap:operation soapAction='urn:xmethods-delayed-quotes#{$key}'/>
    <input>
    <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes'
    encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
</input>
<output>
    <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes'
    encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
</output>
</operation>";
}

echo "</binding>
<service name='CSSAService'>
    <port name='CSSAPort' binding='CSSABinding'>
        <soap:address location='http://{$path}/soapserver.php'/>
    </port>
</service>
</definitions>";