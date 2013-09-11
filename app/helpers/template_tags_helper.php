<?php
/**
 * Checks if the current page is the homepage
 */
function is_home()
{
	if($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] != substr(site_url('/'), 7))
		return true;
	else return false;
}
