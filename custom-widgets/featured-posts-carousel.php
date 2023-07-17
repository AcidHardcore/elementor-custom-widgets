<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Feature_Posts_Carousel extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');


        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-featured-posts-carousel.js');
        wp_register_script('widget-featured-posts-carousel', get_template_directory_uri() . '/js/widget-featured-posts-carousel.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-content', 'widget-featured-posts', 'widget-featured-projects'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-featured-posts-carousel'];
    }

    public function get_name()
    {
        return 'featured-posts-carousel';
    }

    public function get_title()
    {
        return __('Feature Posts Carousel', 'elementor-custom-widgets');
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
            'label' => __('Feature Posts Carousel', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


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
                    '{{WRAPPER}} .featured-posts__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .featured-posts__title',
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
                'default' => 6,
                'condition' => [
                    'post_type' => 'auto'
                ]
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Feed type', 'elementor-custom-widgets'),
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
                'toggle' => false,
            ]
        );

        $this->add_control(
            'blog_type',
            [
                'label' => esc_html__('Select Blog Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => $this->config::BLOG_TYPES,
                'default' => 'sport_blog',
            ]
        );

        $this->add_control(
            'blog_tag',
            [
                'label' => esc_html__('Select Blog Tag', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_blog_tags(),
                'description' => esc_html__('Empty Tags are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'post_type' => 'auto',
                ]
            ]
        );

        $this->add_blog_select_control();

        $this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'cta_text', [
            'label' => __('View More', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

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

    public function get_blogs($blog_type, $blog_tag = null)
    {
        $args = array(
            'post_type' => $blog_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        if (!empty($blog_tag)) {
            $args['tax_query'][] = [
                'taxonomy' => $this->config::BLOG_TAG,
                'field' => 'slug',
                'terms' => $blog_tag
            ];
        }

        $query = new \WP_Query($args);

        return $query->posts;
    }

    public function get_blog_titles($blog_type)
    {
        $posts = $this->get_blogs($this->is_all_blog_types($blog_type));
        $titles = array();

        foreach ($posts as $post) {
            $titles[$post] = get_the_title($post);
        }

        return $titles;
    }

    protected function get_blog_tags() {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => $this->config::BLOG_TAG,
            'hide_empty' => true,
        ]);
        foreach ($terms as $term) {
            $cats[$term->slug] = $term->name;
        }
        return $cats;
    }

    public function get_all_blog_types()
    {
        $keys = array_keys($this->config::BLOG_TYPES);
        $all_blog_types = array_filter($keys, function($key) {
            return $key !== 'all';
        });
        return $all_blog_types;
    }

    public function is_all_blog_types($blog_type)
    {
        if ($blog_type !== 'all') {
            return $blog_type;
        } else {
            return $this->get_all_blog_types();
        }
    }

    private function add_blog_select_control()
    {
        foreach ($this->config::BLOG_TYPES as $blog_type => $name) {
            $this->add_control(
                $blog_type,
                [
                    'label' => esc_html__('Select ' . $name, 'elementor-custom-widgets'),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $this->get_blog_titles($blog_type),
                    'default' => true,
                    'condition' => [
                        'post_type' => 'manual',
                        'blog_type' => $blog_type
                    ]
                ]
            );
        }
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';

        $posts = [];
        if ($settings['post_type'] === 'auto') {
            $count = $settings['count'];
            $items = $this->get_blogs($this->is_all_blog_types($settings['blog_type']), $settings['blog_tag']);
            $posts_count = count($items);
            $auto = $posts_count <= $count ? $items : array_slice($items, 0, $count);
            $posts = $auto;
        } elseif ($settings['post_type'] === 'manual') {
            $posts = $settings[$settings['blog_type']];
        }

        $button_class = 'button';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);

            if($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];

            }
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section featured-posts <?= esc_attr($section_class) ?>">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary wow fadeInUp featured-posts__title"><?= wp_kses_post($settings['title']) ?></h2>
            <?php endif; ?>

            <?php if (is_array($posts)) : ?>
              <div id="featured-posts__carousel" class="mt-300 featured-projects__carousel wow fadeInUp">

                  <?php foreach ($posts as $item) : ?>
                      <?php get_template_part('template-parts/featured-post', null,
                          array(
                              'item' => $item,
                              'blog_tag' => $this->config::BLOG_TAG
                          )
                      ); ?>
                  <?php endforeach; ?>
              </div>
            <?php endif; ?>

          <div class="mt-300 grid-row grid-row--aic grid-row--jcb wow fadeInUp">
            <div class="grid-col grid-col--auto">
              <div class="arrows">
                <button type="button" id="featured-posts__prev" class="arrows__prev"></button>
                <button type="button" id="featured-posts__next" class="arrows__next"></button>
              </div>
            </div>
            <div class="grid-col grid-col--auto">

                <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                     class="<?= $button_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
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


