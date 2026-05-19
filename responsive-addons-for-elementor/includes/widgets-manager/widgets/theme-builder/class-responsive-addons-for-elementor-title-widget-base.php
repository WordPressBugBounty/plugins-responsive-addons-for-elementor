<?php
/**
 * File containing abstract class Responsive_Addons_For_Elementor_Title_Widget_Base.
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Heading as Elementor_Widget_Heading;

/**
 * Abstract base class for title widgets in Responsive Addons for Elementor.
 */
abstract class Responsive_Addons_For_Elementor_Title_Widget_Base extends Elementor_Widget_Heading {
	/**
	 * Abstract method to get the dynamic tag name.
	 *
	 * @return string The dynamic tag name.
	 */
	abstract protected function get_dynamic_tag_name();

	/**
	 * Checks whether to show the page title or not.
	 *
	 * @return bool Whether to show the page title or not.
	 */
	protected function rae_should_show_page_title() {

		 $document = null;

		if ( Plugin::instance()->preview->is_preview_mode() ) {
			$preview_id = Plugin::instance()->preview->get_post_id();
			if ( $preview_id ) {
				$document = Plugin::instance()->documents->get( $preview_id );
			}
		}

		if ( ! $document && Plugin::instance()->documents->get_current() ) {
			$document = Plugin::instance()->documents->get_current();
		}

		// fallback
		if ( ! $document ) {
			$document = Plugin::instance()->documents->get( get_the_ID() );
		}

		if ( $document && 'yes' === $document->get_settings( 'hide_title' ) ) {
			return false;
		}

		return true;
	
	}

	/**
	 * Registers controls for the title widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$dynamic_tag_name = $this->get_dynamic_tag_name();
		if ($this->get_controls('title')) {
			$this->update_control(
				'title',
				array(
					'dynamic' => array(
						'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text(null, $dynamic_tag_name),
					),
				),
				array(
					'recursive' => true,
				)
			);
		}
		if ($this->get_controls('header_size')) {
			$this->update_control(
				'header_size',
				array(
					'default' => 'h1',
				)
			);
		}
		if ($this->get_controls('size')) {
			$this->update_control(
				'size',
				array(
					'condition' => array(),
				)
			);
		}
		
	}

	/**
	 * Renders the title widget.
	 */
	protected function render() {
		 if ( ! $this->rae_should_show_page_title() ) {
			if ( Plugin::instance()->editor->is_edit_mode() ) {
				echo '<div class="elementor-alert elementor-alert-info">'
					. esc_html__( 'Title is hidden via Post Settings → Hide Title.', 'responsive-addons-for-elementor' )
					. '</div>';
			}
			return;
		}

		parent::render();
	}
}
