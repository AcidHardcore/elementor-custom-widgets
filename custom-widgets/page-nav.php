<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Page_Nav extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-page-nav.css');
        wp_register_style('widget-page-nav', get_template_directory_uri() . '/css/widget-page-nav.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-page-nav.js');
        wp_register_script('widget-page-nav', get_template_directory_uri() . '/js/widget-page-nav.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-page-nav'];
    }

    public function get_script_depends()
    {
        return ['widget-page-nav'];
    }

    public function get_name()
    {
        return 'page-nav';
    }

    public function get_title()
    {
        return __('Page Nav', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
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
            'label' => __('Page Nav', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Jump To', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'is_search',
            [
                'label' => esc_html__('Enable Search', 'elementor-custom-widgets'),
                'description' => 'Enable search',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('no', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'link_text', [
                'label' => esc_html__('Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Item Title', 'elementor-custom-widgets'),
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
                'label' => esc_html__('Links', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'link_text' => esc_html__('Link Text', 'elementor-custom-widgets'),
                    ],
                ],
                'title_field' => '{{{ link_text }}}',
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
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
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

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        if (isset($settings['cta_link']) && !empty($settings['cta_link']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_link']);
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="page-nav">
        <div class="grid-cont">
          <div class="page-nav__row">

              <?php if (isset($settings['text'])) : ?>
                <div class="page-nav__title"><?= esc_html($settings['text']) ?>:</div>
              <?php endif; ?>

              <?php foreach ($settings['list'] as $index => $item) :
                  if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                      $this->add_link_attributes('cta_link', $item['cta_link']);
                  }
                  $last_key = array_key_last($settings['list'])
                  ?>

                  <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>


                <a href="<?= esc_url($item['cta_link']['url']) ?>" data-scroll="<?= esc_attr($item['cta_link']['url']) ?>"
                   class="page-nav__link" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                    <?= esc_html($item['link_text']) ?>
                </a>

                  <?php if ($index !== $last_key) : ?>
                  <div class="page-nav__vr"></div>
                  <?php endif; ?>

              <?php endif; ?>
              <?php endforeach; ?>

              <?php if(isset($settings['is_search']) && $settings['is_search'] == 'yes') : ?>
                <div></div><div></div><div></div>
                <input type="text" placeholder="Search" class="search-input careers-nav__search">
              <?php endif; ?>

          </div>
        </div>
      </div>

        <?php if (isset($settings['title']) && !empty($settings['title'])) : ?>

      <div id="float-bar" class="float-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic">
            <div class="grid-col">

                <?php if (isset($settings['title'])) : ?>
                  <div class="h2"><?= esc_html($settings['title']) ?></div>
                <?php endif; ?>

            </div>
            <div class="grid-col grid-col--auto">
              <div class="jump-nav">

                  <?php if (isset($settings['text'])) : ?>
                    <button type="button" class="jump-nav__toggle"><?= esc_html($settings['text']) ?></button>
                  <?php endif; ?>

                <div class="jump-nav__dropdown">

                    <?php foreach ($settings['list'] as $item) : ?>
                        <?php if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
                            $this->add_link_attributes('cta_link', $item['cta_link']);
                        }
                        ?>

                        <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
                        <a href="<?= esc_url($item['cta_link']['url']) ?>"><?= esc_html($item['link_text']) ?></a>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
              </div>
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

    <?php endif; ?>

        <?php
    }
}


