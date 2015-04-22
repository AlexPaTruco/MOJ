<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$username = $user->cf_username;

if(!isset($username)) {
	echo "No CodeForces Handle Has Being Set.";
}else {

?>
<script>
function cfUserInfo(username) {
    $.get("http://codeforces.com/api/user.info?handles=" + username, function(data) {
        var info = JSON.parse(JSON.stringify(data));
        $("#cfUsername").append(info['result'][0]['handle']);
        $("#cfRanking").append(info['result'][0]['rank']);
        $("#cfMaxRank").append(info['result'][0]['maxRank']);
        $("#cfRating").append(info['result'][0]['rating']);
        $("#cfMaxRating").append(info['result'][0]['maxRating']);
        $("#cfFetch").hide();
        $("#cfPanel").fadeIn("slow");
    });
}

	cfUserInfo("<?= $username; ?>");
</script>

	<span id="cfFetch">Fetching CodeForces Data...</span>
	<div id="cfPanel">
		<h4>Profile</h4><hr>
		<span id="cfUsername"><strong>Username: </strong></span><br /><br />

		<h4>Ranking</h4><hr>
		<span id="cfRanking"><strong>Rank: </strong></span><br />
		<span id="cfMaxRank"><strong>Max Rank: </strong></span><br />
		<span id="cfRating"><strong>Rating: </strong></span><br />
		<span id="cfMaxRating"><strong>Max Rating: </strong></span><br /><br />
	</div>

<?php } ?>
