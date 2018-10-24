<?php

if (isset($_POST['submit'])){
	session_start(); //we need to have the session running before we can end it
	session_unset(); //unsets all the session variables in the browser
	session_destroy(); //destroys any sessions in the browser
	header("Location: https://www.gogobin.com?logged-out");
	exit();
}
