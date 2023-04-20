<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Omniglot
 * @subpackage Omniglot/public
 * @author     Omniglot <contact@mainulhassan.info>
 */
class Omniglot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Omniglot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Omniglot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/omniglot-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Omniglot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Omniglot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$droupdown_option = get_option('omniglot_options');
		$current_lang = isset($_GET['lang']) ? $_GET['lang']  : '';
		if(empty($current_lang)){
			 global $post;
			 $current_lang = get_post_meta($post->ID, 'cn_mylang', true);
		}

		foreach ( omniglot_supported_languages() as $code => $name ) {
			if ( $code == $current_lang ) {
				$current_name = $name;
			}
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/omniglot-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name,'cn_plugin_vars', 
			array(	
					'current_lang' => $current_lang,
					'current_text' => $current_name,
					'current_thumbnail' => OMNIGLOT_PLUGIN_URI.'../public/img/'.esc_html( $current_name ).'.png' , 
					'droupdown'=> $droupdown_option['appearance'],
					'page_open_tab' =>  $droupdown_option['page_open_tab'],
					'ajaxurl' => admin_url('admin-ajax.php'),
					'plugin_url' =>OMNIGLOT_PLUGIN_URI,
					'site_url'=>get_site_url()
				));

	}



	public function add_meta_tags() {
		

		$post_id=get_the_ID();
		$cnpost=get_post($post_id);
		$cnpost->guid;
		$cnpost->post_name;
		$link = '';
		$link2 = '';

		$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
		$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
		if ($cn_post_translated_to_from) {
			if ($cn_post_translated_to_from['0']['cn_parent_post_id']) {
				$cn_parent_post_id=$cn_post_translated_to_from['0']['cn_parent_post_id'];
				$cn_post_translated_to=get_post_meta($cn_parent_post_id, 'cn_post_translated_to',true);
			}
		}

		if ($cn_post_translated_to) {
			foreach ($cn_post_translated_to as $translated_to) {
				$cn_child_post_id=$translated_to['cn_child_post_id'];
				foreach ( omniglot_supported_languages() as $code => $name ) : 
					if ($code==$translated_to['translated_to']) {
						$cn_child_post=get_post($cn_child_post_id);
						if($cn_child_post->post_status=='publish'){
							$cn_child_post->guid;
							$cnname[]=$name;
							if ($cn_child_post_id!=$post_id) {
								 $link.='<div class="icondiv"><a class="translatedlink"  href="'.get_post_permalink($cn_child_post_id).'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"></a></div>';	
							}
							
						 }
					 } ?>
			<?php endforeach; 
			}
		}
		if ($cn_post_translated_to_from) {

					foreach ($cn_post_translated_to_from as $translated_to_from) {
						if ($translated_to_from['translated_from']) {
							$cn_parent_post_id=$translated_to_from['cn_parent_post_id'];
							foreach ( omniglot_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$cn_parent_post=get_post($cn_parent_post_id);
						 		if($cn_parent_post->post_status=='publish'){
						 			$link2.='<div class="icondiv"><a class="translatedlink"  href="'.get_post_permalink($cn_parent_post_id).'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"></a></div>';
							 		}
							 }
							 endforeach; 
						}
					}
				} 
		if ($link!='' OR $link2!='') {
			$cnlink='<div class="cnicon"><div class="cn_available">AI translated by <a target="_blank" href="https://omniglot.ai/">Omniglot</a> in:</div>'.$link.$link2.'</div>';
		}
		
		$cnlang = !empty( $_GET['lang'] ) ? $_GET['lang'] : '';

		$post_type=get_post_type($post_id);
		$lang = get_bloginfo( 'language' );
		if ($cn_post_translated_to_from) {
			if ($cnlang) {?>
				<meta name="language" content="<?php echo get_site_url(); ?>/<?php echo $cnlang; ?>">
				<meta name="canonical" href="<?php echo get_site_url(); ?>/<?php echo $cnlang; ?>/<?php echo $cnpost->post_name; ?>">
			<?php }else{?>
					<meta name="language" content="<?php echo get_site_url(); ?>/<?php echo $cn_post_translated_to_from[0]['translated_to']; ?>">
					<meta name="canonical" href="<?php echo get_site_url(); ?>/<?php echo $cn_post_translated_to_from[0]['translated_to']; ?>/<?php echo $cnpost->post_name; ?>">
			<?php }
		}
		if($cn_post_translated_to){
			if ($cnlang) { ?>
					<meta name="language" content="<?php echo get_site_url(); ?>/<?php echo $cnlang; ?>">
					<meta name="canonical" href="<?php echo get_site_url(); ?>/<?php echo $cnlang; ?>/<?php echo $cnpost->post_name; ?>">				
			<?php }else{ ?>
					<meta name="language" content="<?php echo get_site_url(); ?>/<?php echo $cn_post_translated_to[0]['translated_from']; ?>">
					<meta name="canonical" href="<?php echo get_site_url(); ?>/<?php echo $cn_post_translated_to[0]['translated_from']; ?>/<?php echo $cnpost->post_name; ?>">
			<?php }
			
		}
		
	}



   
	public function cn_slug_filter_the_title( $content) {
		$post_id=get_the_ID();
		$post_type=get_post_type( $post_id );

		if($post_type=='product'){
			return $content;
		}

		$translated_from=get_post_meta($post_id, 'translated_from',true);
		$translated_to=get_post_meta($post_id, 'translated_to',true);
		$cn_mylang=get_post_meta($post_id, 'cn_mylang',true);
		$droupdown_option = get_option('omniglot_options');
		$omniglot_options = get_option( 'omniglot_options' );
		$show_watermark    = $omniglot_options['show_watermark'];
		$link = '';
		$link2 = '';
		$watermark = '';
		$cnlink = '';

		$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
		$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
		// print_r($cn_post_translated_to);
		// print_r($cn_post_translated_to_from);
		if ($cn_post_translated_to_from) {
			if ($cn_post_translated_to_from['0']['cn_parent_post_id']) {
				$cn_parent_post_id=$cn_post_translated_to_from['0']['cn_parent_post_id'];
				$cn_post_translated_to=get_post_meta($cn_parent_post_id, 'cn_post_translated_to',true);
						
			}
		}
		if ($cn_post_translated_to) {
						
			foreach ($cn_post_translated_to as $translated_to) {	
				$cn_child_post_id=$translated_to['cn_child_post_id'];
				foreach ( omniglot_supported_languages() as $code => $name ) : 
					if ($code==$translated_to['translated_to']) {
						$cn_child_post=get_post($cn_child_post_id);
						if($cn_child_post->post_status=='publish'){
							$cn_child_post->guid;
							$cnname[]=$name;
							
							if ($cn_child_post_id!=$post_id) {

								{
									$link.='<div class="icondiv"><a class="translatedlink"  href="'.get_site_url().'/'.$cn_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"></a></div>';
								}	
							}
							
						 }
					 } ?>
			<?php endforeach; 
			}
		}		
		if ($cn_post_translated_to_from) {
					foreach ($cn_post_translated_to_from as $translated_to_from) {						
						
						if ($translated_to_from['translated_from']) {
							$cn_parent_post_id=$translated_to_from['cn_parent_post_id'];
							foreach ( omniglot_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$cn_parent_post=get_post($cn_parent_post_id);
						 		if($cn_parent_post->post_status=='publish'){



						 				$link2.='<div class="icondiv"><a class="translatedlink"  href="'.get_site_url().'/'.$cn_parent_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"><p>'.esc_html($flag_name).'</p></a></div>';						 			
						 		}
							}
							endforeach; 
						}
					}
				}
				$dropdown_wrap = $text_wrap = $wpw_dropdown = '';
		if ($show_watermark=='on') {
			$watermark='<div class="cn_available">AI translated by <a target="_blank" href="https://omniglot.ai/">Omniglot</a> in:</div>';
		}
	
				

		if ($link!='' OR $link2!='') {
			$cnlink='<div class="cnicon dropdown">'.$select.$watermark.' '.$link.$link2.$selectend.$select_html.'</div>';
		}
		

		
		if ($post_type=='page') {
			$custom_content = $cnlink . $content;
		}elseif($post_type=='product'){
			$custom_content = $content.$cnlink; // .$cnlink
		}else{
			$custom_content =$cnlink.$content;	
		}
		return $custom_content;
	}




    public function woocommerce_single_title_lang() {
    	$post_id=get_the_ID();
		$translated_from=get_post_meta($post_id, 'translated_from',true);
		$translated_to=get_post_meta($post_id, 'translated_to',true);
		$cn_mylang=get_post_meta($post_id, 'cn_mylang',true);
		$droupdown_option = get_option('omniglot_options');
		$omniglot_options = get_option( 'omniglot_options' );
		$show_watermark    = $omniglot_options['show_watermark'];
		$link = '';
		$link2 = '';
		$watermark = '';
		$cnlink = '';

		$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
		$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
		// print_r($cn_post_translated_to);
		// print_r($cn_post_translated_to_from);
		if ($cn_post_translated_to_from) {
			if ($cn_post_translated_to_from['0']['cn_parent_post_id']) {
				$cn_parent_post_id=$cn_post_translated_to_from['0']['cn_parent_post_id'];
				$cn_post_translated_to=get_post_meta($cn_parent_post_id, 'cn_post_translated_to',true);
						
			}
		}
		if ($cn_post_translated_to) {
						
			foreach ($cn_post_translated_to as $translated_to) {	
				$cn_child_post_id=$translated_to['cn_child_post_id'];
				foreach ( omniglot_supported_languages() as $code => $name ) : 
					if ($code==$translated_to['translated_to']) {
						$cn_child_post=get_post($cn_child_post_id);
						if($cn_child_post->post_status=='publish'){
							$cn_child_post->guid;
							$cnname[]=$name;
							
							if ($cn_child_post_id!=$post_id) {

								
									$link.='<div class="icondiv"><a class="translatedlink"  href="'.get_site_url().'/'.$cn_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"></a></div>';
								
							}
							
						 }
					 } ?>
			<?php endforeach; 
			}
		}
		if ($cn_post_translated_to_from) {
					foreach ($cn_post_translated_to_from as $translated_to_from) {
						
						if ($translated_to_from['translated_from']) {
							$cn_parent_post_id=$translated_to_from['cn_parent_post_id'];
							foreach ( omniglot_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$cn_parent_post=get_post($cn_parent_post_id);
						 		if($cn_parent_post->post_status=='publish'){

						 			
						 				$link2.='<div class="icondiv"><a class="translatedlink"  href="'.get_site_url().'/'.$cn_parent_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png"><p>'.esc_html($flag_name).'</p></a></div>';
						 			
						 		}
							}
							endforeach; 
						}
					}
				}
				$dropdown_wrap = $text_wrap = $wpw_dropdown = '';
		if ($show_watermark=='on') {
				$watermark='<div class="cn_available">AI translated by <a target="_blank" href="https://omniglot.ai/">Omniglot</a> in:</div>';
			}
		

		if ($link!='' OR $link2!='') {
			$cnlink='<div class="cnicon dropdown">'.$select.$watermark.' '.$link.$link2.$selectend.$select_html.'</div>';
		}
		echo $cnlink;
   		//the_title( '<h1 class="product_title entry-title">', '</h1>' );	
   	}




	public function cn_posts_custom( $query ) {
		
		$cnlang = !empty( $_GET['lang'] ) ? $_GET['lang'] : '';

		if ( $cnlang ) {
			if ( $query->is_home() && $query->is_main_query() ) { 
				$query->set( 'orderby', 'title' ); 
				$query->set( 'order', 'DESC' ); 
				 $query->set( 'meta_key', 'cn_mylang' );
				 $query->set( 'meta_value', $cnlang );
				// $query->set('meta_query', array(
				// 				'relation' => 'OR',
				// 		        array(
				// 		              'key' => 'cn_mylang',
				// 		              'value' =>$cnlang,
				// 		              'compare' => 'LIKE',
				// 		              // 'type' => 'numeric'
				// 		        ),
				// 		    ));
			} 
		}
		// echo "<pre>";
		// print_r($query);
		// exit();

		return $query;

	}
	

	 public function cn_ajax_handaler(){
    	global $wpdb;
    	$param= isset($_REQUEST['param'])?trim($_REQUEST['param']):"";
    	if ($param=='find_post_page') {
    		$cn_page_id=$_REQUEST['cn_page_id'];
    		$cn_post=get_post($cn_page_id);
    		$post_id=$cn_post->ID;
    		$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
			$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
			print_r($cn_post_translated_to);
			//print_r($cn_post_translated_to_from);

    	}

    	wp_die();
    }


}