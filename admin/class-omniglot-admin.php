<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Omniglot
 * @subpackage Omniglot/admin
 * @author     Omniglot <contact@mainulhassan.info>
 */
class Omniglot_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The unique identifier.
	 */
	public function enqueue_styles( $hook ) {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/omniglot-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'omniglot-select2-css', plugin_dir_url( __FILE__ ) . '/plugins/select2/css/select2.min.css', array(), $this->version, 'all' );

		if ( 'settings_page_omniglot' === $hook ) {
			wp_enqueue_script( 'omniglot-select2-css' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 *
	 * @param string $hook The unique identifier.
	 */
	public function enqueue_scripts( $hook ) {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/omniglot-admin.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'omniglot-select2-js', plugin_dir_url( __FILE__ ) . 'plugins/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( 'omniglot-params', plugin_dir_url( __FILE__ ), array( 'jquery' ), $this->version, true );
		
		
		wp_localize_script(
			'omniglot-params',
			'omniglotParams',
			array(
				
				'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
				'admin_post_url'               => admin_url('post.php'),
				'translationSuccessMessage'    => esc_html__( 'Translation completed successfully!', 'omniglot' ),
				'total_translated_placeholder' => omniglot_get_placeholder_markup_for_total_translated_posts(),
			)
		);

		if ( 'settings_page_omniglot' === $hook ) {
			wp_enqueue_script( 'omniglot-select2-js' );
		}
	}

	function my_project_updated( $post_id ) {
		$deepl_translated = get_post_meta($post_id, 'deepl_translated',true);
		update_post_meta($post_id, 'deepl_translated', 1);
	}	

	public function cn_register_custom_widget() {
   	 register_widget( 'Cn_Custom_Widget' );
	}

 


}

class Cn_Custom_Widget extends WP_Widget {

    public function __construct() {
    	 $options = array(
        'classname' => 'custom_livescore_widget',
        'description' => 'A live score widget',
	    );

	    parent::__construct(
	        'omniglot_language_switcher', 'Omniglot Language Switcher', $options
	    );
    }
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title');
		}
	
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php 
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
    public function widget( $args, $instance ) {
    	global $wp;
		$cnlang = isset( $_GET['lang'] ) ? $_GET['lang'] : '';
		$cnlink = '';
		
    	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title');
		}
	    echo $args['before_widget'];
	    echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
	   if (get_post_type_archive_link('post')==home_url($wp->request).'/') {
		    ?>
		    <div class="cn_select">
			    <div class="cn_select_ul">
			    	<?php if ($cnlang==''){ ?>
			    	<div cn_tag="selected" class="cn_select_li cn_active">Please select</div>	
			    	<?php } ?>
				<?php foreach ( omniglot_supported_languages() as $code => $name ) : ?>
					<div class="cn_select_li <?php if($cnlang==esc_attr( $code )){ echo 'cn_active';} ?>" cn_tag="<?php if($cnlang==esc_attr( $code )){ echo 'selected';} ?>" cn_value="<?php echo esc_attr( $code ); ?>">
						<img class="icon_img" src="<?php echo OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name )?>.png" style="margin: 2px 10px 0 0;float: left;"> <?php echo esc_html( $name ); ?>
					</div>
				<?php endforeach; ?>
				</div>
				<input type="hidden" name="cn_url" id="cn_url" class="cn_url" value="<?php echo home_url($wp->request);?>">
		    </div>
			<input type="hidden" name="cn_page_id" id="cn_page_id" value="<?php echo get_the_ID();?>">
		    <?php
	    }else{

	    	$post_id=get_the_ID();
			$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
			$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
			$ddllink = '';
			$link = '';
			$link2 = '';
			$ddllink2 = '';
			$cnlink = '';



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
									 $ddllink.= '<option value="'.get_site_url().'/'.$cn_child_post->post_name.'?lang='.$code.'">'. esc_html( $name ).'</option>';
									if ($cnlang==esc_attr( $code )) {
										$link.='<div class="cn_select_li_page cn_active" cn_tag="selected" cn_value="'.get_site_url().'/'.$cn_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png" style="margin: 2px 10px 0 0;float: left;">'. esc_html( $name ).'</div>';						 				
						 			}else{
							 			$link.='<div class="cn_select_li_page" cn_tag="" cn_value="'.get_site_url().'/'.$cn_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png" style="margin: 2px 10px 0 0;float: left;">'. esc_html( $name ).'</div>';	
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
								 			if ($cnlang==esc_attr( $code )) {
												$link2.='<div class="cn_select_li_page cn_active" cn_tag="selected" cn_value="'.get_site_url().'/'.$cn_parent_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png" style="margin: 2px 10px 0 0;float: left;">'. esc_html( $name ).'</div>';						 				
								 			}else{
									 			$link2.='<div class="cn_select_li_page" cn_tag="" cn_value="'.get_site_url().'/'.$cn_parent_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name ).'.png" style="margin: 2px 10px 0 0;float: left;">'. esc_html( $name ).'</div>';	
								 			}
								 			$ddllink2.= '<option value="'.get_site_url().'/'.$cn_parent_post->post_name.'?lang='.$code.'">'. esc_html( $name ).'</option>';
								 		}
								 }
								 endforeach; 
							}
						}
					} 
			if ($link!='' OR $link2!='') {
				$cnlink = $link.$link2;
				$allddl = $ddllink.$ddllink2;
			}
	    	?>
	    	 <div class="cn_select">
			    <div class="cn_select_ul">
			    <?php 
			    if ($cnlang) {
			    	foreach ( omniglot_supported_languages() as $code => $name ) : 
			    	if ($cnlang==$code) {?>
			    	<div class="cnopen">
						<img class="icon_img" src="<?php echo OMNIGLOT_PLUGIN_URI.'../public/img/'. esc_html( $name )?>.png" style="margin: 2px 10px 0 0;float: left;"> <?php echo esc_html( $name ); ?>
					</div>
			    	<?php } ?>
				<?php endforeach; }
				else{?>
					<div class="cnopen">
						Please select
					</div>
				<?php } ?>
				<?php echo $cnlink; ?>
				</div>
				<input type="hidden" name="cn_url" id="cn_url" class="cn_url" value="<?php echo home_url($wp->request);?>">
		    </div>
			<input type="hidden" name="cn_page_id" id="cn_page_id" value="<?php echo get_the_ID();?>">
		    <?php
	    }
	    echo $args['after_widget'];
    }
}
// Register the widget

