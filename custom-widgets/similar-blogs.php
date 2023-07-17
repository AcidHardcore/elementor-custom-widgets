<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Similar_Blogs extends Widget_Base
{
    /**
     * Custom Taxonomy
     */
    public const BLOG_FILTERS = 'blog_filters';

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-similar-blogs.css');
        wp_register_style('widget-similar-blogs', get_template_directory_uri() . '/css/widget-similar-blogs.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-similar-blogs.js');
        wp_register_script('widget-similar-blogs', get_template_directory_uri() . '/js/widget-similar-blogs.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-similar-blogs', 'widget-post-projects'];
    }

    public function get_script_depends()
    {
        return ['widget-similar-blogs'];
    }

    public function get_name()
    {
        return 'similar-blogs';
    }

    public function get_title()
    {
        return __('Similar Blogs', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
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
            'label' => __('Similar Blogs', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'infill_blog',
                'options' => [
                    'infill_blog' => esc_html__('Infill Blog', 'elementor-custom-widgets'),
                    'sport_blog' => esc_html__('Sport Blog', 'elementor-custom-widgets'),
                    'projects' => esc_html__('Projects', 'elementor-custom-widgets'),
                    'landscape_projects' => esc_html__('Infill Landscape Projects', 'elementor-custom-widgets'),
                    'sport_projects' => esc_html__('Infill Sport Projects', 'elementor-custom-widgets'),
                    'post' => esc_html__('News', 'elementor-custom-widgets'),
                ],
            ]
        );

	    $this->add_control(
		    'items_text', [
			    'label' => esc_html__('Posts CTA Text', 'elementor-custom-widgets'),
			    'type' => Controls_Manager::TEXT,
			    'default' => esc_html__('Take a Look', 'elementor-custom-widgets'),
			    'label_block' => true,
		    ]
	    );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .similar-blogs__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .similar-blogs__title',
            ]
        );

        $this->add_control('cta_text', [
            'label' => __('CTA Button Text', 'elementor-custom-widgets'),
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

        $this->end_controls_section();

    }

    public function get_similar_blogs($id)
    {
        $filter_array = get_the_terms($id, self::BLOG_FILTERS);
        $filter = array();

        if (is_array($filter_array)) {
            foreach ($filter_array as $item) {
                $filter[] = $item->slug;
            }
        }

        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'infill_blog',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($filter)) {
            $argsFilters['tax_query']['relation'] = 'OR';

            foreach ($filter as $value) {
                $argsFilters['tax_query'][] = [
                    'taxonomy' => self::BLOG_FILTERS,
                    'field' => 'slug',
                    'terms' => $value
                ];
            }

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'infill_blog',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id], $queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    public function get_similar_sport_blogs($id)
    {
        $filter_array = get_the_terms($id, self::BLOG_FILTERS);
        $filter = array();

        if (is_array($filter_array)) {
            foreach ($filter_array as $item) {
                $filter[] = $item->slug;
            }
        }

        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'sport_blog',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($filter)) {
            $argsFilters['tax_query']['relation'] = 'OR';

            foreach ($filter as $value) {
                $argsFilters['tax_query'][] = [
                    'taxonomy' => self::BLOG_FILTERS,
                    'field' => 'slug',
                    'terms' => $value
                ];
            }

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'sport_blog',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id], $queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    public function get_similar_projects($id)
    {
        $project_field_type = get_field('project_field_type', $id);

        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'projects',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($project_field_type)) {
            $argsFilters['meta_query']['relation'] = 'OR';

            $argsFilters['meta_query'][] = [
                'key' => 'project_field_type',
                'value' => $project_field_type[0],
                'compare' => 'LIKE',
            ];

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'projects',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id],$queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    public function get_similar_landscape_projects($id)
    {

        $project_landscape_type = get_field('project_landscape_type', $id);
        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'landscape_projects',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($project_landscape_type)) {
            $argsFilters['meta_query']['relation'] = 'OR';

            $argsFilters['meta_query'][] = [
                'key' => 'project_landscape_type',
                'value' => $project_landscape_type[0],
                'compare' => 'LIKE',
            ];

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'landscape_projects',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id],$queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    public function get_similar_sport_projects($id)
    {

        $project_sports_type = get_field('project_sports_type', $id);
        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'sport_projects',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($project_sports_type)) {
            $argsFilters['meta_query']['relation'] = 'OR';

            $argsFilters['meta_query'][] = [
                'key' => 'project_sports_type',
                'value' => $project_sports_type[0],
                'compare' => 'LIKE',
            ];

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'sport_projects',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id],$queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    public function get_similar_news($id)
    {
        $filter_array = get_the_terms($id, 'category');
        $filter = array();

        if (is_array($filter_array)) {
            foreach ($filter_array as $item) {
                $filter[] = $item->slug;
            }
        }

        $argsFilters = [
            'posts_per_page' => 3,
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
            'post__not_in' => [$id],
            'fields' => 'ids',
            'no_found_rows' => true,
        ];

        if (!empty($filter)) {
            $argsFilters['tax_query']['relation'] = 'OR';

            foreach ($filter as $value) {
                $argsFilters['tax_query'][] = [
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $value
                ];
            }

        }

        $queryFilters = new \WP_Query($argsFilters);
        $query = $queryFilters;

        if ($queryFilters->post_count < 3) {
            $per_page = 3 - $queryFilters->post_count;
            $argsAll = [
                'posts_per_page' => $per_page,
                'post_type' => 'post',
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array_merge([$id], $queryFilters->posts),
                'fields' => 'ids',
                'no_found_rows' => true,
            ];

            $queryTemp = new \WP_Query();
            $queryAll = new \WP_Query($argsAll);
            $queryTemp->posts = array_merge($queryFilters->posts, $queryAll->posts);
            $queryTemp->post_count = $queryTemp->post_count + $queryAll->post_count;
            $query = $queryTemp;
        }

        return $query->posts;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $post_id = get_the_id();
        if (isset($settings['post_type']) && $settings['post_type'] === 'infill_blog') {
            $posts = $this->get_similar_blogs($post_id);
        }
        if (isset($settings['post_type']) && $settings['post_type'] === 'sport_blog') {
            $posts = $this->get_similar_sport_blogs($post_id);
        }
        if (isset($settings['post_type']) && $settings['post_type'] === 'projects') {
            $posts = $this->get_similar_projects($post_id);
        }
        if (isset($settings['post_type']) && $settings['post_type'] === 'landscape_projects') {
            $posts = $this->get_similar_landscape_projects($post_id);
        }
        if (isset($settings['post_type']) && $settings['post_type'] === 'sport_projects') {
            $posts = $this->get_similar_sport_projects($post_id);
        }

        $button_class = 'button';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
            if($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];

            }
        }
        if (isset($settings['post_type']) && $settings['post_type'] === 'post') {
            $posts = $this->get_similar_news($post_id);
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section similar-blogs">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary similar-blogs__title wow fadeInUp"><?= esc_html($settings['title']) ?></h2>
            <?php endif; ?>

          <div class="grid-row similar-blogs__row wow fadeInUp">

              <?php if (array($posts) && !empty($posts)) : ?>
                  <?php foreach ($posts as $post) : ?>

                      <?php if (get_post_type() === 'infill_blog' ) : ?>
                          <?php get_template_part('template-parts/similar-blog', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                      <?php if (get_post_type() === 'sport_blog') : ?>
                          <?php get_template_part('template-parts/similar-sport-blog', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                      <?php if (get_post_type() === 'projects') : ?>
                          <?php get_template_part('template-parts/similar-project', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                      <?php if (get_post_type() === 'landscape_projects') : ?>
                          <?php get_template_part('template-parts/similar-landscape-project', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                      <?php if (get_post_type() === 'sport_projects') : ?>
                          <?php get_template_part('template-parts/similar-sport-project', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                      <?php if (get_post_type() === 'post' ) : ?>
                          <?php get_template_part('template-parts/similar-news', null, array(
                                  'item' => $post,
                                  'btn_text' => $settings['items_text'] ?? ''
                          )); ?>
                      <?php endif; ?>

                  <?php endforeach; ?>
              <?php endif; ?>

          </div>

            <?php if (isset($settings['cta_text']) && !empty($settings['cta_link']['url'])) : ?>
              <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                 class="mt-300 <?= $button_class ?> wow fadeInUp" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                  <?= esc_html($settings['cta_text']) ?>
              </a>
            <?php endif; ?>

        </div>
      </div>

        <?php
    }
}


