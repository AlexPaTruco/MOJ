<?php
//functions
function openConnectionCF() {
	mysql_connect("localhost","root","") or die(mysql_error());
	mysql_select_db("kick") or die(mysql_error());
}

function userExistsCF($username) {

	$query = "SELECT username FROM kick_judge_scores WHERE username = '$username'";

	$result = mysql_query($query);

	return mysql_num_rows($result);
}

function updateUserCF($username) {

	if(userExistsCF($username)) {
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
function updateCF($username, $info) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "UPDATE kick_cf SET username = '".$info['username']."', rank = '".$info['rank']."', maxRank = '".$info['maxRank']."', rating = '".$info['rating']."', maxRating = '".$info['maxRating']."' WHERE id = '$id'";
	mysql_query($query);
}

function getCF($username) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "SELECT * FROM kick_cf WHERE id = '$id'";

	return mysql_fetch_assoc(mysql_query($query));
}

function isValidCF($username) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "SELECT valid FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return ($row['valid'] == 1);
}

function generateKeyCF($username) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "SELECT secret FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['secret'] == "1") {
		$secret = sha1($username);
		$query = "UPDATE kick_cf SET secret = '$secret' WHERE id = '$id'";
		mysql_query($query);
	}

	$query = "SELECT secret FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return $row['secret'];
}

function validateKeyCF($username, $subject) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "SELECT secret FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if(strpos($subject, substr($row['secret'], 0, 4)) != false)
	{
		$query = "UPDATE kick_cf SET valid = '1', secret = '1' WHERE id = '$id'";
		mysql_query($query);
		return true;
	}else {
		return false;
	}
}

function validateUserCF($username, $cfuser) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['cf'];

	$query = "SELECT username FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['username'] == $cfuser)
	{
		return true;
	}else {
		$query = "UPDATE kick_cf SET valid = '0' WHERE id = '$id'";
		mysql_query($query);
		return false;
	}
}