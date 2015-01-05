<?php

class Conf
{
    private $_file; 
    private $_xml; 
    private $_lastMatch; 

    function __construct($file) 
    { 
        $this->_file = $file; 
        $this->_xml = simplexml_load_file($file); 
    } 

    function write() 
    { 
        file_put_contents($this->_file, $this->_xml->asXML()); 
    } 

    function get($str, $section='conf') 
    { 
        $matches = $this->_xml->xpath("/{$section}/item[@name=\"$str\"]"); 
        if ( count($matches) ) { 
            $this->_lastMatch = $matches[0]; 
            return (string)$matches[0]; 
        } 
        return null; 
    }

    function set($key, $value) 
    { 
        if ( ! is_null($this->get($key)) ) { 
            $this->_lastMatch[0]=$value; 
            return;
        } 
        $conf = $this->_xml->conf; 
        $this->_xml->addChild('item', $value)->addAttribute('name', $key); 
    } 
} 

/*
The Confclass uses the SimpleXmlextension to access name value pairs.
 Here’s the kind of format 
with which it is designed to work: 
<?xml version="1.0"?> 
<conf> 
    <item name="user">bob</item> 
    <item name="pass">newpass</item> 
    <item name="host">localhost</item> 
</conf> 
*/