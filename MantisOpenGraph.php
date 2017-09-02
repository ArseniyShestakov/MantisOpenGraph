<?php
require_once(config_get('class_path').'MantisPlugin.class.php' );

class MantisOpenGraphPlugin extends MantisPlugin
{
	function register()
	{
		$this->name = 'Open Graph Plugin';
		$this->description = 'Open Graph meta information for MantisBT.';
		$this->version = '1.0';
		$this->requires = array(
			'MantisCore' => '1.2.0',
		);
		$this->author = 'Arseniy Shestakov';
		$this->contact = 'find-email-on-website@rseniyshestakov.com';
		$this->url = 'https://github.com/ArseniyShestakov/MantisGraphPlugin';
	}

	function install()
	{
		return true;
	}

	function hooks()
	{
		return array(
			'EVENT_LAYOUT_RESOURCES' => 'addMeta'
		);
	}

	function addMeta($p_event)
	{
		if('view.php' !== basename($_SERVER['PHP_SELF']))
			return;

		$bug_id = gpc_get_int('bug_id');
		if(!bug_exists($bug_id) || !access_ensure_bug_level(VIEWER, $p_bug_id))
			return;

		$bug = bug_get($bug_id);

		$og_title = $bug->summary;
		$og_description = $p_bug->description;
		$og_update_time = date('c', $bug->last_updated);

		return '
			<meta property="og:title" content="'.$og_title.'">
			<meta property="og:description" content="'.$p_bug->description.'">
			<meta property="og:type" content="website">
			<meta property="og:updated_time" content="'.$og_update_time.'">
			';
	}
}
