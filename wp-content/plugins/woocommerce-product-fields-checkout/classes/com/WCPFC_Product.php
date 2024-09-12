<?php 
class WCPFC_Product
{
	var $product_ids_to_which_was_bounded_last_field = array();
	public function __construct()
	{
		add_action('wp_ajax_wcpfc_get_product_list', array(&$this, 'ajax_load_product_list'));
		add_action('wp_ajax_wcpfc_get_category_list', array(&$this, 'ajax_load_category_list'));
		
	}
	public function reset_internal_state()
	{
		$this->product_ids_to_which_was_bounded_last_field = array();
	}
	public function ajax_load_category_list()
	{
		$product_categories = $this->get_product_category_list($_GET['product_category']);
		echo json_encode( $product_categories);
		wp_die();
	}
	function ajax_load_product_list()
	{
		$resultCount = 50;
		$search_string = isset($_GET['search_string']) ? $_GET['search_string'] : null;
		$page = isset($_GET['page']) ? $_GET['page'] : null;
		$offset = isset($page) ? ($page - 1) * $resultCount : null;
		$product_list = $this->get_product_list($search_string ,$offset, $resultCount);
		echo json_encode( $product_list); 
		wp_die();
	}
	function get_product_list($search_string, $offset, $resultCount)
	{
		global $wpdb, $wcpfc_wpml_model;
		 $query_select_string = "SELECT products.ID as id, products.post_parent as product_parent, products.post_title as product_name, product_meta.meta_value as product_sku";
		 $query_select_count_string = "SELECT COUNT(*) as tot";
		 $query_from_string = " FROM {$wpdb->posts} AS products
								 LEFT JOIN {$wpdb->postmeta} AS product_meta ON product_meta.post_id = products.ID AND product_meta.meta_key = '_sku'
								 WHERE  (products.post_type = 'product' OR products.post_type = 'product_variation')
								 AND products.post_status = 'publish' 
								";
		if($search_string)
				$query_from_string .=  " AND ( products.post_title LIKE '%{$search_string}%' OR product_meta.meta_value LIKE '%{$search_string}%' OR products.ID LIKE '%{$search_string}%' ) 
										AND (products.post_type = 'product' OR products.post_type = 'product_variation') ";
		
		$final_query_string =  $query_select_string.$query_from_string." GROUP BY products.ID LIMIT {$offset}, {$resultCount}";
		
		$result = $wpdb->get_results($final_query_string ) ;
		
		if($wcpfc_wpml_model->wpml_is_active())
		{
			$product_ids = $variation_ids = array();
			foreach($result as $product)
			{
				if($product->product_parent == 0 )
					$product_ids[] = $product;
				else
					$variation_ids[] = $product;
			}
			
			//Filter products
			if(!empty($product_ids))
				$product_ids = $wcpfc_wpml_model->remove_translated_id($product_ids, 'product', true);
			
			//Filter variations
			if(!empty($variation_ids))
				$variation_ids = $wcpfc_wpml_model->remove_translated_id($variation_ids, 'product', true);
			
			$result = array_merge($product_ids, $variation_ids);
		}
		
		if(isset($result) && !empty($result))
			foreach($result as $index => $product)
				{
					if($product->product_parent != 0 )
					{
						$readable_name = $this->get_variation_complete_name($product->id);
						$result[$index]->product_name = $readable_name != false ? "<i>".esc_html__('Variation','woocommerce-files-upload')."</i> ".$readable_name : $result[$index]->product_name;
					}
				}
		
		
		if(isset($offset) && isset($resultCount))
		{
			$num_order = $wpdb->get_col($query_select_count_string.$query_from_string);
			$num_order = isset($num_order[0]) ? intval($num_order[0]) : 0;
			$endCount = $offset + $resultCount;
			$morePages = empty($result) ? false : $num_order > $endCount;
			$results = array(
				  "results" => $result,
				  "pagination" => array(
					  "more" => $morePages
				  )
			  );
		}
		else
			$results = array(
				  "results" => $result,
				  "pagination" => array(
					  "more" => false
				  )
			  );
		//wcpfc_var_dump($results);
		return $results;
	}
	public function get_product_category_list($search_string = null)
	 {
		 
		 global $wpdb, $wcpfc_wpml_model;
		  $query_string = "SELECT product_categories.term_id as id, product_categories.name as category_name
							 FROM {$wpdb->terms} AS product_categories
							 LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id = product_categories.term_id 							 						 	 
							 WHERE tax.taxonomy = 'product_cat' 
							 AND product_categories.slug <> 'uncategorized' 
							";
		 if($search_string)
					$query_string .=  " AND ( product_categories.name LIKE '%{$search_string}%' )";
			
		$query_string .=  " GROUP BY product_categories.term_id ";
		$result = $wpdb->get_results($query_string ) ;
		
		//WPML
		if($wcpfc_wpml_model->wpml_is_active())
		{
			$result = $wcpfc_wpml_model->remove_translated_id($result, 'product_cat', true);
		} 
		
		return $result;
	 }
	 public function get_variation_complete_name($variation_id)
	 {
		
		$error = false;
		$variation = wc_get_product($variation_id);
		if($variation == null || $variation == false)
			return "";
		if($variation->is_type('simple') || $variation->is_type('variable'))
			return $variation->get_title();
		
		
		$product_name = $variation->get_title()." - ";	
		if($product_name == " - ")
			return false;
		$attributes_counter = 0;
		foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
		{
			
			if($attributes_counter > 0)
				$product_name .= ", ";
			$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
			
			$product_name .= " ".wc_attribute_label($meta_key).": ".$value;
			$attributes_counter++;
		}
		return $product_name;
	 }
	public function get_product_name($product_id, $include_id = true)
	{
		global $wcpfc_wpml_model;
		$product_id = $wcpfc_wpml_model->get_main_language_id($product_id, 'product');
		
		$product = wc_get_product($product_id);
		
		if(!isset($product) || $product === false)
			return "";
		
		if($product->get_type() == 'variation')
		{
			$readable_name = $this->get_variation_complete_name($product_id);
			$readable_name = $include_id ? "#".$product_id." - ".$readable_name  : $readable_name;
		}
		else
		{
			try{
			    $readable_name = $include_id  ? $product->get_formatted_name() : $product->get_name();
		    }catch (Exception $e){}
		}
		return $readable_name;
	}
	public function get_product_category_name($category_id, $default = false)
	{
		global $wcpfc_wpml_model;
		$category_id = $wcpfc_wpml_model->get_main_language_id($category_id, 'product_cat');
		$category = get_term( $category_id, 'product_cat' );
		return isset($category) ? $category->name : $default;
	}
	public function get_product_id_by_category_id($cat_id)
	{
		
		$args = array(
			'fields'        		=> 'ids',
			'post_type'             => 'product',
			'post_status'           => 'publish',
			'posts_per_page'        => -1,
			'meta_query'            => array(
				array(
					'key'           => '_visibility',
					'value'         => array('catalog', 'visible'),
					'compare'       => 'IN'
				)
			),
			'tax_query'             => array(
				array(
					'taxonomy'      => 'product_cat',
					'field' => 'term_id', //This is optional, as it defaults to 'term_id'
					'terms'         => $cat_id,
					'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				)
			)
		);
		$products = get_posts($args);
		
		return $products;
	}
	public function field_applies_to_product( $field_rule_data, $product)
	{
		global $wcpfc_wpml_model;
		if(!isset($field_rule_data['options']['category_id']) && !isset($field_rule_data['options']['product_id']))
			return true;
			
		{
			//Check if the product id belongs to the selected categories (and eventually subcategories)
			if(wcpfc_get_value_if_set($field_rule_data['options'], 'category_id') != false)
				foreach($field_rule_data['options']['category_id'] as $category_data)
				{
					
					$parent_product = $product->get_parent_id() != 0 ? wc_get_product( $product->get_parent_id()) : $product;
					$category_id = $category_data['id'];
					$product_categories_ids = $product->get_parent_id() != 0 ? $parent_product->get_category_ids() : $product->get_category_ids();
					$product_categories_ids = $wcpfc_wpml_model->get_main_language_ids($product_categories_ids, 'product_cat');
					$condition_categories = [$category_id];
					
					//subcategories ids retrieval
					if($field_rule_data['options']['display_category_policy'] === 'categories_and_children')
					{
						$cat_children = get_term_children( $category_id , "product_cat" );
						$condition_categories = is_array($cat_children) ? array_merge($condition_categories, $cat_children) : $condition_categories;
					}	
					
					//Is the current product belonging to the selected categories and subcategories?
					if(count(array_intersect ($product_categories_ids, $condition_categories )) > 0)
					{
						$num_of_category_present_on_cart[$category_id] = true;
						$field_rule_data['options']['product_id'][] = array('id' => $product->get_id()); //it is added to product ids to check
					}

				}
			
			if(wcpfc_get_value_if_set($field_rule_data['options'], 'product_id') != false)
			{
				foreach($field_rule_data['options']['product_id'] as $product_data)
				{
					if($product->get_id() ==  $product_data['id'] || ($product->get_parent_id() != 0 && $product->get_parent_id() == $product_data['id']))
					{	
						return true;
					}
				}
			}
		}
		return false;
		
	}
}
?>