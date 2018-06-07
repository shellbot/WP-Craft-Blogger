<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WP_Craft_Blogger
 * @subpackage WP_Craft_Blogger/admin
 * @author     Shellbot <hi@codebyshellbot.com>
 */
class WPCB_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_filter( 'ot_show_pages', '__return_false' );
		add_filter( 'ot_use_theme_options', '__return_false' );

	}

	/**
	 * Register pattern custom post type
	 *
	 * @since    0.0.1
	 */
	public function add_pattern_cpt() {

    $labels = array(
      'name'               => _x( 'Patterns', 'post type general name', 'wp-craft-blogger' ),
      'singular_name'      => _x( 'Pattern', 'post type singular name', 'wp-craft-blogger' ),
      'menu_name'          => _x( 'Patterns', 'admin menu', 'wp-craft-blogger' ),
      'name_admin_bar'     => _x( 'Pattern', 'add new on admin bar', 'wp-craft-blogger' ),
      'add_new'            => _x( 'Add New', 'pattern', 'wp-craft-blogger' ),
      'add_new_item'       => __( 'Add New Pattern', 'wp-craft-blogger' ),
      'new_item'           => __( 'New Pattern', 'wp-craft-blogger' ),
      'edit_item'          => __( 'Edit Pattern', 'wp-craft-blogger' ),
      'view_item'          => __( 'View Pattern', 'wp-craft-blogger' ),
      'all_items'          => __( 'All Patterns', 'wp-craft-blogger' ),
      'search_items'       => __( 'Search Patterns', 'wp-craft-blogger' ),
      'parent_item_colon'  => __( 'Parent Patterns:', 'wp-craft-blogger' ),
      'not_found'          => __( 'No patterns found.', 'wp-craft-blogger' ),
      'not_found_in_trash' => __( 'No patterns found in Trash.', 'wp-craft-blogger' ),
    );

    $args = array(
      'labels'            => $labels,
      'public'            => true,
      'publicly_queryable'=> true,
      'show_ui'           => true,
      'show_in_menu'      => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'pattern' ),
      'capability_type'   => 'post',
      'has_archive'       => true,
      'hierarchical'      => false,
      'menu_position'     => 5,
      'menu_icon'         => 'dashicons-lightbulb',
      'supports'          => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );

  	register_post_type( 'pattern', $args );

	}

	/**
	 * Initialize the pattern settings meta box.
	 *
	 * @since    0.0.1
	 */
	function add_pattern_meta_box() {

		$settings = get_option( 'wpcb_global_settings' );

		$primary_craft = ( $settings['wpcb-primary-craft']  ? $settings['wpcb-primary-craft'] : 'knitting' );

    $pattern_meta_box = array(
      'id'        => 'wpcb_pattern_metabox',
      'title'     => __( 'Pattern Details', 'wp-craft-blogger' ),
      'desc'      => '',
      'pages'     => array( 'pattern' ),
      'context'   => 'normal',
      'priority'  => 'high',
      'fields'    => array(
        array(
	        'id' => 'wpcb-pattern-general_tab',
	        'label' => __( 'General', 'wp-craft-blogger' ),
	        'type' => 'tab',
        ),
        array(
	        'id'          => 'wpcb-pattern-craft',
	        'label'       => __( 'Craft', 'wp-craft-blogger' ),
	        'desc'        => __( 'If project uses multiple crafts, choose the main one.', 'wp-craft-blogger' ),
	        'std'         =>  $primary_craft,
	        'type'        => 'select',
          'choices'     => array(
	          array(
	            'label'     => __( 'Crochet', 'wp-craft-blogger' ),
	            'value'     => 'crochet',
	          ),
            array(
              'label'     => __( 'Knitting', 'wp-craft-blogger' ),
              'value'     => 'knitting',
            ),
          ),
        ),
        array(
	        'id'          => 'wpcb-pattern-title',
	        'label'       => __( 'Pattern Name', 'wp-craft-blogger' ),
	        'desc'        => __( 'Leave blank to use post title', 'wp-craft-blogger' ),
	        'type'        => 'text',
        ),
        array(
	        'id'          => 'wpcb-pattern-rav_url',
	        'label'       => __( 'Ravelry URL', 'wp-craft-blogger' ),
	        'desc'        => __( 'If this pattern is available on Ravelry, enter the pattern URL here.', 'wp-craft-blogger' ),
	        'type'        => 'text',
        ),
        array(
	        'id'          => 'wpcb-pattern-gauge',
	        'label'       => __( 'Gauge', 'wp-craft-blogger' ),
	        'desc'        => 'e.g. "20sts over 4in in stockinette".',
	        'type'        => 'text',
        ),
        array(
	        'id' => 'wpcb-pattern-supplies_tab',
	        'label' => __( 'Supplies', 'wp-craft-blogger' ),
	        'type' => 'tab',
        ),
        array(
	        'id'          => 'wpcb-pattern-yarns',
	        'label'       => __( 'Yarn(s)', 'wp-craft-blogger' ),
	        'desc'        => __( 'The yarn or yarns required to complete this pattern.', 'wp-craft-blogger' ),
	        'type'        => 'list-item',
          'settings'    => array(
            array(
              'id'          => 'yardage',
              'label'       => __( 'Yardage', 'wp-craft-blogger' ),
              'type'        => 'text',
            ),
            array(
              'id'          => 'colorway',
              'label'       => __( 'Colorway', 'wp-craft-blogger' ),
              'type'        => 'text',
            ),
          ),
        ),
        array(
          'id'          => 'wpcb-pattern-tools',
          'label'       => __( 'Needles / Hooks', 'wp-craft-blogger' ),
          'desc'        => __( 'The hook / needle size(s) required to complete this pattern.', 'wp-craft-blogger' ),
          'type'        => 'list-item',
          'settings'    => array(
            array(
              'id'          => 'notes',
              'label'       => __( 'Notes', 'wp-craft-blogger' ),
              'desc'        => __( 'e.g. pattern is written for one circular needle, but substitute as preferred.', 'wp-craft-blogger' ),
              'type'        => 'textarea',
              'rows'        => 2,
            ),
          ),
        ),
        array(
          'id'          => 'wpcb-pattern-notions',
          'label'       => __( 'Other notions', 'wp-craft-blogger' ),
          'desc'        => __( 'Any extra bits and pieces', 'wp-craft-blogger' ),
          'type'        => 'list-item',
          'settings'    => array(
            array(
              'id'          => 'amount',
              'label'       => __( 'Amount', 'wp-craft-blogger' ),
              'desc'        => __( 'e.g. "3" buttons, "5yds" ribbon', 'wp-craft-blogger' ),
              'type'        => 'text',
            ),
            array(
              'id'          => 'notes',
              'label'       => __( 'Notes', 'wp-craft-blogger' ),
              'desc'        => __( 'Any special notes about this notion.', 'wp-craft-blogger' ),
              'type'        => 'textarea',
              'rows'        => 2,
            ),
          ),
        ),
        array(
	        'id' => 'wpcb-pattern-instructions_tab',
	        'label' => __( 'Instructions', 'wp-craft-blogger' ),
	        'type' => 'tab',
        ),
        array(
	        'id'          => 'wpcb-pattern-key',
	        'label'       => __( 'Key', 'wp-craft-blogger' ),
	        'desc'        => __( 'List abbreviations used in the pattern and their meaning.', 'wp-craft-blogger' ),
	        'type'        => 'textarea',
	        'rows'        => 4,
        ),
        array(
	        'id'          => 'wpcb-pattern-notes',
	        'label'       => __( 'Pattern Notes', 'wp-craft-blogger' ),
	        'desc'        => __( 'Any extra notes or special instructions, e.g. "Please be sure to read the pattern thoroughly as some instructions are carried out at the same time".', 'wp-craft-blogger' ),
	        'type'        => 'textarea',
	        'rows'        => 4,
        ),
        array(
          'id'          => 'wpcb-pattern-instructions',
          'label'       => __( 'Instructions', 'wp-craft-blogger' ),
          'desc'        => __( 'Add as many instructions as required, you can also upload an image to each instruction.', 'wp-craft-blogger' ),
          'type'        => 'list-item',
          'settings'    => array(
            array(
              'id'          => 'instruction_text',
              'label'       => __( 'Instruction text', 'wp-craft-blogger' ),
              'type'        => 'textarea',
              'rows'        => '10',
            ),
            array(
              'id'          => 'instruction_image',
              'label'       => __( 'Instruction image', 'wp-craft-blogger' ),
              'type'        => 'upload',
            ),
          )
        ),
      )
    );

    if ( function_exists( 'ot_register_meta_box' ) ) {
        ot_register_meta_box( $pattern_meta_box );
    }

	}

	/**
	 * Filter the required "title" field for list-item option types.
	 *
	 * @since    0.0.1
	 */
  function filter_list_item_title_label( $label, $id ) {

    if ( $id == 'wpcb-pattern-yarns' ) {
      $label = __( 'Yarn name', 'wp-craft-blogger' );
    }

    if ( $id == 'wpcb-pattern-tools' ) {
      $label = __( 'Size', 'wp-craft-blogger' );
    }

    if ( $id == 'wpcb-pattern-notions' ) {
      $label = __( 'Notion', 'wp-craft-blogger' );
    }

    return $label;

  }

	//TODO Next two functions are terribad

	/**
	 * Filter the OptionTree header logo link
	 *
	 * @since    0.0.1
	 */
  function filter_header_logo_link() {

		$screen = get_current_screen();
		if( $screen->id == 'pattern_page_wpcb-settings' ) {
			return '';
		} else {
			return '<a href="http://wordpress.org/extend/plugins/option-tree/" target="_blank">OptionTree</a>';
		}

  }

	/**
	 * Filter the OptionTree header version text
	 *
	 * @since    0.0.1
	 */
  function filter_header_version_text() {

		$screen = get_current_screen();
		if( $screen->id == 'pattern_page_wpcb-settings' ) {
			return '<a href="http://wordpress.org/plugins/wp-craft-blogger" target="_blank">' . $this->plugin_name . ' - v' . $this->version . '</a>';
		} else {
			return 'OptionTree ' . OT_VERSION;
		}

  }


	/**
	 * Registers a new global pattern settings page.
	 *
	 * @since    0.0.1
	 */
	public function register_pattern_settings_page() {

	  // Only execute in admin & if OT is installed
  	if ( is_admin() && function_exists( 'ot_register_settings' ) ) {

	    // Register the page
    	ot_register_settings(
        array(
      		array(
            'id'              => 'wpcb_global_settings',
            'pages'           => array(
              array(
	              'id'              => 'wpcb-pattern-settings',
	              'parent_slug'     => 'edit.php?post_type=pattern',
	              'page_title'      => __( 'WP Craft Blogger - Global Settings', 'wp-craft-blogger' ),
	              'menu_title'      => __( 'Settings', 'wp-craft-blogger' ),
	              'capability'      => 'edit_theme_options',
	              'menu_slug'       => 'wpcb-settings',
	              'icon_url'        => null,
	              'position'        => null,
	              'updated_message' => __( 'Settings updated', 'wp-craft-blogger' ),
	              'reset_message'   => __( 'Settings reset', 'wp-craft-blogger' ),
	              'button_text'     => __( 'Save changes', 'wp-craft-blogger' ),
	              'show_buttons'    => true,
	              'screen_icon'     => 'options-general',
	              'contextual_help' => null,
	              'sections'        => array(
	                array(
	                  'id'          => 'wpcb-general',
	                  'title'       => __( 'General', 'wp-craft-blogger' ),
	                ),
	                array(
	                  'id'        => 'wpcb-ravelry-integration',
	                  'title'     => __( 'Ravelry Integration', 'wp-craft-blogger' ),
	                )
	              ),
                'settings'        => array(
	            		array(
	                  'id'          => 'wpcb-primary-craft',
	                  'label'       => __( 'Primary craft', 'wp-craft-blogger' ),
	                  'desc'        => __( 'Will be selected automatically when adding a new pattern.', 'wp-craft-blogger' ),
	                  'std'         => 'knitting',
	                  'type'        => 'radio',
	                  'choices'     => array(
	                    array(
	                      'label'     => __( 'Crochet', 'wp-craft-blogger' ),
	                      'value'     => 'crochet',
	                    ),
	                    array(
	                      'label'     => __( 'Knitting', 'wp-craft-blogger' ),
	                      'value'     => 'knitting',
	                    ),
	                  ),
	                  'section'     => 'wpcb-general',
	                ),
                  array(
                    'id'        => 'wpcb-custom-css',
                    'label'     => __( 'Custom CSS', 'wp-craft-blogger' ),
                    'desc'      => __( 'Advanced users can change the look and feel of patterns by adding your custom styles here. '
                    . 'The container div for patterns is .wpcb-pattern', 'wp-craft-blogger' ),
                    'type'      => 'css',
                    'section'   => 'wpcb-general',
                  ),
                  array(
                    'id'        => 'wpcb-ravelry-integration-notice',
                    'label'     => __( 'Ravelry', 'wp-craft-blogger' ),
                    'desc'      => __( 'Coming soon!', 'wp-craft-blogger' ),
                    'type'      => 'textblock_titled',
                    'section'   => 'wpcb-ravelry-integration',
                  ),
                )
              )
            )
          )
        )
	    );

	  }

	}

}
