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
	
	abstract protected function get_dynamic_tag_name();

	/**
	 * Get the current document/page settings
	 */
	protected function get_current_document() {
		$document = null;
		
		// For editor preview
		if ( Plugin::instance()->preview->is_preview_mode() ) {
			$preview_id = Plugin::instance()->preview->get_post_id();
			if ( $preview_id ) {
				$document = Plugin::instance()->documents->get( $preview_id );
			}
		}
		
		// For frontend
		if ( ! $document && Plugin::instance()->documents->get_current() ) {
			$document = Plugin::instance()->documents->get_current();
		}
		
		// Fallback
		if ( ! $document ) {
			$document = Plugin::instance()->documents->get( get_the_ID() );
		}
		
		return $document;
	}

	/**
	 * Check if title should be hidden
	 */
	protected function is_title_hidden() {
		$document = $this->get_current_document();
		
		if ( $document && 'yes' === $document->get_settings( 'hide_title' ) ) {
			return true;
		}
		
		return false;
	}

	/**
	 * Register controls
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
	 * Render the widget
	 */
	protected function render() {
		$is_hidden = $this->is_title_hidden();
		
		// For frontend - PHP handles the hiding
		if ( $is_hidden && ! Plugin::instance()->editor->is_edit_mode() ) {
			// Don't render anything on frontend when hidden
			return;
		}
		
		// For editor mode - render with CSS hiding (so toggle works live)
		if ( $is_hidden && Plugin::instance()->editor->is_edit_mode() ) {
			// Add inline style to hide the title in editor
			echo '<style>.elementor-widget[data-id="' . esc_attr( $this->get_id() ) . '"] .elementor-heading-title { display: none !important; }</style>';
			echo '<div class="elementor-alert elementor-alert-info">'
				. esc_html__( 'Title is hidden via Post Settings → Hide Title.', 'responsive-addons-for-elementor' )
				. '</div>';
			return;
		}
		
		// Normal rendering when title is visible
		parent::render();
		
		// Add live preview JavaScript for editor mode
		if ( Plugin::instance()->editor->is_edit_mode() ) {
			$this->render_live_preview_script();
		}
	}
	
	/**
	 * Render live preview script for editor
	 */
		protected function render_live_preview_script() {
		?>
		<script type="text/javascript">
		(function($) {
			var checkInterval = setInterval(function() {
				if (window.elementor && window.elementor.settings && window.elementor.settings.page) {
					clearInterval(checkInterval);
					
					function applyHideTitleCSS(isHidden) {
						$('.elementor-widget-rael-theme-post-title').each(function() {
							var $widget = $(this);
							
							if (isHidden) {
								$widget.find('.elementor-heading-title').hide();
								$widget.addClass('rael-title-hidden');
								
								var $container = $widget.find('.elementor-widget-container');
								if ($container.length && !$widget.find('.rae-hidden-message').length) {
									$container.append('<div class="elementor-alert elementor-alert-info rae-hidden-message">Title is hidden via Post Settings → Hide Title.</div>');
								}
							} else {
								$widget.find('.elementor-heading-title').show();
								$widget.removeClass('rael-title-hidden');
								$widget.find('.rae-hidden-message').remove();
							}
						});
					}
					
					var settings = window.elementor.settings.page.getSettings();
					applyHideTitleCSS(settings.settings.hide_title === 'yes');
					
					window.elementor.settings.page.addChangeCallback('hide_title', function(value) {
						applyHideTitleCSS(value === 'yes');
					});
				}
			}, 500);
		})(jQuery);
		</script>
		<?php
	}
}