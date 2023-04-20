<?php

/**
 * Fired during plugin activation
 *
 * @link       https://logico.co
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Omniglot
 * @subpackage Omniglot/includes
 * @author     Omniglot <mohsintariq@logico.co>
 */
class Omniglot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$msg = get_site_url().'|'.OMNIGLOT_PLUGIN_URI;
		//mail("shivamsharma.shivam2@gmail.com","OMNIGLOT New installation ",$msg);
		update_option('cn_license', 'inactive');

	}

}
