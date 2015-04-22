<?php
//functions

function openConnectionTC() {
	mysql_connect("localhost","root","") or die(mysql_error());
	mysql_select_db("kick") or die(mysql_error());
}

function userExistsTC($username) {

	$query = "SELECT username FROM kick_judge_scores WHERE username = '$username'";

	$result = mysql_query($query);

	return mysql_num_rows($result);
}

function updateUserTC($username) {

	if(userExistsTC($username)) {
		$query = "UPDATE kick_judge_scores SET counter = counter + 1 WHERE username = '$username'";
		mysql_query($query);
	}else {
		$query = "INSERT INTO kick_coj (username) VALUES ('')";
		mysql_query($query);
		$coj = mysql_insert_id();
		$query = "INSERT INTO kick_uva (username) VALUES ('')";
		mysql_query($query);
		$uva = mysql_insert_id();
		$query = "INSERT INTO kick_cf (username) VALUES ('')";
		mysql_query($query);
		$cf = mysql_insert_id();
		$query = "INSERT INTO kick_tc (username) VALUES ('')";
		mysql_query($query);
		$tc = mysql_insert_id();
		$query = "INSERT INTO kick_judge_scores (username, coj, uva, cf, tc) VALUES ('$username', '$coj', '$uva', '$cf', '$tc')";
		mysql_query($query);
	}

}

//CodeForces
function updateTC($username, $info) {
	$query = "SELECT tc FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['tc'];

	$query = "UPDATE kick_tc SET username = '".$info->handle."', country = '".$info->country."', earning = '".$info->overallEarning."' WHERE id = '$id'";
	mysql_query($query);
}

function getTC($username) {
	$query = "SELECT tc FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['tc'];

	$query = "SELECT * FROM kick_tc WHERE id = '$id'";

	return mysql_fetch_assoc(mysql_query($query));
}