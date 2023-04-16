<?php
    // Turn on output buffering
    ob_start();
    // Set the timezone to Asia/Manila
    ini_set('date.timezone','Asia/Manila');
    date_default_timezone_set('Asia/Manila');
    // Start the PHP session
    session_start();

    // Require the initialize.php file (which presumably sets up some other constants and functions)
    require_once('initialize.php');
    // Require the DBConnection and SystemSettings classes
    require_once('classes/DBConnection.php');
    require_once('classes/SystemSettings.php');

    // Create a new DBConnection object and store the connection in the $conn variable
    $db = new DBConnection;
    $conn = $db->conn;

    // Define a function to redirect to a given URL
    function redirect($url=''){
        if(!empty($url))
            echo '<script>location.href="'.base_url .$url.'"</script>';
    }

    // Define a function to validate an image file and return its URL, or return the default logo if the file is invalid
    function validate_image($file){
        global $_settings;
        if(!empty($file)){
            // Split the file string into the path and query string
            $ex = explode("?",$file);
            $file = $ex[0];
            // If there is a query string, add it back to the file string
            $ts = isset($ex[1]) ? "?".$ex[1] : '';
            // If the file exists, return its URL with the query string, otherwise return the default logo URL
            if(is_file(base_app.$file)){
                return base_url.$file.$ts;
            }else{
                return base_url.($_settings->info('logo'));
            }
        }else{
            return base_url.($_settings->info('logo'));
        }
    }

    // Define a function to format a number with a given number of decimal places
    function format_num($number = '' , $decimal = ''){
        // Check if the input is numeric
        if(is_numeric($number)){
            // Split the number into its integer and decimal parts
            $ex = explode(".",$number);
            // If there is a decimal part and it is not zero, set $decLen to the number of decimal places, otherwise set it to zero
            $decLen = isset($ex[1]) && abs($ex[1]) != 0 ? strlen($ex[1]) : 0;
            // If a decimal argument was passed, format the number with that number of decimal places, otherwise format it with $decLen decimal places
            if(is_numeric($decimal)){
                return number_format($number,$decimal);
            }else{
                return number_format($number,$decLen);
            }
        }else{
            // If the input is not numeric, return an error message
            return "Invalid Input";
        }
    }

    // This function checks if the device accessing the website is a mobile device or not
    function isMobileDevice(){
        // Array of regular expressions to match against the HTTP_USER_AGENT server variable
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );

        // Loop through the array of regular expressions and check if any match the HTTP_USER_AGENT
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){ // If there is a match,
                return true; // return true, indicating that the device is a mobile device
            }
        }

        // If none of the regular expressions match the HTTP_USER_AGENT, then the device is not a mobile device
        return false; 
    }

    // End output buffering and flush system output buffer
    ob_end_flush();
?>