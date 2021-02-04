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

		add_shortcode( 'ddr_select', [ $this, 'dropdown_redirect_select' ] );
		add_shortcode( 'ddr_option', [ $this, 'dropdown_redirect_select_option' ] );

	}

	/**
	 * Dropdown Redirect Select Field
	 */
	public function dropdown_redirect_select( $atts = [], $content = null, $tag = '' ) {

		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$atts = shortcode_atts( array(
			// Update the default Values
			'placeholder' => esc_html__( 'Please Select', 'dropdown-redirect' ),
			'heading'     => esc_html__( 'I need Something for', 'dropdown-redirect' ),
			'class'       => '',
			'target'      => '_blank'
		), $atts );

		$atts['class']  = $this->sanitize_shortcode_attr_class( $atts['class'] );
		$atts['target'] = sanitize_key( $atts['target'] );

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
			'<div id="ddr-instance-%s" class="dropdown-redirect-container %s"><span class="ddr-heading">%s</span><span class="ddr-select-cont"><select name="" id="" class="ddr-select">',
			$this->counter,
			$atts['class'],
			esc_html( $atts['heading'] )
		);
		$output .= do_shortcode( '[ddr_option title="' . $atts['placeholder'] . '" url=""]' );
		$output .= do_shortcode( $content );
		$output .= '  </select></span></div>';

		$output .= "<script>(function($){
					$('#ddr-instance-" . $this->counter . " .ddr-select-cont select.ddr-select').change(function(){
						var getValue = $(this).val();
				    if(getValue !== ''){
				      window.open(getValue, '" . $atts['target'] . "');      }
					});
				}(jQuery));</script>";

		// Increase the counter for next shortcode
		$this->counter ++;

		// return output
		return $output;

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
	 * Dropdown Redirect Select Option Shortcode
	 * This shortcode is used to create Options in the select field
	 */
	public function dropdown_redirect_select_option( $atts = [], $content = null, $tag = '' ) {


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