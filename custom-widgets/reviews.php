<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Reviews extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-custom-reviews.css');
        wp_register_style('widget-custom-reviews', get_template_directory_uri() . '/css/widget-custom-reviews.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-custom-reviews.js');
        wp_register_script('widget-custom-reviews', get_template_directory_uri() . '/js/widget-custom-reviews.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-content', 'widget-custom-reviews'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-custom-reviews'];
    }

    public function get_name()
    {
        return 'custom-reviews';
    }

    public function get_title()
    {
        return __('Custom Reviews', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-person';
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
            'label' => __('Custom Reviews', 'elementor-custom-widgets'),
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
            'number',
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
                    '{{WRAPPER}} .reviews__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .reviews__title',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __( 'Quote Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testimonials__quote > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Quote Typography', 'elementor-custom-widgets' ),
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .testimonials__quote > *',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Name Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testimonials__author' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .testimonials__author > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Name Typography', 'elementor-custom-widgets' ),
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .testimonials__author',
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label' => esc_html__('Link type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-link',
                'options' => $this->config::LINK_TYPE
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
                'options' => $this->config::BUTTON_TYPE
            ]
        );

        $this->add_control(
            'count',
            [
                'label' => esc_html__('Reviews Count', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 3,
                'condition' => [
                    'post_type' => 'auto'
                ]
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post select type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'auto' => [
                        'title' => esc_html__('Auto', 'elementor-custom-widgets'),
                        'icon' => 'eicon-settings',
                    ],
                    'manual' => [
                        'title' => esc_html__('Manual', 'elementor-custom-widgets'),
                        'icon' => 'eicon-zoom-in',
                    ],

                ],
                'default' => 'auto',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => esc_html__('Select Reviews', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_reviews_titles(),
                'default' => true,
                'condition' => [
                    'post_type' => 'manual'
                ]
            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control('cta_text', [
            'label' => __('CTA Button Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->add_control(
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

    public function get_reviews()
    {
        $args = array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        $query = new \WP_Query($args);

        return $query->posts;
    }

    public function get_reviews_titles()
    {
        $posts = $this->get_reviews();
        $titles = array();

        foreach ($posts as $post) {
            $titles[$post] = get_the_title($post);
        }

        return $titles;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $count = $settings['count'];
        $items = $this->get_reviews();
        $posts_count = count($items);
        $auto = $posts_count <= $count ? $items : array_slice($items, 0, $count);
        $posts = $settings['post_type'] === 'auto' ? $auto : $settings['reviews'] ?? null;

        $link_class = 'cta-link';
        if($settings['link_type'] !== 'cta-link') {
            $link_class .= ' ' . $settings['link_type'];
        }

        $button_class = 'button';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
            if($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];
            }
        }

        $title_class = ' reviews__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }

        $is_arrow = count($posts) > 1;

        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section reviews">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="ta-center wow fadeInUp<?= esc_attr($title_class) ?>">

                  <?php if (isset($settings['number']) && !empty($settings['number']['id']) && $settings['is_num'] === 'num') : ?>
                    <div class="num-title__img"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
                  <?php endif; ?>

                  <?php if ($settings['is_num'] === 'num') : ?>
                    <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                  <?php else : ?>
                      <?= wp_kses_post($settings['title']) ?>
                  <?php endif; ?>

              </h2>
            <?php endif; ?>

          <div id="reviews__carousel" class="mt-200 reviews__carousel">

              <?php if (is_array($posts)) : ?>
                  <?php foreach ($posts as $index => $item) : ?>
                      <?php

                      $name = get_field('name', $item);
                      $position = get_field('position', $item);
                      $from = get_field('from', $item);
                      $project = get_field('project', $item);
                      if ($project) {
                          $project_link = get_the_permalink($project);
                      }
                      $is_video = get_field('is_video', $item);
                      if ($is_video) {
                          $video_id = get_field('video', $item);
                      }
                      $content = get_the_content(null, true, $item);
                      ?>

                  <div class="reviews__slide">
                    <div class="grid-row grid-row--aic">
                      <div <?= $index === 0 ? 'data-wow-delay="0.25s"' : '' ?> class="grid-col grid-col--8 grid-col--md-12 <?= $index === 0 ? 'wow fadeInUp' : '' ?>">

                          <?php if ($content) : ?>
                            <div class="testimonials__quote"><?= wp_kses_post($content) ?></div>
                          <?php endif; ?>

                        <div class="mt-100 testimonials__author">

                            <?php if ($name) : ?>
                              <span><?= esc_html($name) ?></span>
                            <?php endif; ?>

                            <?php if ($name && $position) : ?>
                              <span><?= ' | ' ?></span>
                            <?php endif; ?>

                            <?php if ($position) : ?>
                              <span>  <?= esc_html($position) ?></span>
                            <?php endif; ?>

                            <?php if ($name || $position) : ?>
                              <br>
                            <?php endif; ?>

                            <?php if ($from) : ?>
                                <?= esc_html($from) ?>
                            <?php endif; ?>

                        </div>

                          <?php if ($project) : ?>
                            <a href="<?= $project_link ?>" class="mt-100 <?= esc_attr($link_class) ?>">View Project</a>
                          <?php endif; ?>

                      </div>
                      <div <?= $index === 0 ? 'data-wow-delay="0.5s"' : '' ?> class="grid-col grid-col--4 grid-col--md-12 grid-col--md-order-0 <?= $index === 0 ? 'wow fadeInUp' : '' ?>">

                          <?php if (get_post_thumbnail_id($item) && !$is_video) : ?>
                            <div class="reviews__figure">
                                <?= get_the_post_thumbnail($item, 'full', array('class' => 'reviews__image')) ?>
                            </div>
                          <?php endif; ?>

                          <?php if (get_post_thumbnail_id($item) && $is_video && $video_id) : ?>
                            <div class="reviews__figure video-reviews">
                              <a class="video-reviews__link" href="https://youtu.be/<?= $video_id ?>" data-id="<?= $video_id ?>">
                                  <?= get_the_post_thumbnail($item, 'full', array('class' => 'reviews__image video-reviews__media')) ?>
                              </a>
                              <button class="video-reviews__button" type="button" aria-label="Play video">
                                <svg width="68" height="48" viewBox="0 0 68 48">
                                  <path class="video-reviews__button-shape"
                                        d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                  <path class="video-reviews__button-icon" d="M 45,24 27,14 27,34"></path>
                                </svg>
                              </button>
                            </div>
                          <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <?php endforeach; ?>
              <?php endif; ?>
          </div>

          <div class="mt-300 grid-row grid-row--aic grid-row--jcb wow fadeInUp">
            <div class="grid-col grid-col--auto">

                <?php if ($is_arrow) : ?>
                  <div class="arrows">
                    <button type="button" class="arrows__prev"></button>
                    <button type="button" class="arrows__next"></button>
                  </div>
                <?php endif; ?>

            </div>
            <div class="grid-col grid-col--auto">
                <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="<?= esc_attr($button_class) ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                      <?= esc_html($settings['cta_text']) ?>
                  </a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


