<?php
mysql_connect("localhost","fortrick_alex","alex123") or die(mysql_error());
	mysql_select_db("fortrick_kick") or die(mysql_error());

 $info = json_decode($_POST['par']);
 $puser = mysql_real_escape_string($_POST['username']);
    print_r($info);
    updateUserUVA($puser);
    updateUVA($puser, $info);


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