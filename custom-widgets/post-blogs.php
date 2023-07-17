<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Blogs extends Widget_Base
{

    const BLOG_FILTERS = 'blog_filters';
    const SPORT_BLOG_FILTERS = 'sport_blog_filters';
    const POST_TYPES = [
        'infill_blog' => 'Infill Blog',
        'sport_blog' => 'Sport Blog'
    ];

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-blogs.css');
        wp_register_style('widget-post-blogs', get_template_directory_uri() . '/css/widget-post-blogs.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-post-blogs.js');
        wp_register_script('widget-post-blogs', get_template_directory_uri() . '/js/widget-post-blogs.js', array('jquery'), $js_version, true);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/blog-filter.js');
        wp_register_script('blog-filter', get_template_directory_uri() . '/js/blog-filter.js', array('jquery'), $js_version, true);

        wp_enqueue_script('hubspot-script', 'https://js.hsforms.net/forms/v2.js');

    }

    public function get_style_depends()
    {
        return ['widget-post-projects', 'widget-post-news', 'widget-post-blogs'];
    }

    public function get_script_depends()
    {
        return ['widget-post-blogs', 'blog-filter'];
    }

    public function get_name()
    {
        return 'post-blogs';
    }

    public function get_title()
    {
        return __('Post Blogs', 'elementor-custom-widgets');
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
            'label' => __('Post Blogs', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'posts_per_page', [
                'label' => esc_html__('Posts per Page', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => '8',
            ]
        );

        $this->add_control(
            'filter_label', [
                'label' => esc_html__('Filter Label', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Filter Blogs',
            ]
        );

        $this->add_control(
            'post_types',
            [
                'label' => esc_html__('Select Blog Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => static::POST_TYPES,
                'default' => true,
                'description' => esc_html__('Empty Categories are hidden', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'filters',
            [
                'label' => esc_html__('Select Infill Blog filter', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_filters(),
                'default' => true,
                'description' => esc_html__('Empty Filters are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'post_types' => 'infill_blog'
                ]
            ]
        );

        $this->add_control(
            'sport_filters',
            [
                'label' => esc_html__('Select Sport Blog filter', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_sport_filters(),
                'default' => true,
                'description' => esc_html__('Empty Filters are hidden', 'elementor-custom-widgets'),
                'condition' => [
                    'post_types' => 'sport_blog'
                ]
            ]
        );

        $this->add_control(
            'side_form',
            [
                'label' => esc_html__('Form Settings', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_heading', [
                'label' => esc_html__('Form Heading', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'form_copy', [
                'label' => esc_html__('Form Copy', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'form_hubspot', [
                'label' => esc_html__('Form Hubspot', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
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

    public function get_new_blogs($args)
    {


        $query = new \WP_Query($args);

        return array(
            'posts' => $query->posts,
            'max' => $query->max_num_pages
        );
    }

    public function get_pagination($total_pages, $current_page)
    {
        $links = false;
        if ($total_pages > 1) {

            $args = array(
                'mid_size' => 2,
                'prev_next' => true,
                'prev_text' => __('prev', 'elementor-custom-widgets'),
                'next_text' => __('next', 'elementor-custom-widgets'),
                'screen_reader_text' => __('Posts navigation', 'elementor-custom-widgets'),
                'type' => 'array',
                'current' => $current_page,
                'total' => $total_pages,
            );

            $links = paginate_links($args);

        }
        return $links;
    }

    public function get_filters(): array
    {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => static::BLOG_FILTERS,
            'hide_empty' => true,
        ]);
        foreach ($terms as $term) {
            $cats[$term->slug] = $term->name;
        }
        return $cats;
    }
    public function get_sport_filters(): array
    {
        $cats = array();

        $terms = get_terms([
            'taxonomy' => static::SPORT_BLOG_FILTERS,
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

        $args = array(
            'post_type' => $settings['post_types'],
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
        );
        $loop = $this->get_new_blogs($args);
        if (is_array($loop)) {
            $posts = $loop['posts'];
        }
        $total_pages = $loop['max'];
        $paged = max(1, get_query_var('paged'));
        $pagination = $this->get_pagination($total_pages, $paged);
        if(isset($settings['post_types']) && $settings['post_types'] === 'infill_blog') {
            $temp = array_combine($settings['filters'], $settings['filters']);
            $filters = array_intersect_key($this->get_filters(), $temp);
        } else {
            $temp = array_combine($settings['sport_filters'], $settings['sport_filters']);
            $filters = array_intersect_key($this->get_sport_filters(), $temp);
        }

        $js_args = $args;
        $js_args['paged'] = $paged;
        global $wp;
        $js_args['current_url'] = home_url(add_query_arg(array(), $wp->request));
        $js_args['blogs'] = array();
        $js_args['search'] = '';
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="blogs-filter removed-sm">
        <div class="grid-cont">
          <div class="h5 blogs-filter__cont wow fadeInUp">

              <?php if (isset($settings['filter_label'])) : ?>
                <div class="blogs-filter__title"><?= $settings['filter_label'] ?>:</div>
              <?php endif; ?>

              <?php if (is_array($filters)) : ?>
                  <?php foreach ($filters as $index => $filter) : ?>
                  <div class="blogs-filter__check">
                    <input type="checkbox" data-id="<?= esc_attr($index) ?>" id="<?= esc_attr($index) ?>">
                    <label for="<?= esc_attr($index) ?>"><?= esc_html($filter) ?></label>
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>

          </div>

          <div class="blogs-filter__foot wow fadeInUp">
            <button type="button" class="blogs-filter__button">Clear all</button>

              <?php if (is_array($filters)) : ?>
                  <?php foreach ($filters as $index => $filter) : ?>
                  <div class="blogs-filter__tag" data-id="<?= esc_attr($index) ?>"><?= esc_html($filter) ?>
                    <button type="button" data-id="<?= esc_attr($index) ?>"></button>
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>

          </div>
        </div>
      </div>

      <div class="projects-layout" data-args='<?= json_encode($js_args, JSON_NUMERIC_CHECK) ?>'>
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row grid-row--nog">
            <div class="grid-col grid-col--9 grid-col--sm-12">
              <div class="projects-layout__main">
                <div class="blogs-list wow fadeInUp">
                  <div class="grid-row blogs-list__row">

                      <?php if (is_array($posts)) : ?>
                          <?php foreach ($posts as $post) :
                              get_template_part('template-parts/blog', null, array('item' => $post));
                          endforeach; ?>
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

            <div class="grid-col grid-col--3 grid-col--sm-12">
              <div class="projects-layout__side projects-layout__side--alt">
                <div class="blogs-subscribe removed-sm wow fadeInUp">

                    <?php if (isset($settings['form_heading'])) : ?>
                      <h2><?= esc_html($settings['form_heading']) ?></h2>
                    <?php endif; ?>

                    <?php if (isset($settings['form_copy'])) : ?>
                      <p class="mt-100 p p--md"><?= esc_html($settings['form_copy']) ?></p>
                    <?php endif; ?>

                    <?php if (isset($settings['form_hubspot'])) : ?>
                        <?= $settings['form_hubspot'] ?>
                    <?php endif; ?>

                </div>


                <div class="projects-filter removed blocked-sm">
                  <button type="button" class="projects-filter__close"></button>
                  <div class="h2 tc-secondary projects-filter__title">Filters</div>
                  <button type="button" class="mt-075 projects-filter__button projects-filter__clear">Clear all</button>
                  <div class="mt-150 h5">
                      <?php if (is_array($filters)) : ?>
                          <?php foreach ($filters as $index => $filter) : ?>
                          <div class="projects-filter__check" data-id="<?= esc_attr($index) ?>">
                            <input type="checkbox" data-id="<?= esc_attr($index) ?>" id="mob-<?= esc_attr($index) ?>">
                            <label for="mob-<?= esc_attr($index) ?>"><?= esc_html($filter) ?></label>
                            <div></div>
                          </div>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  </div>
                  <button type="button" class="projects-filter__apply">Apply</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="filter-bar" class="filter-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic grid-row--smg">
            <div class="grid-col">
              <input type="text" placeholder="Search Blog" class="search-input projects-filter__search">
            </div>
            <div class="grid-col grid-col--auto">
              <div class="careers-nav">

                  <?php if (isset($settings['filter_label'])) : ?>
                    <button type="button" class="careers-nav__link"><?= esc_html($settings['filter_label']) ?></button>
                  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


