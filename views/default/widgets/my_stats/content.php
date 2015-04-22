<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$puser = $user->username;

include "functions.php";

if($_GET['update'] == "stats") {
	$score = getScore($puser, true);
} else {
	$score = getScore($puser, false);
}

$coj = getCOJScore($puser);
$uva = getUVAScore($puser);
$cf = getCFScore($puser);
$tc = getTCScore($puser);

echo "<h4>My Scores By Judge: </h4><hr>";
echo "COJ: ".$coj."<br/>";
echo "UVA: ".$uva."<br/>";
echo "CodeForces: ".$cf."<br/>";
echo "TopCoder: ".$tc."<br/>";
if($score['equiv'] != 0) {
	echo "<span style='color: red; font-weight: bold;'>Equivalances: ".$score['equiv']."</span><br/>";
}
echo "<hr>";

echo "<strong>Final Score: </strong>".$score['score']."<br/><br/>";

echo "<h4>Known Accepted Problems: </h4><hr>";
echo "<strong>Total: </strong>".(getCOJac($puser) + getUVAac($puser));
echo "<br/><br/><a href='?update=stats'>Refresh</a>";