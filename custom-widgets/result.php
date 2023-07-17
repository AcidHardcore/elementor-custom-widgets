<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Result extends Widget_Base
{

    private Config $config;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->config = new Config;

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-result.css');
        wp_register_style('widget-result', get_template_directory_uri() . '/css/widget-result.css', array('main-styles'), $css_version);

    }

    public function get_style_depends()
    {
        return ['widget-hero', 'widget-result'];
    }

    public function get_name()
    {
        return 'result';
    }

    public function get_title()
    {
        return __('Result', 'elementor-custom-widgets');
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
            'label' => __('Result', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'important_note',
            [
                'label' => esc_html__('Warning:', 'elementor-custom-widgets'),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>This widget is working only on the Recommender Result template</strong>', 'elementor-custom-widgets'),
                'content_classes' => 'your-class',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Title Typography', 'elementor-custom-widgets'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h2',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Text Typography', 'elementor-custom-widgets'),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} p',
            ]
        );

        $this->add_control(
            'hero_title', [
                'label' => esc_html__('Hero Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => false,
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'button--primary',
                'options' => $this->config::BUTTON_TYPE
            ]
        );

        $this->add_control(
            'recommended-system',
            [
                'label' => esc_html__('Recommended System', 'elementor-custom-widgets'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'system_title', [
                'label' => esc_html__('System Title', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'elementor-custom-widgets'),
                'label_block' => false,
            ]
        );

        $this->add_control(
            'system_copy', [
                'label' => esc_html__('System Copy', 'elementor-custom-widgets'),
                'description' => esc_html('Use [[FIRST_NAME]] wherever you want the user\'s first name to appear, and use [[PRODUCT_NAME]] whenever you would like the synthetic product name to appear.', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'placeholder' => esc_html__('Greeting Copy', 'elementor-custom-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'sentences_6_and_7', [
            'label' => __('Sentences 6 and 7', 'elementor-custom-widgets'),
            'type' => Controls_Manager::WYSIWYG,
            'placeholder' => esc_html__('Sentences 6 and 7', 'elementor-custom-widgets'),
            'label_block' => true,
        ]);

        $this->add_control(
            'second_paragraph_copy', [
            'label' => __('Second Paragraph', 'elementor-custom-widgets'),
            'description' => 'This is the copy that will appear as the second paragraph in the Turf Recommender Results PDF.',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'placeholder' => esc_html__('Second Paragraph', 'elementor-custom-widgets'),
            'label_block' => true,
        ]);

        $this->add_control(
            'footer_disclaimer', [
            'label' => __('Footer Disclaimer', 'elementor-custom-widgets'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'placeholder' => esc_html__('Footer Disclaimer', 'elementor-custom-widgets'),
            'label_block' => true,
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


        $result_id = get_query_var('result_id');

        if(empty($result_id) && is_admin()) {
            $result_id = 1;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . "turf_recommender_results";

        $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $result_id);
        $result = $wpdb->get_results($sql);

        $data_used = [];

        $upload_directories = wp_upload_dir();
        $results_pdfs_directory = $upload_directories['basedir'] . '/results';

        if (!file_exists($results_pdfs_directory)) {
            if (wp_mkdir_p($results_pdfs_directory)) {
                $data_used['uploads_dir'] = $results_pdfs_directory;
            }
        } else {
            $data_used['uploads_dir'] = $results_pdfs_directory;
        }

// reset the Turf Recommender
        $_SESSION['current_step'] = 1;
        unset($_SESSION['result_id']);

        $data_used['result_id'] = $result_id;
        $data_used['filename'] = 'Turf_Recommender_Result_' . $data_used['result_id'];

        $top_sports = explode(",", $result[0]->step_4_ids);

        $top_sports_names = [];

        foreach ($top_sports as $top_sport) {
            $sport = get_post($top_sport);
            $top_sports_names[] = $sport->post_name;
        }

        $slug = ['crossflex'];

        if (count($top_sports_names) > 0) {
            if (count($top_sports_names) === 1) {
                if (in_array('football', $top_sports_names) || in_array('field-hockey', $top_sports_names)) {
                    // football and field hockey, 24/7
                    $slug = ['twenty-fourseven'];
                } elseif (in_array('soccer', $top_sports_names) || in_array('rugby', $top_sports_names)) {
                    // soccer and rugby, sportgrass
                    $slug = ['sportgrass'];
                } elseif (in_array('baseball', $top_sports_names) || in_array('softball', $top_sports_names)) {
                    // baseball and softball, tripleplay
                    $slug = ['tripleplay'];
                } else {
                    // all others, it must be CrossFlex
                    $slug = ['crossflex'];
                }
            } elseif (count($top_sports_names) === 2) {
                if (in_array('football', $top_sports_names) && in_array('field-hockey', $top_sports_names)) {
                    // football and field hockey, 24/7
                    $slug = ['twenty-fourseven'];
                } elseif (in_array('soccer', $top_sports_names) && in_array('rugby', $top_sports_names)) {
                    // soccer and rugby, sportgrass
                    $slug = ['sportgrass'];
                } elseif (in_array('baseball', $top_sports_names) && in_array('softball', $top_sports_names)) {
                    // baseball and softball, tripleplay
                    $slug = ['tripleplay'];
                } else {
                    // all others, it must be CrossFlex
                    $slug = ['crossflex'];
                }
            } else {
                // all others, it must be CrossFlex
                $slug = ['crossflex'];
            }
        } else {
            // somehow we don't have any sports saved!
            header('Location: /sports-field-construction/synthetic/turf-recommender/', true, 303);
            exit;
        }

        $product = get_posts(['post_type' => 'motz_products', 'posts_per_page' => 1, 'post_name__in' => $slug]);


//        todo: do we need this inline styles?
// this is silly
//        $_SESSION['product'] = $product;

//        function turf_recommender_custom_color()
//        {
//            // ... just silly
//            $product = $_SESSION['product'];
//            unset($_SESSION['product']);
//            // custom CSS
//            if (get_field('red', $product[0]->ID) && get_field('green', $product[0]->ID) && get_field('blue', $product[0]->ID)) {
//                $css = '#content .header_image_c .title_panel_c { background-color: rgba(' . get_field('red', $product[0]->ID) . ', ' . get_field('green', $product[0]->ID) . ', ' . get_field('blue', $product[0]->ID) . ', 0.9) !important; }';
//                wp_add_inline_style('turf-recommender-results-style', $css);
//            }
//        }

//        add_action('wp_enqueue_scripts', 'turf_recommender_custom_color');

        $product_header_image = get_field('product_header_image', $product[0]->ID);
        $product_name = get_field('product_name', $product[0]->ID);
        $product_logo = get_field('product_logo', $product[0]->ID);
        $graphic = get_field('graphic', $product[0]->ID);

        $data_used['date'] = date("n.j.y");

//        $data_used['product_name'] = get_field('product_name', $product[0]->ID);
        $data_used['product_name'] = $product_name;

        $greeting_copy = $settings['system_copy'];
        if (isset($greeting_copy) && !empty($greeting_copy)) {
            $greeting_copy = str_replace('[[FIRST_NAME]]', $result[0]->first_name, $greeting_copy);
            $greeting_copy = str_replace('[[PRODUCT_NAME]]', $data_used['product_name'], $greeting_copy);
        }

        $recommended_system_copy = get_field('recommended_system_copy', $product[0]->ID, false);
        $recommended_system_copy = str_replace('[[FACILITY_NAME]]', $result[0]->facility_name, $recommended_system_copy);

        if ($slug === 'twenty-fourseven') {
            $recommended_system_copy = str_replace('[[SPORTS]]', implode(" and ", $top_sports_names), $recommended_system_copy);
        } elseif ($slug === 'sportgrass') {
            $recommended_system_copy = str_replace('[[SPORTS]]', implode(" and ", $top_sports_names), $recommended_system_copy);
        } elseif ($slug === 'tripleplay') {
            $recommended_system_copy = str_replace('[[SPORTS]]', implode(" and ", $top_sports_names), $recommended_system_copy);
        } else {
            if (count($top_sports_names) === 1) {
                $recommended_system_copy = str_replace('[[SPORTS]]', implode("", $top_sports_names), $recommended_system_copy);
            } elseif (count($top_sports_names) === 2) {
                $recommended_system_copy = str_replace('[[SPORTS]]', implode(" and ", $top_sports_names), $recommended_system_copy);
            } else {
                $last_element = array_pop($top_sports_names);
                array_push($top_sports_names, 'and ' . $last_element);
                $recommended_system_copy = str_replace('[[SPORTS]]', implode(", ", $top_sports_names), $recommended_system_copy);
            }
        }

        $data_used['recommended_system_copy_pdf'] = $recommended_system_copy;

        $role = get_post($result[0]->step_2_id);
        $second_paragraph_copy = get_field('results_paragraph_copy', $role->ID, false);

        $venue = get_post($result[0]->step_1_id);
        $synthetic_product = get_field_object('product_detail_page', $product[0]->ID);
        $sentence_2 = get_field('results_paragraph_copy', $venue->ID, false);
        $sentence_2 = preg_replace('/\[\[(.*?)\]\]/', '<a href="/projects/?prsy[]=' . $synthetic_product['value']->ID . '">$1</a>', $sentence_2);
        $second_paragraph_copy .= ' ' . $sentence_2;

        $planning_stage = get_post($result[0]->step_3_id);
        $second_paragraph_copy .= ' ' . get_field('results_paragraph_copy', $planning_stage->ID, false);

        $sentences_4_and_5 = get_field('sentences_4_and_5', $product[0]->ID, false);
        $sentences_4_and_5 = str_replace('[[FACILITY_NAME]]', $result[0]->facility_name, $sentences_4_and_5);
        $sentences_4_and_5 = str_replace('stack your system', '<a href="' . get_permalink(get_field('product_detail_page', $product[0]->ID)) . '#stack-your-system">stack your system</a>', $sentences_4_and_5);
        $second_paragraph_copy .= '</p><p>' . $sentences_4_and_5;

        $second_paragraph_copy .= ' ' . $settings['sentences_6_and_7'];

        $data_used['second_paragraph_copy_pdf'] = $settings['second_paragraph_copy'];

        $data_used['background_image'] = get_template_directory() . '/img/turf_recommender_results/pdf_bg_' . $slug[0] . '.jpg';

        $data_used['footer_disclaimer'] = $settings['footer_disclaimer'];

// create and save the PDF
        if (class_exists('motz')) {
            $motz = motz();

            if (!file_exists($data_used['uploads_dir'] . '/' . $data_used['filename'] . '.pdf')) {
                $motz->generate_pdf($data_used);
            }
        }


        ?>
        <?php if (is_page_template('page-templates/turf-recommender-results.php')) : ?>

      <div class="hero hero--sm">

          <?php if (isset($product_header_image)) : ?>
              <?= wp_get_attachment_image($product_header_image, 'full', false, array('class' => 'hero__bg')) ?>
          <?php endif; ?>

        <div class="grid-cont hero__cont">
            <?php if (isset($settings['hero_title'])) : ?>
              <h1 class="hero__title"><?= $settings['hero_title'] ?></h1>
            <?php endif; ?>
        </div>

      </div>

      <div class="section recommended-system">
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row">

            <div class="grid-col grid-col--sm-12">

                <?php if (isset($settings['system_title']) && $product_name) : ?>
                  <h1><?= $settings['system_title'] ?> <br> <?= esc_html($product_name) ?></h1>
                <?php endif; ?>

                <?php if (isset($greeting_copy)) : ?>
                  <p class="mt-100 p p--lg"><?= wp_kses_post($greeting_copy) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid-col grid-col--auto grid-col--sm-12 grid-col--sm-order-0">
                <?php if (isset($product_logo)) : ?>
                  <div class="recommended-system__logo">
                      <?= wp_get_attachment_image($product_logo, 'full') ?>
                  </div>
                <?php endif; ?>
            </div>

          </div>
        </div>
      </div>

      <div class="section section--nobg recommended-details">
        <div class="grid-cont grid-cont--sm wow fadeInUp">

            <?php if (isset($graphic)) : ?>
                <?= wp_get_attachment_image($graphic, 'full', false, array('class' => 'recommended-details__cover')) ?>
            <?php endif; ?>

          <h2>Your Results | <?= esc_html($product_name) ?></h2>

          <p><?= wp_kses_post($recommended_system_copy) ?></p>

          <h2>Recommended for You</h2>

          <p><?= wp_kses_post($second_paragraph_copy) ?></p>

            <?php
            $button_class = 'button';
            if ($settings['button_type'] !== 'button') {
                $button_class .= ' ' . $settings['button_type'];
            }
            ?>

          <div class="mt-100">
            <a href="<?= get_permalink(get_field('product_detail_page', $product[0]->ID)); ?>" class="<?= esc_attr($button_class) ?>">Learn More About This Series</a>
          </div>

          <div id="inner_other_actions_c" class="mt-100 grid-row">
<!--            <div class="grid-col grid-col--auto">-->
<!--              <a href="--><?php //echo $upload_directories['baseurl'] . '/results/' . $data_used['filename']; ?><!--.pdf" class="--><?//= esc_attr($button_class) ?><!--" target="_blank">Download Results</a>-->
<!--            </div>-->
            <div class="grid-col grid-col--auto">
              <a id="share_cta" href="<?php the_permalink(); ?>" class="<?= esc_attr($button_class) ?>">Copy link</a>
            </div>
            <div class="grid-col grid-col--auto">
              <a href="/synthetic/turf-products/" class="<?= esc_attr($button_class) ?>">Get started planning your project</a>
            </div>
          </div>
        </div>
      </div>

    <?php else : ?>

      <div id="<?= esc_attr($section_id) ?>" class="section">
        <div class="grid-cont grid-cont--xs">
          <p>Pick up Turf Recommender Result template for the page</p>
        </div>
      </div>

    <?php endif; ?>
        <?php
    }
}

?>



