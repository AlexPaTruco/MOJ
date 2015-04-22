<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$username = $user->cf_username;
$puser = $user->username;

include "functions.php";

openConnectionCF();
$info = array();
$update = false;
$error = false;
$valid = true;

if(strpos($_SERVER['HTTP_REFERER'], 'edit') != false)
{
    $update = true;
    //$_SERVER['HTTP_REFERER'] = "";
}
if(isset($_GET['update']))
{
    if($_GET['update'] == "cf") {
        $update = true;
    }
}

if($username == "")
{
	echo "No CodeForces Handle Has Being Set.";
}else {

	if(!userExistsCF($puser) || $update)
	{
		include "cf.php";
		//print_r(userInfo($username));

		$prof = userInfo($username);

		//print_r($prof);
		if(empty($prof))
		{
			echo "Username ".$username.", was NOT found in CodeForces!";
			$error = true;
		}else {
		//print_r($prof);
		$info['username'] = $prof->result[0]->handle;
		$info['rank'] = $prof->result[0]->rank;
		$info['maxRank'] = $prof->result[0]->maxRank;
		$info['rating'] = $prof->result[0]->rating;
		$info['maxRating'] = $prof->result[0]->maxRating;

		if(isset($prof->result[0]->firstName)) {
			$firstname = $prof->result[0]->firstName;
		}

		validateUserCF($puser, $username);
        if(isValidCF($puser)) {
            $valid = true;
        }else {
            $valid = validateKeyCF($puser, $firstname);
        }
        
		updateUserCF($puser);
		updateCF($puser,$info);
	}
		
	}else {
		$info = getCF($puser);
		validateUserCF($puser, $username);
        if(isValidCF($puser)) {
            $valid = true;
        }else {
            $valid = false;
        }

		if($info['username'] == "")
		{
			echo "Username ".$username.", was NOT found in CodeForces";
			$error = true;
		}
	}

	if($valid) {
		if(!$error) {
			echo "<h4>Profile</h4><hr>";
			echo "Username: ".$info['username']."<br /><br />";

			echo "<h4>Ranking</h4><hr>";
			echo "Rank: ".$info['rank']."<br />";
			echo "Max Rank: ".$info['maxRank']."<br />";
			echo "Rating: ".$info['rating']."<br />";
			echo "Max Rating: ".$info['maxRating']."<br />";
		}
	}else {
		echo "Paste this key in your firstname: ".substr(generateKeyCF($puser), 0, 4);
	}

	echo "<br /><a href='?update=cf'>Refresh</a>";
}
