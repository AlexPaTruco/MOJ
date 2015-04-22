<?php

/*
 * Problem Equivalence Page
 *
 */

 elgg_register_event_handler('init', 'system', 'faq_page_init');

 function faq_page_init() {

 	elgg_register_page_handler('faq', 'faq_page_handler');

 	elgg_register_menu_item('site', ElggMenuItem::factory(array(
 		'name' => 'faq',
 		'href' => '/faq',
 		'text' => elgg_echo('FAQ'),
 		)));

 } 

 function faq_page_handler($page, $handler) {
 	if(!isset($page[0])) {
 		$page[0] = 'index';
 	}

 	$plugin_path = elgg_get_plugins_path();
 	$pages = $plugin_path . 'faq/pages/faq';

 	switch ($page[0]) {
 		case 'index':
 			include "$pages/index.php";
 			break;
 		default:
 			return false;
 		}
 		return true;
 }