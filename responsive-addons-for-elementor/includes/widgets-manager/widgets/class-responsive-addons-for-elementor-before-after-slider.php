<?php
/**
 * RAEL Before After Slider Widget
 *
 * @since      2.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Control_Media;
use Responsive_Addons_For_Elementor\Helper\Helper;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * RAEL Before After Slider Widget
 */
class Responsive_Addons_For_Elementor_Before_After_Slider extends Widget_Base {
    /**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL Before After Slider' widget name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name(){
        return 'rael-before-after-slider';
    }

    /**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL Before After Slider' widget title.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Before After Slider', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL Before After Slider' widget icon.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-before-after rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Before After Slider widget belongs to.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

    /**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'imagesloaded',
            'rael-twentytwenty',
            'rael-event-move',
		);
	}

    /**
	 * Register BeforeAfterSlider controls.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {
        //Content Tab
        $this->rael_before_image_settings();
        $this->rael_after_image_settings();
        $this->rael_orientation_settings();
        $this->rael_comparison_handler_settings();

        //Styles Tab
        $this->rael_before_after_style_settings();
    }
     
    protected function rael_before_image_settings(){
        $this->start_controls_section(
            'rael_section_before_image',
            array(
                'label' => __( 'Before Image', 'responsive-addons-for-elementor' ),
            )
        );

         $this->add_control(
            'rael_before_image',
            array(
                'label' => __( 'Before Image Input', 'responsive-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default'   => 'media',
                'label_block' => true,
                'options' => array(
                    'media' => __( 'Media', 'responsive-addons-for-elementor' ),
                    'url' => __( 'URL', 'responsive-addons-for-elementor' ),
                ),
            )
        );

        $this->add_control(
            'rael_before_image_media',
            array(
                'label' => __( 'Before Photo', 'responsive-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
				'default'       => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'       => array( 
                    'active' => true 
                ),
                'condition' => array(
                    'rael_before_image' => 'media',
                ),
            )
        );

        
        $this->add_control(
            'rael_before_image_url',
            array(
                'label' => __( 'Before Image URL', 'responsive-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'https://your-image.com/before.jpg', 'responsive-addons-for-elementor' ),  
                'condition' => array(
                    'rael_before_image' => 'url',
                ),
                'label_block' => true,
            ),
        );

        $this->add_group_control(
			Group_Control_Image_Size::get_type(), // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
			array(
				'name'      => 'rael_before_image_media',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'rael_before_image' => 'media',
				),
			)
		);
        

        $this->add_control(
            'rael_before_image_label',
            array(
                'label' => __('Before Image Label', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Before', 'responsive-addons-for-elementor'),
                'dynamic'   => array(
					'active' => true,
				),
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-before-label:before' => 'content: "{{VALUE}}";',
                ),
            )
        );


		$this->end_controls_section();

    }

    protected function rael_after_image_settings(){
        $this->start_controls_section(
            'rael_section_after_image',
            array(
                'label' => __( 'After Image', 'responsive-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'rael_after_image',
            array(
                'label' => __( 'After Image Input', 'responsive-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default'   => 'media',
                'label_block' => true,
                'options' => array(
                    'media' => __( 'Media', 'responsive-addons-for-elementor' ),
                    'url' => __( 'URL', 'responsive-addons-for-elementor' ),
                ),
            )
        );

        $this->add_control(
            'rael_after_image_media',
            array(
                'label' => __( 'After Photo', 'responsive-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
				'default'       => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'       => array( 'active' => true ),
                'condition' => array(
                    'rael_after_image' => 'media',
                ),
            )
        );


        $this->add_control(
            'rael_after_image_url',
            array(
                'label' => __( 'After Image URL', 'responsive-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'https://your-image.com/after.jpg', 'responsive-addons-for-elementor' ),
                'condition' => array(
                    'rael_after_image' => 'url',
                ),
                'label_block' => true,
            )
        );

        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'rael_after_image_media', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'rael_after_image' => 'media',
				),
			)
		);
        

        $this->add_control(
            'rael_after_image_label',
            array(
                'label' => __('After Image Label', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('After', 'responsive-addons-for-elementor'),
                'dynamic'   => array(
					'active' => true,
				),
                'selectors' => array(
					'{{WRAPPER}} .twentytwenty-after-label:before' => 'content: "{{VALUE}}";',
				),
                
            )
        );

		$this->end_controls_section();
    }

    protected function rael_orientation_settings(){
        $this->start_controls_section(
            'rael_before_after_orientation_settings',
            array(
                'label' => __( 'Orientation', 'responsive-addons-for-elementor' ),
            )
        );


        $this->add_control(
            'rael_before_after_orientation',
            array(
                'label' => __('Orientation', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'horizontal',
                'options' => array(
                    'vertical' => array(
                        'title' => __('Vertical', 'responsive-addons-for-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'horizontal' => array(
                        'title' => __('Horizontal', 'responsive-addons-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'toggle' => false,
            )
        );
        
        $this->add_responsive_control(
            'rael_before_after_alignment',
            array(
                'label' => __('Alignment', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'responsive-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'responsive-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'responsive-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ),
                ),
                'selectors_dictionary' => array(
                    'left'   => 'margin-left: 0 !important; margin-right: auto !important;',
                    'center' => 'margin-left: auto !important; margin-right: auto !important;',
                    'right'  => 'margin-left: auto !important; margin-right: 0 !important;',
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '{{VALUE}}',
                ),
                'toggle' => false,
            )
        );



        $this->add_control(
            'rael_before_after_move_on_hover',
            array(
                'label' => __('Move on Hover', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'label_on' => __('Yes', 'responsive-addons-for-elementor'),
                'label_off' => __('No', 'responsive-addons-for-elementor'),
            )
        );

        $this->add_control(
			'rael_before_after_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.5)',
               	'selectors' => array(
					'{{WRAPPER}} .twentytwenty-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);


		$this->end_controls_section();
    }

    protected function rael_comparison_handler_settings(){
        $this->start_controls_section(
            'rael_section_comparison_handle',
            array(
                'label' => __( 'Comparison Handle', 'responsive-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'rael_before_after_comparison_handle_offset',
            array(
                'label' => __('Offset', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
                'default' => array(
                    'size' => 50,
                ),
                'range' => array(
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                    ),
                'label_block' => true,
            )
        );


        $this->add_control(
            'rael_before_after_comparison_handle_color',
            array(
                'label' => __('Handle Color', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-handle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle::before' => 'background:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle::after' => 'background: {{VALUE}};',
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-right-color:  {{VALUE}};',
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}};',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-right-color: {{VALUE}};',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-left-color:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-up-arrow' => 'border-bottom-color:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-down-arrow' => 'border-top-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'rael_before_after_comparison_thickness',
            array(
                'label' => __('Thickness', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
                'default' => array(
                    'size' => 5,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 15,
                    )
                ),
                'selectors' => array(
                   '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle::before' => 'width: {{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle::after' => 'width: {{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-handle' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle::before' => 'height: {{SIZE}}{{UNIT}}; margin-top:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle::after' => 'height: {{SIZE}}{{UNIT}}; margin-top:calc( -{{SIZE}}{{UNIT}}/2 );',
				),
            )
        );

        $this->add_control(
            'rael_before_after_comparison_circle_width',
            array(
                'label' => __('Circle Width', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
                'default' => array(
                    'size' => 40,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-handle' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 - {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} ); margin-top:calc( -{{SIZE}}{{UNIT}}/2 - {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before' => 'margin-bottom: calc( ( {{SIZE}}{{UNIT}} + ( {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after' => 'margin-top: calc( ( {{SIZE}}{{UNIT}} + ( {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:before' => 'margin-left: calc( ( {{SIZE}}{{UNIT}} + ( {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:after' => 'margin-right: calc( ( {{SIZE}}{{UNIT}} + ( {{rael_before_after_comparison_thickness.size}}{{rael_before_after_comparison_thickness.unit}} * 2 ) ) / 2 );',
                ),
            )
        );

        $this->add_control(
            'rael_before_after_comparison_circle_radius',
            array(
                'label' => __('Circle Radius', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'range' => array(
                    '%' => array(
                        'max' => 100,
                    )
                ),
                'selectors'  => array(
					'{{WRAPPER}} .twentytwenty-handle' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
            )
        );

        $this->add_control(
            'rael_before_after_comparison_triangle_size',
            array(
                'label' => __('Triangle Size', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
                'default' => array(
                    'size' => 6,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    )
                ),
                'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-right-width: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-right-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-left-arrow, {{WRAPPER}} .twentytwenty-right-arrow, {{WRAPPER}} .twentytwenty-up-arrow, {{WRAPPER}} .twentytwenty-down-arrow' => 'border-width: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'.rtl {{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
				),
            )
        );

		$this->end_controls_section();
    }

   
    /**
	 * Register card style controls.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function rael_before_after_style_settings() {
        $this->start_controls_section(
            'rael_section_before_after_style',
            array(
                'label' => __( 'Before/After Label', 'responsive-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        
		$this->add_control(
			'typography',
			array(
				'label' => __( 'Before/After Label', 'responsive-addons-for-elementor'  ),
				'type'  => Controls_Manager::HEADING,
			)
		);

        $this->add_control(
            'rael_before_after_label_hover_normal',
            array(
                'label' => __('Show Label On', 'responsive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'label_block' => true,
                'options' => array(
                    'hover' => __('Hover only', 'responsive-addons-for-elementor'),
                    'normal' => __('Normal only', 'responsive-addons-for-elementor'),
                    'both' => __('Hover and Normal', 'responsive-addons-for-elementor'),
                ),
				'prefix_class' => 'rael-ba-label-',
            )
        );


        $this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_before_after_label_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			)
		);

		$this->add_control(
			'rael_before_after_label_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}}  .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'color: {{VALUE}};',
                ),
            )
		);

		$this->add_control(
			'rael_before_after_label_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'background-color: {{VALUE}};',
                ),
			)
		);

		$this->add_responsive_control(
			'rael_before_after_label_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_before_after_label_horizontal_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'flex-start' => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon' => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'      => 'flex-start',
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-before-label, {{WRAPPER}} .twentytwenty-after-label' => 'align-items: {{VALUE}};',
                ),
				'prefix_class' => 'rael%s-ba-halign-',
				'toggle'       => false,
				'condition'    => array(
					'rael_before_after_orientation' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'rael_before_after_label_vertical_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'flex-start' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'flex-start',
				'toggle'       => false,
                'selectors' => array(
                    '{{WRAPPER}} .twentytwenty-before-label, {{WRAPPER}} .twentytwenty-after-label' => 'justify-content: {{VALUE}};',     
                ),
				'prefix_class' => 'rael%s-ba-valign-',
                'condition'    => array(
					'rael_before_after_orientation' => 'vertical',
				),

			)
		);
   
        $this->end_controls_section();
    }

    /**
	 * Get Custom help URL
	 *
	 * @since 2.0.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/before-after-slider/';
	}


    protected function get_image_src( $position ) {
	$settings  = $this->get_settings_for_display();
	$image_src = '';

	if ( 'before' === $position ) {
		if ( 'media' === $settings['rael_before_image'] ) {
			if ( ! empty( $settings['rael_before_image_media']['id'] ) ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src(
					$settings['rael_before_image_media']['id'],
					'rael_before_image_media',
					$settings
				);
			} else {
				// Fallback to Elementor placeholder
				$image_src = ! empty( $settings['rael_before_image_media']['url'] )
					? $settings['rael_before_image_media']['url']
					: Utils::get_placeholder_image_src();
			}
		} elseif ( 'url' === $settings['rael_before_image'] && ! empty( $settings['rael_before_image_url'] ) ) {
			$image_src = esc_url( $settings['rael_before_image_url'] );
		}
	} elseif ( 'after' === $position ) {
		if ( 'media' === $settings['rael_after_image'] ) {
			if ( ! empty( $settings['rael_after_image_media']['id'] ) ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src(
					$settings['rael_after_image_media']['id'],
					'rael_after_image_media',
					$settings
				);
			} else {
				// Fallback to Elementor placeholder
				$image_src = ! empty( $settings['rael_after_image_media']['url'] )
					? $settings['rael_after_image_media']['url']
					: Utils::get_placeholder_image_src();
			}
		} elseif ( 'url' === $settings['rael_after_image'] && ! empty( $settings['rael_after_image_url'] ) ) {
			$image_src = esc_url( $settings['rael_after_image_url'] );
		}
	}

	return $image_src;
}



    /**
	 * Render widget.
	 *
	 * 	@access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $node_id = $this->get_id();
        ob_start();
        $before_image = $this->get_image_src( 'before' );
        $after_image = $this->get_image_src( 'after' );
        ?>
          <div class="rael-before-after-slider">
            <div class="rael-before-after-container" data-move-on-hover="<?php echo esc_attr( $settings['rael_before_after_move_on_hover'] ); ?>" data-orientation="<?php echo esc_attr( $settings['rael_before_after_orientation'] ); ?>" data-offset="<?php echo esc_attr( ( $settings['rael_before_after_comparison_handle_offset']['size'] / 100 ) ); ?>">
				<img class="rael-before-img" style="position: absolute;" src="<?php echo esc_url( $before_image ); ?>" alt="<?php echo esc_attr( $settings['rael_before_image_label'] ); ?>"/>
				<img class="rael-after-img" src="<?php echo esc_url( $after_image ); ?>" alt="<?php echo esc_attr( $settings['rael_after_image_label'] ); ?>"/>
			</div>
		</div>
        <?php
        $html = ob_get_clean();
		echo wp_kses_post( $html );
    }
    
    /**
     * Render Before After Slider widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.0.0
     * @access protected
     */
    protected function content_template() {
        ?>
        <#
        var before_img = '';
        var after_img = '';

        if( 'media' == settings.rael_before_image ) {
            var before_image = {
                id: settings.rael_before_image_media.id,
                url: settings.rael_before_image_media.url,
                size: settings.rael_before_image_media_size,
                dimension: settings.rael_before_image_media_custom_dimension,
                model: view.getEditModel()
            };
            before_img = elementor.imagesManager.getImageUrl( before_image );
        } else {
            before_img = _.escape( settings.rael_before_image_url );
        }

        if( 'media' == settings.rael_after_image ) {
            var after_image = {
                id: settings.rael_after_image_media.id,
                url: settings.rael_after_image_media.url,
                size: settings.rael_after_image_media_size,
                dimension: settings.rael_after_image_media_custom_dimension,
                model: view.getEditModel()
            };
            after_img = elementor.imagesManager.getImageUrl( after_image );
        } else {
            after_img = _.escape( settings.rael_after_image_url );
        }

        if ( ! before_img || ! after_img ) {
            return;
        }

        #>
        <div class="rael-before-after-slider">
            <div class="rael-before-after-container" data-move-on-hover="{{settings.rael_before_after_move_on_hover}}" data-orientation="{{settings.rael_before_after_orientation}}" data-offset="{{settings.rael_before_after_comparison_handle_offset.size/100}}">
                <img class="rael-before-img" style="position: absolute;" src="{{before_img}}" alt="{{elementor.helpers.sanitize(settings.rael_before_image_label)}}"/>
                <img class="rael-after-img" src="{{after_img}}" alt="{{elementor.helpers.sanitize(settings.rael_after_image_label)}}"/>
            </div>
        </div>
        <# elementorFrontend.hooks.doAction( 'frontend/element_ready/rael-before-after-slider.default' ); #>
        <?php
    }
};