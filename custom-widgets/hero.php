<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Hero extends Widget_Base
{
    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-hero.css');
        wp_register_style('widget-hero', get_template_directory_uri() . '/css/widget-hero.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-hero.js');
        wp_register_script('widget-hero', get_template_directory_uri() . '/js/widget-hero.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-hero'];
    }

    public function get_script_depends()
    {
        return ['widget-hero'];
    }

    public function get_name()
    {
        return 'hero';
    }

    public function get_title()
    {
        return __('Hero', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-video-camera';
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
            'label' => __('Hero', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'is_image',
            [
                'label' => esc_html__('Media Switcher', 'elementor-custom-widgets'),
                'description' => 'Switching between image and video options',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Video', 'elementor-custom-widgets'),
                'label_off' => esc_html__('Image', 'elementor-custom-widgets'),
                'return_value' => 'video',
                'default' => 'image',
            ]
        );

        $this->add_control(
            'is_logo',
            [
                'label' => esc_html__('Switch types', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'title' => [
                        'title' => esc_html__('Title', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'logo' => [
                        'title' => esc_html__('Logo', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'both' => [
                        'title' => esc_html__('Title and Logo', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'badge' => [
                        'title' => esc_html__('Badge', 'elementor-custom-widgets'),
                        'icon' => 'eicon-post',
                    ],
                ],
                'default' => 'title',
                'toggle' => false,
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
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} .hero__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .hero__title',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'video_id', [
                'label' => __('Video ID', 'elementor-custom-widgets'),
                'description' => 'get it from Youtube URL ?v=',
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'is_image' => 'video',
                ],
            ]
        );

        $this->add_control(
            'link_text', [
                'label' => __('Link text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Link text', 'elementor-custom-widgets'),
                'condition' => [
                    'is_image' => 'video',
                ],
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label' => esc_html__('Link type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-link--alt',
                'options' => $this->config::LINK_TYPE
            ]
        );

        $this->add_control(
            'full_video_link',
            [
                'label' => esc_html__('Full Video Link', 'elementor-custom-widgets'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-custom-widgets'),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
                'condition' => [
                    'is_image' => 'video',
                ],

            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Choose Logo', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'is_logo',
                            'operator' => '==',
                            'value' => 'logo'
                        ],
                        [
                            'name' => 'is_logo',
                            'operator' => '==',
                            'value' => 'both'
                        ],
                        [
                            'name' => 'is_logo',
                            'operator' => '==',
                            'value' => 'badge'
                        ]
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'section_id',
            [
                'label' => esc_html__('Section ID', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'small',
            [
                'label' => esc_html__('Switch sizes', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'large' => [
                        'title' => esc_html__('Large', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'small' => [
                        'title' => esc_html__('Small', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'tiny' => [
                        'title' => esc_html__('Tiny', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'large',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'scroll_down',
            [
                'label' => __('Scroll down', 'elementor-custom-widgets'),
                'description' => esc_html__('The ID of the next section', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();

        $section_class = '';

        if (isset($settings['small']) && $settings['small'] == 'small') {
            $section_class = 'hero--sm';
        }

        if (isset($settings['small']) && $settings['small'] == 'tiny') {
            $section_class = 'hero--xs';
        }

        $section_class .= isset($settings['is_image']) && $settings['is_image'] === 'video' ? ' video' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $link_class = 'cta-link';
        if (isset($settings['full_video_link']) && !empty($settings['full_video_link']['url'])) {
            $this->add_link_attributes('full_video_link', $settings['full_video_link']);

            if($settings['link_type'] !== 'cta-link') {
                $link_class .= ' ' . $settings['link_type'];
            }

        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="hero <?= esc_attr($section_class) ?>">

          <?php if (isset($settings['image']) && !empty($settings['image']['id']) && $settings['is_image'] !== 'video') : ?>
              <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'hero__bg')) ?>
          <?php endif; ?>

        <div class="grid-cont hero__cont">
            <?php if (isset($settings['title']) && $settings['is_logo'] !== 'logo' && $settings['is_logo'] !== 'both' && is_page()) : ?>
              <h1 class="hero__title"><?= wp_kses_post($settings['title']) ?></h1>
            <?php elseif (is_single() && !is_admin() && !empty($settings['title'])): ?>
              <h1 class="hero__title here__title--single"><?= wp_kses_post($settings['title']) ?></h1>
<!--            projects-->
            <?php elseif (is_single() && !is_admin() && empty($settings['title'])) : ?>
              <h1 class="hero__title here__title--single"><?= wp_kses_post(get_the_title()) ?></h1>
            <?php endif; ?>

            <?php if (isset($settings['title']) && is_search()) : ?>
              <h1 class="hero__title"><?= wp_kses_post($settings['title']) ?></h1>
            <?php endif; ?>

          <!--for admin-->
            <?php if (isset($settings['title']) && $settings['is_logo'] !== 'logo' && $settings['is_logo'] !== 'both' && is_admin()) : ?>
              <h1 class="hero__title"><?= wp_kses_post($settings['title']) ?></h1>
            <?php endif; ?>

            <?php if (isset($settings['logo']) && !empty($settings['logo']['id']) && $settings['is_logo'] === 'logo') : ?>
                <?= wp_get_attachment_image($settings['logo']['id'], 'full') ?>
            <?php endif; ?>

            <?php if (isset($settings['title']) && isset($settings['logo']) && !empty($settings['logo']['id']) && $settings['is_logo'] === 'both') : ?>
              <h1 class="hero__title"><?= wp_kses_post($settings['title']) ?></h1>
                <?= wp_get_attachment_image($settings['logo']['id'], 'full') ?>
            <?php endif; ?>

            <?php if (isset($settings['link_text']) && !empty($settings['full_video_link']['url'])) : ?>
              <a href="<?= esc_url($settings['full_video_link']['url']) ?>"
                 class="mt-100 <?= $link_class ?>" <?php echo $this->get_render_attribute_string('full_video_link'); ?>>
                  <?= esc_html($settings['link_text']) ?>
              </a>
            <?php endif; ?>

        </div>

          <?php if (isset($settings['scroll_down']) && !empty($settings['scroll_down'])) : ?>
            <a href="#<?= esc_attr($settings['scroll_down']) ?>" data-scroll="#<?= esc_attr($settings['scroll_down']) ?>"
               class="hero__scroll">Scroll down</a>
          <?php endif; ?>

          <?php if (isset($settings['video_id']) && $settings['is_image'] === 'video') : ?>
            <a class="video__link" href="https://youtu.be/<?= $settings['video_id'] ?>" data-id="<?= $settings['video_id'] ?>">
                <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                    <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'hero__bg video__media')) ?>
                <?php endif; ?>
            </a>
            <button class="hero__play video__button" type="button"></button>

          <?php endif; ?>

          <?php if (isset($settings['logo']) && !empty($settings['logo']['id']) && $settings['is_logo'] === 'badge') : ?>
              <?= wp_get_attachment_image($settings['logo']['id'], 'full', false, array('class' => 'hero__badge')) ?>
          <?php endif; ?>

      </div>

        <?php
    }
}


