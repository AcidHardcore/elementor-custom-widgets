<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Our_Features extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-our-features.css');
        wp_register_style('widget-our-features', get_template_directory_uri() . '/css/widget-our-features.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-our-features.js');
        wp_register_script('widget-our-features', get_template_directory_uri() . '/js/widget-our-features.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-our-features'];
    }

    public function get_script_depends() {
        return ['widget-our-features'];
    }

    public function get_name()
    {
        return 'our-features';
    }

    public function get_title()
    {
        return __('Our Features', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-featured-image';
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
            'label' => __('Our Features', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-features__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .our-features__title',
            ]
        );

        $this->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-features__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .our-features__text > *',
            ]
        );

        $this->add_control(
            'is_col',
            [
                'label' => esc_html__('Grid Settings', 'elementor-custom-widgets'),
                'description' => "Enable it when adding videos",
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Wide', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Narrow', 'elementor-custom-widgets'),
                'return_value' => 'wide',
                'default' => 'narrow',
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'col_align',
            [
                'label' => esc_html__('Column Alignment', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
            ]
        );

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .feature-item__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .feature-item__title',
            ]
        );

        $repeater->add_control(
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

        $repeater->add_control(
            'num_image',
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

        $repeater->add_control(
            'is_image',
            [
                'label' => esc_html__('Media Switcher', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Video', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Image', 'elementor-custom-widgets'),
                'return_value' => 'video',
                'default' => 'image',
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_image' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'video_id', [
                'label' => __('Video ID', 'elementor-custom-widgets'),
                'description' => 'get it from Youtube URL ?v=',
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'is_image' => 'video',
                ],
            ]
        );

        $repeater->add_control('text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .feature-item__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .feature-item__text',
            ]
        );

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
                'options' => $this->config::BUTTON_TYPE
            ]
        );

        $repeater->add_control(
            'cta_link',
            [
                'label' => esc_html__('CTA Link', 'elementor-custom-widgets'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-custom-widgets'),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => esc_html__('Features', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
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

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $row_class = $settings['is_col'] === 'narrow' ? ' grid-cont--xs' : ' grid-cont--sm';
        $video_col_class = $settings['is_col'] === 'narrow' ? ' grid-col--auto' : '';
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section our-features <?= esc_attr($section_class) ?>">

          <?php if ((isset($settings['title']) || isset($settings['text'])) && (!empty($settings['title']) || !empty($settings['text']))) : ?>
            <div class="mb-400 grid-cont ta-center wow fadeInUp">
              <div class="grid-row grid-row--nog grid-row--jcc">
                <div class="grid-col grid-col--9 grid-col--md-12">

                    <?php if (isset($settings['title']) && !empty($settings['title'])) : ?>
                      <h2 class="tc-primary our-features__title"><?= wp_kses_post($settings['title']) ?></h2>
                    <?php endif; ?>

                    <?php if (isset($settings['text']) && !empty($settings['text'])) : ?>
                      <div class="mt-100 our-features__text"><?= wp_kses_post($settings['text']) ?></div>
                    <?php endif; ?>

                </div>
              </div>
            </div>
          <?php endif; ?>

        <div class="grid-cont <?= esc_attr($row_class) ?>">
            <?php foreach ($settings['features'] as $index => $item) : ?>
                <?php
                $button_class = 'button';
                if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                    $this->add_link_attributes('cta_link', $item['cta_link']);
                    if($item['button_type'] !== 'button') {
                        $button_class .= ' ' . $item['button_type'];

                    }
                }

                $current_item_class = 'elementor-repeater-item-' . $item['_id'];

                $title_class = 'tc-primary feature-item__title';
                $text_class = 'feature-item__text';
                if ($item['is_num'] === 'num') {
                    $title_class .= ' num-title';
                }
                ?>
              <div class="our-features__item">
                <div class="grid-row grid-row--aic">

                    <?php if ($item['col_align'] === 'right') : ?>

                      <div class="grid-col grid-col--sm-12 grid-col--sm-order-0 wow fadeInUp <?= esc_attr($video_col_class) ?> <?= $current_item_class ?>">

                          <?php if (isset($item['image']) && !empty($item['image']['id']) && $item['is_image'] === 'image') : ?>
                              <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'our-features__image')) ?>
                          <?php endif; ?>

                          <?php if ($item['is_image'] === 'video' && isset($item['video_id'])) : ?>
                            <div class="our-features__video video-features">
                              <a class="video-features__link" href="https://youtu.be/<?= $item['video_id'] ?>">
                                <picture>
                                  <source srcset="https://i.ytimg.com/vi_webp/<?= $item['video_id'] ?>/hqdefault.webp" type="image/webp">
                                  <img class="video-features__media" src="https://i.ytimg.com/vi/<?= $item['video_id'] ?>/hqdefault.jpg" alt="<?= esc_attr($item['title'] ?? '') ?>" loading="lazy">
                                </picture>
                              </a>
                              <button class="video-features__button" type="button" aria-label="Play video">
                                <svg width="68" height="48" viewBox="0 0 68 48">
                                  <path class="video-features__button-shape"
                                        d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                  <path class="video-features__button-icon" d="M 45,24 27,14 27,34"></path>
                                </svg>
                              </button>
                            </div>
                          <?php endif; ?>
                      </div>

                    <?php else : ?>

                      <div data-wow-delay="0.25s" class="grid-col grid-col--sm-12 wow fadeInUp <?= $current_item_class ?>">

                          <?php if (isset($item['title'])) : ?>
                            <h2 class="<?= esc_attr($title_class) ?>">

                                <?php if (isset($item['num_image']) && !empty($item['num_image']['id']) && $item['is_num'] === 'num') : ?>
                                  <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($item['num_image']['id'])); ?></div>
                                <?php endif; ?>

                                <?php if ($item['is_num'] === 'num') : ?>
                                  <span class="num-title__text"><?= wp_kses_post($item['title']) ?></span>
                                <?php else : ?>
                                    <?= wp_kses_post($item['title']) ?>
                                <?php endif; ?>

                            </h2>
                          <?php endif; ?>

                          <?php if (isset($item['text'])) : ?>
                            <div class="mt-100 <?= $text_class ?>"><?= wp_kses_post($item['text']) ?></div>
                          <?php endif; ?>

                          <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                            <a href="<?= esc_url($item['cta_link']['url']) ?>"
                               class="mt-150 <?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                <?= esc_html($item['link_text']) ?>
                            </a>
                          <?php endif; ?>
                      </div>

                    <?php endif; ?>
                  <div class="grid-col grid-col--auto removed-md"></div>
                  <div class="grid-col grid-col--auto grid-col--sm-12"></div>

                    <?php if ($item['col_align'] === 'right') : ?>

                      <div data-wow-delay="0.25s" class="grid-col grid-col--sm-12 wow fadeInUp <?= $current_item_class ?>">
                          <?php if (isset($item['title'])) : ?>
                            <h2 class="<?= esc_attr($title_class) ?>">

                                <?php if (isset($item['num_image']) && !empty($item['num_image']['id']) && $item['is_num'] === 'num') : ?>
                                  <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($item['num_image']['id'])); ?></div>
                                <?php endif; ?>

                                <?php if ($item['is_num'] === 'num') : ?>
                                  <span class="num-title__text"><?= wp_kses_post($item['title']) ?></span>
                                <?php else : ?>
                                    <?= wp_kses_post($item['title']) ?>
                                <?php endif; ?>

                            </h2>
                          <?php endif; ?>

                          <?php if (isset($item['text'])) : ?>
                            <div class="mt-100 <?= $text_class ?>"><?= wp_kses_post($item['text']) ?></div>
                          <?php endif; ?>

                          <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                            <a href="<?= esc_url($item['cta_link']['url']) ?>"
                               class="mt-150 <?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                <?= esc_html($item['link_text']) ?>
                            </a>
                          <?php endif; ?>
                      </div>

                    <?php else : ?>

                      <div class="grid-col grid-col--sm-12 grid-col--sm-order-0 wow fadeInUp <?= esc_attr($video_col_class) ?> <?= $current_item_class ?>">

                          <?php if (isset($item['image']) && !empty($item['image']['id']) && $item['is_image'] === 'image') : ?>
                              <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'our-features__image')) ?>
                          <?php endif; ?>

                          <?php if ($item['is_image'] === 'video' && isset($item['video_id'])) : ?>
                            <div class="our-features__video video-features">
                              <a class="video-features__link" href="https://youtu.be/<?= $item['video_id'] ?>">
                                <picture>
                                  <source srcset="https://i.ytimg.com/vi_webp/<?= $item['video_id'] ?>/hqdefault.webp" type="image/webp">
                                  <img class="video-features__media" src="https://i.ytimg.com/vi/<?= $item['video_id'] ?>/hqdefault.jpg" alt="<?= esc_attr($item['title'] ?? '') ?>" loading="lazy">
                                </picture>
                              </a>
                              <button class="video-features__button" type="button" aria-label="Play video">
                                <svg width="68" height="48" viewBox="0 0 68 48">
                                  <path class="video-features__button-shape"
                                        d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                  <path class="video-features__button-icon" d="M 45,24 27,14 27,34"></path>
                                </svg>
                              </button>
                            </div>
                          <?php endif; ?>

                      </div>

                    <?php endif; ?>

                </div>
              </div>
            <?php endforeach; ?>

        </div>
      </div>

        <?php
    }
}


