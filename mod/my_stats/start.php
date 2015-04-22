<?php

function my_stats_init() {
	elgg_register_widget_type('my_stats', 'My Stats', 'My Stats Widget');
}

elgg_register_event_handler('init', 'system', 'my_stats_init');