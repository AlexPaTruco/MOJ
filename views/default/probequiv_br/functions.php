<?php
/*
 *
 * Functions for browsing problem equivalance
 *
 */

function getEquiv($username) {

	$query = "SELECT * FROM kick_probequiv";
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result)) {
		$eq[] = $row;
	}

	$query = "SELECT * FROM kick_judges";
	$result = mysql_query($query);
	$judges = array();

	while($row = mysql_fetch_assoc($result)) {
		$code = $row['code'];
		$judges[$code] = $row['name'];
	}

	$query = "SELECT probequivid FROM kick_probequv_relation WHERE username = '$username'";
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result)) {
		$votes[] = $row['probequivid'];
	}

	foreach($eq as $x) {
		if(in_array($x['id'], $votes)) {
			$x['voted'] = true;
		}else {
			$x['voted'] = false;
		}

		$code1 = $x['judge1'];
		$code2 = $x['judge2'];
		$x['judge1'] = $judges[$code1];
		$x['judge2'] = $judges[$code2];
		$resp[] = $x;
	}

	return $resp;

}

function submitVote($rid, $username) {
	$query = "INSERT INTO kick_probequv_relation (probequivid, username) VALUES ('$rid', '$username')";
	$result = mysql_query($query);

	if($result) {
		$query = "UPDATE kick_probequiv SET votes = votes + 1 WHERE id = '$rid'";
		$result = mysql_query($query);

		$query = "SELECT votes FROM kick_probequiv WHERE id = '$rid'";
		$result = mysql_query($query);

		$row = mysql_fetch_assoc($result);

		if($row['votes'] >= 3) {
			$query = "UPDATE kick_probequiv SET HiddenFlag = 1 WHERE id = '$rid'";
			$result = mysql_query($query);
		}
		return true;
	}

	return false;
}