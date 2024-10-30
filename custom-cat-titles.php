<?php
/*
Plugin Name: Custom Categories Titles
Plugin URI: http://www.bestcreditcards.com.au
Description: The ability to set custom titles for your category pages
Version: 1.0
Author: Michael Restuccia
*/

	


class CCT {
	
	
	public $cat_title;
	
	function __construct() {
		add_action('init', array(&$this, 'run'));
	}
	
	function run() {
		add_filter('wp_title', array(&$this, 'set_title'), 1, 3);
		add_action('edit_category_form', array(&$this, 'edit_title_form'), 1, 1);
		add_action('edit_category', array(&$this, 'edit_title'), 1, 1);
		add_action('create_category', array(&$this, 'add_title'), 1, 1);
	}
	
	function set_title($title, $sep = '', $location = '') {
		if (is_category()) {
			$this->cat_title = $this->get_title();
			if (empty($this->cat_title)) {
				$category = get_the_category(); 
				$this->cat_title = $category[0]->cat_name;
			}
			$title = $this->cat_title . ' ' . $sep . ' ';
			
			return $title;
		}

	}
	
	function get_category() {
		$id = intval(get_query_var('cat'));
		return $id;
	}
	
	function get_title() {
		$this->cat_title = get_option('cct_title_' . $this->get_category());
		return $this->cat_title;
	}
	
	
	function edit_title_form($cat) {
		if (!empty($cat)) {
			if (!isset($cat->cat_ID)) {
				$cat->cat_ID = $cat->term_id;
			}
			
		$cat_title = get_option('cct_title_' . $cat->cat_ID);
		if (file_exists(dirname(__FILE__)."/views/edit-category.php")) {
			include dirname(__FILE__)."/views/edit-category.php";
			}
		}
	}
	
	function edit_title($id) {
		$option = 'cct_title_' . $id;
		$this->cat_title = get_option($option);
		if ($this->cat_title != $_POST['title']) {
			update_option($option, $_POST['title']);
		} elseif (empty($_POST['title'])) {
			delete_option($option);
		}
		
	}
	
	function add_title($id) {
		$option = 'cct_title_' . $id;
		if (!empty($_POST['title'])) {	
			add_option($option, $_POST['title']);
		}
	}	

}

$cct = new CCT();


