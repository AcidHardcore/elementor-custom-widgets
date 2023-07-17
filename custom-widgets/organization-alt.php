<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Organization_Alt extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

    }

    public function get_style_depends()
    {
        return ['widget-organization-type'];
    }

    public function get_script_depends()
    {
        return ['widget-organization-type'];
    }

    public function get_name()
    {
        return 'organization-alt';
    }

    public function get_title()
    {
        return __('Organization Alt', 'elementor-custom-widgets');
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
            'label' => __('Organization Alt', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'is_logo',
            [
                'label' => esc_html__('Title type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'logo' => [
                        'title' => esc_html__('Logo', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'title' => [
                        'text' => esc_html__('Title', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'logo',
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Choose Logo', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'is_logo' => 'logo'
                ]
            ],
        );

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
                'condition' => [
                    'is_logo' => 'title'
                ]
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'secondary',
                'selectors' => [
                    '{{WRAPPER}} .organization-type__title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'is_logo' => 'title'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .organization-type__title',
                'condition' => [
                    'is_logo' => 'title'
                ]
            ]
        );

        $this->add_control(
            'bg',
            [
                'label' => esc_html__('Choose Background', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'col_position',
            [
                'label' => esc_html__('Columns position', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'view_type',
            [
                'label' => esc_html__('View Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'content' => [
                        'title' => esc_html__('Content', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'accordion' => [
                        'title' => esc_html__('Accordion', 'elementor-custom-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                ],
                'default' => 'content',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'lead_color',
            [
                'label' => __( 'Lead Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__lead' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'content'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Lead Typography', 'elementor-custom-widgets' ),
                'name' => 'lead_typography',
                'selector' => '{{WRAPPER}} .organization-type__lead',
                'condition' => [
                    'view_type' => 'content'
                ]
            ]
        );

        $this->add_control(
            'toggle_color',
            [
                'label' => __( 'Toggle Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__toggle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Toggle Typography', 'elementor-custom-widgets' ),
                'name' => 'toggle_typography',
                'selector' => '{{WRAPPER}} .organization-type__toggle',
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_control(
            'spoiler_color',
            [
                'label' => __( 'Spoiler Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .organization-type__spoiler' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Spoiler Typography', 'elementor-custom-widgets' ),
                'name' => 'spoiler_typography',
                'selector' => '{{WRAPPER}} .organization-type__spoiler',
                'condition' => [
                    'view_type' => 'accordion'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'types',
            [
                'label' => esc_html__('Types', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Type #1', 'elementor-custom-widgets'),
                    ],

                ],
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'view_type' => 'content'
                ]
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
            'text', [
            'label' => __('Accordion', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'row' => 3,
            'placeholder' => esc_html__('text', 'elementor-custom-widgets'),
        ]);


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
                'condition' => [
                    'view_type' => 'accordion'
                ]
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

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['nop']) && $settings['nop'] === 'yes' ? 'section--nop' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $title_class = 'organization-type__title';
        $list = array();

        if ($settings['view_type'] === 'content') {
            $list = $settings['types'];
        }
        if ($settings['view_type'] === 'accordion') {
            $list = $settings['accordions'];
        }

        if (!empty($list)) {
            $first_key = array_key_first($list);
        }


        ?>


      <div id="<?= esc_attr($section_id) ?>" class="section organization-type organization-type--alt <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="organization-type__cont">
            <div class="grid-row grid-row--aic">

                <?php if ($settings['col_position'] === 'left') : ?>
                  <div class="grid-col grid-col--sm-12 grid-col--ass">
                    <div class="organization-type__nav organization-type__nav--alt">
                      <div class="organization-type__bg organization-type__bg--alt">

                          <?php if (isset($settings['bg']) && !empty($settings['bg']['id'])) : ?>
                              <?= wp_get_attachment_image($settings['bg']['id'], 'full', false, array('class' => 'active')) ?>
                          <?php endif; ?>

                          <?php if (isset($settings['logo']) && !empty($settings['logo']['id']) && $settings['is_logo'] !== 'title') : ?>
                              <?= wp_get_attachment_image($settings['logo']['id'], 'full', false, array('class' => 'logo wow fadeInUp')) ?>
                          <?php endif; ?>

                          <?php if (isset($settings['title']) && $settings['is_logo'] === 'title') : ?>
                            <div class="grid-cont">
                              <h2 class="<?= $title_class ?>"><?= wp_kses_post($settings['title']) ?></h2>
                            </div>
                          <?php endif; ?>

                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <div data-wow-delay="0.25s" class="grid-col grid-col--sm-12 wow fadeInUp">

                    <div class="organization-type__slide active">
                        <?php if (!empty($list)) : ?>
                            <?php foreach ($list as $index => $item) : ?>


                                <?php if ($settings['view_type'] === 'content') : ?>

                                    <?php
                                    $class = 'organization-type__lead';
                                    $class .= $index === $first_key ? '' : ' mt-150'
                                    ?>

                                    <?php if (isset($item['title'])) : ?>
                                <h3 class="tc-secondary <?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?><i></i></h3>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($settings['view_type'] === 'accordion') : ?>

                                    <?php $class = $index === $first_key ? 'active ' : '' ?>

                                    <?php if (isset($item['title'])) : ?>
                                <h3 class="organization-type__toggle <?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?><i></i></h3>
                                    <?php endif; ?>

                                    <?php if (isset($item['text'])) : ?>
                                <div class="mt-100 organization-type__spoiler"><?= wp_kses_post($item['text']) ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                  </div>
                <?php endif; ?>

              <div class="grid-col grid-col--auto removed-md"></div>
              <div class="grid-col grid-col--auto removed-sm"></div>

                <?php if ($settings['col_position'] === 'left') : ?>
                  <div data-wow-delay="0.25s" class="grid-col grid-col--sm-12 wow fadeInUp">

                    <div class="organization-type__slide active">
                        <?php if (!empty($list)) : ?>
                            <?php foreach ($list as $index => $item) : ?>


                                <?php if ($settings['view_type'] === 'content') : ?>

                                    <?php
                                    $class = 'organization-type__lead';
                                    $class .= $index === $first_key ? '' : ' mt-150'
                                    ?>

                                    <?php if (isset($item['title'])) : ?>
                                <h3 class="tc-secondary <?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?><i></i></h3>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($settings['view_type'] === 'accordion') : ?>

                                    <?php $class = $index === $first_key ? 'active ' : '' ?>

                                    <?php if (isset($item['title'])) : ?>
                                <h3 class="organization-type__toggle <?= esc_attr($class) ?>"><?= wp_kses_post($item['title']) ?><i></i></h3>
                                    <?php endif; ?>

                                    <?php if (isset($item['text'])) : ?>
                                <div class="mt-100 organization-type__spoiler"><?= wp_kses_post($item['text']) ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                  </div>
                <?php else: ?>
                  <div class="grid-col grid-col--sm-12 grid-col--ass grid-col--sm-order-0">
                    <div class="organization-type__nav organization-type__nav--alt">
                      <div class="organization-type__bg organization-type__bg--alt organization-type__bg--right">

                          <?php if (isset($settings['bg']) && !empty($settings['bg']['id'])) : ?>
                              <?= wp_get_attachment_image($settings['bg']['id'], 'full', false, array('class' => 'active')) ?>
                          <?php endif; ?>

                          <?php if (isset($settings['logo']) && !empty($settings['logo']['id']) && $settings['is_logo'] !== 'title') : ?>
                              <?= wp_get_attachment_image($settings['logo']['id'], 'full', false, array('class' => 'logo wow fadeInUp')) ?>
                          <?php endif; ?>

                          <?php if (isset($settings['title']) && $settings['is_logo'] === 'title') : ?>
                            <div class="grid-cont">
                              <h2 class="<?= $title_class ?>"><?= wp_kses_post($settings['title']) ?></h2>
                            </div>
                          <?php endif; ?>

                      </div>
                    </div>
                  </div>
                <?php endif; ?>
            </div>
          </div>
        </div>
          <?php if (isset($settings['number']) && !empty($settings['number']['id'])) : ?>
            <div class="organization-type__num"><?= file_get_contents(wp_get_original_image_path($settings['number']['id'])); ?></div>
          <?php endif; ?>
      </div>

        <?php
    }
}


