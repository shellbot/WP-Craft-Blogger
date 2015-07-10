<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    WP_Craft_Blogger
 * @subpackage WP_Craft_Blogger/public
 * @author     Shellbot <hi@codebyshellbot.com>
 */
class WPCB_Public {

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
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpcb-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register individual pattern shortcode [wpcb_pattern]
	 *
	 * @since    0.0.1
	 */
  public function add_shortcodes() {
  	add_shortcode( 'wpcb_pattern', array( $this, 'pattern_shortcode' ) );
  }

	/**
	 * Callback function for individual pattern shortcode [wpcb_pattern]
	 *
	 * @since    0.0.1
	 */
  public function pattern_shortcode( $atts ) {
	  extract( shortcode_atts(array(
    	'id' => '',
	  ), $atts, 'wpcb_pattern' ) );

	  //If no ID is set, do nothing.
	  if( $id == '') {
    	return;
	  }

	  $post = get_post( $id );

	  //If no matching pattern found, do nothing.
	  if( !$post || $post->post_type != 'pattern' ) {
    	return;
	  }

	  //ID is valid, show pattern.
	  return $this->output_pattern( $post );
  }

	/**
	 * Filter content and replace [pattern] shortcode with styled pattern.
	 *
	 * @since    0.0.1
	 */
	public function pattern_content_filter( $content ) {

	  global $post;

	  if ( get_post_type() == 'pattern' ) {
	  	remove_filter( 'the_content', array( $this, 'content_filter' ), 10 );

	    $pattern_output = apply_filters( 'wpcb_pattern_output', $this->output_pattern( $post ) );

	    if ( !post_password_required() && ( is_single() ) ) {
	        if( strpos( $content, '[pattern]' ) !== false ) {
	          // Replace shortcode with pattern
	          $content = str_replace( '[pattern]', $pattern_output, $content );
	        } else if( is_single() ) {
	          // Add pattern to the end of single pages or excerpts (unless there's a 'more' tag)
	          $content .= $pattern_output;
	        }
	    } else {
	      $content = str_replace( '[pattern]', '', $content ); // Remove shortcode from excerpt
	    }

	      add_filter( 'the_content', array( $this, 'pattern_content_filter' ), 10 );
	  }

	  return $content;
	}

	/**
	 * Output a pattern
	 *
	 * @since    0.0.1
	 */
	public function output_pattern( $post ) {

	  ob_start();
	  include( plugin_dir_path( __FILE__ ) . '/partials/wpcb-public-display.php' );
	  $output = ob_get_contents();
	  ob_end_clean();

	  return $output;

	}

  /**
	 * Include patterns in loop so they can be displayed among regular posts.
	 *
	 * @since    0.0.1
	 */
  function add_patterns_to_loop( $query ) {

	  global $pagenow;

	  if( $pagenow == 'edit.php' ) {
    	return;
	  }

    // Querying specific page (not set as home/posts page) or attachment
    if( !$query->is_home() ) {
      if( $query->is_page() || $query->is_attachment() ) {
        return;
      }
    }

    // Querying a specific taxonomy
    if( !is_null( $query->tax_query ) ) {
      $tax_queries = $query->tax_query->queries;
      $pattern_taxonomies = get_object_taxonomies( 'pattern' );

      if( is_array($tax_queries) ) {
        foreach( $tax_queries as $tax_query ) {
          if( isset( $tax_query['taxonomy'] ) && $tax_query['taxonomy'] !== '' && !in_array( $tax_query['taxonomy'], pattern_taxonomies ) ) {
          	return;
          }
        }
      }
    }

    $post_type = $query->get( 'post_type' );

    if( $post_type == '' || $post_type == 'post' ) {
      $post_type = array( 'post','pattern' );
    } else if( is_array($post_type) ) {
	    if( in_array('post', $post_type) && !in_array('pattern', $post_type) ) {
        $post_type[] = 'pattern';
	    }
		}

    $post_type = apply_filters( 'wpcb_query_posts', $post_type, $query );

    $query->set( 'post_type', $post_type );

    return;

  }

  /**
	 * Adds custom CSS from global settings page to site header.
	 *
	 * @since    0.0.1
	 */
  public function output_header_css() {

		$settings = get_option( 'wpcb_global_settings' );

	  if( $settings['wpcb-custom-css'] ) {
	    $css = '<style type="text/css">' . $settings['wpcb-custom-css'] . '</style>';
	    echo $css;
	  }

  }

}
