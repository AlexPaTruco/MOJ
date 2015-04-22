<?php

include('simple_html_dom.php');

function getScore($username, $refresh) {
	$usUVA = "";
	$usCOJ = "";
	$usCF = "";
	$equivalance = 0;

	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['uva'];

	$query = "SELECT username, ac FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		$score += $row['ac'] * 0.4;
		$usUVA = $row['username'];
	}

	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$id = $row['coj'];

	$query = "SELECT username, score FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//COJ
	if(!empty($row)) {
		$score += $row['score'];
		$usCOJ = $row['username'];
	}

	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$id = $row['cf'];

	$query = "SELECT username, rating FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//CodeForces
	if(!empty($row)) {
		$score += $row['rating'] * 0.0017;
		$usCF = $row['username'];
	}

	$query = "SELECT * FROM kick_myscore WHERE username = '$username'";
	$result = mysql_query($query);

	if(!mysql_num_rows($result)) {
		$query = "INSERT INTO kick_myscore (username, score) VALUES ('$username', '$score')";
		$result = mysql_query($query);
	}

	$query = "UPDATE kick_myscore SET score = '$score' WHERE username = '$username'";
	$result = mysql_query($query);

	if($refresh) {
		$query = "SELECT * FROM kick_probequiv WHERE HiddenFlag = 1";
		$result = mysql_query($query);

		while($row = mysql_fetch_assoc($result)) {
			$probs[] = $row;
		}

		if($usCOJ != "") {
			$list = checkProbCOJ($usCOJ);
		}

		//die(print_r($probs));
		foreach($probs as $prob) {
			if(checkProbs($prob['judge1'], $prob['prob1'], $list, $usUVA, $prob['indexs'], $usCF) && checkProbs($prob['judge2'], $prob['prob2'], $list, $usUVA, $prob['indexs'], $usCF)) {
				switch($prob['judge1']) {
					case 1:
						//$score--;
						$equivalance--;
						break;
					case 2:
						//$score -= 0.4;
						$equivalance -= 0.4;
						break;
					case 3:
						//$score -= 0.0017;
						$equivalance -= 0.0017;
						break;
					default:
					break;
				}
			}
		}
		$query = "UPDATE kick_myscore SET equiv = '$equivalance' WHERE username = '$username'";
		$result = mysql_query($query);

	}


				$query = "SELECT * FROM kick_myscore WHERE username = '$username'";
				$result = mysql_query($query);

				$row = mysql_fetch_assoc($result);

				$score = $score + $row['equiv'];
				$equivalance = $row['equiv'];


	$a['score'] = $score;
	$a['equiv'] = $equivalance;
	return $a;
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function checkProbs($code, $probid, $list, $usUVA, $index, $usCF) {
	switch ($code) {
		case 1:
			if (strpos($list,$probid) !== false) {
   			 return true;
			}else {
				return false;
			}
			break;
		case 2:
			return checkProbUVA($usUVA, $probid);
			break;
		case 3:
			return checkProbCF($usCF, $probid, $index);
		default:
			return false;
			break;
	}
}

function unameId($username) {
	$html = @file_get_contents('http://uhunt.felix-halim.net/api/uname2uid/' . $username);
	return clean($html);
}

function checkProbUVA($username, $probid) {

	$username = unameId($username);
	$html = @file_get_contents('http://uhunt.felix-halim.net/api/p/ranklist/'.$probid.'/'.$username.'/0/0');

	$html = json_decode($html);

	if($html[0]->uid == $username) {
		return true;
	}else {
		return false;
	}
}

function checkProbCF($username, $probid, $index) {
	//die($username . " " . $probid . " " . $index);
	$html = @file_get_contents('http://codeforces.com/api/user.status?handle='.$username.'&from=1') or $html = null;

   $probs = json_decode($html);
   //die(print_r($probs));

   foreach ($probs->result as $prob) {
   		if($prob->problem->contestId == $probid && $prob->problem->index == $index && $prob->verdict == "OK") {
   			return true;
   		}
   }
   return false;
}

function checkProbCOJ($username) {

	$html = @file_get_html('http://coj.uci.cu/user/useraccount.xhtml?username=' . $username) or $html = null;

	 foreach ($html->find('div[id=probsACC]') as $e) {
	 			$text = $e;
            }

   return $text;
}

function getUVAScore($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['uva'];

	$query = "SELECT ac FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return ($row['ac'] * 0.4);
	}

	return 0;

}

function getCOJScore($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['coj'];

	$query = "SELECT score FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return ($row['score']);
	}

	return 0;

}

function getCFScore($username) {
	$query = "SELECT cf FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['cf'];

	$query = "SELECT rating FROM kick_cf WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return ($row['rating'] * 0.0017);
	}

	return 0;

}

function getTCScore($username) {
	$query = "SELECT tc FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['tc'];

	$query = "SELECT earning FROM kick_tc WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return ($row['earning']);
	}

	return 0;

}

function getCOJac($username) {
	$query = "SELECT coj FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['coj'];

	$query = "SELECT ac FROM kick_coj WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return (strip_tags($row['ac']));
	}

	return 0;
}

function getUVAac($username) {
	$query = "SELECT uva FROM kick_judge_scores WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$score = 0;
	$id = $row['uva'];

	$query = "SELECT ac FROM kick_uva WHERE id = '$id'";

	$row = mysql_fetch_assoc(mysql_query($query));

	//UVA
	if(!empty($row)) {
		return $row['ac'];
	}

	return 0;
}