<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Feature_Posts extends Widget_Base
{
    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-featured-posts.css');
        wp_register_style('widget-featured-posts', get_template_directory_uri() . '/css/widget-featured-posts.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-featured-posts'];
    }

    public function get_name()
    {
        return 'featured-posts';
    }

    public function get_title()
    {
        return __('Feature Posts', 'elementor-custom-widgets');
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
            'label' => __('Feature Posts', 'elementor-custom-widgets'),
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

        $this->add_control(
            'posts',
            [
                'label' => esc_html__('Select Posts', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_titles(),
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

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
                'options' => $this->config::BUTTON_TYPE
            ]
        );

        $this->add_control('left_text', [
            'label' => __('Left Button Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->add_control(
            'left_link',
            [
                'label' => esc_html__('Left Link', 'elementor-custom-widgets'),
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

        $this->add_control('right_text', [
            'label' => __('Right Button Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->add_control(
            'right_link',
            [
                'label' => esc_html__('Right Link', 'elementor-custom-widgets'),
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

    public function get_featured_posts()
    {
        $args = array(
            'post_type' => 'post',
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

    public function get_post_titles()
    {
        $posts = $this->get_featured_posts();
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
        $items = $this->get_featured_posts();
        $posts_count = count($items);
        $auto = $posts_count <= 3 ? $items : array_slice($items, 0, 3);
        $posts = $settings['post_type'] === 'auto' ? $auto : $settings['posts'] ?? null;
        $button_class = 'button';
        if (isset($item['left_link']) && !empty($item['left_link']['url'])) {
            $this->add_link_attributes('left_link', $item['left_link']);
        }

        if (isset($item['right_link']) && !empty($item['right_link']['url'])) {
            $this->add_link_attributes('right_link', $item['right_link']);
        }

        if($settings['button_type'] !== 'button') {
            $button_class .= ' ' . $settings['button_type'];

        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section featured-posts">
        <div class="grid-cont">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary wow fadeInUp featured-posts__title"><?= wp_kses_post($settings['title']) ?></h2>
            <?php endif; ?>

          <div class="mt-050 grid-row">
            <div data-wow-delay="0.25s" class="grid-col grid-col--6 grid-col--sm-12 wow fadeInUp">
                <?php $post_1 = $posts[0] ?? null; ?>
                <?php if (isset($post_1)) : ?>
                  <div class="mt-150 featured-posts__item">

                      <?= get_the_post_thumbnail($post_1, 'full',  array('class' => 'featured-posts__image')) ?>

                    <div class="featured-posts__text">
                        <?php
                        $categories = get_the_category($post_1);
                        $cat_string = '';
                        if (is_array($categories)) {
                            $cat_string = implode(' | ', wp_list_pluck($categories, 'name'));
                        }

                        ?>
                        <?php if ($cat_string) : ?>
                          <div class="featured-posts__overline"><?= $cat_string ?></div>
                        <?php endif; ?>

                      <div class="mt-050 featured-posts__heading"><?= esc_html(get_the_title($post_1)) ?></div>
                      <a href="<?php the_permalink($post_1); ?>"
                         class="mt-075 cta-link cta-link--sm cta-link--enlarged">Read More</a>
                    </div>
                  </div>
                <?php endif; ?>
            </div>
            <div data-wow-delay="0.5s" class="grid-col grid-col--6 grid-col--sm-12 wow fadeInUp">
                <?php if (is_array($posts)) : ?>
                    <?php foreach ($posts as $index => $post) : ?>
                        <?php if ($post && $index >= 1) : ?>
                      <div class="mt-150 featured-posts__item featured-posts__item--sm">

                          <?= get_the_post_thumbnail($post, 'full',  array('class' => 'featured-posts__image')) ?>

                        <div class="featured-posts__text">
                            <?php
                            $categories = get_the_category($post);
                            $cat_string = '';
                            if (is_array($categories)) {
                                $cat_string = implode(' | ', array_column($categories, 'name'));
                            }
                            ?>

                            <?php if ($cat_string) : ?>
                              <div class="featured-posts__overline"><?= $cat_string ?></div>
                            <?php endif; ?>

                          <div class="mt-050 featured-posts__heading"><?= esc_html(get_the_title($post)) ?></div>
                          <a href="<?php the_permalink($post); ?>"
                             class="mt-075 cta-link cta-link--sm cta-link--enlarged">Read More</a>
                        </div>
                      </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


          </div>
          <div class="mt-150 grid-row grid-row--jcc wow fadeInUp">
            <div class="mt-100 grid-col grid-col--auto">
                <?php if (isset($settings['left_text']) && !empty($settings['left_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['left_link']['url']) ?>"
                     class="<?= $button_class ?>" <?php echo $this->get_render_attribute_string('left_link'); ?>>
                      <?= esc_html($settings['left_text']) ?>
                  </a>
                <?php endif; ?>
            </div>
            <div class="mt-100 grid-col grid-col--auto">
                <?php if (isset($settings['right_text']) && !empty($settings['right_link']['url'])) : ?>
                  <a href="<?= esc_url($settings['right_link']['url']) ?>"
                     class="<?= $button_class ?>" <?php echo $this->get_render_attribute_string('right_link'); ?>>
                      <?= esc_html($settings['right_text']) ?>
                  </a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


