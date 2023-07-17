<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Post_Search extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');

    }

    public function get_style_depends()
    {
        return ['widget-post-projects', 'widget-post-news'];
    }

    public function get_name()
    {
        return 'post-search';
    }

    public function get_title()
    {
        return __('Post Search', 'elementor-custom-widgets');
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
            'label' => __('Post Search', 'elementor-custom-widgets'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'important_note',
            [
                'label' => esc_html__('Note', 'elementor-custom-widgets'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => esc_html__('All data takes automatically.', 'elementor-custom-widgets'),
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

    public function get_pagination($total_pages)
    {
        $links = false;
        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));

            $args = array(
                'mid_size' => 2,
//            'show_all' => true,
                'prev_next' => true,
                'prev_text' => __('prev', 'elementor-custom-widgets'),
                'next_text' => __('next', 'elementor-custom-widgets'),
                'screen_reader_text' => __('Posts navigation', 'elementor-custom-widgets'),
                'type' => 'array',
                'current' => $current_page,
                'total' => $total_pages,
            );

            $links = paginate_links($args);

//            if (!strpos($pagination_links[0], 'prev')) {
//                array_unshift($pagination_links, '<span class="prev pagination__prev">prev</span>');
//            }
//            $last_key = array_key_last($pagination_links);
//            if (!strpos($pagination_links[$last_key], 'next')) {
//                array_push($pagination_links, '<span class="pagination__next">next</span>');
//            }
        }
        return $links;
    }


    protected function render()
    {
        $settings = $this->get_active_settings();
        $section_id = isset($settings['section_id']) && !empty($settings['section_id']) ? $settings['section_id'] : '';

        $total_pages = $GLOBALS['wp_query']->max_num_pages;
        $pagination = $this->get_pagination($total_pages);

        ?>
        <?php if (have_posts()) : ?>

      <div id="<?= esc_attr($section_id) ?>" class="projects-layout">
        <div class="grid-cont grid-cont--sm">
          <div class="projects-layout__main projects-layout__main--alt">
            <div class="blogs-list wow fadeInUp">
              <div class="grid-row blogs-list__row">
                  <?php
                  while (have_posts()) :
                      the_post();

                      $post = get_the_ID();
                      $link = get_the_permalink($post);
                      $title = get_the_title($post);
                      $image = get_the_post_thumbnail($post, 'large');
                      if(!$image) {
                          $image = wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'large', false, array('class' => 'blogs-list__logo') );
                      }
                      ?>
                    <div class="grid-col grid-col--4 grid-col--md-6 grid-col--xs-12 blogs-list__col">
                      <div class="blogs-list__item">

                        <div class="blogs-list__figure">
                            <?php if ($image) : ?>
                                <?= $image ?>
                            <?php endif; ?>
                        </div>

                        <div class="blogs-list__cont">

                            <?php if ($title) : ?>
                              <div class="mt-025 blogs-list__heading"><?= esc_html($title) ?></div>
                            <?php endif; ?>

                            <?php if ($link) : ?>
                              <a href="<?= $link ?>" class="mt-050 cta-link cta-link--sm cta-link--enlarged">Read More</a>
                            <?php endif; ?>

                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
              </div>
            </div>

              <?php if ($total_pages > 1 && $pagination) : ?>
                <nav class="mt-300 pagination wow fadeInUp">
                    <?php foreach ($pagination as $key => $link) : ?>
                        <?php echo str_replace('page-numbers', 'pagination__num', $link); ?>
                    <?php endforeach; ?>

                </nav>
              <?php endif; ?>

          </div>
        </div>
      </div>

    <?php endif; ?>

        <?php
    }
}


