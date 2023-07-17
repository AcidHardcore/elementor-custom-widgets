<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class Top_Projects extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-top-projects.css');
        wp_register_style('widget-top-projects', get_template_directory_uri() . '/css/widget-top-projects.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-top-projects.js');
        wp_register_script('widget-top-projects', get_template_directory_uri() . '/js/widget-top-projects.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-top-projects'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-top-projects'];
    }

    public function get_name()
    {
        return 'top-projects';
    }

    public function get_title()
    {
        return __('Top Projects', 'elementor-custom-widgets');
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
            'label' => __('Top Projects', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .top-projects__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Title Typography', 'elementor-custom-widgets'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .top-projects__title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .top-projects__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Text Typography', 'elementor-custom-widgets'),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .top-projects__text',
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
                'label' => esc_html__('Post Count', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 3,
                'condition' => [
                    'feed_type' => 'auto'
                ]
            ]
        );

        $this->add_control(
            'feed_type',
            [
                'label' => esc_html__('Select Feed Type', 'elementor-custom-widgets'),
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
            'auto_post_type',
            [
                'label' => esc_html__('Auto Post Types', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => 'post',
                'options' => $this->config::CUSTOM_POST_TYPES,
                'description' => 'Choose One or More',
                'condition' => [
                    'feed_type' => 'auto',
                ]
            ]
        );

        $this->add_control(
            'project_tag',
            [
                'label' => esc_html__('Select Project Tag', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_tags($this->config::PROJECT_TAG),
                'description' => esc_html__('Empty Tags are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'feed_type' => 'auto',
                ]
            ]
        );

        $this->add_control(
            'blog_tag',
            [
                'label' => esc_html__('Select Blog Tag', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_tags($this->config::BLOG_TAG),
                'description' => esc_html__('Empty Tags are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'feed_type' => 'auto',
                ]
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post select type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'post' => [
                        'title' => esc_html__('Post News', 'elementor-custom-widgets'),
                        'icon' => 'eicon-settings',
                    ],
                    'infill_blog' => [
                        'title' => esc_html__('Infill Blog', 'elementor-custom-widgets'),
                        'icon' => 'eicon-zoom-in',
                    ],
                    'sport_blog' => [
                        'title' => esc_html__('Sport Blog', 'elementor-custom-widgets'),
                        'icon' => 'eicon-zoom-in',
                    ],
                    'projects' => [
                        'title' => esc_html__('Projects', 'elementor-custom-widgets'),
                        'icon' => 'eicon-elementor',
                    ],
                    'landscape_projects' => [
                        'title' => esc_html__('Landscape Projects', 'elementor-custom-widgets'),
                        'icon' => 'eicon-carousel',
                    ],
                    'sport_projects' => [
                        'title' => esc_html__('Sport Projects', 'elementor-custom-widgets'),
                        'icon' => 'eicon-counter',
                    ],

                ],
                'default' => 'post',
                'toggle' => false,
                'condition' => [
                    'feed_type' => 'manual',
                ]
            ]
        );

        $this->add_post_select_control();

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

    public function get_top_projects($post_type, $project_tag = null, $blog_tag = null)
    {
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        $blog_types = array_keys($this->config::BLOG_TYPES);
        $project_types = array_keys($this->config::PROJECT_TYPES);

        if (!empty($project_tag) && is_array(array_intersect($post_type, $project_types))) {
            $args['tax_query'][] = [
                'taxonomy' => $this->config::PROJECT_TAG,
                'field' => 'slug',
                'terms' => $project_tag
            ];
        }

        if (!empty($blog_tag) && is_array(array_intersect($post_type, $blog_types))) {
            $args['tax_query'][] = [
                'taxonomy' => $this->config::BLOG_TAG,
                'field' => 'slug',
                'terms' => $blog_tag
            ];
        }


        $query = new \WP_Query($args);

        return $query->posts;
    }

    public function get_top_projects_titles($post_type)
    {
        $posts = $this->get_top_projects($post_type);
        $titles = array();

        foreach ($posts as $post) {
            $titles[$post] = get_the_title($post);
        }

        return $titles;
    }

    private function add_post_select_control()
    {
        foreach ($this->config::CUSTOM_POST_TYPES as $post_type => $name) {
            $this->add_control(
                $post_type,
                [
                    'label' => esc_html__('Select ' . $name, 'elementor-custom-widgets'),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $this->get_top_projects_titles($post_type),
                    'default' => true,
                    'condition' => [
                        'feed_type' => 'manual',
                        'post_type' => $post_type
                    ]
                ]
            );
        }
    }

    protected function get_post_tags($taxonomy)
    {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);
        foreach ($terms as $term) {
            $cats[$term->slug] = $term->name;
        }
        return $cats;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $posts = [];
        if ($settings['feed_type'] === 'auto') {
            $count = $settings['count'];
            $items = $this->get_top_projects($settings['auto_post_type'], $settings['project_tag'], $settings['blog_tag']);
            $posts_count = count($items);
            $auto = $posts_count <= $count ? $items : array_slice($items, 0, $count);
            $posts = $auto;
        } elseif ($settings['feed_type'] === 'manual') {
            $posts = $settings[$settings['post_type']];

        }

        ?>
      <div id="page-nav"></div>

      <div id="<?= esc_attr($section_id) ?>" class="top-projects">
        <div class="grid-cont grid-cont--sm top-projects__inner">
          <div id="top-projects__carousel" class="top-projects__carousel">

              <?php if (is_array($posts)) : ?>
                  <?php foreach ($posts as $item) : ?>

                      <?php get_template_part('template-parts/top-project', null,
                          array(
                              'item' => $item,
                              'button_type' => $settings['button_type'],
                              'blog_tag' => $this->config::BLOG_TAG,
                              'project_tag' => $this->config::PROJECT_TAG,
                              'category' => $this->config::CATEGORY,
                          )
                      ); ?>

                  <?php endforeach; ?>
              <?php endif; ?>

          </div>
          <div class="arrows top-projects__arrows">
            <button type="button" id="top-projects__prev" class="arrows__prev top-projects__prev"></button>
            <button type="button" id="top-projects__next" class="arrows__next top-projects__next"></button>
          </div>
        </div>
      </div>
        <?php
    }
}


