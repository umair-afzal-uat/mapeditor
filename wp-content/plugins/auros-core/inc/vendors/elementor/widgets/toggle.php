<?php

namespace Elementor;

use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor toggle widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class OSF_Widget_Toggle extends Widget_Toggle {

    protected function _register_controls() {
        $this->start_controls_section(
            'section_toggle',
            [
                'label' => __('Toggle', 'auros-core'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control('tab_title', [
            'label'       => __('Title & Content', 'auros-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Toggle Title', 'auros-core'),
            'label_block' => true,
        ]);

        $repeater->add_control('tab_content', [
            'label'      => __('Content', 'auros-core'),
            'type'       => Controls_Manager::WYSIWYG,
            'default'    => __('Toggle Content', 'auros-core'),
            'show_label' => false,
        ]);

        $this->add_control(
            'tabs',
            [
                'label'       => __('Toggle Items', 'auros-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'   => __('Toggle #1', 'auros-core'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auros-core'),
                    ],
                    [
                        'tab_title'   => __('Toggle #2', 'auros-core'),
                        'tab_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auros-core'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
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

        $this->add_control(
            'title_html_tag',
            [
                'label'     => __('Title HTML Tag', 'auros-core'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ],
                'default'   => 'div',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'       => __('Icon', 'auros-core'),
                'type'        => Controls_Manager::ICON,
                'default'     => is_rtl() ? 'fa fa-caret-left' : 'fa fa-caret-right',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'icon_active',
            [
                'label'       => __('Active Icon', 'auros-core'),
                'type'        => Controls_Manager::ICON,
                'default'     => 'fa fa-caret-up',
                'label_block' => true,
                'condition'   => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'show_item_first',
            [
                'label' => __('Show Item First', 'auros-core'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'event_type',
            [
                'label'        => __('Event Type', 'auros-core'),
                'type'         => Controls_Manager::SELECT,
                'options'      => array(
                    'toggle'    => 'Toggle',
                    'accordion' => 'Accordion'
                ),
                'default'      => 'toggle',
                'prefix_class' => 'elementor-event-type-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style',
            [
                'label' => __('Toggle', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label'     => __('Border Width', 'auros-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title'   => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => __('Border Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title'   => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label'     => __('Space Between', 'auros-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-toggle-item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_title',
            [
                'label' => __('Title', 'auros-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_background',
            [
                'label'     => __('Background', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'color: {{VALUE}};',
                ],
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label'     => __('Active Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active' => 'color: {{VALUE}};',
                ],
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_4,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-tab-title',
                'scheme'   => Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => __('Padding', 'auros-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_icon',
            [
                'label'     => __('Icon', 'auros-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'       => __('Alignment', 'auros-core'),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'left'  => [
                        'title' => __('Start', 'auros-core'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('End', 'auros-core'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'     => is_rtl() ? 'right' : 'left',
                'toggle'      => false,
                'label_block' => false,
                'condition'   => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title .elementor-toggle-icon .fa:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_active_color',
            [
                'label'     => __('Active Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active .elementor-toggle-icon .fa:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            [
                'label'     => __('Spacing', 'auros-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-icon.elementor-toggle-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-icon.elementor-toggle-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_content',
            [
                'label'     => __('Content', 'auros-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'content_background_color',
            [
                'label'     => __('Background', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => __('Color', 'auros-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-tab-content',
                'scheme'   => Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __('Padding', 'auros-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render toggle widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $id_int                    = substr($this->get_id_int(), 0, 3);

        ?>
        <div class="elementor-toggle" role="tablist">
        <?php
        foreach ($settings['tabs'] as $index => $item) :
            $tab_count = $index + 1;
            $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

            $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

            if ($tab_count == 1 && $settings['show_item_first'] === 'yes') {
                $this->add_render_attribute($tab_title_setting_key, 'class', 'elementor-active');
                $this->add_render_attribute($tab_content_setting_key, 'style', 'display: block;');
            }
            $this->add_render_attribute($tab_title_setting_key, [
                'id'            => 'elementor-tab-title-' . $id_int . $tab_count,
                'class'         => ['elementor-tab-title'],
                'tabindex'      => $id_int . $tab_count,
                'data-tab'      => $tab_count,
                'role'          => 'tab',
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);

            $this->add_render_attribute($tab_content_setting_key, [
                'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
                'class'           => ['elementor-tab-content', 'elementor-clearfix'],
                'data-tab'        => $tab_count,
                'role'            => 'tabpanel',
                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
            ]);

            $this->add_inline_editing_attributes($tab_content_setting_key, 'advanced');
            ?>
            <div class="elementor-toggle-item">
            <<?php echo esc_html($settings['title_html_tag']); ?><?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>
            >
            <?php if ($settings['icon']) : ?>
            <span class="elementor-toggle-icon elementor-toggle-icon-<?php echo esc_attr($settings['icon_align']); ?>" aria-hidden="true">
							<i class="elementor-toggle-icon-closed <?php echo esc_attr($settings['icon']); ?>"></i>
							<i class="elementor-toggle-icon-opened <?php echo esc_attr($settings['icon_active']); ?>"></i>
						</span>
        <?php endif; ?>
            <?php echo $item['tab_title']; ?>
            </<?php echo esc_html($settings['title_html_tag']); ?>>
            <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>><?php echo $this->parse_text_editor($item['tab_content']); ?></div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render toggle widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {
        ?>
        <div class="elementor-toggle" role="tablist">
            <#
            if ( settings.tabs ) {
            var tabindex = view.getIDInt().toString().substr( 0, 3 );

            _.each( settings.tabs, function( item, index ) {
            var tabCount = index + 1,
            tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
            tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );
            if(tabCount == 1 && settings.show_item_first === 'yes'){
            view.addRenderAttribute( tabTitleKey, 'class', 'elementor-active');
            view.addRenderAttribute( tabContentKey, 'style', 'display:block;');
            }
            view.addRenderAttribute( tabTitleKey, {
            'id': 'elementor-tab-title-' + tabindex + tabCount,
            'class': [ 'elementor-tab-title' ],
            'tabindex': tabindex + tabCount,
            'data-tab': tabCount,
            'role': 'tab',
            'aria-controls': 'elementor-tab-content-' + tabindex + tabCount
            } );

            view.addRenderAttribute( tabContentKey, {
            'id': 'elementor-tab-content-' + tabindex + tabCount,
            'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
            'data-tab': tabCount,
            'role': 'tabpanel',
            'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
            } );

            view.addInlineEditingAttributes( tabContentKey, 'advanced' );
            #>
            <div class="elementor-toggle-item">
                <{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
                <# if ( settings.icon ) { #>
                <span class="elementor-toggle-icon elementor-toggle-icon-{{ settings.icon_align }}" aria-hidden="true">
								<i class="elementor-toggle-icon-closed {{ settings.icon }}"></i>
								<i class="elementor-toggle-icon-opened {{ settings.icon_active }}"></i>
							</span>
                <# } #>
                {{{ item.tab_title }}}
            </
            {{{ settings.title_html_tag }}}>
            <div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
        </div>
        <#
        } );
        } #>
        </div>
        <?php
    }
}

$widgets_manager->register_widget_type(new OSF_Widget_Toggle());
