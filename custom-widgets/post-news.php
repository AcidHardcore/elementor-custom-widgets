<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_News extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-news.css');
        wp_register_style('widget-post-news', get_template_directory_uri() . '/css/widget-post-news.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-post-projects', 'widget-post-news'];
    }

    public function get_name()
    {
        return 'post-news';
    }

    public function get_title()
    {
        return __('Post News', 'elementor-custom-widgets');
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
            'label' => __('Post News', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'posts_per_page', [
                'label' => esc_html__('Posts per Page', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => '12',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Categories Filter', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_cats(),
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

    public function get_new_posts($arg)
    {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $arg['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'paged' => $paged
        );

        if(isset($arg['categories']) && !empty($arg['categories'])) {
            $args['category__in'] = $arg['categories'];
        }

        $query = new \WP_Query($args);

        return array(
            'posts' =>$query->posts,
            'max' => $query->max_num_pages
        );
    }

    public function get_pagination($total_pages)
    {
        $links = false;
        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));

            $args = array(
                'mid_size' => 2,
//            'show_all' => true,
                'prev_next' => true,
                'prev_text' => __('prev', 'elementor-custom-widgets'),
                'next_text' => __('next', 'elementor-custom-widgets'),
                'screen_reader_text' => __('Posts navigation', 'elementor-custom-widgets'),
                'type' => 'array',
                'current' => $current_page,
                'total' => $total_pages,
            );

            $links = paginate_links($args);

//            if (!strpos($pagination_links[0], 'prev')) {
//                array_unshift($pagination_links, '<span class="prev pagination__prev">prev</span>');
//            }
//            $last_key = array_key_last($pagination_links);
//            if (!strpos($pagination_links[$last_key], 'next')) {
//                array_push($pagination_links, '<span class="pagination__next">next</span>');
//            }
        }
        return $links;
    }

    public function get_cats(): array
    {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => 'category',
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
        $arg = array(
            'posts_per_page' => $settings['posts_per_page'],
            'categories' => $settings['categories']
        );
        $loop = $this->get_new_posts($arg);
        $posts = $loop['posts'];
        $total_pages = $loop['max'];
        $pagination = $this->get_pagination($total_pages);
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="projects-layout">
        <div class="grid-cont grid-cont--sm">
          <div class="projects-layout__main projects-layout__main--alt">
            <div class="blogs-list wow fadeInUp">
              <div class="grid-row blogs-list__row">
                  <?php if (is_array($posts)) : ?>
                      <?php foreach ($posts as $post) : ?>
                          <?php
                          $link = get_the_permalink($post);
                          $title = get_the_title($post);
                          $image = get_the_post_thumbnail($post, 'large');
                          $date = get_the_date('m:d:Y', $post);
                          ?>
                      <div class="grid-col grid-col--4 grid-col--md-6 grid-col--xs-12 blogs-list__col">
                        <div class="blogs-list__item">

                          <div class="blogs-list__figure">
                              <?php if ($image) : ?>
                                  <?= $image ?>
                              <?php endif; ?>
                          </div>

                          <div class="blogs-list__cont">
                              <?php if ($date) : ?>
                                <div class="blogs-list__overline"><?= esc_html($date) ?></div>
                              <?php endif; ?>

                              <?php if ($title) : ?>
                                <div class="mt-025 blogs-list__heading"><?= esc_html($title) ?></div>
                              <?php endif; ?>

                              <?php if ($link) : ?>
                                <a href="<?= $link ?>" class="mt-050 cta-link cta-link--sm cta-link--enlarged">Read Full Story</a>
                              <?php endif; ?>

                          </div>
                        </div>
                      </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
            </div>

              <?php if ($total_pages > 1 && $pagination) : ?>
                <nav class="mt-300 pagination wow fadeInUp">
                    <?php foreach ($pagination as $key => $link) : ?>
                        <?php echo str_replace('page-numbers', 'pagination__num', $link); ?>
                    <?php endforeach; ?>

                </nav>
              <?php endif; ?>

          </div>
        </div>
      </div>

        <?php
    }
}


