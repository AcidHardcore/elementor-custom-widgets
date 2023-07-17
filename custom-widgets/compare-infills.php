<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Compare_Infills extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-compare-infills.css');
        wp_register_style('widget-compare-infills', get_template_directory_uri() . '/css/widget-compare-infills.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-compare-infills'];
    }

    public function get_name()
    {
        return 'compare-infills';
    }

    public function get_title()
    {
        return __('Compare Infills', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-columns';
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
            'label' => __('Compare Infills', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $this->add_control(
            'title', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .compare-infills__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .compare-infills__title',
            ]
        );


        $this->add_control(
            'table_1_text',
            [
                'label' => esc_html__('Table 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_table_color',
            [
                'label' => __( 'Table Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .compare-infills__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Table Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_table_typography',
                'selector' => '{{WRAPPER}} .compare-infills__heading',
            ]
        );

        $this->add_control(
            'text_table_color',
            [
                'label' => __( 'Table Item Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .compare-infills__lead' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Table Item Typography', 'elementor-custom-widgets' ),
                'name' => 'text_table_typography',
                'selector' => '{{WRAPPER}} .compare-infills__lead',
            ]
        );

        $this->add_control(
            'title_1', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'image_1_1',
            [
                'label' => esc_html__('Choose Logo 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_1_2',
            [
                'label' => esc_html__('Choose Logo 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $table_1 = new Repeater();

        $table_1->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $table_1->add_control(
            'is_check_1',
            [
                'label' => esc_html__('Check', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $table_1->add_control(
            'text_1', [
            'label' => __('Text 1', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('text 1', 'elementor-custom-widgets'),
        ]);

        $table_1->add_control(
            'is_check_2',
            [
                'label' => esc_html__('Check', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $table_1->add_control(
            'text_2', [
            'label' => __('Text 2', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('text 2', 'elementor-custom-widgets'),
        ]);


        $this->add_control(
            'table_1',
            [
                'label' => esc_html__('Table 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $table_1->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'table_2_text',
            [
                'label' => esc_html__('Table 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_2', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'image_2_1',
            [
                'label' => esc_html__('Choose Logo 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_2_2',
            [
                'label' => esc_html__('Choose Logo 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $table_2 = new Repeater();

        $table_2->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $table_2->add_control(
            'is_check_1',
            [
                'label' => esc_html__('Check', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $table_2->add_control(
            'text_1', [
            'label' => __('Text 1', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('text 1', 'elementor-custom-widgets'),
        ]);

        $table_2->add_control(
            'is_check_2',
            [
                'label' => esc_html__('Check', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $table_2->add_control(
            'text_2', [
            'label' => __('Text 2', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('text 2', 'elementor-custom-widgets'),
        ]);


        $this->add_control(
            'table_2',
            [
                'label' => esc_html__('Table 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $table_2->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
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

      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg compare-infills">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary num-title wow fadeInUp compare-infills__title">

                  <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                  <?php endif; ?>
                <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
              </h2>
            <?php endif; ?>

        </div>
        <div class="grid-cont grid-cont--sm compare-infills__cont wow fadeInUp">

          <table class="mt-300 h4 compare-infills__table">
            <thead>
            <tr>
                <?php if (isset($settings['title_1'])) : ?>
                  <th class="compare-infills__heading"><?= wp_kses_post($settings['title_1']) ?></th>
                <?php endif; ?>

                <?php if (isset($settings['image_1_1']) && !empty($settings['image_1_1']['id'])) : ?>
                  <th>
                      <?= wp_get_attachment_image($settings['image_1_1']['id'], 'full', false, array('class' => 'compare-infills__logo')) ?>
                  </th>
                <?php endif; ?>

                <?php if (isset($settings['image_1_2']) && !empty($settings['image_1_2']['id'])) : ?>
                  <th>
                      <?= wp_get_attachment_image($settings['image_1_2']['id'], 'full', false, array('class' => 'compare-infills__logo')) ?>
                  </th>
                <?php endif; ?>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($settings['table_1'] as $item) : ?>
              <tr>
                <td class="compare-infills__lead">
                    <?= esc_html($item['title']) ?>
                </td>
                <td>
                    <?php if (isset($item['is_check_1']) && $item['is_check_1'] == 'yes') : ?>
                      <div class="compare-infills__check"></div>
                    <?php endif; ?>
                    <?php if (isset($item['text_1']) && !empty($item['text_1'])) : ?>
                      <div class="compare-infills__note"><?= wp_kses_post($item['text_1'])?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (isset($item['is_check_2']) && $item['is_check_2'] == 'yes') : ?>
                      <div class="compare-infills__check"></div>
                    <?php endif; ?>
                    <?php if (isset($item['text_2']) && !empty($item['text_2'])) : ?>
                      <div class="compare-infills__note"><?= wp_kses_post($item['text_2'])?></div>
                    <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>

            </tbody>
          </table>
        </div>
        <div class="grid-cont grid-cont--sm compare-infills__cont wow fadeInUp">
          <table class="mt-300 h4 compare-infills__table">
            <thead>
            <tr>

                <?php if (isset($settings['title_2'])) : ?>
                  <th><?= wp_kses_post($settings['title_2']) ?></th>
                <?php endif; ?>

                <?php if (isset($settings['image_2_1']) && !empty($settings['image_2_1']['id'])) : ?>
                  <th>
                      <?= wp_get_attachment_image($settings['image_2_1']['id'], 'full', false, array('class' => 'compare-infills__logo')) ?>
                  </th>
                <?php endif; ?>

                <?php if (isset($settings['image_2_2']) && !empty($settings['image_2_2']['id'])) : ?>
                  <th>
                      <?= wp_get_attachment_image($settings['image_2_2']['id'], 'full', false, array('class' => 'compare-infills__logo')) ?>
                  </th>
                <?php endif; ?>

            </tr>
            </thead>
            <tbody>

            <?php foreach ($settings['table_2'] as $item) : ?>

              <tr>
                <td>
                    <?= esc_html($item['title']) ?>
                </td>
                <td>
                    <?php if (isset($item['is_check_1']) && $item['is_check_1'] == 'yes') : ?>
                      <div class="compare-infills__check"></div>
                    <?php endif; ?>
                    <?php if (isset($item['text_1']) && !empty($item['text_1'])) : ?>
                      <div class="compare-infills__note"><?= wp_kses_post($item['text_1'])?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (isset($item['is_check_2']) && $item['is_check_2'] == 'yes') : ?>
                      <div class="compare-infills__check"></div>
                    <?php endif; ?>
                    <?php if (isset($item['text_2']) && !empty($item['text_2'])) : ?>
                      <div class="compare-infills__note"><?= wp_kses_post($item['text_2'])?></div>
                    <?php endif; ?>
                </td>
              </tr>

            <?php endforeach; ?>

            </tbody>
          </table>
        </div>
      </div>

        <?php
    }
}


