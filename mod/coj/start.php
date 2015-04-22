<?php

function coj_init() {
	elgg_register_widget_type('coj_stats', 'COJ Stats', 'COJ Widget');
}

elgg_register_event_handler('init', 'system', 'coj_init');