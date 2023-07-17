<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Service_Select extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-service-select.css');
        wp_register_style('widget-service-select', get_template_directory_uri() . '/css/widget-service-select.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-service-select.js');
        wp_register_script('widget-service-select', get_template_directory_uri() . '/js/widget-service-select.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['slick-styles', 'widget-content', 'widget-featured-projects', 'widget-our-features', 'widget-service-select'];
    }

    public function get_script_depends()
    {
        return ['slick-scripts', 'widget-featured-projects', 'widget-service-select'];
    }

    public function get_name()
    {
        return 'service-select';
    }

    public function get_title()
    {
        return __('Service Select', 'elementor-custom-widgets');
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
            'label' => __('Service Select', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'service_title', [
                'label' => __('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-select__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Title Typography', 'elementor-custom-widgets'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .service-select__title',
            ]
        );

        $this->add_control(
            'title_1', [
                'label' => __('Title 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title 1', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_2', [
                'label' => __('Title 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title 2', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_3', [
                'label' => __('Title 3', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title 3', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading 1,2,3 Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-select__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Heading 1,2,3 Typography', 'elementor-custom-widgets'),
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .service-select__heading',
            ]
        );

        $this->add_control(
            'tip_first', [
            'label' => __('Tip First', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('tip first', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'tip_first_color',
            [
                'label' => __('Tip First Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-select__first-tip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Tip First Typography', 'elementor-custom-widgets'),
                'name' => 'tip_first_typography',
                'selector' => '{{WRAPPER}} .service-select__first-tip',
            ]
        );

        $this->add_control(
            'tip', [
            'label' => __('Tip', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('tip', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'tip_color',
            [
                'label' => __('Tip Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-select__scroll' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Tip Typography', 'elementor-custom-widgets'),
                'name' => 'tip_typography',
                'selector' => '{{WRAPPER}} .service-select__scroll',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-select__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Subtitle Typography', 'elementor-custom-widgets'),
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .service-select__item',
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

        $repeater = new Repeater();

        $repeater->add_control(
            'subtitle', [
            'label' => __('Subtitle', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('subtitle', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXTAREA,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
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

        $repeater->add_control(
            'image_featured',
            [
                'label' => esc_html__('Choose Featured Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'project_options',
            [
                'label' => esc_html__('Project Options', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'project_type',
            [
                'label' => esc_html__('Projects Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'projects',
                'options' => $this->config::PROJECT_TYPES,
            ]
        );

        $repeater->add_control(
            'project_field_type',
            [
                'label' => esc_html__('Projects Field Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'natural',
                'options' => $this->config::PROJECT_FIELD_TYPE,
                'condition' => [
                    'post_type' => 'auto',
                    'project_type' => 'projects'
                ]
            ]
        );

        $repeater->add_control(
            'project_title', [
                'label' => __('Project Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'count',
            [
                'label' => esc_html__('Projects Count', 'elementor-custom-widgets'),
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

        $repeater->add_control(
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

        $repeater->add_control(
            'projects',
            [
                'label' => esc_html__('Select Projects', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_project_titles(),
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

        $repeater->add_control(
            'project_cta_text', [
            'label' => __('CTA Button Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'default' => __('View More', 'elementor-custom-widgets')
        ]);

        $repeater->add_control(
            'project_cta_link',
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

        $this->add_control(
            'list',
            [
                'label' => esc_html__('List', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'subtitle' => esc_html__('Subtitle #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ subtitle }}}',
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

    public function get_projects($project_type = 'projects', $project_field_type = null)
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

        if ($project_type === 'projects') {

            if (!empty($project_field_type)) {
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

    public function get_project_titles()
    {
        $posts = $this->get_projects();
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

//        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
//            $this->add_link_attributes('cta_link', $settings['cta_link']);
//        }

        $list = $settings['list'];
        is_array($list) ? $first_key = array_key_first($list) : $first_key = null;

//        if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
//            $this->add_link_attributes('cta_link', $item['cta_link']);
//        }
        ?>

      <div id="page-nav" class="page-nav page-nav--alt"></div>

      <div id="<?= esc_attr($section_id) ?>" class="service-select">
        <div class="service-select__side">

            <?php if (isset($settings['service_title'])) : ?>
              <h2 class="tc-secondary service-select__title"><?= esc_html($settings['service_title']) ?></h2>
            <?php endif; ?>

            <?php if (isset($settings['title_1'])) : ?>
              <div class="mt-150 service-select__heading"><?= esc_html($settings['title_1']) ?></div>
            <?php endif; ?>

          <div class="service-select__group">
              <?php if (is_array($settings['list'])) : ?>
                  <?php foreach ($list as $index => $item) : ?>
                      <?php if ($index < 2 && isset($item['subtitle'])) : ?>
                    <div data-index="<?= $index + 1 ?>" class="service-select__item"><?= esc_html($item['subtitle']) ?></div>
                      <?php endif; ?>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>

            <?php if (isset($settings['title_2'])) : ?>
              <div class="mt-150 service-select__heading"><?= esc_html($settings['title_2']) ?></div>
            <?php endif; ?>

          <div class="service-select__group">
              <?php if (is_array($settings['list'])) : ?>
                  <?php foreach ($list as $index => $item) : ?>
                      <?php if ($index > 1 && $index < 4 && isset($item['subtitle'])) : ?>
                    <div data-index="<?= $index + 1 ?>" class="service-select__item"><?= esc_html($item['subtitle']) ?></div>
                      <?php endif; ?>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>

            <?php if (isset($settings['title_3'])) : ?>
              <div class="mt-150 service-select__heading"><?= esc_html($settings['title_3']) ?></div>
            <?php endif; ?>

          <div class="service-select__group">
              <?php if (is_array($settings['list'])) : ?>
                  <?php foreach ($list as $index => $item) : ?>
                      <?php if ($index > 3 && isset($item['subtitle'])) : ?>
                    <div data-index="<?= $index + 1 ?>" class="service-select__item"><?= esc_html($item['subtitle']) ?></div>
                      <?php endif; ?>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>

        </div>
        <div class="service-select__main">
          <div class="service-select__slide active">

              <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                  <?= wp_get_attachment_image($settings['image']['id'], 'full', false, array('class' => 'service-select__cover')) ?>
              <?php endif; ?>

              <?php if (isset($settings['tip_first'])) : ?>
                <div class="h2 tc-secondary service-select__first-tip"><?= esc_html($settings['tip_first']) ?></div>
              <?php endif; ?>

          </div>

            <?php if (is_array($settings['list'])) : ?>
                <?php foreach ($list as $index => $item) : ?>
                <!--                    --><?php //$class = $index === $first_key ? 'active ' : '' ?>

                <div class="service-select__slide ">

                    <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                        <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'service-select__cover')) ?>
                    <?php endif; ?>

                    <?php if (isset($settings['tip'])) : ?>
                      <div class="service-select__scroll">
                        <svg width="21" height="22" viewBox="0 0 21 22">
                          <g fill="none" fill-rule="evenodd" stroke="#C2D500" transform="translate(1.24 1)">
                            <polyline points="4.286 5.714 14.286 15 4.286 24.286" transform="rotate(90 9.286 15)"/>
                            <polyline points="4.286 -4.286 14.286 5 4.286 14.286" transform="rotate(90 9.286 5)"/>
                          </g>
                        </svg>
                          <?= esc_html($settings['tip']) ?>
                      </div>
                    <?php endif; ?>

                </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
      </div>


      <div class="service-select-spoiler">

          <?php if (is_array($settings['list'])) : ?>
              <?php foreach ($list as $index => $item) : ?>
                  <?php
                  if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                      $this->add_link_attributes('cta_link', $item['cta_link']);
                  }
                  ?>

              <div class="section our-features service-select-features">
                <div class="grid-cont grid-cont--sm">
                  <div class="our-features__item">
                    <div class="grid-row grid-row--aic">
                      <div class="grid-col grid-col--sm-12">

                          <?php if (isset($item['subtitle'])) : ?>
                            <h2 class="tc-primary"><?= esc_html($item['subtitle']) ?></h2>
                          <?php endif; ?>

                          <?php if (isset($item['text'])) : ?>
                            <p class="mt-100"><?= esc_html($item['text']) ?></p>
                          <?php endif; ?>

                          <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                            <a href="<?= esc_url($item['cta_link']['url']) ?>"
                               class="mt-150 button button--primary" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                <?= esc_html($item['link_text']) ?>
                            </a>
                          <?php endif; ?>

                      </div>
                      <div class="grid-col grid-col--auto removed-md"></div>
                      <div class="grid-col grid-col--auto grid-col--sm-12"></div>
                      <div class="grid-col grid-col--auto grid-col--sm-12 grid-col--sm-order-0">

                          <?php if (isset($item['image_featured']) && !empty($item['image_featured']['id'])) : ?>
                              <?= wp_get_attachment_image($item['image_featured']['id'], 'full', false, array('class' => 'our-features__image')) ?>
                          <?php endif; ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php endforeach; ?>
          <?php endif; ?>

        <div class="section--nobg"></div>

          <?php
          if (is_array($settings['list'])) :
              foreach ($list as $index => $item) :

                  /**
                   * hide maintenance for projects for now
                   */
                  $item_class = '';
                  if($item['project_type'] === 'maintenance_projects') {
                      $item_class = 'hidden';
                  };

                  if (isset($item['project_cta_link']) && !empty($item['project_cta_link']['url'])) {
                      $this->add_link_attributes('project_cta_link', $item['project_cta_link']);
                  }
                  if ($item['post_type'] === 'auto') {
                      $count = $item['count'];
                      $items = $this->get_projects($item['project_type'], $item['project_field_type']);
                      $posts_count = count($items);
                      $auto = $posts_count <= $count ? $items : array_slice($items, 0, $count);
                      $posts = $auto;
                  } elseif ($item['post_type'] === 'manual') {
                      $posts = $item['projects'];
                  }
                  ?>

                <div class="section section--nobg featured-projects service-select-projects <?= esc_attr($item_class) ?>">
                  <div class="grid-cont">

                      <?php if (isset($item['project_title'])) : ?>
                        <h2 class="tc-primary"><?= wp_kses_post($item['project_title']) ?></span></h2>
                      <?php endif; ?>

                    <div id="featured-projects__carousel" class="mt-300 featured-projects__carousel wow fadeInUp">
                        <?php if (is_array($posts)) : ?>
                            <?php foreach ($posts as $item) : ?>
                                <?php

                                $image_id = get_field('project_teaser_image', $item);
                                if (empty($image_id)) {
                                    $images = get_field('project_images', $item);
                                    $images_arr = [];
                                    foreach ($images as $i) {
                                        if (isset($i['project_image']) && !empty($i['project_image'])) {
                                            $images_arr[] = $i['project_image'];
                                        }
                                    }
                                    $image_id = !empty($images_arr) ? $images_arr[0] : false;
                                }
                                $title = get_the_title($item);
                                $link = get_the_permalink($item);
                                $categories = get_the_terms($item, 'project_service');
                                $cat_string = '';
                                if (is_array($categories)) {
                                    $cat_string = implode(' | ', wp_list_pluck($categories, 'name'));
                                }
                                $is_video = get_field('is_teaser_video', $item);
                                $video_id = get_field('project_teaser_video', $item);
                                $slide_class = 'featured-projects__slide';
                                if ($is_video) {
                                    $slide_class .= ' featured-projects__slide--video';
                                }
                                ?>
                            <div class="<?= esc_attr($slide_class) ?>">


                                <?php if ($image_id && !$is_video) : ?>
                                  <div class="featured-projects__figure">
                                      <?= wp_get_attachment_image($image_id, 'large') ?>
                                  </div>
                                <?php endif; ?>

                                <?php if ($image_id && $is_video && $video_id) : ?>
                                  <div class="featured-projects__figure video-project">
                                    <a class="video-project__link" href="https://youtu.be/<?= $video_id ?>" data-id="<?= $video_id ?>">
                                        <?= wp_get_attachment_image($image_id, 'large', false, array('class' => 'video-project__media')) ?>
                                    </a>
                                    <button class="video-project__button" type="button" aria-label="Play video">
                                      <svg width="68" height="48" viewBox="0 0 68 48">
                                        <path class="video-project__button-shape"
                                              d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                        <path class="video-reviews__button-icon" d="M 45,24 27,14 27,34"></path>
                                      </svg>
                                    </button>
                                  </div>
                                <?php endif; ?>

                                <?php if (!$image_id && !$is_video) : ?>
                                  <div class="featured-projects__figure"></div>
                                <?php endif; ?>


                              <div class="featured-projects__cont">

                                  <?php if ($cat_string) : ?>
                                    <div class="featured-projects__overline"><?= esc_html($cat_string) ?></div>
                                  <?php endif; ?>

                                  <?php if ($title) : ?>
                                    <div class="mt-025 featured-projects__heading"><?= esc_html($title) ?></div>
                                  <?php endif; ?>

                                  <?php if ($link) : ?>
                                    <a href="<?= esc_url($link) ?>" class="mt-050 cta-link cta-link--sm cta-link--enlarged">Learn More</a>
                                  <?php endif; ?>
                              </div>
                            </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <div class="mt-300 grid-row grid-row--aic grid-row--jcb wow fadeInUp">
                      <div class="grid-col grid-col--auto">
                        <div class="arrows">
                          <button type="button" id="featured-projects__prev" class="featured-projects__prev arrows__prev"></button>
                          <button type="button" id="featured-projects__next" class="featured-projects__next arrows__next"></button>
                        </div>
                      </div>
                      <div class="grid-col grid-col--auto">

                          <?php if (isset($item['project_cta_text']) && !empty($item['project_cta_link']['url'])) : ?>
                            <a href="<?= esc_url($item['project_cta_link']['url']) ?>"
                               class="button button--primary" <?php echo $this->get_render_attribute_string('project_cta_link'); ?>>
                                <?= esc_html($item['project_cta_text']) ?>
                            </a>
                          <?php endif; ?>

                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
          <?php endif; ?>
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
                    <a href="<?= esc_url($settings['cta_link']['url']) ?>" data-scroll="<?= esc_attr($settings['cta_link']['url']) ?>"
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


