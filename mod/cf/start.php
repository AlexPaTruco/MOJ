<?php

function cf_init() {
	elgg_register_widget_type('cf_stats', 'CodeForces Stats', 'CodeForces Widget');
}

elgg_register_event_handler('init', 'system', 'cf_init');