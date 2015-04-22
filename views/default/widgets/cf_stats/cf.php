<?php

//Functions for CodeForces!

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function userInfo($username) {
	$random = generateRandomString(6);
	$time = time();
	$apiKey = "9595c2f8a8884a7082c149560213fb81e83a3120";
	$secret = "d69754fa150750481ae8962eaa5b0d2f9634fef3";
	$apiSig = hash("sha512", $random."/user.info?apiKey=".$apiKey."&handles=".$username."&time=".$time."#".$secret);
	$html = @file_get_contents('http://codeforces.com/api/user.info?handles='.$username.'&apiKey='.$apiKey.'&time='.$time.'&apiSig='.$random.$apiSig);

	return json_decode($html);
	//return 'http://codeforces.com/api/user.info?handles='.$username.'&apiKey='.$apiKey.'&time='.$time.'&apiSig='.$apiSig;
}