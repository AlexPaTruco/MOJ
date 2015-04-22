<?php

//Functions for UHunt!

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function unameId($username) {
	$html = @file_get_contents('http://uhunt.felix-halim.net/api/uname2uid/' . $username);
	return clean($html);
}

function userRank($id) {
	$html = @file_get_contents('http://uhunt.felix-halim.net/api/ranklist/'. $id . '/0/0');

	return json_decode($html);
}