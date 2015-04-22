<?php
/**
 * Elgg custom index layout
 * 
 * You can edit the layout of this page with your own layout and style. 
 * Whatever you put in this view will appear on the front page of your site.
 * 
 */

$mod_params = array('class' => 'elgg-module-highlight');

?>

<div class="custom-index elgg-main elgg-grid clearfix">
	<div class="elgg-col elgg-col-1of2 custom-index-col1">
		<div class="elgg-inner pvm">
	<h2>Welcome to MOJ Alpha!</h2>

	<p>My Online Judge (MOJ) is the place where you can share your programming skills! This website
		is a <strong>research project</strong>, designed to link your different online judges stats such as COJ, UVA and CodeForces
	and share with other fellow programmers. Here you can meet the competition and share news, stories and programs with others.
</p>

	<h2>How Can I Help?</h2>

	<p>Since this a work in progress research project, we need all the feedback we can get!
		If you have any comments, bugs, ideas, suggestions that you need to report, please email us 
		at <a href="mailto:feedback@moj.projectace.net">feedback@moj.projectace.net</a>.</p>

	<p>If you get lost in the page, please try visiting our <a href="faq">FAQ</a> page, where you will find answers to some of your
		questions.</p>
<?php
// left column



?>

<?php
// a view for plugins to extend
echo elgg_view("index/lefthandside");

// files
if (elgg_is_active_plugin('file')) {
	echo elgg_view_module('featured',  elgg_echo("custom:files"), $vars['files'], $mod_params);
}

// groups
if (elgg_is_active_plugin('groups')) {
	echo elgg_view_module('featured',  elgg_echo("custom:groups"), $vars['groups'], $mod_params);
}
?>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of2 custom-index-col2">
		<div class="elgg-inner pvm">
<?php
// right column

// Top box for login or welcome message
if (elgg_is_logged_in()) {
	$top_box = "<h2>" . elgg_echo("welcome") . " ";
	$top_box .= elgg_get_logged_in_user_entity()->name;
	$top_box .= "</h2>";
} else {
	$top_box = $vars['login'];
}
echo elgg_view_module('featured',  '', $top_box, $mod_params);

// a view for plugins to extend
echo elgg_view("index/righthandside");

// files
echo elgg_view_module('featured',  elgg_echo("custom:members"), $vars['members'], $mod_params);

// groups
if (elgg_is_active_plugin('blog')) {
	echo elgg_view_module('featured',  elgg_echo("custom:blogs"), $vars['blogs'], $mod_params);
}

// files
if (elgg_is_active_plugin('bookmarks')) {
	echo elgg_view_module('featured',  elgg_echo("custom:bookmarks"), $vars['bookmarks'], $mod_params);
}
?>
		</div>
	</div>
</div>
