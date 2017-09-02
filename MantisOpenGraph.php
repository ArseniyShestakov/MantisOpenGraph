<?php
require_once(config_get('class_path').'MantisPlugin.class.php' );

class MantisOpenGraphPlugin extends MantisPlugin
{
	function register()
	{
		$this->name = 'Open Graph Plugin';
		$this->description = 'Open Graph and Twitter Card meta information for MantisBT.';
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

		$bug_id = gpc_get_int('id');
		if(!bug_exists($bug_id))// || !access_ensure_bug_level(VIEWER, $bug_id))
			return;

		$bug = bug_get($bug_id);
		$i_title = 'Bug #'. $bug_id.': '.$bug->summary;
		$i_description = trim(substr($bug->description, 0, 300));
		$i_url = config_get('path').'view.php?id='.$bug_id;
		$i_image_url = config_get('path').'images/og_logo.png';

		$og = array(
			'site_name' => config_get('from_name'),
			'title' => $i_title,
			'description' => $i_description,
			'type' => 'article',
			'updated_time' => date('c', $bug->last_updated),
			'url' => $i_url,
			'image' => $i_image_url,
			'image:width' => '128',
			'image:height' => '128'
		);
		$twitter = array(
			'card' => 'summary',
			'title' => $i_title,
			'description' => $i_description,
			'image:src' => $i_image_url,
			'url' => $i_url,
			'domain' => parse_url($i_url, PHP_URL_HOST),
			'site' => '@VCMIOfficial'
		);

		$return = array();
		foreach($og as $property => $content)
		{
			$return[] = '<meta property="og:'.$property.'" content="'.$content.'">';
		}
		foreach($twitter as $property => $content)
		{
			$return[] = '<meta name="twitter:'.$property.'" content="'.$content.'">';
		}

		return implode(PHP_EOL, $return);
	}
}
