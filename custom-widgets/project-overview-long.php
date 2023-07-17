<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Overview_Long extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-project-overview-long.css');
        wp_register_style('widget-project-overview-long', get_template_directory_uri() . '/css/widget-project-overview-long.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-project-overview-long'];
    }

    public function get_name()
    {
        return 'project-overview-long';
    }

    public function get_title()
    {
        return __('Project Overview Long', 'elementor-custom-widgets');
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
            'label' => __('Project Overview Long', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'title', [
                'label' => esc_html__('Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Project Overview'
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .project-details__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title Typography', 'elementor-custom-widgets' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .project-details__title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor-custom-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .project-details__text > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Typography', 'elementor-custom-widgets' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .project-details__text > *',
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

    public function get_meta_fields()
    {
        $list = [];
        $project_id = get_the_id();

        if(get_post_type($project_id) === 'projects') {
            $is_international = get_field('is_international_location');
            if($is_international) {
                $city = '';
                $state = '';
                $location = get_field('project_location_international');
            } else {
                $city = get_field('project_city', $project_id);
                $state = get_field('project_state', $project_id);
                $location = $city . ', ' . $state;
            }

            $project_product_id = get_field('project_product', $project_id);
            $project_product = $project_product_id ? get_the_title($project_product_id) : null;

            $project_sports = [];
            $project_sports_ids = get_field('project_sports');
            if (!empty($project_sports_ids)) {
                foreach ($project_sports_ids as $value) {
                    if (isset($value['sport']) && !empty($value['sport'])) {
                        $project_sports[] = get_the_title($value['sport']);
                    }
                }
            }

            $services_type = [];
            $services = get_field('services_type', $project_id);
            if (!empty($services)) {
                foreach ($services as $value) {
                    if (isset($value['label']) && !empty($value['label'])) {
                        $services_type[] = $value['label'];
                    }
                }
            }

            $list[0]['name'] = 'LOCATION';
            $list[0]['items'][] = $location;
            $list[1]['name'] = 'SYSTEM TYPE';
            $list[1]['items'][] = $project_product;
            $list[2]['name'] = 'SPORT(S) PLAYED';
            $list[2]['items'] = $project_sports;
            $list[3]['name'] = 'SERVICES USED';
            $list[3]['items'] = $services_type;
        }

        if(get_post_type($project_id) === 'landscape_projects') {
            $infill = get_field('project_infill', $project_id);
            $landscape_type = get_field('project_landscape_type', $project_id);
            $location = get_field('project_location', $project_id);
            $installer = get_field('project_installer', $project_id);
            $installer_link = '';
            if($installer) {
                $link_url = $installer['url'];
                $link_title = $installer['title'];
                $link_target = $installer['target'] ? $installer['target'] : '_self';
                $installer_link = sprintf('<a  href="%s" target="%s">%s</a>', $link_url, $link_target, $link_title);
            }
            $designer = get_field('project_designer', $project_id);
            $designer_link = '';
            if($designer) {
                $link_url = $designer['url'];
                $link_title = $designer['title'];
                $link_target = $designer['target'] ? $designer['target'] : '_self';
                $designer_link = sprintf('<a  href="%s" target="%s">%s</a>', $link_url, $link_target, $link_title);
            }
            $year = get_field('project_year', $project_id);
            $turf = get_field('project_turf', $project_id);
            $pad = get_field('project_pad', $project_id);

            $list[0]['name'] = 'Application';
            $list[0]['items'][] = $landscape_type;
            $list[1]['name'] = 'Location';
            $list[1]['items'][] = $location;
            $list[2]['name'] = 'Infill Type';
            $list[2]['items'][] = $infill;
            $list[3]['name'] = 'Installer';
            $list[3]['items'][] = $installer_link;
            $list[4]['name'] = 'Designer';
            $list[4]['items'][] = $designer_link;
            $list[5]['name'] = 'Year';
            $list[5]['items'][] = $year;
            $list[6]['name'] = 'Turf';
            $list[6]['items'][] = $turf;
            $list[7]['name'] = 'Pad';
            $list[7]['items'][] = $pad;
        }

        if(get_post_type($project_id) === 'sport_projects') {
            $infill = get_field('project_infill', $project_id);
            $sports_type = get_field('project_sports_type', $project_id);
            $location = get_field('project_location', $project_id);
            $installer = get_field('project_installer', $project_id);
            $installer_link = '';
            if($installer) {
                $link_url = $installer['url'];
                $link_title = $installer['title'];
                $link_target = $installer['target'] ? $installer['target'] : '_self';
                $installer_link = sprintf('<a  href="%s" target="%s">%s</a>', $link_url, $link_target, $link_title);
            }
            $designer = get_field('project_designer', $project_id);
            $designer_link = '';
            if($designer) {
                $link_url = $designer['url'];
                $link_title = $designer['title'];
                $link_target = $designer['target'] ? $designer['target'] : '_self';
                $designer_link = sprintf('<a  href="%s" target="%s">%s</a>', $link_url, $link_target, $link_title);
            }
            $year = get_field('project_year', $project_id);
            $turf = get_field('project_turf', $project_id);
            $pad = get_field('project_pad', $project_id);

            $list[0]['name'] = 'Sport Type';
            $list[0]['items'] = $sports_type;
            $list[1]['name'] = 'Location';
            $list[1]['items'] = $location;
            $list[2]['name'] = 'Infill Type';
            $list[2]['items'][] = $infill;
            $list[3]['name'] = 'Installer';
            $list[3]['items'][] = $installer_link;
            $list[4]['name'] = 'Designer';
            $list[4]['items'][] = $designer_link;
            $list[5]['name'] = 'Year';
            $list[5]['items'][] = $year;
            $list[6]['name'] = 'Turf';
            $list[6]['items'][] = $turf;
            $list[7]['name'] = 'Pad';
            $list[7]['items'][] = $pad;
        }

        return $list;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $list = $this->get_meta_fields();
        $project_id = get_the_ID();
        $overview = get_field('project_overview', $project_id);
        ?>


      <div id="<?= esc_attr($section_id) ?>" class="section section--nop project-details <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row grid-row--aic">
            <div class="grid-col grid-col--6 grid-col--sm-12">
              <div class="project-details__main wow fadeInUp">

                  <?php if (isset($settings['title'])) : ?>
                    <h2 class="tc-primary project-details__title"><?= esc_html($settings['title']) ?></h2>
                  <?php endif; ?>

                  <?php if (isset($overview)) : ?>
                    <div class="mt-100 project-details__text"><?= wp_kses_post($overview) ?></div>
                  <?php endif; ?>

              </div>
            </div>
            <div class="grid-col grid-col--1 grid-col--sm-12"></div>
            <div class="grid-col grid-col--5 grid-col--sm-12 grid-col--sm-order-0">
              <div class="project-details__side wow fadeInUp">

                  <?php if (!empty($list)) : ?>
                      <?php foreach ($list as $index => $value) : ?>
                          <?php $last_key = array_key_last($list); ?>

                          <?php if (isset($value['name']) && isset($value['items']) && !empty($value['items'][0])) : ?>
                        <div class="project-details__label"><?= esc_html($value['name']) ?></div>
                          <?php endif; ?>
                          <?php if (isset($value['items']) && !empty($value['items'])) : ?>
                              <?php foreach ($value['items'] as $item) : ?>

                                  <?php if (is_array($item)) : ?>
                                      <?php foreach ($item as $value) : ?>
                              <div class="project-details__value"><?= wp_kses_post($value) ?></div>
                                      <?php endforeach; ?>
                            <hr class="project-details__hr">
                                  <?php else : ?>
                            <div class="project-details__value"><?= wp_kses_post($item) ?></div>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                          <?php endif; ?>
                          <?php if ($index !== $last_key && !empty($value['items'][0])) : ?>
                        <hr class="project-details__hr">
                          <?php endif; ?>

                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

        <?php
    }
}


