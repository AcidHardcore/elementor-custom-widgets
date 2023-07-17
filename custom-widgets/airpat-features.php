<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Airpat_Features extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-airpat-features.css');
        wp_register_style('widget-airpat-features', get_template_directory_uri() . '/css/widget-airpat-features.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-airpat-features.js');
        wp_register_script('widget-airpat-features', get_template_directory_uri() . '/js/widget-airpat-features.js', array('jquery'), $js_version, true);
    }

    public function get_style_depends()
    {
        return ['widget-content', 'widget-organization-type', 'widget-airpat-features'];
    }

    public function get_script_depends()
    {
        return ['widget-airpat-features'];
    }

    public function get_name()
    {
        return 'airpat-features';
    }

    public function get_title()
    {
        return __('Airpat Features', 'elementor-custom-widgets');
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
            'label' => __('Airpat Features', 'elementor-custom-widgets'),
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
                'label' => __('Title Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} .airpat-features__title' => 'color: {{VALUE}};',
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
                'label' => __('Title Typography', 'elementor-custom-widgets'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .airpat-features__title',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
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
            'is_title',
            [
                'label' => esc_html__('Enable Item Title Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-widgets'),
                'label_off' => esc_html__('No', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'no',

            ]
        );

        $this->add_control(
            'number',
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

        $accordion = new Repeater();

        $accordion->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $accordion->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'secondary',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .organization-type__toggle' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $accordion->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Title Typography', 'elementor-custom-widgets'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .organization-type__toggle',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
            ]
        );

        $accordion->add_control(
            'text', [
            'label' => __('Accordion', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'row' => 3,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);

        $accordion->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'white',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .organization-type__spoiler' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
            ]
        );

        $accordion->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .organization-type__spoiler',
                'scheme' => Core\Schemes\Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'accordions',
            [
                'label' => esc_html__('Accordion', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $accordion->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Accordion #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
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

        $title_class = '';
        if ($settings['is_num'] === 'num') {
            $title_class = ' num-title';
        }

        if (!empty($settings['accordions'])) {
            $first_key = array_key_first($settings['accordions']);
        }
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section airpat-features">
        <div class="grid-cont organization-type--alt">
          <div class="grid-row grid-row--aic">
            <div class="grid-col grid-col--7 grid-col--md-12 wow fadeInUp">

                <?php if (isset($settings['title'])) : ?>
                  <h2 class="mb-250 airpat-features__title <?= esc_attr($title_class) ?>">
                      <?php if (isset($settings['number']) && !empty($settings['number']['id']) && $settings['is_num'] === 'num') : ?>
                        <div class="num-title__img num-title__img--left"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
                      <?php endif; ?>

                      <?php if ($settings['is_num'] === 'num') : ?>
                        <span class="num-title__text"><?= wp_kses_post($settings['title']) ?></span>
                      <?php else : ?>
                          <?= wp_kses_post($settings['title']) ?>
                      <?php endif; ?>

                  </h2>
                <?php endif; ?>

                <?php if (!empty($settings['accordions'])) : ?>

                    <?php foreach ($settings['accordions'] as $index => $item) : ?>

                        <?php
                        $current_item_class = 'elementor-repeater-item elementor-repeater-item-' . $item['_id'];

                        $class = 'organization-type__toggle';
                        $class .= $index === $first_key ? ' active' : '';

                        if (isset($settings['is_title']) && $settings['is_title'] === 'yes') {
                            $class .= ' airpat-features__title';
                        }
                        ?>

                    <div class="<?= $current_item_class ?>">
                        <?php if (isset($item['title'])) : ?>
                          <h3 class="<?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?><i></i></h3>
                        <?php endif; ?>

                        <?php if (isset($item['text'])) : ?>
                            <?= wp_kses_post($item['text']) ?>
                        <?php endif; ?>
                    </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <div data-wow-delay="0.25s" class="grid-col grid-col--5 grid-col--md-12 wow fadeInUp">
              <div class="mt-200 airpat-features__figure">

                  <?php if (isset($settings['image']) && !empty($settings['image']['id'])) : ?>
                      <?= wp_get_attachment_image($settings['image']['id'], 'full') ?>
                  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


