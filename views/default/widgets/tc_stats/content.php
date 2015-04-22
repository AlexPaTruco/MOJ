<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$username = $user->tc_username;
$puser = $user->username;

include "functions.php";

$update = false;

if(isset($_POST['partc'])) {
    $info = json_decode($_POST['partc']);
    print_r($info);
    updateUserTC($puser);
    updateTC($puser, $info);
    die();
}

if(strpos($_SERVER['HTTP_REFERER'], 'edit') != false)
{
    $update = true;
    //$_SERVER['HTTP_REFERER'] = "";
}
if(isset($_GET['update']))
{
    if($_GET['update'] == "tc") {
        $update = true;
    }
}

?>
<script>

function updateTC(partc) {
    partc = JSON.stringify(partc);
    //$.post("http://moj.projectace.net/profile/"+ "<?=$puser;?>", { partc: partc}, function(data) {
    //});

    jQuery.ajax({
                type: "POST",
                data:  { partc: partc},

                success: function(data){
                }
                });  
}

function tcUserInfo(username, refresh) {
	$("#tcFetch").show();
	$("#tcPanel").hide();
    $.get("http://api.topcoder.com/rest/statistics/" + username + "?user_key=2bab8f60f3b5123714be6619eb89c4f7", function(data) {

    	if(refresh) {
    		updateTC(data);
    	}

    	var info = JSON.parse(JSON.stringify(data));
        $("#tcUsername").html(info['handle']);
        $("#tcCountry").html(info['country']);
        $("#tcEarning").html(info['overallEarning']);
        $("#tcFetch").hide();
        $("#tcPanel").fadeIn("slow");
    });
}
</script>
<?php
	
	if(!isset($username)) {
	echo "No TopCoder Handle Has Being Set.";
}else {

	if(!$update) {
		$info = getTC($puser);

		echo '<span id="tcFetch" style="display: none;">Fetching TopCoder Data...</span>
		<div id="tcPanel">
		<h4>Profile</h4><hr>
		<strong>Username: </strong><span id="tcUsername">'.$info['username'].'</span><br/>
		<strong>Country: </strong><span id="tcCountry">'.$info['country'].'</span><br/><br/>

		<h4>Ranking</h4><hr>
		<strong>Earning: </strong><span id="tcEarning">'.$info['earning'].'</span><br /><br />
		<a href="#" onclick=\'tcUserInfo('.json_encode($username).', true)\'>Refresh</a>
	</div>';
	}else {?>
	<span id="tcFetch">Fetching TopCoder Data...</span>
 	<div id="tcPanel">
		<h4>Profile</h4><hr>
		<strong>Username: </strong><span id="tcUsername"></span><br/>
		<strong>Country: </strong><span id="tcCountry"></span><br/><br/>

		<h4>Ranking</h4><hr>
		<strong>Earning: </strong><span id="tcEarning"></span><br /><br/>
		<a href="#" onclick='tcUserInfo("<?= $username; ?>", true)'>Refresh</a>
	</div>
<script>tcUserInfo("<?= $username; ?>", true);</script>

	

<?php } } ?>