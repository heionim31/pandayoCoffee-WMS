<?php 
// Check if the session has already started, and if not, start a new session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine whether the connection is using HTTPS or HTTP and assign the corresponding protocol to the $link variable
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 

// Concatenate the protocol, server name, and current request URI into the $link variable, creating a complete URL
$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI'];

// Check if the userdata session variable is not set and the request URI does not contain login.php. If so, redirect the user to the admin/login.php page.
if(!isset($_SESSION['userdata']) && !strpos($link, 'login.php')){
	redirect('admin/login.php');
}

// Check if the userdata session variable is set and the request URI contains login.php. If so, redirect the user to the admin/index.php page.
if(isset($_SESSION['userdata']) && strpos($link, 'login.php')){
	redirect('admin/index.php');
}

// Set up an array $module that contains the possible login types. Check if the userdata session variable is set, the request URI contains index.php or admin/, and the login_type value of userdata is not 1. If so, display an alert message to the user and redirect them to the appropriate page based on their login type. Use the exit statement to prevent further code execution.
$module = array('','warehouse_manager','warehouse_staff');
if(isset($_SESSION['userdata']) && (strpos($link, 'index.php') || strpos($link, 'admin/')) && $_SESSION['userdata']['login_role'] !=  'warehouse_manager'){
	echo "<script>alert('Access Denied!');location.replace('".base_url.$module[$_SESSION['userdata']['login_role']]."');</script>";
    exit;
}
