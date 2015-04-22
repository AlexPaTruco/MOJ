<?php
//functions
function openConnection() {
	mysql_connect("localhost","root","") or die(mysql_error());
	mysql_select_db("kick") or die(mysql_error());
}

function userExists($username) {

	$query = "SELECT username FROM kick_judge_scores WHERE username = '$username'";

	$result = mysql_query($query);

	return mysql_num_rows($result);
}

function updateUser($username) {

	if(userExists($username)) {
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

function updateCOJ($username, $info) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "UPDATE kick_coj SET username = '".$info['username']."', name = '".$info['name']."', globalRank = '".$info['globalRank']."', countryRank = '".$info['countryRank']."', score = '".$info['score']."', ac = '".$info['ac']."', ce = '".$info['ce']."', pe = '".$info['pe']."', wa = '".$info['wa']."' WHERE id = '$id'";
	mysql_query($query);
}

function cojExists($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT username FROM kick_coj WHERE id = '$id'";
	$result = mysql_query($query);

	$row = mysql_fetch_assoc($result);

	return $row['username'] != "";
}

function getLastSync($username) {
	$query = "SELECT lastSync FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	return $row['lastSync'];
}

function getCOJ($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT * FROM kick_coj WHERE id = '$id'";

	return mysql_fetch_assoc(mysql_query($query));
}

function isValidCOJ($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT valid FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return ($row['valid'] == 1);
}

function generateKeyCOJ($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT secret FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['secret'] == "1") {
		$secret = sha1($username);
		$query = "UPDATE kick_coj SET secret = '$secret' WHERE id = '$id'";
		mysql_query($query);
	}

	$query = "SELECT secret FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return $row['secret'];
}

function validateKeyCOJ($username, $subject) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT secret FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if(strpos($subject, substr($row['secret'], 0, 4)) != false)
	{
		$query = "UPDATE kick_coj SET valid = '1', secret = '1' WHERE id = '$id'";
		mysql_query($query);
		return true;
	}else {
		return false;
	}
}

function validateUserCOJ($username, $cojuser) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['coj'];

	$query = "SELECT username FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['username'] == $cojuser)
	{
		return true;
	}else {
		$query = "UPDATE kick_coj SET valid = '0' WHERE id = '$id'";
		mysql_query($query);
		return false;
	}
}