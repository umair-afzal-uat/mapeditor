<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class OSF_Elementor_Team extends OSF_Elementor_Carousel_Base {

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
        return 'opal-teams';
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
        return __('Opal Teams', 'auros-core');
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
        return 'eicon-person';
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
            'section_team',
            [
                'label' => __('Teams', 'auros-core'),
            ]
        );


        $this->add_control(
            'options',
            [
                'label' => __('Additional Options', 'auros-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'team_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
                'default'   => 'full',
                'separator' => 'none',
            ]
        );


        $this->add_responsive_control(
            'column',
            [
                'label'   => __('Columns', 'auros-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => __('Style', 'auros-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => array(
                    'style-1' => esc_html__('Style 1', 'auros-core'),
                    'style-2' => esc_html__('Style 2', 'auros-core'),
                    'style-3' => esc_html__('Style 3', 'auros-core'),
                ),
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => __('View', 'auros-core'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('name', [
            'label'   => __('Name', 'auros-core'),
            'default' => 'John Doe',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('image', [
            'label'      => __('Choose Image', 'auros-core'),
            'default'    => [
                'url' => Elementor\Utils::get_placeholder_image_src(),
            ],
            'type'       => Controls_Manager::MEDIA,
            'show_label' => false,
        ]);

        $repeater->add_control('job', [
            'label'   => __('Job', 'auros-core'),
            'default' => 'Designer',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('link', [
            'label'       => __('Link to', 'auros-core'),
            'placeholder' => __('https://your-link.com', 'auros-core'),
            'type'        => Controls_Manager::URL,
        ]);

        $repeater->add_control('facebook', [
            'label'   => __('Facebook', 'auros-core'),
            'default' => 'www.facebook.com/opalwordpress',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('twitter', [
            'label'   => __('Twitter', 'auros-core'),
            'default' => 'https://twitter.com/opalwordpress',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('youtube', [
            'label'   => __('Youtube', 'auros-core'),
            'default' => 'https://www.youtube.com/user/WPOpalTheme',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('google', [
            'label'   => __('Google', 'auros-core'),
            'default' => 'https://plus.google.com/u/0/+WPOpal',
            'type'    => Controls_Manager::TEXT,
        ]);

        $repeater->add_control('pinterest', [
            'label'   => __('Pinterest', 'auros-core'),
            'default' => 'https://www.pinterest.com/',
            'type'    => Controls_Manager::TEXT,
        ]);

        $this->add_control(
            'teams',
            [
                'label'       => __('Team Item', 'auros-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{name}}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Option
        $this->add_control_carousel();

        // Name.
        $this->start_controls_section(
            'section_style_team_name',
            [
                'label' => __('Name', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_text_color',
            [
                'label'     => __('Text Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-name, {{WRAPPER}} .elementor-team-name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'scheme'   => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-team-name',
            ]
        );

        $this->end_controls_section();

        // Job.
        $this->start_controls_section(
            'section_style_team_job',
            [
                'label' => __('Job', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'job_text_color',
            [
                'label'     => __('Text Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_2,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'scheme'   => Schemes\Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-team-job',
            ]
        );

        $this->end_controls_section();

        // Information.
        $this->start_controls_section(
            'section_style_team_information',
            [
                'label' => __('Information', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'information_text_color',
            [
                'label'     => __('Text Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-infomation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'information_typography',
                'scheme'   => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-team-infomation',
            ]
        );

        $this->end_controls_section();

        // Information.
        $this->start_controls_section(
            'section_style_team_social',
            [
                'label' => __('Social', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => __('Social Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-socials li.social .fa' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label'     => __('Social Hover Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-team-socials li.social .fa:hover' => 'color: {{VALUE}};',
                ],
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
        if (!empty($settings['teams']) && is_array($settings['teams'])) {

            $this->add_render_attribute('wrapper', 'class', 'elementor-teams-wrapper');
            $this->add_render_attribute('wrapper', 'class', $settings['style']);

            // Row
            $this->add_render_attribute('row', 'class', 'row');
            if ($settings['enable_carousel'] === 'yes') {
                $this->add_render_attribute('row', 'class', 'owl-carousel owl-theme');
                $carousel_settings = $this->get_carousel_settings();
                $this->add_render_attribute('row', 'data-settings', wp_json_encode($carousel_settings));
            }

            $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
            if (!empty($settings['column_tablet'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
            }
            if (!empty($settings['column_mobile'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
            }

            // Item
            $this->add_render_attribute('item', 'class', 'elementor-team-item');

            $this->add_render_attribute('meta', 'class', 'elementor-team-meta');

            ?>
            <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
                <div <?php echo $this->get_render_attribute_string('row') ?>>
                    <?php
                    foreach ($settings['teams'] as $team): ?>
                        <div <?php echo $this->get_render_attribute_string('item'); ?>>
                            <?php call_user_func(array($this, 'render_' . str_replace('-', '_', $settings['style'])), $team, $settings) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
    }

    protected function render_style_1($team, $settings) { ?>
        <div class="elementor-team-meta-inner">
            <div class="elementor-team-image">
                <?php
                $team['team_image_size']             = $settings['team_image_size'];
                $team['team_image_custom_dimension'] = $settings['team_image_custom_dimension'];
                if (!empty($team['image']['url'])) :
                    $image_html = Group_Control_Image_Size::get_attachment_image_html($team, 'image');
                    echo $image_html;
                endif;
                ?>
            </div>
            <div class="elementor-team-details">
                <?php
                $team_name_html = $team['name'];
                if (!empty($team['link']['url'])) :
                    $team_name_html = '<a href="' . esc_url($team['link']['url']) . '">' . $team_name_html . '</a>';
                endif;
                ?>
                <div class="elementor-team-name"><?php echo $team_name_html; ?></div>
                <div class="elementor-team-job"><?php echo $team['job']; ?></div>
            </div>
        </div>
        <?php
    }

    protected function render_style_2($team, $settings) { ?>
        <div class="elementor-team-meta-inner">
            <div class="elementor-team-image">
                <?php
                $team['team_image_size']             = $settings['team_image_size'];
                $team['team_image_custom_dimension'] = $settings['team_image_custom_dimension'];
                if (!empty($team['image']['url'])) :
                    $image_html = Group_Control_Image_Size::get_attachment_image_html($team, 'image');
                    echo $image_html;
                endif;
                ?>
            </div>
            <div class="elementor-team-details">
                <?php
                $team_name_html = $team['name'];
                if (!empty($team['link']['url'])) :
                    $team_name_html = '<a href="' . esc_url($team['link']['url']) . '">' . $team_name_html . '</a>';
                endif;
                ?>
                <div class="elementor-team-name"><?php echo $team_name_html; ?></div>
                <div class="elementor-team-job"><?php echo $team['job']; ?></div>
            </div>
        </div>
        <?php
    }

    protected function render_style_3($team, $settings) { ?>
        <div class="elementor-team-meta-inner">
            <div class="elementor-team-image">
                <?php
                $team['team_image_size']             = $settings['team_image_size'];
                $team['team_image_custom_dimension'] = $settings['team_image_custom_dimension'];
                if (!empty($team['image']['url'])) :
                    $image_html = Group_Control_Image_Size::get_attachment_image_html($team, 'image');
                    echo $image_html;
                endif;
                ?>
            </div>
            <div class="elementor-team-details">
                <?php
                $team_name_html = $team['name'];
                if (!empty($team['link']['url'])) :
                    $team_name_html = '<a href="' . esc_url($team['link']['url']) . '">' . $team_name_html . '</a>';
                endif;

                ?>
                <div class="elementor-team-name"><?php echo $team_name_html; ?></div>
                <div class="elementor-team-job"><?php echo $team['job']; ?></div>
                <div class="elementor-team-socials">
                    <ul class="socials">
                        <?php foreach ($this->get_socials() as $key => $social): ?>
                            <?php if (!empty($team[$key])) : ?>
                                <li class="social">
                                    <a href="<?php echo esc_url($team[$key]) ?>">
                                        <i class="fa <?php echo esc_attr($social); ?>"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    private function get_socials() {
        return array(
            'facebook'  => 'fa-facebook',
            'twitter'   => 'fa-twitter',
            'youtube'   => 'fa-youtube',
            'google'    => 'fa-google-plus',
            'pinterest' => 'fa-pinterest'
        );
    }

}

$widgets_manager->register_widget_type(new OSF_Elementor_Team());
