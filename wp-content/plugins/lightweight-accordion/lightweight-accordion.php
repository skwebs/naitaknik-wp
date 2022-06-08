<?php
/**
	 * Plugin Name: Lightweight Accordion
	 * Plugin URI: https://smartwp.com/lightweight-accordion
	 * Description: Extremely simple accordion for adding collapse elements to pages without affecting page load time. Works for Classic Editor via shortcode and Gutenberg via Block.
	 * Version: 1.5.11
	 * Text Domain: lightweight-accordion
	 * Author: Andy Feliciotti
	 * Author URI: https://smartwp.com
*/

//Enqueue CSS when in use
add_filter( 'the_content', 'enqueue_lightweight_accordion_styles' );
add_action( 'enqueue_block_editor_assets', 'enqueue_lightweight_accordion_styles' );
function enqueue_lightweight_accordion_styles($content = ""){
	global $post;
	$include_frontend_stylesheet = apply_filters( 'lightweight_accordion_include_frontend_stylesheet', true);
	$include_admin_stylesheet = apply_filters( 'lightweight_accordion_include_admin_stylesheet', true);
	
	$plugin_url = plugin_dir_url( __FILE__ );
	
	if( $include_frontend_stylesheet && ( isset($post->post_content) && has_shortcode( $post->post_content, 'lightweight-accordion') || has_block('lightweight-accordion/lightweight-accordion') ) ){
		wp_enqueue_style('lightweight-accordion', $plugin_url . 'lightweight-accordion.css', array(), '1.3.2');
	}
	
	if( $include_admin_stylesheet && ( is_admin() ) ){
		wp_enqueue_style('lightweight-accordion-admin-styles', $plugin_url . 'editor-styles.css', array(), '1.3.2');
	}
	
	return $content;
}

//Shortcode function to display lightweight accordion
function lightweight_accordion_shortcode( $atts, $content = null ) { 
	$atts = shortcode_atts( array(
		'anchor' => null,
		'title' => null,
		'title_tag' => 'span',
		'accordion_open' => false,
		'bordered' => false,
		'title_background_color' => false,
		'title_text_color' => false,
		'schema' => false,
		'class' => false
	), $atts, 'lightweight-accordion' );
	
	return render_lightweight_accordion( $atts, $content, false );
}
add_shortcode('lightweight-accordion', 'lightweight_accordion_shortcode');
add_shortcode('lightweight-accordion-nested', 'lightweight_accordion_shortcode');

//Block handler for Gutenberg
function lightweight_accordion_block_handler( $atts, $content ) {
	return render_lightweight_accordion( $atts, $content, true );
}

//Render the actual accordion
function render_lightweight_accordion( $options, $content, $isBlock ) {
	$output = '';
	
	$process_shortcodes = apply_filters( 'lightweight_accordion_process_shortcodes', true);
	
	if($process_shortcodes){
		$content = do_shortcode($content);
	}
	
	if(!$isBlock){
		$content = wpautop(preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', force_balance_tags($content)));
	}
	
	$anchor = '';
	if(isset($options['anchor']) && $options['anchor']){
		$anchor = ' id="'.$options['anchor'].'"';
	}
	
	$open = '';
	if($options['accordion_open']){
		$open = ' open';
	}
	
	$classes = array('lightweight-accordion');
	if($options['bordered']){
		$classes[] = 'bordered';
	}
	if(isset($options['class']) && $options['class']){
		$classes[] = $options['class'];
	}
	if(isset($options['className']) && $options['className']){
		$classes[] = $options['className'];
	}
	
	$bodyClasses = array('lightweight-accordion-body');
	
	$titleStyles = $bodyStyles = array();
	if($options['title_text_color']){
		$titleStyles[] = 'color:'.$options['title_text_color'];
		$classes[] = 'has-text-color';
	}
	if($options['title_background_color']){
		$titleStyles[] = 'background:'.$options['title_background_color'];
		$bodyStyles[] = 'border-color:'.$options['title_background_color'];
		$classes[] = 'has-background';
	}
	if(!empty($titleStyles)){
		$titleStyles = ' style="'.implode(';',$titleStyles).';"';
	}else{
		$titleStyles = '';
	}
	if(!empty($bodyStyles)){
		$bodyStyles = ' style="'.implode(';',$bodyStyles).';"';
	}else{
		$bodyStyles = '';
	}
	
	$propBox = $propTitle = $propContent = null;
	if(isset($options['schema']) && $options['schema'] == 'faq'){
		global $lightweight_accordion_schema;
		if ( !isset($lightweight_accordion_schema) || !is_array($lightweight_accordion_schema) ) {
			$lightweight_accordion_schema = array(
				'@context' => "https://schema.org",
				'@type' => 'FAQPage',
				'mainEntity' => array()
			);
		}
		$lightweight_accordion_schema['mainEntity'][] = array(
			'@type' => 'Question',
			'name' => $options['title'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text' => $content
			)
		);
		
		$output_microdata = apply_filters( 'lightweight_accordion_output_microdata', false);
		if($output_microdata){
			$propBox = ' itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"';
			$propTitle = ' itemprop="name"';
			$propContent = ' itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"';
			$content = ' <span itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><span itemprop="text">'.$content.'</span></span>';
			$lightweight_accordion_schema = null;
		}
	}
	
	if( $options['title'] && isset($content) ){
		$output .= '<div class="'.implode(' ', $classes).'"'.$anchor.'><details'.$propBox.''.$open.'><summary class="lightweight-accordion-title"'.$titleStyles.'><'.$options['title_tag'].''.$propTitle.'>'.$options['title'].'</'.$options['title_tag'].'></summary><div class="'.implode(' ', $bodyClasses).'"'.$bodyStyles.'>';
			$output .= $content;
		$output .= '</div></details></div>';
	}
	
	return $output;
}

//Output JSON-LD schema in the footer
add_action( 'wp_footer', 'lightweight_accordion_output_schema' );
function lightweight_accordion_output_schema() {
	global $lightweight_accordion_schema;

	if (is_array($lightweight_accordion_schema)) {
		$output = '<script type="application/ld+json" class="lightweight-accordion-faq-json">';
		$output .= wp_json_encode($lightweight_accordion_schema);
		$output .= '</script>';
		echo $output;
	}
}

//Register Gutenberg block
add_action('init', function () {
	// Skip block registration if Gutenberg is not enabled.
	if (!function_exists('register_block_type')) {
		return;
	}
	$dir = dirname(__FILE__);

	$index_js = 'block/index.js';
	wp_register_script(
		'lightweight-accordion',
		plugins_url($index_js, __FILE__),
		array(
			'wp-editor',
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components'
		),
		filemtime("$dir/$index_js")
	);

	register_block_type('lightweight-accordion/lightweight-accordion', array(
		'editor_script' => 'lightweight-accordion',
		'render_callback' => 'lightweight_accordion_block_handler',
		'attributes' => [
			'content' => [
				'type'    => 'array',
				'default' => 'Content'
			],
			'title' => [
				'type'    => 'string',
				'default' => 'Accordion Title'
			],
			'accordion_open' => [
				'type'    => 'boolean',
				'default' => false
			],
			'bordered' => [
				'type'    => 'boolean',
				'default' => false
			],
			'title_tag' => [
				'type'    => 'string',
				'default' => 'span'
			],
			'title_text_color' => [
				'type'    => 'string',
				'default' => false
			],
			'title_background_color' => [
				'type'    => 'string',
				'default' => false
			],
			'schema' => [
				'type'    => 'string',
				'default' => false
			]
		]
	));
});