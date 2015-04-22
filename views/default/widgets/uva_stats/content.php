<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$username = $user->uva_username;

$puser = $user->username;

if($username == "") {
    echo "No UVA username has being set.";
}else {

include "functions.php";

$user->kick_score = getScoreUVA($puser);

$update = false;
$valid = true;

if(isset($_POST['par'])) {
    $info = json_decode($_POST['par']);
    echo  "helllooo";
    print_r($info);
    updateUserUVA($puser);
    updateUVA($puser, $info);
    die();
}

if(strpos($_SERVER['HTTP_REFERER'], 'edit') != false)
{
    $update = true;
    $_SERVER['HTTP_REFERER'] = "";
}
if(isset($_GET['update']))
{
    if($_GET['update'] == "uva") {
        $update = true;
    }
}?>

<script>

function updateUVA(par) {
    par = JSON.stringify(par);
    //alert(par);
    $.post("", { par: par, username: "<?= $puser; ?>"}, function(data) {
        //alert(data);
    });
    //alert("<?=elgg_get_plugins_path();?>");
    //jQuery.ajax({
                //type: "POST",
                //data:  { par: par, username: "<?= $puser; ?>"},

                //success: function(data){
                    //alert(data);
                //}
                //});  
}

function specificProblemById(pid) {
    $.post("http://uhunt.felix-halim.net/api/p/id/" + pid, function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function specificProblemByNumber(pnum) {
    $.post("http://uhunt.felix-halim.net/api/p/num/" + pnum, function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function unameToUid(uname, refresh) {
    $.post("http://uhunt.felix-halim.net/api/uname2uid/" + uname, function(data) {
        userRank(data, refresh);
        //alert(data);
    });
}

function userLastSubs(id) {
    $.post("http://uhunt.felix-halim.net/api/subs-user-last/" + id + "/10", function(data) {
        $("#result").html(JSON.stringify(data));
    });
}

function userRank(id, refresh) {
    $("#UvaFetch").show();
        $("#UvaPanel").hide("slow");
    $.post("http://uhunt.felix-halim.net/api/ranklist/" + id + "/0/0", function(data) {

        if(refresh) {
            updateUVA(data);
        }

        //alert(JSON.stringify(data));
        var info = JSON.parse(JSON.stringify(data));
        $("#UvaUsername").html(info[0]['username']);
        $("#Uvaname").html(info[0]['name']);
        $("#UvaRanking").html(info[0]['rank']);
        $("#UvaSubs").html(info[0]['nos']);
        $("#UvaAc").html(info[0]['ac']);
        $("#UvaFetch").hide();
        $("#UvaPanel").fadeIn("slow");
    });
}

    //unameToUid("<?= $username; ?>", false);

</script>

<?php

if(!isset($username)) {
	echo "No UVA username has being set.";
}else {

    if(!$update) {
        $info = getUVA($puser);

        validateUserUVA($puser, $username);
        if(isValidUVA($puser)) {
            $valid = true;
        }else {
            $valid = validateKeyUVA($puser, $info['name']);
        }

        if($valid) {
        echo '  
        <span id="UvaFetch" style="display: none;">Fetching UVA Data...</span>
        <div id="UvaPanel">
    <h4>Profile</h4><hr>
    <strong>Username: </strong> <span id="UvaUsername">'.$info['username'].'</span><br />
    <strong>Name: </strong> <span id="Uvaname">'.$info['name'].'</span><br /><br />

    <h4>Ranking</h4><hr>
    <strong>World Ranking: </strong> <span id="UvaRanking">'.$info['rank'].'</span><br /><br />

    <h4>Stats</h4><hr>
    <strong>Number of Submissions: </strong> <span id="UvaSubs">'.$info['nos'].'</span><br />
    <strong>Accepted: </strong> <span id="UvaAc">'.$info['ac'].'</span><br/><br/>
    <a href="#" onclick=\'unameToUid('.json_encode($username).', true)\'>Refresh</a>';
}else {
    echo '<script> unameToUid('.json_encode($username).', true); </script>';
    echo "Paste this key in your firstname: ".substr(generateKeyUVA($puser), 0, 4) ."<br/>";
    echo '<a href="#" onclick=\'unameToUid('.json_encode($username).', true)\'>Refresh</a>';
}
    }else {  

        $info = getUVA($puser);

        validateUserUVA($puser, $username);
        if(isValidUVA($puser)) {
            $valid = true;
        }else {
            $valid = validateKeyUVA($puser, $info['name']);
        }

        if($valid) {?>
    <span id="UvaFetch">Fetching UVA Data...</span>
<div id="UvaPanel">
    <h4>Profile</h4><hr>
    <strong>Username: </strong> <span id="UvaUsername"></span><br />
    <strong>Name: </strong> <span id="Uvaname"></span><br /><br />

    <h4>Ranking</h4><hr>
    <strong>World Ranking: </strong> <span id="UvaRanking"></span><br /><br />

    <h4>Stats</h4><hr>
    <strong>Number of Submissions: </strong> <span id="UvaSubs"></span><br />
    <strong>Accepted: </strong> <span id="UvaAc"></span><br/><br/>
    <a href="#" onclick='unameToUid("<?= $username; ?>", true);'>Refresh</a>
</div>
    <script> unameToUid("<?= $username; ?>", true); </script>
    <?php }else {
        echo '<script> unameToUid('.json_encode($username).', true); </script>';
    echo "Paste this key in your firstname: ".substr(generateKeyUVA($puser), 0, 4)."<br/>";
    echo '<a href="#" onclick=\'unameToUid('.json_encode($username).', true)\'>Refresh</a>';
}}}
}
?>