<?php include "functions.php"; ?>
<?php 

if(isset($_POST['rid'])) {
	$rid = mysql_real_escape_string($_POST['rid']);

	if(submitVote($rid, $vars['username'])) {
		echo "<label style='color: green;'>Vote Submitted!</label>";
	}else {
		echo "<label style='color: red;'>Vote NOT Submitted!</label>";
	}
}

	$row = getEquiv($vars['username']);

foreach($row as $eq) {
	if(!$eq['HiddenFlag']) {
		echo "<form action='' method='POST'>";
		echo "<label>Reported By: ".$eq['username']."</label><hr><br/>";
		echo "<label>Judge 1: ".$eq['judge1']."</label><br/>";
		if($eq['judge1'] == "CodeForces") {
			echo "<label>Contest ID: ".$eq['prob1']."</label><br/>";
			echo "<label>Index: ".$eq['indexs']."</label><br/><br/>";
		}else {
		echo "<label>Problem ID: ".$eq['prob1']."</label><br/><br/>";
	}
		echo "<label>Judge 2: ".$eq['judge2']."</label><br/>";
		if($eq['judge2'] == "CodeForces") {
			echo "<label>Contest ID: ".$eq['prob2']."</label><br/>";
			echo "<label>Index: ".$eq['indexs']."</label><br/><br/>";
		}else {
		echo "<label>Problem ID: ".$eq['prob2']."</label><br/><br/>";
	}
		echo "<input type='hidden' name='rid' value='".$eq['id']."'/>";
		if($eq['voted']) {
			echo "<label style='color: green;'>Already Voted!</label>";
		}else {
			echo "<button type='submit' class='elgg-button elgg-button-submit'>Vote!</button>";
		}
		echo "<br/><label>Number of Votes:".$eq['votes']."</label>";
		echo "<hr><br/>";
		echo "</form>";
	}
}