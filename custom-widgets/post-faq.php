<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Faq extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

    }

    public function get_style_depends()
    {
        return ['widget-faqs'];
    }

    public function get_script_depends()
    {
        return ['widget-faqs'];
    }

    public function get_name()
    {
        return 'post-faq';
    }

    public function get_title()
    {
        return __('Post Faq', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-editor-quote';
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
            'label' => __('Post Faq', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'question_color',
            [
                'label' => __( 'Question Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faqs__question' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Question Typography', 'elementor-custom-widgets' ),
                'name' => 'question_typography',
                'selector' => '{{WRAPPER}} .faqs__question',
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label' => __( 'Answer Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faqs__answer > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Answer Typography', 'elementor-custom-widgets' ),
                'name' => 'answer_typography',
                'selector' => '{{WRAPPER}} .faqs__answer > *',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question', [
                'label' => esc_html__('Question', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'answer', [
                'label' => esc_html__('Answer', 'elementor-custom-widgets'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'row' => 3
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Questions', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => esc_html__('Question', 'elementor-custom-widgets'),
                    ],
                ],
                'title_field' => '{{{ question }}}',
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
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="mt-400 faqs wow fadeInUp">
          <?php if (isset($settings['list']) && is_array($settings['list'])) : ?>
              <?php foreach ($settings['list'] as $item) : ?>
              <div class="faqs__item">
                  <?php if (isset($item['question']) && !empty($item['question'])) : ?>
                    <div class="h3 faqs__question"><i></i><?= esc_html($item['question']) ?></div>
                  <?php endif; ?>

                  <?php if (isset($item['answer']) && !empty($item['answer'])) : ?>
                    <div class="faqs__answer">
                        <?= wp_kses_post($item['answer']) ?>
                    </div>
                  <?php endif; ?>
              </div>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>

        <?php
    }
}


