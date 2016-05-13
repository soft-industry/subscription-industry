<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Subscribtion_Industry
 * @subpackage Subscribtion_Industry/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Subscribtion_Industry
 * @subpackage Subscribtion_Industry/includes
 * @author     Your Name <email@example.com>
 */
class Subscribtion_Industry_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'si_subscribers';
        $sql = "CREATE TABLE {$table_name} (
	  id int(11) NOT NULL AUTO_INCREMENT,
      name varchar(255) DEFAULT NULL,
      email varchar(255) DEFAULT NULL,
      user_id int(11),
      activation_key varchar(255),
      status int(11) NOT NULL DEFAULT '0',
      last_send datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      UNIQUE KEY (email),
      UNIQUE KEY (id)
	);";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);


        $options = get_option('si_options');

        if (empty($options['confirm_page'])) {
            $id = wp_insert_post(array(
                'post_content' => '',
                'post_title' => 'Subscription page',
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => false,
                'ping_status' => '',
            ));
            if (!is_wp_error($id)) update_option('si_options', array('confirm_page' => $id));
        }




    }




}
