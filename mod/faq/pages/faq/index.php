<?php



$vars = array();

$content = elgg_view('faq/content', $vars);

 $params = array(
        'title' => 'Frequently Asked Questions',
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);
$title = "Frequently Asked Questions";
echo elgg_view_page($title, $body);