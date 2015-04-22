<?php

//Block access for non logged in users
gatekeeper();

//Get user
$user = elgg_get_logged_in_user_entity();

$vars = array('username' => $user->username);

$content = elgg_view('probequiv/content', $vars);

 $params = array(
        'title' => 'Report Problem Equivalance',
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);
$title = "Report Problem Equivalance";
echo elgg_view_page($title, $body);