<?php

//Functions for UHunt!

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function tcProfile($username) {
	$html = @file_get_contents('http://api.topcoder.com/rest/statistics/'.$username.'?user_key=2bab8f60f3b5123714be6619eb89c4f7');
	return json_decode($html);
}