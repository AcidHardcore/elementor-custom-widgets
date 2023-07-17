<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Helpful_Resources extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-helpful-resources.css');
        wp_register_style('widget-helpful-resources', get_template_directory_uri() . '/css/widget-helpful-resources.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-helpful-resources'];
    }

    public function get_name()
    {
        return 'helpful-resources';
    }

    public function get_title()
    {
        return __('Helpful Resources', 'elementor-custom-widgets');
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
            'label' => __('Helpful Resources', 'elementor-custom-widgets'),
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
                    '{{WRAPPER}} .helpful-resources__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .helpful-resources__title',
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

        $repeater = new Repeater();

        $repeater->add_control(
            'type', [
                'label' => esc_html__('Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'text', [
            'label' => __('Text', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXTAREA,
            'row' => 3,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Call to Action', 'elementor-custom-widgets'),
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

        $this->add_control(
            'list',
            [
                'label' => esc_html__('List', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'type' => esc_html__('Resource #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ type }}}',
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

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $title_class = '';
        if ($settings['is_num'] === 'num') {
            $title_class = ' num-title';
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section helpful-resources <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary num-title wow fadeInUp helpful-resources__title <?= esc_attr($title_class) ?>">

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

          <div data-wow-delay="0.25s" class="grid-row helpful-resources__row wow fadeInUp">

              <?php if (is_array($settings['list'])) : ?>
                  <?php foreach ($settings['list'] as $item ) : ?>
                      <?php
                      if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $item['cta_link']);
                      }
                      ?>

                  <div class="grid-col grid-col--4 grid-col--md-6 grid-col--sm-12 helpful-resources__col">
                    <div class="helpful-resources__item">

                        <?php if( isset($item['type']) ) : ?>
                          <div class="helpful-resources__overline"><?= $item['type'] ?></div>
                        <?php endif; ?>

                        <?php if (isset($item['text'])) : ?>
                          <div class="mt-050 helpful-resources__heading"><?= wp_kses_post($item['text']) ?></div>
                        <?php endif; ?>

                        <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                          <a href="<?= esc_url($item['cta_link']['url']) ?>"
                             class="mt-075 cta-link cta-link--sm cta-link--enlarged" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                              <?= esc_html($item['link_text']) ?>
                          </a>
                        <?php endif; ?>

                    </div>
                  </div>

                  <?php endforeach; ?>
              <?php endif; ?>

          </div>
        </div>
      </div>

        <?php
    }
}


