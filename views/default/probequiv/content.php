<?php

include "functions.php";

$judge_list = getJudgeList();
$err = true;

if(isset($_POST['btnSubmit'])) {
	$judge1 = mysql_real_escape_string($_POST['judge1']);
	$judge2 = mysql_real_escape_string($_POST['judge2']);
	$prob1 = mysql_real_escape_string($_POST['prob1']);
	$prob2 = mysql_real_escape_string($_POST['prob2']);
	$index = null;
	if(isset($_POST['index1']) || isset($_POST['index2'])) {
		if(isset($_POST['index1']) && $_POST['index1'] != "") {
			$index = mysql_real_escape_string($_POST['index1']);
		}else {
			$index = mysql_real_escape_string($_POST['index2']);
		}
	}
	$resp = "lol";
	if(submitReport($vars['username'], $judge1, $prob1, $judge2, $prob2, $resp, $index)) {
		echo "<h3 style='color: green;'>".$resp."</h3>";
		$err = false;
	}else {
		echo "<h3 style='color: red;'>".$resp."</h3>";
	}

}

	if($err) {
?>

<form action="" method="POST">
	<label>Reporting As: </label>
	<span><?= $vars['username']; ?></span>
	<br/><br/>

	<h3>First Problem:</h3><hr>
	<br/>

	<label>Judge: </label><br/>

	<select name="judge1" onchange="changeJudge(this)">
		<?php 

			foreach ($judge_list as $row) {
				echo "<option value='".$row['code']."'>".$row['name']."</option>";
			}

		?>
	</select>

	<br/><br/>

	<label id="lbljudge1">Problem ID:</label>
	<input name="prob1" type="text" placeholder="ex. 1234" /><br/>

	<div id="indexjudge1" style="display: none">
	<label>Index:</label>
	<input name="index1" type="text" placeholder="ex. A" />
</div>
	<br/><br/>
	<h3>Second Problem:</h3><hr>
	<br/>

	<label>Judge: </label><br/>

	<select name="judge2" onchange="changeJudge(this)">
		<?php 

			foreach ($judge_list as $row) {
				echo "<option value='".$row['code']."'>".$row['name']."</option>";
			}

		?>
	</select>

	<br/><br/>

	<label id="lbljudge2">Problem ID:</label>
	<input name="prob2" type="text" placeholder="ex. 1234" /><br/>

	<div id="indexjudge2" style="display: none">
	<label>Index:</label>
	<input name="index2" type="text" placeholder="ex. B" />
</div>
	<br/><br/>

	<input name="btnSubmit" type="submit" class="elgg-button elgg-button-submit" value="submit"/>
</form>
<script type="text/javascript">

function changeJudge(code) {
	if(code.value == 3)
	{
		$("#index" + code.name).show();
		$("#lbl" + code.name).html("Contest ID: ");
	}else {
		$("#index" + code.name).hide();
		$("#lbl" + code.name).html("Problem ID: ");
	}
}
</script>
<?php } ?>
