<?php

    /**
    * gets the http headers of a client request (Idea and core code: http://www.binarytides.com/get-http-request-headers-in-php/)
    * 
    * @param mixed $q Specific http header
    * @return array 
    */

    function getheaders($q) 
    {
        foreach($_SERVER as $name => $value)
        {
            if(substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        if (!$q) {
            return $headers;
        } else {
            return $headers[$q];
        }
    }

    /**
    * echoes all http headers
    * 
    */
    function dump_headers() {
        foreach (getheaders() as $name => $value) 
        {
            echo "$name: $value\n";

        }
    }


    function validate($args) {
        global $collections;
        foreach ($args as $name=>$value) {
            if (mysql_escape_string($value)<>$value){ 
                return mysql_escape_string($value)."\n$value\nYou are trying to mess with me by injecting sql mothafucka?";
            }
        }
        /*if (!array_search($args[0],$collections))   {
            return "$args[0] not a valid collection";
        } */
        if (count($args)>1 and !is_numeric($args[1])) {
            return "$args[1] not a valid index";
        }
        return "OK";
    }

?>