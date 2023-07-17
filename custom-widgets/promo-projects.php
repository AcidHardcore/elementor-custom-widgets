<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Promo_Projects extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-promo-projects.css');
        wp_register_style('widget-promo-projects', get_template_directory_uri() . '/css/widget-promo-projects.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-promo-projects.js');
        wp_register_script('widget-promo-projects', get_template_directory_uri() . '/js/widget-promo-projects.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-content', 'widget-promo-projects'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-promo-projects'];
    }

    public function get_name()
    {
        return 'promo-projects';
    }

    public function get_title()
    {
        return __('Promo Projects', 'elementor-custom-widgets');
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
            'label' => __('Promo Projects', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .promo-projects__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .promo-projects__title',
            ]
        );

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
            'count',
            [
                'label' => esc_html__('Projects Count', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => [
                    'post_type' => 'auto'
                ]
            ]
        );

        $this->add_control(
            'project_type',
            [
                'label' => esc_html__('Projects Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'projects',
                'options' => $this->config::PROJECT_TYPES,
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label' => esc_html__('Projects Content Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'project-case-study',
                'options' => $this->config::CONTENT_TYPE,
                'condition' => [
                    'post_type' => 'auto',
                    'project_type' => 'projects'
                ]
            ]
        );

        $this->add_control(
            'project_field_type',
            [
                'label' => esc_html__('Projects Field Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $this->config::PROJECT_FIELD_TYPE,
                'condition' => [
                    'post_type' => 'auto',
                    'project_type' => 'projects'
                ]
            ]
        );

        $this->add_control(
            'project_tag',
            [
                'label' => esc_html__('Select Project Tag', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_project_tags(),
                'description' => esc_html__('Empty Tags are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'post_type' => 'auto',
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
                'toggle' => false,
            ]
        );

        $this->add_project_select_control();

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

    public function get_projects($project_type = 'projects', $content_type = null, $project_field_type = null, $project_tag = null)
    {
        $args = array(
            'post_type' => $project_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'fields' => 'ids',
            'no_found_rows' => true,
        );

        if (!empty($project_tag)) {
            $args['tax_query'][] = [
                'taxonomy' => $this->config::PROJECT_TAG,
                'field' => 'slug',
                'terms' => $project_tag
            ];
        }

        if ($project_type === 'projects') {
            if (!empty($content_type && $content_type !== 'all')) {
                $args['meta_query']['relation'] = 'OR';

                $args['meta_query'][] = [
                    'key' => 'content_type',
                    'value' => sprintf(':"%s";', $content_type),
                    'compare' => 'LIKE',
                ];

            }

            if (!empty($project_field_type) && $project_field_type !== 'all') {
                $args['meta_query']['relation'] = 'AND';

                $args['meta_query'][] = [
                    'key' => 'project_field_type',
                    'value' => $project_field_type,
                    'compare' => 'LIKE',
                ];

            }
        }

        $query = new \WP_Query($args);

        return $query->posts;
    }

    public function get_project_titles($project_type)
    {
        $posts = $this->get_projects($this->is_all_project_types($project_type));
        $titles = array();

        foreach ($posts as $post) {
            $titles[$post] = get_the_title($post);
        }

        return $titles;
    }

    private function add_project_select_control()
    {
        foreach ($this->config::PROJECT_TYPES as $project_type => $name) {
            $this->add_control(
                $project_type,
                [
                    'label' => esc_html__('Select ' . $name, 'elementor-custom-widgets'),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $this->get_project_titles($project_type),
                    'default' => true,
                    'condition' => [
                        'post_type' => 'manual',
                        'project_type' => $project_type
                    ]
                ]
            );
        }
    }

    protected function get_project_tags() {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => $this->config::PROJECT_TAG,
            'hide_empty' => true,
        ]);
        foreach ($terms as $term) {
            $cats[$term->slug] = $term->name;
        }
        return $cats;
    }

    public function get_all_project_types()
    {
        $keys = array_keys($this->config::PROJECT_TYPES);
        $all_project_types = array_filter($keys, function($key) {
            return $key !== 'all';
        });
        return $all_project_types;
    }

    public function is_all_project_types($project_type)
    {
        if ($project_type !== 'all') {
            return $project_type;
        } else {
            return $this->get_all_project_types();
        }
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $posts = [];
        if ($settings['post_type'] === 'auto') {
            $count = $settings['count'];
            $items = $this->get_projects($this->is_all_project_types($settings['project_type']), $settings['content_type'], $settings['project_field_type'], $settings['project_tag']);
            $posts_count = count($items);
            $auto = $posts_count <= $count ? $items : array_slice($items, 0, $count);
            $posts = $auto;
        } elseif ($settings['post_type'] === 'manual') {
            $posts = $settings[$settings['project_type']];
        }

        $title_class = 'promo-projects__title';
        if ($settings['is_num'] === 'num') {
            $title_class .= ' num-title';
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section promo-projects <?= esc_attr($section_class) ?>">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary wow fadeInUp <?= esc_attr($title_class) ?>">

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

          <div id="promo-projects__carousel" class="mt-300 promo-projects__carousel wow fadeInUp">
              <?php if (is_array($posts) && !empty($posts)) : ?>
                  <?php foreach ($posts as $item) : ?>

                      <?php get_template_part('template-parts/promo-project', null, array('item' => $item)); ?>

                  <?php endforeach; ?>
              <?php endif; ?>

          </div>
        </div>
      </div>

        <?php
    }
}


