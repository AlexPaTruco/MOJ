<?php
/*
 *
 * Function for problem equivalance
 *
 */

function getJudgeList() {
	$query = "SELECT * FROM kick_judges";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_assoc($result)) {
		$resp[] = $row;
	}

	return $resp;
}

function submitReport($username, $judge1, $prob1, $judge2, $prob2, &$resp, $index) {

	if($prob1 && $prob2) {

		$query = "SELECT id FROM kick_probequiv WHERE judge1 = '$judge1' AND judge2 = '$judge2' AND prob1 = '$prob1' AND prob2 = '$prob2'";
		$result = mysql_query($query);

		if(!mysql_num_rows($result)) {

			$query = "INSERT INTO kick_probequiv (username, judge1, judge2, prob1, prob2, indexs) ";
			$query .= "VALUES ('$username', '$judge1', '$judge2', '$prob1', '$prob2', '$index')";
			$result = mysql_query($query);

			if($result) {
				$resp = "Problem Reported!";
				return true;
			}else {
				$resp = "Something Whent Wrong! :(";
				$resp = mysql_error();
				return false;
			}

		}else {
			$resp = "This Report Has Already Being Reported!";
			return false;
		}

	}else {
		$resp = "Please fill both problems id's!";
		return false;
	}
}