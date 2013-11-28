<?php 
/*
* Plugin Name: Search through Recipe
* Description: Extends wordpress default search by searching through custom posttype (recipe)
* Author: Mahiubl Hasan
* Author Uri: http://sohag07hasan.elance.com
* */

class CustomPostTypeSearch{
	
	private $where;
	
	//initialize
	function __construct(){
		add_filter('pre_get_posts', array(&$this, 'pre_get_posts'));		
		//add_filter('the_posts', array(&$this, 'the_posts'), 100, 2);		
		add_filter('posts_search', array(&$this, 'posts_search'), 100, 2);
		add_filter('posts_where', array(&$this, 'posts_where_request'), 100, 2);
	}
	
	
	//filter query var
	function pre_get_posts($q){
		if($q->is_search()){
			//$q->set('post_type', array('post', 'page', 'gmc_recipe', 'gmc_recipeingredient', 'gmc_recipestep'));
			$q->set('post_type', array('post', 'page', 'gmc_recipe'));
			return $q;
		}
	}
	
	/**
	 * Store the search query to use it later
	 * */
	function posts_search($where, $q){
		$this->where = $where;
		return $where;
	}
	
	/**
	 * The final query, It's only used for test purpose and the hooks might be commenteded inside the __construct method
	 * */
	function the_posts($posts, $q){
		var_dump($q->request);
		return $posts;
	}
	
	
	/**
	 * Include the parents to make better search
	 * */
	function posts_where_request($where, $q){
		global $wpdb;			
		if($q->is_search() && !empty($this->where)){
			$where .= " OR $wpdb->posts.ID in (select $wpdb->posts.post_parent from $wpdb->posts where 1=1 $this->where )";
			$this->where = null;
		}
		
		
		return $where;
	}
	
}

return new CustomPostTypeSearch();

?>