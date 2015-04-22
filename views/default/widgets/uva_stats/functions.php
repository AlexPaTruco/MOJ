<?php
//functions

function openConnectionUVA() {
	mysql_connect("localhost","root","") or die(mysql_error());
	mysql_select_db("kick") or die(mysql_error());
}

function userExistsUVA($username) {

	$query = "SELECT username FROM kick_judge_scores WHERE username = '$username'";

	$result = mysql_query($query);

	return mysql_num_rows($result);
}

function updateUserUVA($username) {

	if(userExistsUVA($username)) {
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
function updateUVA($username, $info) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "UPDATE kick_uva SET username = '".$info[0]->username."', rank = '".$info[0]->rank."', name = '".$info[0]->name."', nos = '".$info[0]->nos."', ac = '".$info[0]->ac."' WHERE id = '$id'";
	mysql_query($query);
}

function getUVA($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "SELECT * FROM kick_uva WHERE id = '$id'";

	return mysql_fetch_assoc(mysql_query($query));
}

function isValidUVA($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "SELECT valid FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return ($row['valid'] == 1);
}

function generateKeyUVA($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "SELECT secret FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['secret'] == "1") {
		$secret = sha1($username);
		$query = "UPDATE kick_uva SET secret = '$secret' WHERE id = '$id'";
		mysql_query($query);
	}

	$query = "SELECT secret FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	return $row['secret'];
}

function validateKeyUVA($username, $subject) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "SELECT secret FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//die("Subject: " . $subject . ", Secret: " .  substr($row['secret'], 0, 4));
	if(strpos($subject, substr($row['secret'], 0, 4)) != false)
	{
		$query = "UPDATE kick_uva SET valid = '1', secret = '1' WHERE id = '$id'";
		mysql_query($query);
		return true;
	}else {
		return false;
	}
}

function validateUserUVA($username, $cfuser) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$id = $row['uva'];

	$query = "SELECT username FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	if($row['username'] == $cfuser)
	{
		return true;
	}else {
		$query = "UPDATE kick_uva SET valid = '0' WHERE id = '$id'";
		mysql_query($query);
		return false;
	}
}

function getScoreUVA($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['uva'];

	$query = "SELECT ac FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		$score += $row['ac'] * 0.4;
	}

	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$id = $row['coj'];

	$query = "SELECT score FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//COJ
	if(!empty($row)) {
		$score += $row['score'];
	}

	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$id = $row['cf'];

	$query = "SELECT rating FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//CodeForces
	if(!empty($row)) {
		$score += $row['rating'] * 0.0017;
	}

	return $score;
}