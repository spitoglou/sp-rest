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


?>