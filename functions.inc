<?php
    /**
    The following function gets the http headers of a client request
    when php is running as CGI
    Found it here: http://www.binarytides.com/get-http-request-headers-in-php/
    */
    if(!function_exists('getallheaders'))
    {
        function getallheaders() 
        {
            foreach($_SERVER as $name => $value)
            {
                if(substr($name, 0, 5) == 'HTTP_')
                {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }

    function dump_headers() {
        foreach (getallheaders() as $name => $value) 
        {
            echo "$name: $value\n";

        }
    }
?>