<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Subscribtion_Industry
 * @subpackage Subscribtion_Industry/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Subscribtion_Industry
 * @subpackage Subscribtion_Industry/admin
 * @author     Your Name <email@example.com>
 */
class Subscribtion_Industry_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $subscribtion_industry The ID of this plugin.
     */
    private $subscribtion_industry;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $subscribtion_industry The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($version)
    {
        $this->version = $version;
        add_action('init', array($this, 'newsletters'));
        add_filter('si_templates', array($this, 'default_templates'));


        add_action('load-post.php', array($this, 'load_metabox'));
        add_action('load-post-new.php', array($this, 'load_metabox'));

        add_action('admin_menu', array($this, 'subscribers_page'));
    }

    public function load_metabox()
    {
        include_once 'class-atf-options-metabox.php';
        Newsletters_Metabox::get_instance($this->version);
    }

    public function newsletters()
    {
        $labels = array(
            'name' => _x('Newsletters', 'post type general name', 'your-plugin-textdomain'),
            'singular_name' => _x('Newsletter', 'post type singular name', 'your-plugin-textdomain'),
            'menu_name' => _x('Newsletters', 'admin menu', 'your-plugin-textdomain'),
            'name_admin_bar' => _x('Newsletter', 'add new on admin bar', 'your-plugin-textdomain'),
            'add_new' => _x('Add New', 'Newsletter', 'your-plugin-textdomain'),
            'add_new_item' => __('Add New Newsletter', 'your-plugin-textdomain'),
            'new_item' => __('New Newsletter', 'your-plugin-textdomain'),
            'edit_item' => __('Edit Newsletter', 'your-plugin-textdomain'),
            'view_item' => __('View Newsletter', 'your-plugin-textdomain'),
            'all_items' => __('All Newsletters', 'your-plugin-textdomain'),
            'search_items' => __('Search Newsletters', 'your-plugin-textdomain'),
            'parent_item_colon' => __('Parent Newsletters:', 'your-plugin-textdomain'),
            'not_found' => __('No Newsletters found.', 'your-plugin-textdomain'),
            'not_found_in_trash' => __('No Newsletters found in Trash.', 'your-plugin-textdomain'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'newsletter'),
            'capability_type' => 'page',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => null,
            'supports' => array('title', 'page-attributes'),
        );
        register_post_type('newsletters', $args);
    }


    public function default_templates($templates)
    {
        return array_merge($templates, array(
            'default' => array(
                'name' => 'Default text',
                'describtion' => 'The default text template',
                'preview' => plugin_dir_url(__FILE__) . 'img/email_template_txt.png',
                'fields' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'title' => 'Content',
                    ),
                ),
            ),
            'default_html' => array(
                'name' => 'Default HTML',
                'describtion' => 'The default text template',
                'preview' => plugin_dir_url(__FILE__) . 'img/email_template_html.png',
                'fields' => array(
                    'content' => array(
                        'type' => 'editor',
                        'title' => 'Content',
                    ),
                ),
            ),
            'default_example' => array(
                'name' => 'Default HTML',
                'describtion' => 'The default text template',
                'preview' => plugin_dir_url(__FILE__) . 'img/email_campaigns_en.png',
                'fields' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'title' => 'Content',
                    ),
                    'editor' => array(
                        'type' => 'editor',
                        'title' => 'Content',
                    ),
                    'text' => array(
                        'type' => 'text',
                        'title' => 'Content',
                    ),
                ),
            ),


        ));
    }

    public function subscribers_page()
    {


        add_submenu_page(
            'users.php',
            'Subscribes',
            'Subscribes',
            'manage_options',
            'subscribers',
            array($this, $this->subscribers_views_controller()));
    }

    public function subscribers_views_controller()
    {


        if (isset($_GET['action'])) $action = $_GET['action'];
        else $action = '';

        switch ($action) {
            case 'delete':
                if (isset($_POST['confirm_delete']) && 'dodelete' == $_POST['confirm_delete']) {
                    $this->do_delete();
                    wp_redirect(get_admin_url(null, 'users.php?page=subscribers'));
                    exit;
                } else {
                    return 'load_confirm_deletetion_view';
                }
                break;
            case 'edit':
                if (isset($_POST['action']) && 'doedit' == $_POST['action']) {
                    $this->do_edit();
                    wp_redirect(get_admin_url(null, 'users.php?page=subscribers'));
                    exit;
                } else {
                    add_action('admin_enqueue_scripts', array($this, 'enqueue_atfHtmlHelper_assets'));
                    return 'load_edit_view';
                }
                break;
            default:
                return 'load_default_view';
        }


    }

    public function enqueue_atfHtmlHelper_assets()
    {
        echo 'asldkjsdfkjg';
        wp_enqueue_style('atf-options-si', plugin_dir_url(__FILE__) . 'css/options.css', array(), '1.1', 'all');
        wp_enqueue_script('atf-options-js', plugin_dir_url(__FILE__) . 'js/atf-options.js', array('jquery', 'wp-color-picker', 'jquery-ui-sortable'), $this->version, false);
        wp_localize_script('atf-options-js', 'redux_upload', array('url' => get_template_directory_uri() . '/atf/options/admin/assets/blank.png'));
    }

    public function do_delete()
    {
        //ToDO: use confirmation by nonce 
        if (isset($_POST['subscribers']) && is_array($_POST)) {
            $subscribers = array_map('intval', $_POST['subscribers']);

            global $wpdb;

            $delete = 'DELETE FROM ' . $wpdb->prefix . 'si_subscribers WHERE id IN (' . implode(',', $subscribers) . ');';
            $wpdb->get_results($delete);
        }


        return true;
    }

    public function do_edit()
    {
        
        //ToDO: use confirmation by nonce 
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        $plugin_public = Subscribtion_Industry_Public::get_instance();

        if (empty($_POST['id'])) {
            $plugin_public->insert_subscriber($email, $name, empty($_POST['confirm']));
        } else {
            $data = array(
                'name' => $name,
                'email' => $email,
            );
            $where = array(
                'id' => intval($_POST['id']),
            );
            if (!$_POST['confirm'] && $_POST['was_confirmed']) {
                $data['activation_key'] = wp_generate_password(24, true);
            } elseif (!empty($_POST['confirm'])) {
                $data['activation_key'] = '';
            }
            
            
            $plugin_public->update_subscriber($data, $where);
        }


        return true;
    }

    public function load_confirm_deletetion_view()
    {


        if (isset($_GET['subscriber'])) $subscribers[] = $_GET['subscriber'];
        elseif (isset($_GET['subscribers'])) $subscribers = $_GET['subscribers'];
        else $subscribers = array();


        global $wpdb;

        $select = 'SELECT * FROM ' . $wpdb->prefix . 'si_subscribers WHERE id IN (' . implode(',', $subscribers) . ');';
        $subscribers = $wpdb->get_results($select);

        ?>
        <div class="wrap">


        <h2><?php _e('Delete subscriber', 'si'); ?></h2>
        <form method="post">
            <input type="hidden" name="confirm_delete" value="dodelete"/>
            <p>
                You have specified this subscriber for deletion:
            </p>
            <ul>
                <?php foreach ($subscribers as $subscriber) {
                    ?>
                    <li>
                        <input type="hidden" name="subscribers[]"
                               value="<?php echo $subscriber->id; ?>"><?php echo 'ID #' . $subscriber->id . ': ' . $subscriber->email . ' [' . $subscriber->name . ']'; ?>
                    </li>
                    <?php
                } ?>

            </ul>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="Confirm Deletion"></p>
        </form>

        <?php
        return true;
    }

    public function load_edit_view()
    {
        $data = array(
            'id' => '',
            'name' => '',
            'email' => '',
            'status' => '',
        );

        if (isset($_GET['subscriber'])) {
            $subscriber = intval($_GET['subscriber']);

            global $wpdb;

            $select = 'SELECT * FROM ' . $wpdb->prefix . 'si_subscribers WHERE id IN (' . $subscriber . ');';
            $subscriber = $wpdb->get_results($select, ARRAY_A);

            $data = wp_parse_args($subscriber[0], $data);

        } else {
            $subscriber = null;
        }

        include_once('htmlhelper.php');
        ?>
        <div class="wrap atf-fields">


        <h2><?php
            if ($subscriber !== null) echo __('Edit subscriber', 'si') . ' #' . $data['id'];
            else _e('New subscriber', 'si');
            ?></h2>

        <form method="post">
            <input type="hidden" name="action" value="doedit"/>
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/>
            <input type="hidden" name="was_confirmed" value="<?php if (empty($data['activation_key']) && isset($data['activation_key'])) echo 1; ?>"/>
            <table class="form-table">
                <tr class="form-required">
                    <th scope="row"><label for="name"><?php _e('Name'); ?></label></th>
                    <td><?php AtfHtmlHelper::text(array('id' => 'name', 'name' => 'name', 'value' => $data['name'])); ?></td>
                </tr>
                <tr class="form-required">
                    <th scope="row"><label for="email">Email <span class="description">(required)</span></label></th>
                    <td><?php AtfHtmlHelper::text(array('id' => 'email', 'name' => 'email', 'value' => $data['email'])); ?></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label>Confirm</label></th>
                    <td><?php AtfHtmlHelper::tumbler(array('id' => 'confirm', 'name' => 'confirm', 'value' => (empty($data['activation_key']) && isset($data['activation_key'])))); ?></td>
                </tr>
            </table>


            <p>
                You have specified this subscriber for deletion:
            </p>


            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="Submit"></p>
        </form>

        <?php
        return true;
    }

    public function load_default_view()
    {

        if (isset($_GET['orderby']) && in_array($_GET['orderby'], array('name', 'email', 'status', 'last_send'))) $orderby = $_GET['orderby'];
        else $orderby = 'id';

        if ($orderby == 'status') $orderby_sql = 'activation_key';
        else $orderby_sql = $orderby;

        if (isset($_GET['order'])) $order = $_GET['order'];
        else $order = 'asc';

        global $wpdb;
        $select = 'SELECT * FROM ' . $wpdb->prefix . 'si_subscribers ORDER BY ' . $orderby_sql . ' ' . $order . ';';
        $subscribers = $wpdb->get_results($select);

        include 'subscribers_views/default.php';

        return true;
    }

}
