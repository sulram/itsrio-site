<?php
class Tema extends ET_Builder_Module {

	function init() {
		$this->name = esc_html__( 'ITS - Tema', 'et_builder' );
		$this->slug = 'et_pb_tema';
		$this->fb_support = true;

		$this->whitelisted_fields = ['title', 'subtitle','palestrante','data','content'];

		$this->fields_defaults = array(
			'title' => ['', 'add_default_setting'],
			'content' => ['', 'add_default_setting'],
			);

		$this->main_css_element = '%%order_class%% .et_pb_post';
	}

	function get_fields() {
		$palestrantes = [];
		$query_palestrantes = new WP_Query([
			'post_type' => 'palestrantes'
			]);

		while ($query_palestrantes->have_posts()) {
			$query_palestrantes->the_post();
			$palestrantes[get_the_ID()] = esc_html__( get_the_title(), 'et_builder' );
		}

		$fields = array(
			'title' => array(
				'label'             => esc_html__( 'Título', 'et_builder' ),
				'type'              => 'text',
				),
			'content' => array(
				'label'             => esc_html__( 'Conteúdo', 'et_builder' ),
				'type'              => 'tiny_mce',
				),
			);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $closed;
		global $wp_filter;
		global $paged;
		global $post;
		global $meta;
		global $data;

		$wp_filter_cache = $wp_filter;
		$title = $this->shortcode_atts['title'];
		$content   = wpautop($this->shortcode_content);

		$output = '';
		$data['its_tabs'][] = pll__('tema');

		ob_start();
		include(__DIR__.'/view_tema.php');
		$output = ob_get_contents();
		ob_end_clean();

		$wp_filter = $wp_filter_cache;
		unset($wp_filter_cache);
		wp_reset_postdata();

		return $output;
	}
}

new Tema;
