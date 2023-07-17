<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class Quick_Links extends Widget_Base
{

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    $the_theme = wp_get_theme();
    $theme_version = $the_theme->get('Version');

    $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-quick-links.css');
    wp_register_style('widget-quick-links', get_template_directory_uri() . '/css/widget-quick-links.css', array('main-styles'), $css_version);

  }

  public function get_style_depends() {
    return ['widget-quick-links'];
  }

  public function get_name() {
    return 'quick-links';
  }

  public function get_title() {
    return __('Quick Links', 'elementor-custom-widgets');
  }

  public function get_icon() {
    return 'eicon-editor-external-link';
  }

  public function get_categories() {
    return ['Motz'];
  }

  public function get_keywords() {
    return ['custom'];
  }

  protected function register_controls() {

    $this->start_controls_section('section_intro', [
      'label' => __('Quick Links', 'elementor-custom-widgets'),
      'tab' => Controls_Manager::TAB_CONTENT,
    ]);

    $repeater = new Repeater();

    $repeater->add_control(
      'link_text', [
        'label' => esc_html__( 'Link Text', 'elementor-custom-widgets' ),
        'type' => Controls_Manager::TEXT,
        'default' => esc_html__( 'Learn More' , 'elementor-custom-widgets' ),
        'label_block' => true,
      ]
    );

      $repeater->add_control(
          'title_color',
          [
              'label' => __( 'Title Color', 'elementor-custom-widgets' ),
              'type' => Controls_Manager::COLOR,
              'selectors' => [
                  '{{WRAPPER}} {{CURRENT_ITEM}} .h2' => 'color: {{VALUE}};',
              ],
          ]
      );

      $repeater->add_group_control(
          Group_Control_Typography::get_type(),
          [
              'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
              'name' => 'title_typography',
              'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .h2',
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
      'image',
      [
        'label' => esc_html__('Choose Image', 'elementor-custom-widgets'),
        'type' => Controls_Manager::MEDIA,
        'default' => [
          'url' => Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $this->add_control(
      'list',
      [
        'label' => esc_html__( 'Links', 'elementor-custom-widgets' ),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [
          [
            'link_text' => esc_html__( 'Link Text', 'elementor-custom-widgets' ),
          ],
        ],
        'title_field' => '{{{ link_text }}}',
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

  protected function render() {
    $settings = $this->get_active_settings();
    $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
    ?>


    <div id="<?= esc_attr($section_id) ?>" class="quick-links">
      <div class="grid-row grid-row--nog">


        <?php foreach ($settings['list'] as $item) :
        if (isset($item['cta_link']) && !empty($item['cta_link']['url'])) {
        $this->add_link_attributes('cta_link', $item['cta_link']);
        }
            $current_item_class = 'elementor-repeater-item-' . $item['_id'];
        ?>

          <div class="grid-col grid-col--6 grid-col--sm-12">

            <?php if (isset($item['link_text']) && !empty($item['cta_link']['url'])) : ?>
              <a href="<?= esc_url($item['cta_link']['url']) ?>"
                 class="quick-links__item <?= $current_item_class ?>" <?php echo $this->get_render_attribute_string('cta_link'); ?>>

                <?php if (isset($item['image']) && !empty($item['image']['id'])) : ?>
                  <?= wp_get_attachment_image($item['image']['id'], 'full', false, array('class' => 'quick-links__image')) ?>
                <?php endif; ?>

                <span class="h2 wow fadeInUp"><?= wp_kses_post($item['link_text']) ?></span>
              </a>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php
  }
}


