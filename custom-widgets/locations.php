<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Locations extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-locations.css');
        wp_register_style('widget-locations', get_template_directory_uri() . '/css/widget-locations.css', array('main-styles'), $css_version);

//        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-locations.js');
//        wp_register_script('widget-locations', get_template_directory_uri() . '/js/widget-locations.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-locations'];
    }

//    public function get_script_depends()
//    {
//        return ['widget-locations'];
//    }

    public function get_name()
    {
        return 'locations';
    }

    public function get_title()
    {
        return __('Locations', 'elementor-custom-widgets');
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
            'label' => __('Locations', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


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
        $distributor_search = get_query_var('distsearch', null);
        $distributor_search_bounds = get_query_var( 'distsearchbounds' );
        $distributor_categories = get_query_var( 'distcat', [] );
        $distributor_products = get_query_var( 'distp', [] );
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section section--nop locations">
        <div class="locations__side">
          <div id="search_c" class="locations-results">
            <form method="post" action="/where-to-buy/">
              <div id="search_input_c" class="grid-row grid-row--smg">
                <div class="grid-col">
                  <div class="locations__row">
                    <label for="distsearch" class="screen-reader-text">Search by Location</label>
                    <input type="text" value="<?php echo esc_html($distributor_search); ?>" id="distsearch" name="distsearch" class="distsearch search-input" placeholder="Enter City, State, Or Zip"/>
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
                  <button type="button" class="locations__close" ></button>
                </div>
              </div>

              <form id="filter_form" method="get">
                <input type="hidden" value="<?= esc_html($distributor_search); ?>" name="distsearch"/>

                <div class="h5 locations-filters__heading">Product</div>

                  <?php $products = ['envirofill', 'safeshell' ];
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


        <?php
    }
}


