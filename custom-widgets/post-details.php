<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Details extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/widget-post-details.css');
        wp_register_style('widget-post-details', get_template_directory_uri() . '/css/widget-post-details.css', array('main-styles'), $css_version);

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/widget-post-details.js');
        wp_register_script('widget-post-details', get_template_directory_uri() . '/js/widget-post-details.js', array('jquery'), $js_version, true);

    }

    public function get_style_depends()
    {
        return ['widget-post-details'];
    }

    public function get_script_depends()
    {
        return ['widget-post-details'];
    }

    public function get_name()
    {
        return 'post-details';
    }

    public function get_title()
    {
        return __('Post Details', 'elementor-custom-widgets');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
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
            'label' => __('Post Details', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'more_options',
            [
                'label' => esc_html__('All settings are automatic takes from a blog post', 'elementor-custom-widgets'),
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

        $this->end_controls_section();

    }

    public function social_share($id)
    {
        $URL = urlencode(get_permalink($id));
        $title = htmlspecialchars(urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
        $twitterTitle = empty(get_post_meta($id, '_yoast_wpseo_twitter-title')[0]) ?
            $title :
            htmlspecialchars(urlencode(html_entity_decode(get_post_meta($id, '_yoast_wpseo_twitter-title')[0], ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
        $socials = [
            'facebook' => [
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $URL,
                'icon' => '<svg viewBox="0 0 320 512" height="16" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg>'
            ],
            'linkedin' => [
                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $URL . '&amp;title=' . $title,
                'icon' => '<svg viewBox="0 0 448 512" height="16" fill="currentColor"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path></svg>'
            ],
            'twitter' => [
                'url' => 'https://twitter.com/intent/tweet?text=' . $twitterTitle . '&amp;url=' . $URL,
                'icon' => '<svg viewBox="0 0 512 512" height="16" fill="currentColor"> <path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path> </svg>',
            ],
        ];

        return $socials;
    }

    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';
        $blog_id = get_the_id();
        $socials = $this->social_share($blog_id);
        $date = get_the_date('m-d-Y', $blog_id);
        $title = get_the_title($blog_id);
//        $author = $this->get_author($blog_id);
        ?>

      <div id="<?= esc_attr($section_id) ?>" class="section post-details">
        <div class="grid-cont grid-cont--sm">
            <?php if ($date) : ?>
              <div class="h5 post-details__date wow fadeInUp"><?= esc_html($date) ?></div>
            <?php endif; ?>

            <?php if (is_array($socials)) : ?>
              <div class="mt-075 post-details__share wow fadeInUp">
                  <?php foreach ($socials as $item) : ?>
                    <a href="<?= $item['url'] ?>">
                        <?= $item['icon'] ?>
                    </a>
                  <?php endforeach; ?>
              </div>
            <?php endif; ?>

          <div class="mt-100 post-details__text wow fadeInUp">
              <?php if ($title) : ?>
                <h1 class="h2"><?= esc_html($title) ?></h1>
              <?php endif; ?>

              <?php the_content() ?>
          </div>

        </div>
      </div>


        <?php
    }
}


