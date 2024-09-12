<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class OSF_Elementor_Metadata extends Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve testimonial widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'opal-metadata';
    }

    /**
     * Get widget title.
     *
     * Retrieve testimonial widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return __('Opal Portfolio Metadata', 'auros-core');
    }

    /**
     * Get widget icon.
     *
     * Retrieve testimonial widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-columns';
    }

    public function get_categories() {
        return array('opal-addons');
    }

    /**
     * Register testimonial widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_metadata',
            [
                'label' => __('Data', 'auros-core'),
            ]
        );


        $this->add_control(
            'style',
            [
                'label'   => __('Style', 'auros-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => array(
                    'horizontal' => esc_html__('Horizontal', 'auros-core'),
                    'vertical'   => esc_html__('Vertical', 'auros-core'),
                ),
            ]
        );

        $repeater = new  \Elementor\Repeater();

        $repeater->add_control('label', [
            'label'   => __('Label', 'auros-core'),
            'default' => 'Client',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('value', [
            'label'   => __('Value', 'auros-core'),
            'default' => 'Google',
            'type'    => Controls_Manager::TEXT,
        ]);

        $this->add_control(
            'data',
            [
                'label'       => __('Meta Item', 'auros-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{label}}}',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render testimonial widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['data']) && is_array($settings['data'])) {

            $this->add_render_attribute('wrapper', 'class', 'elementor-metadata-wrapper');
            $this->add_render_attribute('wrapper', 'class', $settings['style']);

            ?>
            <ul <?php echo $this->get_render_attribute_string('wrapper'); ?>>
                <?php foreach ($settings['data'] as $data): ?>
                    <li>
                        <span class="label"><?php echo esc_html($data['label']); ?></span>:
                        <span class="value"><?php echo esc_html($data['value']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
        }
    }
}

$widgets_manager->register_widget_type(new OSF_Elementor_Metadata());
