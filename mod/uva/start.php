<?php

function uva_init() {
	elgg_register_widget_type('uva_stats', 'UVA Stats', 'UVA Widget');
}

elgg_register_event_handler('init', 'system', 'uva_init');