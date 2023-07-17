<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Table extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-table.css');
        wp_register_style('widget-post-table', get_template_directory_uri() . '/css/widget-post-table.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-post-table'];
    }

    public function get_name()
    {
        return 'post-table';
    }

    public function get_title()
    {
        return __('Post Table', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-editor-quote';
    }

    public function get_categories()
    {
        return ['Motz'];
    }

    public function get_keywords()
    {
        return ['custom'];
    }

    protected function register_controls()
    {

        $this->start_controls_section('section_intro', [
            'label' => __('Post Table', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'is_head',
            [
                'label' => esc_html__('Enable Table Head', 'elementor-custom-widgets'),
                'description' => 'Enable a table head row',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('no', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .table th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .table th',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .table td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .table td',
            ]
        );

        $repeater = new Repeater();


        $repeater->add_control(
            'item_type',
            [
                'label' => esc_html__('Item Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'col',
                'options' => [
                    'row' => esc_html__('Row', 'elementor-custom-widgets'),
                    'col' => esc_html__('Column', 'elementor-custom-widgets'),

                ],

            ]
        );

        $repeater->add_control(
            'item', [
                'label' => esc_html__('Item', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'row' => 3,
                'condition' => [
                    'item_type' => 'col',
                ],
            ]
        );

        $this->add_control(
            'table',
            [
                'label' => esc_html__('Table', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_type' => esc_html__('Item', 'elementor-custom-widgets'),
                    ],
                ],
                'title_field' => '{{{ item_type }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'section_id', [
                'label' => esc_html__('Section ID', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="mt-300 table">
        <table>
            <?php $first_row = true; ?>
            <?php if (isset($settings['table']) && is_array($settings['table'])) : ?>
                <?php $first_key = array_key_first($settings['table']); ?>
                <?php $last_key = array_key_last($settings['table']); ?>

                <?php foreach ($settings['table'] as $index => $item) : ?>
                    <?php if ($settings['is_head'] !== 'yes') : ?>
                        <?php if ($item['item_type'] === 'row' && $first_row === true) : ?>
                            <?php $first_row = false; ?>
                    <tbody>
                    <tr>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'row' && $first_row === false) : ?>
                </tr>
                <tr>
                    <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index !== $last_key ) : ?>
                    <td><?= wp_kses_post($item['item']) ?></td>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index === $last_key) : ?>
                    <td><?= wp_kses_post($item['item']) ?></td>
                    </tr>
                    </tbody>
                        <?php endif; ?>
                    <?php else: ?>

                        <?php if ($item['item_type'] === 'row' && $index === $first_key) : ?>
                    <thead>
                    <tr>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index !== $last_key && $first_row === true) : ?>
                    <th><?= wp_kses_post($item['item']) ?></th>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index === $last_key && $first_row === true) : ?>
                    <th><?= wp_kses_post($item['item']) ?></th>
                    </tr>
                    </thead>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'row' && $first_row === true && $index !== $first_key) : ?>
                            <?php $first_row = false; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'row' && $first_row === false) : ?>
                </tr>
                <tr>
                    <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index !== $last_key && $first_row === false) : ?>
                    <td><?= wp_kses_post($item['item']) ?></td>
                        <?php endif; ?>

                        <?php if ($item['item_type'] === 'col' && $index === $last_key && $first_row === false) : ?>
                    <td><?= wp_kses_post($item['item']) ?></td>
                    </tr>
                    </tbody>
                        <?php endif; ?>


                    <?php endif; ?>

                <?php endforeach; ?>

            <?php endif; ?>
        </table>
      </div>

        <?php
    }
}


