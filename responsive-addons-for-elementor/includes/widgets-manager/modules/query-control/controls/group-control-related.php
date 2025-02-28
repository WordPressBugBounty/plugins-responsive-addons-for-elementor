<?php
namespace Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Group_Control_Related extends Group_Control_Query {

	public static function get_type() {
		return 'rael-related-query';
	}

	/**
	 * Build the group-controls array
	 * Note: this method completely overrides any settings done in Group_Control_Posts
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	protected function init_fields_by_name( $name ) {
		$fields = parent::init_fields_by_name( $name );

		$tabs_wrapper = $name . '_query_args';
		$include_wrapper = $name . '_query_include';

		$fields['post_type']['options']['related'] = __( 'Related', 'responsive-addons-for-elementor' );
		$fields['include_term_ids']['condition']['post_type!'][] = 'related';
		$fields['related_taxonomies']['condition']['post_type'][] = 'related';
		$fields['include_authors']['condition']['post_type!'][] = 'related';
		$fields['exclude_authors']['condition']['post_type!'][] = 'related';
		$fields['avoid_duplicates']['condition']['post_type!'][] = 'related';
		$fields['offset']['condition']['post_type!'][] = 'related';

		$related_taxonomies = [
			'label' => __( 'Term', 'responsive-addons-for-elementor' ),
			'type' => Controls_Manager::SELECT2,
			'options' => $this->get_supported_taxonomies(),
			'label_block' => true,
			'multiple' => true,
			'condition' => [
				'include' => 'terms',
				'post_type' => [
					'related',
				],
			],
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab' => $include_wrapper,
		];

		$related_fallback = [
			'label' => __( 'Fallback', 'responsive-addons-for-elementor' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'fallback_none' => __( 'None', 'responsive-addons-for-elementor' ),
				'fallback_by_id' => __( 'Manual Selection', 'responsive-addons-for-elementor' ),
				'fallback_recent' => __( 'Recent Posts', 'responsive-addons-for-elementor' ),
			],
			'default' => 'fallback_none',
			'description' => __( 'Displayed if no relevant results are found. Manual selection display order is random', 'responsive-addons-for-elementor' ),
			'condition' => [
				'post_type' => 'related',
			],
			'separator' => 'before',
		];

		$fallback_ids = [
			'label' => __( 'Search & Select', 'responsive-addons-for-elementor' ),
			'type' => 'rael-query',
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'multiple' => true,
			'filter_type' => 'by_id',
			'condition' => [
				'post_type' => 'related',
				'related_fallback' => 'fallback_by_id',
			],
			'export' => false,
		];

		$fields = \Elementor\Utils::array_inject( $fields, 'include_term_ids', [ 'related_taxonomies' => $related_taxonomies ] );
		$fields = \Elementor\Utils::array_inject( $fields, 'offset', [ 'related_fallback' => $related_fallback ] );
		$fields = \Elementor\Utils::array_inject( $fields, 'related_fallback', [ 'fallback_ids' => $fallback_ids ] );

		return $fields;
	}

	protected function get_supported_taxonomies() {
		$supported_taxonomies = [];

		$public_types = $this->get_public_post_types();

		foreach ( $public_types as $type => $title ) {
			$taxonomies = get_object_taxonomies( $type, 'objects' );
			foreach ( $taxonomies as $key => $tax ) {
				if ( ! array_key_exists( $key, $supported_taxonomies ) ) {
					$label = $tax->label;
					if ( in_array( $tax->label, $supported_taxonomies ) ) {
						$label = $tax->label . ' (' . $tax->name . ')';
					}
					$supported_taxonomies[ $key ] = $label;
				}
			}
		}

		return $supported_taxonomies;
	}

	protected static function init_presets() {
		parent::init_presets();
		static::$presets['related'] = [
			'related_fallback',
			'fallback_ids',
		];
	}

	/**
	 * Get public post type list
	 *
	 * @param array $args
	 * @return array
	 */
	public function get_public_post_types( $args = array() ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility.
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return apply_filters( 'rael/core_elements/get_public_post_types', $post_types );
	}
}
