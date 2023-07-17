<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Project_Overview extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-project-overview.css');
        wp_register_style('widget-project-overview', get_template_directory_uri() . '/css/widget-project-overview.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-project-overview'];
    }

    public function get_name()
    {
        return 'project-overview';
    }

    public function get_title()
    {
        return __('Project Overview', 'elementor-custom-widgets');
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
            'label' => __('Project Overview', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'more_options',
            [
                'label' => esc_html__('All settings are automatic takes from a Project post', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'none',
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
        if(!empty($project_sports_ids)) {
          foreach ($project_sports_ids as $value) {
            if(isset($value['sport']) && !empty($value['sport'])) {
                $project_sports[] = get_the_title($value['sport']);
            }
          }
        }

        $services_type = [];
        $services = get_field('services_type', $project_id);
        if(!empty($services)) {
          foreach($services as $value) {
            if(isset($value['label']) && !empty($value['label'])) {
                $services_type[] =  $value['label'];
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

        return $list;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_class = isset($settings['stacked']) && $settings['stacked'] === 'yes' ? 'section--nobg' : '';
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $list = $this->get_meta_fields();
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section project-overview <?= esc_attr($section_class) ?>">
        <div class="grid-cont grid-cont--sm">
          <div class="project-overview__row">
              <?php if (!empty($list)) : ?>
                  <?php foreach ($list as $index => $value) : ?>
                      <?php $last_key = array_key_last($list); ?>

                  <div class="project-overview__col">
                      <?php if (isset($value['name'])) : ?>
                        <div class="project-overview__label"><?= esc_html($value['name']) ?></div>
                      <?php endif; ?>
                      <?php if (isset($value['items']) && !empty($value['items'])) : ?>
                          <?php foreach ($value['items'] as $item) : ?>
                          <div class="project-overview__value"><?= esc_html($item) ?></div>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  </div>

                      <?php if ($index !== $last_key) : ?>
                    <div class="project-overview__space"></div>
                      <?php endif; ?>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>
        </div>
      </div>
        <?php
    }
}


