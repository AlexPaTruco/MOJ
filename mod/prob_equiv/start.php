<?php

/*
 * Problem Equivalence Page
 *
 */

 elgg_register_event_handler('init', 'system', 'prob_equiv_init');

 function prob_equiv_init() {

 	elgg_register_page_handler('probequiv', 'probequiv_page_handler');

 	elgg_register_menu_item('site', ElggMenuItem::factory(array(
 		'name' => 'probequiv',
 		'href' => '/probequiv',
 		'text' => elgg_echo('Report Equivalence'),
 		)));

 	elgg_register_menu_item('site', ElggMenuItem::factory(array(
 		'name' => 'probequiv_br',
 		'href' => '/probequiv/browse',
 		'text' => elgg_echo('Browse Equivalence'),
 		)));

 } 

 function probequiv_page_handler($page, $handler) {
 	if(!isset($page[0])) {
 		$page[0] = 'index';
 	}

 	$plugin_path = elgg_get_plugins_path();
 	$pages = $plugin_path . 'prob_equiv/pages/probequiv';

 	switch ($page[0]) {
 		case 'index':
 			include "$pages/index.php";
 			break;
 		case 'browse':
 			include "$pages/browse.php";
 			break;
 		default:
 			return false;
 		}
 		return true;
 }