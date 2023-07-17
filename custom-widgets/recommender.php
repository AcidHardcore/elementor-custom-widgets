<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Recommender extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-recommender.css');
        wp_register_style('widget-recommender', get_template_directory_uri() . '/css/widget-recommender.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-recommender'];
    }

    public function get_name()
    {
        return 'recommender';
    }

    public function get_title()
    {
        return __('Recommender', 'elementor-custom-widgets');
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
            'label' => __('Recommender', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'important_note',
            [
                'label' => esc_html__('Warning:', 'elementor-custom-widgets'),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>4 items only</strong>', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .recommender__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .recommender__title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .recommender__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Description Typography', 'elementor-custom-widgets' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .recommender__subtitle',
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Choose Number', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title', [
                'label' => esc_html__('Question', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Question', 'elementor-custom-widgets'),
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'desc', [
            'label' => __('Description', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('Description', 'elementor-custom-widgets'),
            'label_block' => false,
        ]);


        $this->add_control(
            'questions',
            [
                'label' => esc_html__('Questions', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Question #1', 'elementor-custom-widgets'),
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
        $fill_width = 25 * $_SESSION['current_step'];
        $items = array();
        $input_type = 'radio';
        if ($_SESSION['current_step'] === 1) {
            $items = get_posts(['post_type' => 'motz_venues', 'posts_per_page' => -1]);
        } elseif ($_SESSION['current_step'] === 2) {
            $items = get_posts(['post_type' => 'motz_roles', 'posts_per_page' => -1]);
        } elseif ($_SESSION['current_step'] === 3) {
            $items = get_posts(['post_type' => 'motz_planning_stages', 'posts_per_page' => -1]);
        } elseif ($_SESSION['current_step'] === 4) {
            $items = get_posts(['post_type' => 'motz_sports', 'posts_per_page' => -1]);
            $input_type = 'checkbox';
        }

        ?>
        <?php if (is_page_template('page-templates/turf-recommender.php')) : ?>

        <div id="<?= esc_attr($section_id) ?>" class="section recommender">
            <div class="grid-cont">

                <?php if (!empty($settings['questions'])) : ?>
                    <?php foreach ($settings['questions'] as $index => $item) : ?>
                        <?php if ($index + 1 === $_SESSION['current_step']) : ?>

                            <div class="progress-bar">
                                <div class="progress-bar__title">Question <?= $index + 1 ?>/4</div>
                                <div class="mt-100 progress-bar__line">
                                    <div class="progress-bar__fill" style="width: <?= $fill_width ?>%"></div>
                                    <div class="progress-bar__ticks"></div>
                                </div>
                            </div>

                            <?php if (isset($item['number']) && !empty($item['number']['id'])) : ?>
                                <div class="mt-300 recommender__num"><?= file_get_contents(wp_get_original_image_path($item['number']['id'])); ?></div>
                            <?php endif; ?>

                            <?php if (isset($item['title'])) : ?>
                                <div class="mt-100 h1 tc-primary recommender__title"><?= wp_kses_post($item['title']) ?></div>
                            <?php endif; ?>

                            <?php if (isset($item['desc'])) : ?>
                                <div class="mt-025 h4 tc-primary recommender__subtitle"><?= wp_kses_post($item['desc']) ?></div>
                            <?php endif; ?>

                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div id="step_<?= esc_attr($_SESSION['current_step']) ?>" class="mt-100 grid-row">

                    <?php if (!empty($items)) : ?>
                        <?php foreach ($items as $item) : ?>
                            <?php $name = get_field('name', $item->ID); ?>
                            <div class="mt-150 grid-col grid-col--4 grid-col--lg-6 grid-col--md-12">
                                <div class="recommender__item" id="o_<?php echo $item->ID; ?>" data-id="<?php echo $item->ID; ?>">
                                    <input type="<?= esc_attr($input_type) ?>" name="recommender" class="recommender__radio">
                                    <div class="h3 recommender__label"><?= esc_html($name) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <div id="progress_buttons_c" class="mt-300 grid-row grid-row--nog grid-row--jce recommender__foot">
                    <div class="grid-col grid-col--auto">
                        <button id="previous" class="button button--primary button--disabled">Prev</button>
                    </div>
                    <div id="progress_c" class="recommender__dots">
                        <?php for ($i = 1; $i < 5; $i++): ?>
                            <?php if ($i === $_SESSION['current_step']): ?>
                                <button type="button" class="active current"></button>
                            <?php elseif ($i < $_SESSION['current_step']): ?>
                                <button type="button" class="active"></button>
                            <?php else: ?>
                                <button type="button"></button>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="grid-col grid-col--auto">
                        <button id="next" class="button button--primary button--disabled">Next</button>
                    </div>
                </div>
            </div>
        </div>

    <?php else : ?>

        <div id="<?= esc_attr($section_id) ?>" class="section recommender">
            <div class="grid-cont">
                <p>Pick up Turf Recommender template for the page</p>
            </div>
        </div>

    <?php endif; ?>
        <?php
    }
}

?>



