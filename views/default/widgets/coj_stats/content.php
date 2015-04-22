<?php

$guid = elgg_get_page_owner_guid();

$user = get_entity($guid);

$username = $user->coj_username;
$puser = $user->username;

include "functions.php";

openConnection();
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
    if($_GET['update'] == "coj") {
        $update = true;
    }
}
//die(cojExists($puser));
 // Include the library

if(!userExists($puser) || $update) {
//die("hell");
//include('simple_html_dom.php');

        
            // Retrieve the DOM from a given URL
            $html = @file_get_html('http://coj.uci.cu/user/useraccount.xhtml?username=' . $username) or $html = null;
            //print_r($html->find('div[id=gInfo]'));

            if($html != null)
            {

            foreach ($html->find('div[id=gInfo]') as $e) {
                $image = str_replace("../", "", $e->children(0)->children(0)->children(0)->src);
                $firstname = $e->children(2)->innertext;
                $lastname = $e->children(4)->innertext;
                $country = $e->children(10)->innertext;
                $userrank = str_replace('href="', 'href="http://coj.uci.cu', $e->children(39)->innertext);
                $countryrank = str_replace('href="', 'href="http://coj.uci.cu', $e->children(43)->innertext);
                $score = str_replace('href="', 'href="http://coj.uci.cu', $e->children(37)->innertext);
            }


            foreach ($html->find('table[class=table table-bordered table-condensed]') as $e) {
                //echo str_replace('src="', 'src="http://coj.uci.cu', $e->innertext);
                $ac = str_replace('href="', 'href="http://coj.uci.cu', $e->children(1)->children(0)->innertext);
                $ce = str_replace('href="', 'href="http://coj.uci.cu', $e->children(1)->children(1)->innertext);
                $pe = str_replace('href="', 'href="http://coj.uci.cu', $e->children(1)->children(4)->innertext);
                $wa = str_replace('href="', 'href="http://coj.uci.cu', $e->children(1)->children(7)->innertext);
            }
        $info['username'] = $username;
        $info['name'] = $firstname." ".$lastname;
        $info['globalRank'] = $userrank;
        $info['countryRank'] = $country." ".$countryrank;
        $info['score'] = $score;
        $info['ac'] = $ac;
        $info['ce'] = $ce;
        $info['pe'] = $pe;
        $info['wa'] = $wa;

        validateUserCOJ($puser, $username);
        if(isValidCOJ($puser)) {
            $valid = true;
        }else {
            $valid = validateKeyCOJ($puser, $firstname);
        }

        }else {
            $error = true;
        }

        updateUser($puser);
        updateCOJ($puser,$info);
}else {
    $info = getCOJ($puser);

    if($info['username'] == "") { $error = true;}

     validateUserCOJ($puser, $username);
        if(isValidCOJ($puser)) {
            $valid = true;
        }else {
            $valid = false;
        }
}

if($valid) {
    if(!$error) {
            echo "<h4>Profile</h4><hr>";
            echo "Username: ".$info['username']."<br />";
            echo "Name: ".$info['name']."<br />";

            echo "<br /><h4>Ranking</h4><hr>";
            echo "Global Rank: ".$info['globalRank']."<br />";
            echo "Rank in ".$info['countryRank']."<br />";

                        echo "<br /><h4>Stats:</h4><hr>";
            echo "Score: ".$info['score']."<br />";
            echo "Accepted: ".$info['ac']."<br />";
            echo "Compilation Error: ".$info['ce']."<br />";
            echo "Presentation Error: ".$info['pe']."<br />";
            echo "Wrong Answer: ".$info['wa']."<br />";

        }else {
            echo "No profile to show.";
        }
    }else {
        echo "Paste this key in your firstname: ".substr(generateKeyCOJ($puser), 0, 4);
    }
            echo "<br /><a href='?update=coj'>Refresh</a>";
            // foreach ($html->find('div[id=stats]') as $e) {
            //     echo str_replace("src='", "src='http://coj.uci.cu", $e->innertext);
            // }

            // echo "<h3>All Info: </h3><hr>";

            // foreach ($html->find('div[id=gInfo]') as $e) {
            //     echo str_replace("../", "http://coj.uci.cu/", $e->innertext);
            // }
            
            // echo "<h3>Achievements:</h3><hr>";
            // foreach ($html->find('div[id=achievements]') as $e) {
            //     echo str_replace("src='", "src='http://coj.uci.cu", $e->innertext);
            // }
            
            // echo "<h3>Accepted Problems:</h3><hr>";
            // foreach ($html->find('div[id=probsACC]') as $e) {
            //     echo $e->innertext;
            // }
            
            // echo "<h3>Wrong Answer Problems:</h3><hr>";
            // foreach ($html->find('div[id=probsWA]') as $e) {
            //     echo $e->innertext;
            // }