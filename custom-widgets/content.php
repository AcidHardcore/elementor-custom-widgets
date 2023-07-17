<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Content extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-content.css');
        wp_register_style('widget-content', get_template_directory_uri() . '/css/widget-content.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content'];
    }

    public function get_name()
    {
        return 'content';
    }

    public function get_title()
    {
        return __('Content', 'elementor-custom-widgets');
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
            'label' => __('Content', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'is_num',
            [
                'label' => esc_html__('Title Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Num', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Default', 'elementor-custom-widgets'),
                'return_value' => 'num',
                'default' => 'default',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_num' => 'num'
                ]
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
                    '{{WRAPPER}} .content__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .content__title',
            ]
        );

        $this->add_control(
            'col_count',
            [
                'label' => esc_html__('Columns Count', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'one' => [
                        'title' => esc_html__('One', 'elementor-custom-widgets'),
                        'icon' => 'eicon-nowrap',
                    ],
                    'two' => [
                        'title' => esc_html__('Two', 'elementor-custom-widgets'),
                        'icon' => 'eicon-align-stretch-h',
                    ],
                    'three' => [
                        'title' => esc_html__('Three', 'elementor-custom-widgets'),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'one',
                'toggle' => false,
            ]
        );
        $this->add_control(
            'text_align_1',
            [
                'label' => esc_html__('Alignment Column 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'condition' => [
                    'col_count' => ['one', 'two', 'three']
                ]
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-col > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .content-col > *',
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
            ]);

        $this->add_control(
            'text_align_2',
            [
                'label' => esc_html__('Alignment Column 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
                'condition' => [
                    'col_count' => ['two', 'three']
                ]
            ]
        );

        $this->add_control(
            'text_2',
            [
                'label' => __(
                    'Text 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'condition' => [
                    'col_count' => ['two', 'three']
                ]
            ]);

        $this->add_control(
            'text_align_3',
            [
                'label' => esc_html__('Alignment Column 3', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
                'condition' => [
                    'col_count' => 'three'
                ]
            ]
        );

        $this->add_control(
            'text_3',
            [
                'label' => __('Text 3', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'condition' => [
                    'col_count' => 'three'
                ]
            ]);


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

        $this->add_control(
            'stacked',
            [
                'label' => esc_html__('Stacked', 'elementor-custom-widgets'),
                'description' => 'Decreasing space to the bottom section with the same option enabled',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );
        
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $title_class = 'content__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }
        $section_class = 'content';
        $section_class .= isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_class .= isset($settings['text_align']) ? ' ta-' . $settings['text_align'] : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $col = $settings['col_count'];
        $col_1_class = 'content-col';
        $col_2_class = 'content-col';
        $col_3_class = 'content-col';
        switch ($col) {
            case 'one':
                $col_1_class .= isset($settings['text_align_1']) ? ' ta-' . $settings['text_align_1'] : '';
                break;
            case 'two':
                $col_1_class .= isset($settings['text_align_1']) ? ' ta-' . $settings['text_align_1'] : '';
                $col_2_class .= isset($settings['text_align_2']) ? ' ta-' . $settings['text_align_2'] : '';
                break;
            case 'three':
                $col_1_class .= isset($settings['text_align_1']) ? ' ta-' . $settings['text_align_1'] : '';
                $col_2_class .= isset($settings['text_align_2']) ? ' ta-' . $settings['text_align_2'] : '';
                $col_3_class .= isset($settings['text_align_3']) ? ' ta-' . $settings['text_align_3'] : '';
                break;
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section <?= esc_attr($section_class) ?>">
        <div class="grid-cont">


            <?php if ($col === 'one') : ?>

              <div class="grid-row grid-row--nog grid-row--jcc">
                <div class="grid-col grid-col--9 grid-col--md-12 wow fadeInUp">

                    <?php if (isset($settings['title'])) : ?>
                      <h2 class="tc-primary <?= esc_attr($title_class) ?>">

                          <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_num'] === 'num') : ?>
                            <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                          <?php endif; ?>

                          <?php if ($settings['is_num'] === 'num') : ?>
                            <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                          <?php else : ?>
                              <?= wp_kses_post($settings['title']) ?>
                          <?php endif; ?>

                      </h2>
                    <?php endif; ?>

                    <?php if (isset($settings['text'])) : ?>
                      <div class="mt-150 <?= esc_attr($col_1_class) ?>"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>
                </div>
              </div>

            <?php endif; ?>

            <?php if ($col === 'two') : ?>
              <div class="grid-row grid-row--jca">
                <div class="grid-col grid-col--9 grid-col--md-12 wow fadeInUp">

                    <?php if (isset($settings['title'])) : ?>
                      <h2 class="<?= esc_attr($title_class) ?>">

                          <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_num'] === 'num') : ?>
                            <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                          <?php endif; ?>

                          <?php if ($settings['is_num'] === 'num') : ?>
                            <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                          <?php else : ?>
                              <?= wp_kses_post($settings['title']) ?>
                          <?php endif; ?>

                      </h2>
                    <?php endif; ?>

                </div>

                <div class="grid-col grid-col--5 grid-col--md-12 wow fadeInUp <?= esc_attr($col_1_class) ?>">

                    <?php if (isset($settings['text'])) : ?>
                      <div class="mt-150"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>

                </div>

                <div class="grid-col grid-col--5 grid-col--md-12 wow fadeInUp <?= esc_attr($col_2_class) ?>">

                    <?php if (isset($settings['text_2'])) : ?>
                      <div class="mt-150"><?= wp_kses_post($settings['text_2']) ?></div>
                    <?php endif; ?>

                </div>
              </div>

            <?php endif; ?>

            <?php if ($col === 'three') : ?>
              <div class="grid-row grid-row--jca">
                <div class="grid-col grid-col--9 grid-col--md-12 wow fadeInUp">

                    <?php if (isset($settings['title'])) : ?>
                      <h2 class="<?= esc_attr($title_class) ?>">

                          <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_num'] === 'num') : ?>
                            <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['image']['id'])); ?></div>
                          <?php endif; ?>

                          <?php if ($settings['is_num'] === 'num') : ?>
                            <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                          <?php else : ?>
                              <?= wp_kses_post($settings['title']) ?>
                          <?php endif; ?>

                      </h2>
                    <?php endif; ?>

                </div>

                <div class="grid-col grid-col--4 grid-col--md-12 wow fadeInUp <?= esc_attr($col_1_class) ?>">

                    <?php if (isset($settings['text'])) : ?>
                      <div class="mt-150"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>

                </div>

                <div class="grid-col grid-col--4 grid-col--md-12 wow fadeInUp <?= esc_attr($col_2_class) ?>">

                    <?php if (isset($settings['text_2'])) : ?>
                      <div class="mt-150"><?= wp_kses_post($settings['text_2']) ?></div>
                    <?php endif; ?>

                </div>

                <div class="grid-col grid-col--4 grid-col--md-12 wow fadeInUp <?= esc_attr($col_3_class) ?>">

                    <?php if (isset($settings['text_3'])) : ?>
                      <div class="mt-150"><?= wp_kses_post($settings['text_3']) ?></div>
                    <?php endif; ?>

                </div>
              </div>
            <?php endif; ?>


        </div>
      </div>
        <?php
    }
}


