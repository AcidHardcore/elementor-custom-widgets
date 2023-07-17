<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Our_People extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-our-people.css');
        wp_register_style('widget-our-people', get_template_directory_uri() . '/css/widget-our-people.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-our-people.js');
        wp_register_script('widget-our-people', get_template_directory_uri() . '/js/widget-our-people.js', array('jquery'), $js_version, true);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/employee-filter.js');
        wp_register_script('employee-filter', get_template_directory_uri() . '/js/employee-filter.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-post-projects', 'widget-post-news', 'widget-post-blogs', 'widget-our-people'];
    }

    public function get_script_depends()
    {
        return ['widget-our-people', 'employee-filter'];
    }

    public function get_name()
    {
        return 'our-people';
    }

    public function get_title()
    {
        return __('Our People', 'elementor-custom-widgets');
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
            'label' => __('Our People', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


        $this->add_control(
            'filter_label', [
                'label' => esc_html__('Filter Label', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Filter Blogs',
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-people__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .our-people__title',
            ]
        );

        $this->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .our-people__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .our-people__desc',
            ]
        );

        $this->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Contact Us', 'elementor-custom-widgets'),
                'label_block' => true,
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

    public function get_employees($args)
    {


        $query = new \WP_Query($args);

        return array(
            'posts' => $query->posts,
            'max' => $query->max_num_pages
        );
    }

    public function get_teams(): array
    {
        $teams = [];
        $result = [];

        $args = array(
            'post_type' => 'employees',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'fields' => 'ids',
        );
        $employees = $this->get_employees($args);
        if (is_array($employees)) {
            foreach ($employees['posts'] as $index => $person) {
                $team = get_field('team', $person);
                $teams[] = get_field('team', $person);
            }
            $tempArr = array_unique(array_column($teams, 'value'));
            $result = array_intersect_key($teams, $tempArr);
        }

        return $result;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
        }

        $args = array(
            'post_type' => 'employees',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids',
        );
        $loop = $this->get_employees($args);
        if (is_array($loop)) {
            $posts = $loop['posts'];
        }
        $filters = $this->get_teams();

        $js_args = $args;
        $js_args['teams'] = array();
        ?>

      <div id="page-nav" class="page-nav page-nav--alt"></div>

      <div id="<?= esc_attr($section_id) ?>" class="blogs-filter removed-sm">
        <div class="grid-cont">
          <div class="h5 blogs-filter__cont wow fadeInUp">

              <?php if (isset($settings['filter_label'])) : ?>
                <div class="blogs-filter__title"><?= $settings['filter_label'] ?>:</div>
              <?php endif; ?>

              <?php if (is_array($filters)) : ?>
                  <?php foreach ($filters as $filter) : ?>
                  <div class="blogs-filter__check">
                    <input type="checkbox" data-id="<?= esc_attr($filter['value']) ?>" id="<?= esc_attr($filter['value']) ?>">
                    <label for="<?= esc_attr($filter['value']) ?>"><?= esc_html($filter['label']) ?></label>
                  </div>
                  <?php endforeach; ?>

              <?php endif; ?>

          </div>

          <div class="blogs-filter__foot wow fadeInUp">
            <button type="button" class="blogs-filter__button">Clear all</button>
          </div>
        </div>
      </div>

      <div class="projects-layout__side removed blocked-sm">
        <div class="projects-filter removed blocked-sm">
          <button type="button" class="projects-filter__close"></button>
          <div class="h2 tc-secondary projects-filter__title">Filters</div>
          <button type="button" class="mt-075 projects-filter__button projects-filter__clear">Clear all</button>
          <div class="mt-150 h5">
              <?php if (is_array($filters)) : ?>
                  <?php foreach ($filters as $filter) : ?>
                  <div class="projects-filter__check" data-id="<?= esc_attr($filter['value']) ?>">
                    <input type="checkbox" data-id="<?= esc_attr($filter['value']) ?>" id="mob-<?= esc_attr($filter['value']) ?>">
                    <label for="mob-<?= esc_attr($filter['value']) ?>"><?= esc_html($filter['label']) ?></label>
                    <div></div>
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>
            <button type="button" class="projects-filter__apply">Apply</button>
          </div>
        </div>
      </div>

      <div class="section our-people" data-args='<?= json_encode($js_args, JSON_NUMERIC_CHECK) ?>'>
        <div class="grid-cont">
          <div class="grid-row grid-row--nog grid-row--jcc">
            <div class="grid-col grid-col--10 grid-col--md-12 wow fadeInUp">
                <?php if (isset($settings['title'])) : ?>
                  <h2 class="tc-primary num-title our-people__title"><?= esc_html($settings['title']) ?></h2>
                <?php endif; ?>

                <?php if (isset($settings['text'])) : ?>
                  <p class="mt-100 our-people__desc"><?= wp_kses_post($settings['text']) ?></p>
                <?php endif; ?>

            </div>
          </div>

          <div class="mt-100 grid-row grid-row--smg wow fadeInUp">

              <?php if (is_array($posts)) : ?>
                  <?php for ($i = 0; $i < count($posts); $i++) :
                      get_template_part('template-parts/employee', null, array('item' => $posts[$i]));

                      if (($i + 1) % 5 === 0) : ?>
                        <div class="grid-col grid-col--12 removed-lg"></div>
                      <?php endif;
                  endfor; ?>
              <?php endif; ?>

          </div>
        </div>
      </div>

      <div id="filter-bar" class="filter-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic grid-row--smg">
            <div class="grid-col"></div>
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


      <div id="float-bar" class="float-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic">
            <div class="grid-col">
              <div class="h2">Our People</div>
            </div>
            <div class="grid-col grid-col--auto">
              <div class="careers-nav">
                  <?php if (isset($settings['link_text']) && !empty($settings['cta_link']['url'])) : ?>
                    <a href="<?= esc_url($settings['cta_link']['url']) ?>"
                       class="careers-nav__link" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                        <?= esc_html($settings['link_text']) ?>
                    </a>
                  <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


