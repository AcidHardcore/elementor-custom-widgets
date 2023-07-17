<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Appreciations extends Widget_Base
{

    const PROJECT_TYPES = [
        [
            'slug' => 'projects',
            'name' => 'Sport Field Construction Projects'
        ],
        [
            'slug' => 'landscape_projects',
            'name' => 'Infill Landscape Projects'
        ],
        [
            'slug' => 'sport_projects',
            'name' => 'Infill Sport Projects'
        ],
//        [
//            'slug' => 'maintenance_projects',
//            'name' => 'Maintenance Projects'
//        ],
    ];

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-appreciations.css');
        wp_register_style('widget-appreciations', get_template_directory_uri() . '/css/widget-appreciations.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-appreciations.js');
        wp_register_script('widget-appreciations', get_template_directory_uri() . '/js/widget-appreciations.js', array('jquery'), $js_version, true);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/appreciation-filter.js');
        wp_register_script('appreciation-filter', get_template_directory_uri() . '/js/appreciation-filter.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-post-blogs', 'widget-appreciations'];
    }

    public function get_script_depends()
    {
        return ['widget-appreciations', 'appreciation-filter'];
    }

    public function get_name()
    {
        return 'appreciations';
    }

    public function get_title()
    {
        return __('Appreciations', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
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
            'label' => __('Appreciations', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .appreciations__title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .appreciations__title',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'posts_per_page', [
                'label' => __('Projects per Page', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('7', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'floating_bar',
            [
                'label' => esc_html__('Floating Bar', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bar_title', [
                'label' => esc_html__('Title Bar', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
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

        $this->add_control(
            'nop',
            [
                'label' => esc_html__('Reduce Spacing', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

    }

    public function get_new_projects($args)
    {

        $query = new \WP_Query($args);

        return array(
            'posts' => $query->posts,
            'max' => $query->max_num_pages
        );
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['nop']) && $settings['nop'] === 'yes' ? 'section--nop' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
        }


        ?>
      <div id="page-nav" class="page-nav page-nav--alt"></div>

      <div id="<?= esc_attr($section_id) ?>" class="section appreciations <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-secondary appreciations__title"><?= esc_html($settings['title']) ?></h2>
            <?php endif; ?>

          <div class="mt-150 h4 appreciations__tabs">

              <?php foreach (self::PROJECT_TYPES as $index => $service) : ?>
                <a href="#appreciations__pane-<?= $index ?>" data-scroll="#appreciations__pane-<?= $index ?>"><?= $service['name'] ?></a>
              <?php endforeach; ?>
          </div>


            <?php foreach (self::PROJECT_TYPES as $index => $service) : ?>
                <?php
                $args = array(
                    'post_type' => $service['slug'],
                    'post_status' => 'publish',
                    'posts_per_page' => $settings['posts_per_page'],
                    'orderby' => 'project_quote',
                    'order' => 'DESC',
                    'fields' => 'ids',
                    'meta_query' => [
//                        'relation' => 'AND',
                        [
                            'key' => 'project_quote',
                            'compare' => '!=',
                            'value' => ''
                        ]
                    ]
                );
                $loop = $this->get_new_projects($args);
                $paged = max(1, get_query_var('paged'));
                $js_args = $args;
                $js_args['paged'] = $paged;
                $js_args['testimonials'] = array();
                $js_args['max_page'] = $loop['max'];
                ?>
              <div id="appreciations__pane-<?= $index ?>" data-slug="<?= $service['slug'] ?>" class="appreciations__pane" data-index="<?= $index ?>" data-args='<?= json_encode($js_args, JSON_NUMERIC_CHECK) ?>'>

                <div class="h1 tc-primary"><?= $service['name'] ?></div>

                <div class="mt-100 blogs-filter">
                  <div class="h5 blogs-filter__cont">
                    <div class="blogs-filter__title">Filter<span class="removed-md"> Testimonials</span>:</div>
                    <div class="blogs-filter__check">
                      <input type="checkbox" data-id="<?= $service['slug'] ?>" data-type="video">
                      <label>Customer Videos</label>
                    </div>
                    <div class="blogs-filter__check">
                      <input type="checkbox" data-id="<?= $service['slug'] ?>" data-type="text">
                      <label>Testimonials</label>
                    </div>
                    <button type="button" class="blogs-filter__button active">Clear all</button>
                  </div>
                </div>

                <div class="appreciations__grid">

                    <?php if (!empty($loop)) : ?>
                          <?php foreach ($loop['posts'] as $j => $post) : ?>

                                  <?php $is_quote_video = get_field('is_quote_video', $post); ?>
                                  <?php if ($is_quote_video) : ?>
                                      <?php get_template_part('template-parts/appreciation-video', null, array('item' => $post)); ?>
                                  <?php else: ?>
                                      <?php get_template_part('template-parts/appreciation-quote', null, array('item' => $post)); ?>
                                  <?php endif; ?>

                          <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <div class="mt-250 grid-row grid-row--jcc">
                  <div class="grid-col grid-col--auto">
                      <?php if (isset($loop['max']) && $loop['max'] > 1) : ?>
                        <button class="button button--primary load-more">View More</button>
                      <?php endif; ?>
                  </div>
                </div>

              </div>
            <?php endforeach; ?>


        </div>
      </div>

      <div id="float-bar" class="float-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic">
            <div class="grid-col">

                <?php if (isset($settings['bar_title'])) : ?>
                  <h2 class="h2"><?= esc_html($settings['bar_title']) ?></h2>
                <?php endif; ?>

            </div>
            <div class="grid-col"></div>
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


