<?php

elgg_register_event_handler('init', 'system', 'hello_world_init');

function hello_world_init() {
	elgg_register_page_handler('hello', 'hello_world_page_handler');
}

function hello_world_page_handler() {
    $params = array(
        'title' => 'Hello world!',
        'content' => 'This is my first plugin.',
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page('Hello', $body);
}