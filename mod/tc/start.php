<?php

function tc_init() {
	elgg_register_widget_type('tc_stats', 'TopCoder Stats', 'TopCoder Widget');
}

elgg_register_event_handler('init', 'system', 'tc_init');