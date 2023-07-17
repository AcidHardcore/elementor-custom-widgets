<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Topic_Select extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-topic-select.css');
        wp_register_style('widget-topic-select', get_template_directory_uri() . '/css/widget-topic-select.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-topic-select.js');
        wp_register_script('widget-topic-select', get_template_directory_uri() . '/js/widget-topic-select.js', array('jquery'), $js_version, true);

        wp_enqueue_script('hubspot-script', 'https://js.hsforms.net/forms/v2.js');

    }

    public function get_style_depends()
    {
        return ['widget-topic-select', 'widget-locations'];
    }

    public function get_script_depends()
    {
        return ['widget-topic-select'];
    }

    public function get_name()
    {
        return 'topic-select';
    }

    public function get_title()
    {
        return __('Topic Select', 'elementor-custom-widgets');
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
            'label' => __('Topic Select', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .topic-select__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .topic-select__title',
            ]
        );

        $this->add_control(
            'text', [
                'label' => esc_html__('Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .topic-select__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .topic-select__text',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name', [
                'label' => esc_html__('Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_id', [
            'label' => __('Tab ID', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__('Tabs', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('Name #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'panel_1_heading',
            [
                'label' => esc_html__('Panel 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_1 = new Repeater();

        $panel_1->add_control(
            'name', [
                'label' => esc_html__('Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'tab_id', [
            'label' => __('Tab ID', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $panel_1->add_control(
            'form_shortcode', [
                'label' => esc_html__('Hubspot form', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'panel_1_aside',
            [
                'label' => esc_html__('Aside Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_1->add_control(
            'aside_image',
            [
                'label' => esc_html__('Choose Aside Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_1->add_control(
            'aside_title', [
                'label' => esc_html__('Aside Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'aside_text', [
                'label' => esc_html__('Aside Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'aside_cta_text', [
                'label' => esc_html__('Aside Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'aside_cta_link',
            [
                'label' => esc_html__(' Aside CTA Link', 'elementor-custom-widgets'),
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

        $panel_1->add_control(
            'panel_1_footer',
            [
                'label' => esc_html__('Footer Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_1->add_control(
            'footer_image',
            [
                'label' => esc_html__('Choose Footer Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_1->add_control(
            'footer_title', [
                'label' => esc_html__('Footer Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'footer_text', [
                'label' => esc_html__('Footer Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'footer_cta_text', [
                'label' => esc_html__('Footer Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_1->add_control(
            'footer_cta_link',
            [
                'label' => esc_html__(' Footer CTA Link', 'elementor-custom-widgets'),
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
            'panel_1',
            [
                'label' => esc_html__('Panel 1', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $panel_1->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('Name #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'panel_2_heading',
            [
                'label' => esc_html__('Panel 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_2 = new Repeater();

        $panel_2->add_control(
            'name', [
                'label' => esc_html__('Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'tab_id', [
            'label' => __('Tab ID', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $panel_2->add_control(
            'form_shortcode', [
                'label' => esc_html__('Hubspot form', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'panel_1_aside',
            [
                'label' => esc_html__('Aside Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_2->add_control(
            'aside_image',
            [
                'label' => esc_html__('Choose Aside Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_2->add_control(
            'aside_title', [
                'label' => esc_html__('Aside Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'aside_text', [
                'label' => esc_html__('Aside Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'aside_cta_text', [
                'label' => esc_html__('Aside Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'aside_cta_link',
            [
                'label' => esc_html__(' Aside CTA Link', 'elementor-custom-widgets'),
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

        $panel_2->add_control(
            'panel_1_footer',
            [
                'label' => esc_html__('Footer Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_2->add_control(
            'footer_image',
            [
                'label' => esc_html__('Choose Footer Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_2->add_control(
            'footer_title', [
                'label' => esc_html__('Footer Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'footer_text', [
                'label' => esc_html__('Footer Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'footer_cta_text', [
                'label' => esc_html__('Footer Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_2->add_control(
            'footer_cta_link',
            [
                'label' => esc_html__(' Footer CTA Link', 'elementor-custom-widgets'),
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
            'panel_2',
            [
                'label' => esc_html__('Panel 2', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $panel_2->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('Name #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'panel_3_heading',
            [
                'label' => esc_html__('Panel 3', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_3 = new Repeater();

        $panel_3->add_control(
            'name', [
                'label' => esc_html__('Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'tab_id', [
            'label' => __('Tab ID', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $panel_3->add_control(
            'form_shortcode', [
                'label' => esc_html__('Hubspot form', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'panel_3_aside',
            [
                'label' => esc_html__('Aside Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_3->add_control(
            'aside_image',
            [
                'label' => esc_html__('Choose Aside Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_3->add_control(
            'aside_title', [
                'label' => esc_html__('Aside Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'aside_text', [
                'label' => esc_html__('Aside Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'aside_cta_text', [
                'label' => esc_html__('Aside Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'aside_cta_link',
            [
                'label' => esc_html__(' Aside CTA Link', 'elementor-custom-widgets'),
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

        $panel_3->add_control(
            'panel_3_footer',
            [
                'label' => esc_html__('Footer Article', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $panel_3->add_control(
            'footer_image',
            [
                'label' => esc_html__('Choose Footer Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $panel_3->add_control(
            'footer_title', [
                'label' => esc_html__('Footer Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'footer_text', [
                'label' => esc_html__('Footer Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'footer_cta_text', [
                'label' => esc_html__('Footer Link Text', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Link text', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $panel_3->add_control(
            'footer_cta_link',
            [
                'label' => esc_html__(' Footer CTA Link', 'elementor-custom-widgets'),
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
            'panel_3',
            [
                'label' => esc_html__('Panel 3', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $panel_3->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('Name #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'section_id',
            [
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
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $tab_ids = [];

        $distributor_search = get_query_var('distsearch', null);
        $distributor_search_bounds = get_query_var('distsearchbounds');
        $distributor_categories = get_query_var('distcat', []);
        $distributor_products = get_query_var('distp', []);

        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section section--nobg topic-select <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">

            <?php if (isset($settings['title'])) : ?>
              <h2 class="tc-primary topic-select__title"><?= wp_kses_post($settings['title']) ?></h2>
            <?php endif; ?>

            <?php if (isset($settings['text'])) : ?>
              <div class="mt-050 h5 tc-primary topic-select__text"><?= wp_kses_post($settings['text']) ?></div>
            <?php endif; ?>

            <?php if (isset($settings['tabs']) && is_array($settings['tabs'])) : ?>
              <div class="mt-200 h4 topic-select__tabs">
                  <?php foreach ($settings['tabs'] as $index => $tab) : ?>
                      <?php
                      $tab_class = '';
                      if ($index === 1 && ($distributor_search || $distributor_products || $distributor_categories)) {
                          $tab_class = 'active';
                      }
                      ?>

                      <?php if (isset($tab['name']) && isset($tab['tab_id'])): ?>
                          <?php $tab_ids[] = $tab['tab_id']; ?>
                      <a href="#<?= esc_attr($tab['tab_id']) ?>" class="<?= esc_attr($tab_class) ?>"><?= wp_kses_post($tab['name']) ?></a>
                      <?php endif; ?>

                  <?php endforeach; ?>
              </div>
            <?php endif; ?>
        </div>

          <?php
          $tab_content_class = '';
          if (empty($distributor_search) && empty($distributor_products) && empty($distributor_categories)) {
              $tab_content_class = 'active faded';
          }
          ?>
        <div id="<?= isset($tab_ids[0]) ? esc_attr($tab_ids[0]) : '' ?>" class="topic-select__pane topic-select__pane--parent <?= esc_attr($tab_content_class) ?>">
          <div class="grid-cont grid-cont--sm">
            <div class="mt-250 grid-row">
              <div class="grid-col grid-col--sm-12">

                  <?php if (isset($settings['panel_1']) && is_array($settings['panel_1'])) : ?>
                    <select id="project-type" class="select topic-select__select">
                        <?php foreach ($settings['panel_1'] as $panel) : ?>

                            <?php if (isset($panel['name']) && isset($panel['tab_id'])): ?>
                            <option value="#<?= esc_attr($panel['tab_id']) ?>" data-name="<?= esc_attr($panel['name']) ?>"><?= wp_kses_post($panel['name']) ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                  <?php endif; ?>

              </div>
              <div class="grid-col grid-col--auto grid-col--sm-12"></div>
              <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12"></div>
            </div>
              <?php if (isset($settings['panel_1']) && is_array($settings['panel_1'])) : ?>
                  <?php foreach ($settings['panel_1'] as $index => $panel) : ?>
                      <?php
                      if (isset($panel['aside_cta_link']) && !empty($panel['aside_cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $panel['aside_cta_link']);
                      }
                      if (isset($panel['footer_cta_link']) && !empty($panel['footer_cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $panel['footer_cta_link']);
                      }
                      ?>
                  <div id="<?= isset($panel['tab_id']) ? esc_attr($panel['tab_id']) : '' ?>" class="topic-select__pane topic-select__pane--inner topic-select__pane--js <?= $index === 0 ? 'active' : '' ?>">
                    <div class="grid-row">
                      <div class="grid-col grid-col--sm-12 mt-400">

                          <?php if (isset($panel['form_shortcode'])) : ?>
                              <?= $panel['form_shortcode'] ?>
                          <?php endif ?>

                      </div>
                      <div class="grid-col grid-col--auto grid-col--sm-12"></div>
                      <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12">

                          <?php if (isset($panel['aside_image']) && !empty($panel['aside_image']['id'])) : ?>
                              <?= wp_get_attachment_image($panel['aside_image']['id'], 'full', false, array('class' => 'topic-select__image')) ?>
                          <?php endif; ?>

                        <div class="topic-select__serve">

                            <?php if (isset($panel['aside_title'])) : ?>
                              <h3 class="h2 tc-primary"><?= wp_kses_post($panel['aside_title']) ?><i></i></h3>
                            <?php endif; ?>

                            <?php if (isset($panel['aside_text'])) : ?>
                              <p class="mt-075"><?= wp_kses_post($panel['aside_text']) ?></p>
                            <?php endif; ?>

                            <?php if (isset($panel['aside_cta_text']) && !empty($panel['aside_cta_link']['url'])) : ?>
                              <a href="<?= esc_url($panel['aside_cta_link']['url']) ?>"
                                 class="mt-100 cta-link cta-link--primary" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                  <?= esc_html($panel['aside_cta_text']) ?>
                              </a>
                            <?php endif; ?>

                        </div>
                      </div>
                    </div>

                    <div class="mt-400 financial-planning">

                        <?php if (isset($panel['footer_image']) && !empty($panel['footer_image']['id'])) : ?>
                            <?= wp_get_attachment_image($panel['footer_image']['id'], 'full', false, array('class' => 'financial-planning__image')) ?>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_title'])) : ?>
                          <h3 class="tc-secondary"><?= wp_kses_post($panel['footer_title']) ?><i></i></h3>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_text'])) : ?>
                          <p class="mt-100"><?= wp_kses_post($panel['footer_text']) ?></p>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_cta_text']) && !empty($panel['footer_cta_link']['url'])) : ?>
                          <a href="<?= esc_url($panel['footer_cta_link']['url']) ?>"
                             class="mt-100 cta-link" <?php echo $this->get_render_attribute_string('footer_cta_link'); ?>>
                              <?= esc_html($panel['footer_cta_text']) ?>
                          </a>
                        <?php endif; ?>

                    </div>
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>

          </div>
        </div>

          <?php
          if (!empty($distributor_search) || !empty($distributor_products) || !empty($distributor_categories)) {
              $tab_content_class = 'active';
          }
          ?>
        <div id="<?= isset($tab_ids[1]) ? esc_attr($tab_ids[1]) : '' ?>" class="topic-select__pane topic-select__pane--parent <?= esc_attr($tab_content_class) ?>">
          <div class="grid-cont grid-cont--sm">
            <div class="mt-250 grid-row">
              <div class="grid-col grid-col--sm-12">

                  <?php if (isset($settings['panel_2']) && is_array($settings['panel_2'])) : ?>
                    <select id="project-type-infill" class="select topic-select__select">
                      <option disabled="" selected="" value="#">Type of Infill Project*</option>
                        <?php foreach ($settings['panel_2'] as $panel) : ?>

                            <?php if (isset($panel['name']) && isset($panel['tab_id'])): ?>
                            <option value="#<?= esc_attr($panel['tab_id']) ?>" data-name="<?= esc_attr($panel['name']) ?>"><?= wp_kses_post($panel['name']) ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                  <?php endif; ?>

              </div>
              <div class="grid-col grid-col--auto grid-col--sm-12"></div>
              <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12"></div>
            </div>
          </div>


            <?php if (isset($settings['panel_2']) && is_array($settings['panel_2'])) : ?>
                <?php foreach ($settings['panel_2'] as $index => $panel) : ?>
                    <?php
                    if (isset($panel['aside_cta_link']) && !empty($panel['aside_cta_link']['url'])) {
                        $this->add_link_attributes('cta_link', $panel['aside_cta_link']);
                    }
                    if (isset($panel['footer_cta_link']) && !empty($panel['footer_cta_link']['url'])) {
                        $this->add_link_attributes('cta_link', $panel['footer_cta_link']);
                    }

                    if ($index === 0 && empty($distributor_search) && empty($distributor_products) && empty($distributor_categories)) {
                        $pan_class = 'active faded';
                    } elseif ($index === 0 && ($distributor_search || $distributor_products || $distributor_categories)) {
                        $pan_class = 'active';
                    } else {
                        $pan_class = '';
                    }
                    ?>
                <div id="<?= isset($panel['tab_id']) ? esc_attr($panel['tab_id']) : '' ?>" class="topic-select__pane topic-select__pane--js <?= esc_attr($pan_class) ?>">
                    <?php if ($index === 0) : ?>
                      <div class="locations mt-150">
                        <div class="locations__side">
                          <div id="search_c" class="locations-results">
                            <form method="post" action="/where-to-buy/">
                              <div id="search_input_c" class="grid-row grid-row--smg">
                                <div class="grid-col">
                                  <div class="locations__row">
                                    <label for="distsearch" class="screen-reader-text">Search by Location</label>
                                    <input type="text" value="<?php echo esc_html($distributor_search); ?>" id="distsearch" name="distsearch" class="distsearch search-input"
                                           placeholder="Enter City, State, Or Zip"/>
                                    <input type="submit" class="searchsubmit" value="Search Dealers"/>
                                  </div>
                                </div>
                                <input type="hidden" value="" name="distsearchbounds" id="distsearchbounds"/>
                                <div class="grid-col grid-col--auto">
                                  <button type="button" class="locations__button">Filter</button>
                                </div>
                              </div>
                            </form>
                          </div>
                          <div id="filter_c" class="locations-filters">

                            <div id="filter_form_c">
                              <div class="mb-150 grid-row grid-row--nog grid-row--aic">
                                <div class="grid-col">
                                  <div class="h3 tc-primary">Filters</div>
                                </div>
                                <div class="grid-col grid-col--auto">
                                  <button type="button" class="locations__button clear-all">Clear<span class="removed-md"> All</span></button>
                                </div>
                                <div class="grid-col grid-col--auto">
                                  <button type="button" class="locations__close"></button>
                                </div>
                              </div>

                              <form id="filter_form" method="get">
                                <input type="hidden" value="<?= esc_html($distributor_search); ?>" name="distsearch"/>

                                <div class="h5 locations-filters__heading">Product</div>

                                  <?php $products = ['envirofill', 'safeshell'];
                                  foreach ($products as $product) : ?>
                                    <div class="locations-filters__item input checkbox">
                                      <input class="locations-filters__check" name="distp[]" id="checkbox_<?php echo $product; ?>" type="checkbox"
                                             value="<?php echo $product; ?>" <?php echo((isset($distributor_products) && in_array($product, $distributor_products)) ? 'checked' : ''); ?> />
                                      <label for="checkbox_<?php echo $product; ?>" class="h5 locations-filters__label"><?php echo ucfirst($product); ?></label>
                                    </div>
                                  <?php endforeach; ?>

                                <div class="h5 locations-filters__heading">Type of infill distributer</div>

                                  <?php $categories = ['installer', 'reseller'];
                                  foreach ($categories as $category) : ?>
                                    <div class="locations-filters__item input checkbox">
                                      <input class="locations-filters__check" name="distcat[]" id="checkbox_<?php echo $category; ?>" type="checkbox"
                                             value="<?php echo $category; ?>" <?php echo((isset($distributor_categories) && in_array($category, $distributor_categories)) ? 'checked' : ''); ?> />
                                      <label for="checkbox_<?php echo $category; ?>" class="h5 locations-filters__label"><?php echo ucfirst($category); ?></label>
                                    </div>
                                  <?php endforeach; ?>

                                <button type="submit" class="h3 locations-filters__apply">Apply</button>
                              </form>
                            </div>

                          </div>
                        </div>
                        <div id="results_c" class="mt-150"></div>
                        <div id="map" class="locations__main"></div>
                      </div>
                      <div class="grid-cont grid-cont--sm">
                        <div class="mt-400 financial-planning">

                            <?php if (isset($panel['footer_image']) && !empty($panel['footer_image']['id'])) : ?>
                                <?= wp_get_attachment_image($panel['footer_image']['id'], 'full', false, array('class' => 'financial-planning__image')) ?>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_title'])) : ?>
                              <h3 class="tc-secondary"><?= wp_kses_post($panel['footer_title']) ?><i></i></h3>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_text'])) : ?>
                              <p class="mt-100"><?= wp_kses_post($panel['footer_text']) ?></p>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_cta_text']) && !empty($panel['footer_cta_link']['url'])) : ?>
                              <a href="<?= esc_url($panel['footer_cta_link']['url']) ?>"
                                 class="mt-100 cta-link" <?php echo $this->get_render_attribute_string('footer_cta_link'); ?>>
                                  <?= esc_html($panel['footer_cta_text']) ?>
                              </a>
                            <?php endif; ?>

                        </div>
                      </div>

                    <?php else : ?>
                      <div class="grid-cont grid-cont--sm">
                        <div class="grid-row">
                          <div class="grid-col grid-col--sm-12 mt-150">

                              <?php if (isset($panel['form_shortcode'])) : ?>
                                  <?= $panel['form_shortcode'] ?>
                              <?php endif ?>

                          </div>
                          <div class="grid-col grid-col--auto grid-col--sm-12"></div>
                          <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12">

                              <?php if (isset($panel['aside_image']) && !empty($panel['aside_image']['id'])) : ?>
                                  <?= wp_get_attachment_image($panel['aside_image']['id'], 'full', false, array('class' => 'topic-select__image')) ?>
                              <?php endif; ?>

                            <div class="topic-select__serve">

                                <?php if (isset($panel['aside_title'])) : ?>
                                  <h3 class="h2 tc-primary"><?= wp_kses_post($panel['aside_title']) ?><i></i></h3>
                                <?php endif; ?>

                                <?php if (isset($panel['aside_text'])) : ?>
                                  <p class="mt-075"><?= wp_kses_post($panel['aside_text']) ?></p>
                                <?php endif; ?>

                                <?php if (isset($panel['aside_cta_text']) && !empty($panel['aside_cta_link']['url'])) : ?>
                                  <a href="<?= esc_url($panel['aside_cta_link']['url']) ?>"
                                     class="mt-100 cta-link cta-link--primary" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                      <?= esc_html($panel['aside_cta_text']) ?>
                                  </a>
                                <?php endif; ?>

                            </div>
                          </div>
                        </div>

                        <div class="mt-400 financial-planning">

                            <?php if (isset($panel['footer_image']) && !empty($panel['footer_image']['id'])) : ?>
                                <?= wp_get_attachment_image($panel['footer_image']['id'], 'full', false, array('class' => 'financial-planning__image')) ?>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_title'])) : ?>
                              <h3 class="tc-secondary"><?= wp_kses_post($panel['footer_title']) ?><i></i></h3>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_text'])) : ?>
                              <p class="mt-100"><?= wp_kses_post($panel['footer_text']) ?></p>
                            <?php endif; ?>

                            <?php if (isset($panel['footer_cta_text']) && !empty($panel['footer_cta_link']['url'])) : ?>
                              <a href="<?= esc_url($panel['footer_cta_link']['url']) ?>"
                                 class="mt-100 cta-link" <?php echo $this->get_render_attribute_string('footer_cta_link'); ?>>
                                  <?= esc_html($panel['footer_cta_text']) ?>
                              </a>
                            <?php endif; ?>

                        </div>

                      </div>
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <div id="<?= isset($tab_ids[2]) ? esc_attr($tab_ids[2]) : '' ?>" class="topic-select__pane topic-select__pane--parent">
          <div class="grid-cont grid-cont--sm">
            <div class="mt-250 grid-row">
              <div class="grid-col grid-col--sm-12">

                  <?php if (isset($settings['panel_3']) && is_array($settings['panel_3'])) : ?>
                    <select id="project-type-maintenace" class="select topic-select__select">
                        <?php foreach ($settings['panel_3'] as $panel) : ?>

                            <?php if (isset($panel['name']) && isset($panel['tab_id'])): ?>
                            <option value="#<?= esc_attr($panel['tab_id']) ?>" data-name="<?= esc_attr($panel['name']) ?>"><?= wp_kses_post($panel['name']) ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                  <?php endif; ?>

              </div>
              <div class="grid-col grid-col--auto grid-col--sm-12"></div>
              <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12"></div>
            </div>

              <?php if (isset($settings['panel_3']) && is_array($settings['panel_3'])) : ?>
                  <?php foreach ($settings['panel_3'] as $index => $panel) : ?>
                      <?php
                      if (isset($panel['aside_cta_link']) && !empty($panel['aside_cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $panel['aside_cta_link']);
                      }
                      if (isset($panel['footer_cta_link']) && !empty($panel['footer_cta_link']['url'])) {
                          $this->add_link_attributes('cta_link', $panel['footer_cta_link']);
                      }
                      ?>
                  <div id="<?= isset($panel['tab_id']) ? esc_attr($panel['tab_id']) : '' ?>" class="topic-select__pane topic-select__pane--inner topic-select__pane--js <?= $index === 0 ? 'active' : '' ?>">
                    <div class="grid-row">
                      <div class="grid-col grid-col--sm-12 mt-400">

                          <?php if (isset($panel['form_shortcode'])) : ?>
                              <?= $panel['form_shortcode'] ?>
                          <?php endif ?>

                      </div>
                      <div class="grid-col grid-col--auto grid-col--sm-12"></div>
                      <div class="grid-col grid-col--5 grid-col--md-6 grid-col--sm-12">
                          <?php if (isset($panel['aside_image']) && !empty($panel['aside_image']['id'])) : ?>
                              <?= wp_get_attachment_image($panel['aside_image']['id'], 'full', false, array('class' => 'topic-select__image')) ?>
                          <?php endif; ?>

                        <div class="topic-select__serve">

                            <?php if (isset($panel['aside_title'])) : ?>
                              <h3 class="h2 tc-primary"><?= wp_kses_post($panel['aside_title']) ?><i></i></h3>
                            <?php endif; ?>

                            <?php if (isset($panel['aside_text'])) : ?>
                              <p class="mt-075"><?= wp_kses_post($panel['aside_text']) ?></p>
                            <?php endif; ?>

                            <?php if (isset($panel['aside_cta_text']) && !empty($panel['aside_cta_link']['url'])) : ?>
                              <a href="<?= esc_url($panel['aside_cta_link']['url']) ?>"
                                 class="mt-100 cta-link cta-link--primary" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                                  <?= esc_html($panel['aside_cta_text']) ?>
                              </a>
                            <?php endif; ?>

                        </div>
                      </div>
                    </div>

                    <div class="mt-400 financial-planning">

                        <?php if (isset($panel['footer_image']) && !empty($panel['footer_image']['id'])) : ?>
                            <?= wp_get_attachment_image($panel['footer_image']['id'], 'full', false, array('class' => 'financial-planning__image')) ?>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_title'])) : ?>
                          <h3 class="tc-secondary"><?= wp_kses_post($panel['footer_title']) ?><i></i></h3>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_text'])) : ?>
                          <p class="mt-100"><?= wp_kses_post($panel['footer_text']) ?></p>
                        <?php endif; ?>

                        <?php if (isset($panel['footer_cta_text']) && !empty($panel['footer_cta_link']['url'])) : ?>
                          <a href="<?= esc_url($panel['footer_cta_link']['url']) ?>"
                             class="mt-100 cta-link" <?php echo $this->get_render_attribute_string('footer_cta_link'); ?>>
                              <?= esc_html($panel['footer_cta_text']) ?>
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


