<?php

namespace DropdownRedirect;
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if class already defined, bail out
if ( class_exists( 'DropdownRedirect\Shortcode' ) ) {
	return;
}


/**
 * This class will create meta boxes for Shortcodes
 *
 * @package    DropdownRedirect
 * @subpackage DropdownRedirect/includes
 * @author     Rao <raoabid491@gmail.com>
 */
class Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The counter of shortcode usage on the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $counter;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->counter     = 1;

		$this->register_shortcode_hooks();
	}

	/**
	 * Register Shortcode Hooks
	 */
	public function register_shortcode_hooks() {

		add_shortcode( 'ddr_select', [ $this, 'ddr_select' ] );
		add_shortcode( 'ddr_option', [ $this, 'ddr_option' ] );
		add_shortcode( 'cl_post_title', [ $this, 'caldera_learn_basic_blocks_post_title_shortcode_handler' ] );

	}

	/**
	 *
	 */
	public function caldera_learn_basic_blocks_post_title_shortcode_handler( $atts ) {
		$atts = shortcode_atts( [
			'id'      => 0,
			'heading' => 'h3',
		], $atts, 'cl_post_title' );

		return $this->caldera_learn_basic_blocks_post_title( $atts['id'], $atts['heading'] );
	}

	/**
	 * Output the post title wrapped in a heading
	 *
	 * @param int $post_id The post ID
	 * @param string $heading Allows : h2,h3,h4 only
	 *
	 * @return string
	 */
	public function caldera_learn_basic_blocks_post_title( $post_id, $heading ) {

		if ( ! in_array( $heading, [ 'h2', 'h3', 'h4' ] ) ) {
			$heading = 'h2';
		}
		$title = get_the_title( absint( $post_id ) );

		return "<$heading>$title</$heading>";
	}


	/**
	 * Dropdown Redirect Select Field
	 */
	public function ddr_select( $atts = [], $content = null, $tag = '' ) {

		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$atts = shortcode_atts( array(
			// Update the default Values
			'placeholder' => esc_html__( 'Please Select', 'dropdown-redirect' ),
			'heading'     => esc_html__( 'I need Something for', 'dropdown-redirect' ),
			'class'       => '',
			'target'      => '_blank',
			'id'          => 'ddr-instance-' . absint( $this->counter )
		), $atts );

		// Do Some Sanitization of $atts
		$atts['class']  = $this->sanitize_shortcode_attr_class( $atts['class'] );
		$atts['target'] = sanitize_key( $atts['target'] );
		$atts['id']     = sanitize_html_class( $atts['id'] );

		return $this->get_ddr_select_html_output( $atts, $content );

	}

	/**
	 *
	 */
	public function sanitize_shortcode_attr_class( $classes ) {

		$classes = array_map( function ( $class ) {
			return sanitize_html_class( $class );
		}, explode( ' ', $classes ) );

		return implode( ' ', $classes );

	}

	/**
	 *
	 */
	public function get_ddr_select_html_output( $atts, $content ) {

		// start output
		$output = '';

		$output .= '<style>
					.dropdown-redirect-container {
					    display: flex;
					    justify-content: flex-start;
					    align-items: baseline;
					    padding-bottom: 1em;
					}
					
					span.dr-select-cont {
					    flex-grow: 1;
					    padding-left: 0.5em;
					}
					
					span.dr-heading {
					    align-self: flex-end;
					}
					
					span.dr-select-cont select {
					    /* all: unset; */
					    margin-bottom: 0;
					}
				</style>';


		// Update output
		$output .= sprintf(
			'<div id="%s" class="dropdown-redirect-container %s"><span class="ddr-heading">%s</span><span class="ddr-select-cont"><select class="ddr-select">',
			$atts['id'],
			$atts['class'],
			esc_html( $atts['heading'] )
		);
		$output .= do_shortcode( '[ddr_option title="' . $atts['placeholder'] . '" url=""]' );
		$output .= do_shortcode( $content );
		$output .= '  </select></span></div>';
		$output .= "<script>(function($){ $('#" . $atts['id'] . " .ddr-select-cont select.ddr-select').change(function(){ var getValue = $(this).val(); if(getValue !== ''){ window.open(getValue, '" . $atts['target'] . "');}});}(jQuery));</script>";

		// Increase the counter for next shortcode instance
		$this->counter ++;

		// return output
		return $output;

	}

	/**
	 * Dropdown Redirect Select Option Shortcode
	 * This shortcode is used to create Options in the select field
	 */
	public function ddr_option( $atts = [], $content = null, $tag = '' ) {


		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$atts = shortcode_atts( array(
			// Update the default Values
			'title' => '',
			'url'   => '',
		), $atts );

		// Update output
		$output = sprintf( '<option value="%s">%s</option>',
			esc_url_raw( $atts['url'] ),
			esc_html( $atts['title'] )
		);

		// return output
		return $output;

	}


}