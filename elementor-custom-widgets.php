<?php

	/**
	 * Plugin Name: Elementor - Custom Widgets
	 * Description: Custom element added to Elementor - Modified Blog Elements
	 * Plugin URI: https://elementor.com/
	 * Version: 1.0.0
	 * Author: Vitaly
	 * Author URI: #
	 * Text Domain: elementor-custom-widgets
	 */


use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

// This file is pretty much a boilerplate WordPress plugin.
// It does very little except including wp-widget.php

	final class ElementorCustomWidgets {

		/**
		 * Plugin Version
		 *
		 * @since 1.0.0
		 *
		 * @var string The plugin version.
		 */
		const VERSION = '1.0.0';

		/**
		 * Plugin name
		 *
		 * @since 1.0.0
		 *
		 * @var string The plugin name.
		 */
		const PLUGIN_NAME = 'elementor-custom-widgets';

		/**
		 * Minimum Elementor Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum Elementor version required to run the plugin.
		 */
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/**
		 * Minimum PHP Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum PHP version required to run the plugin.
		 */
		const MINIMUM_PHP_VERSION = '7.0';

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @static
		 *
		 * @var Elementor_Test_Extension The single instance of the class.
		 */
		private static $_instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function __construct() {

			add_action( 'init', [ $this, 'i18n' ] );
			add_action( 'plugins_loaded', [ $this, 'init' ] );

		}

		public static function instance() {
			if ( ! self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Load Textdomain
		 *
		 * Load plugin localization files.
		 *
		 * Fired by `init` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function i18n() {

			load_plugin_textdomain( 'elementor-custom-widgets' );

		}

		/**
		 * Initialize the plugin
		 *
		 * Load the plugin only after Elementor (and other plugins) are loaded.
		 * Checks for basic plugin requirements, if one check fail don't continue,
		 * if all check have passed load the files required to run the plugin.
		 *
		 * Fired by `plugins_loaded` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init() {

			// Add Plugin actions
			$this->init_functions();

			// ELEMENTOR WIDGETS
			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

				return;
			}

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

				return;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

				return;
			}

			// Add Plugin actions
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
			add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

			// Register widget scripts
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

			//Add Custom widget category
			add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories'] );

		}


		/**
		 * widget_scripts
		 *
		 * Load required plugin core files.
		 *
		 * @since 1.2.0
		 * @access public
		 */
		public function widget_scripts() {

			//wp_register_style( 'custom-widgets', plugins_url( '/custom-widgets/assets/post-navigation-custom.css', __FILE__ ) );
			//wp_enqueue_style( 'custom-widgets' );

			//wp_register_script( 'custom-widgets-js', plugins_url( '/custom-widgets/assets/post-navigation-custom.js', __FILE__ ), ['jquery'], '', true );
			//wp_enqueue_script( 'custom-widgets-js' );
		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have Elementor installed or activated.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf( /* translators: 1: Plugin name 2: Elementor */ esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-custom-widgets' ), '<strong>' . esc_html__( 'Elementor Custom Widgets', 'elementor-custom-widgets' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'elementor-custom-widgets' ) . '</strong>' );

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required Elementor version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_elementor_version() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf( /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */ esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-widgets' ), '<strong>' . esc_html__( 'Elementor Custom Widgets', 'elementor-custom-widgets' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'elementor-custom-widgets' ) . '</strong>', self::MINIMUM_ELEMENTOR_VERSION );

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required PHP version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_php_version() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf( /* translators: 1: Plugin name 2: PHP 3: Required PHP version */ esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-widgets' ), '<strong>' . esc_html__( 'Elementor Custom Widgets', 'elementor-custom-widgets' ) . '</strong>', '<strong>' . esc_html__( 'PHP', 'elementor-custom-widgets' ) . '</strong>', self::MINIMUM_PHP_VERSION );

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Init Widgets
		 *
		 * Include widgets files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_widgets() {

			// It is now safe to include Widgets files
			$this->include_widgets_files();

			// Register widget

			Plugin::instance()->widgets_manager->register( new Elementor\Content() );
			Plugin::instance()->widgets_manager->register( new Elementor\Hero() );
			Plugin::instance()->widgets_manager->register( new Elementor\Short_Story() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_Services() );
			Plugin::instance()->widgets_manager->register( new Elementor\Work_With_Us() );
			Plugin::instance()->widgets_manager->register( new Elementor\Quick_Links() );
			Plugin::instance()->widgets_manager->register( new Elementor\Feature_Posts() );
			Plugin::instance()->widgets_manager->register( new Elementor\Testimonials() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_Drivers() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_Features() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_Awards() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_Journey() );
			Plugin::instance()->widgets_manager->register( new Elementor\Credibility() );
			Plugin::instance()->widgets_manager->register( new Elementor\Custom_Reviews() );
			Plugin::instance()->widgets_manager->register( new Elementor\Community_Involvement() );
			Plugin::instance()->widgets_manager->register( new Elementor\Page_Nav() );
			Plugin::instance()->widgets_manager->register( new Elementor\Turf_Process() );
			Plugin::instance()->widgets_manager->register( new Elementor\Turf_Recommnder() );
			Plugin::instance()->widgets_manager->register( new Elementor\Organization_Type() );
			Plugin::instance()->widgets_manager->register( new Elementor\Sport_Type() );
			Plugin::instance()->widgets_manager->register( new Elementor\Who_We_Serve() );
			Plugin::instance()->widgets_manager->register( new Elementor\Feature_Projects() );
			Plugin::instance()->widgets_manager->register( new Elementor\Feature_Posts_Carousel() );
			Plugin::instance()->widgets_manager->register( new Elementor\Envirofill() );
			Plugin::instance()->widgets_manager->register( new Elementor\Safeshell() );
			Plugin::instance()->widgets_manager->register( new Elementor\Natural_Gas_Services() );
			Plugin::instance()->widgets_manager->register( new Elementor\Where_Buy() );
			Plugin::instance()->widgets_manager->register( new Elementor\Helpful_Resources() );
			Plugin::instance()->widgets_manager->register( new Elementor\Organization_Alt() );
			Plugin::instance()->widgets_manager->register( new Elementor\Airpat_Features() );
			Plugin::instance()->widgets_manager->register( new Elementor\Key_Numbers() );
			Plugin::instance()->widgets_manager->register( new Elementor\Maintenance_Offerings() );
			Plugin::instance()->widgets_manager->register( new Elementor\Promo_Projects() );
			Plugin::instance()->widgets_manager->register( new Elementor\Quick_Links_Extended() );
			Plugin::instance()->widgets_manager->register( new Elementor\Why_Motz() );
			Plugin::instance()->widgets_manager->register( new Elementor\Core_Values() );
			Plugin::instance()->widgets_manager->register( new Elementor\Accolades() );
			Plugin::instance()->widgets_manager->register( new Elementor\Follow_Us() );
			Plugin::instance()->widgets_manager->register( new Elementor\Follow_Us_Alt() );
			Plugin::instance()->widgets_manager->register( new Elementor\What_Is_Infill() );
			Plugin::instance()->widgets_manager->register( new Elementor\Infill_Envirofill() );
			Plugin::instance()->widgets_manager->register( new Elementor\Infill_Safeshell() );
			Plugin::instance()->widgets_manager->register( new Elementor\Compare_Infills() );
			Plugin::instance()->widgets_manager->register( new Elementor\Locations() );
			Plugin::instance()->widgets_manager->register( new Elementor\Home_Depot() );
			Plugin::instance()->widgets_manager->register( new Elementor\Meet_The_Team() );
			Plugin::instance()->widgets_manager->register( new Elementor\Recommender() );
			Plugin::instance()->widgets_manager->register( new Elementor\Feedback() );
			Plugin::instance()->widgets_manager->register( new Elementor\Result() );
			Plugin::instance()->widgets_manager->register( new Elementor\Top_Projects() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_News() );
			Plugin::instance()->widgets_manager->register( new Elementor\Faqs() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Blogs() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Subscribe() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Details() );
			Plugin::instance()->widgets_manager->register( new Elementor\Similar_Blogs() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Projects() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Overview() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Overview_Long() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Result() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Testimonials() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Content() );
			Plugin::instance()->widgets_manager->register( new Elementor\Project_Content_Short() );
			Plugin::instance()->widgets_manager->register( new Elementor\Hero_Contact() );
			Plugin::instance()->widgets_manager->register( new Elementor\Topic_Select() );
			Plugin::instance()->widgets_manager->register( new Elementor\Our_People() );
			Plugin::instance()->widgets_manager->register( new Elementor\Capabilities() );
			Plugin::instance()->widgets_manager->register( new Elementor\Hero_Serve() );
			Plugin::instance()->widgets_manager->register( new Elementor\Service_Select() );
			Plugin::instance()->widgets_manager->register( new Elementor\Appreciations() );
			Plugin::instance()->widgets_manager->register( new Elementor\Appreciations_Alt() );
			Plugin::instance()->widgets_manager->register( new Elementor\Positions() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Faq() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Table() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Features() );
			Plugin::instance()->widgets_manager->register( new Elementor\Post_Search() );
		}

		/**
		 * Include Widgets files
		 *
		 * Load widgets files
		 *
		 * @since 1.2.0
		 * @access private
		 */
		private function include_widgets_files() {

			require_once( __DIR__ . '/custom-widgets/content.php' );
			require_once( __DIR__ . '/custom-widgets/hero.php' );
			require_once( __DIR__ . '/custom-widgets/short-story.php' );
			require_once( __DIR__ . '/custom-widgets/our-services.php' );
			require_once( __DIR__ . '/custom-widgets/work-with-us.php' );
			require_once( __DIR__ . '/custom-widgets/quick-links.php' );
			require_once( __DIR__ . '/custom-widgets/featured-posts.php' );
			require_once( __DIR__ . '/custom-widgets/testimonials.php' );
			require_once( __DIR__ . '/custom-widgets/our-drivers.php' );
			require_once( __DIR__ . '/custom-widgets/our-features.php' );
			require_once( __DIR__ . '/custom-widgets/our-awards.php' );
			require_once( __DIR__ . '/custom-widgets/our-journey.php' );
			require_once( __DIR__ . '/custom-widgets/credibility.php' );
			require_once( __DIR__ . '/custom-widgets/reviews.php' );
			require_once( __DIR__ . '/custom-widgets/community-involvement.php' );
			require_once( __DIR__ . '/custom-widgets/page-nav.php' );
			require_once( __DIR__ . '/custom-widgets/turf-process.php' );
			require_once( __DIR__ . '/custom-widgets/turf-recommender.php' );
			require_once( __DIR__ . '/custom-widgets/organization-type.php' );
			require_once( __DIR__ . '/custom-widgets/sport-type.php' );
			require_once( __DIR__ . '/custom-widgets/who-we-serve.php' );
			require_once( __DIR__ . '/custom-widgets/featured-projects.php' );
			require_once( __DIR__ . '/custom-widgets/featured-posts-carousel.php' );
			require_once( __DIR__ . '/custom-widgets/envirofill.php' );
			require_once( __DIR__ . '/custom-widgets/safeshell.php' );
			require_once( __DIR__ . '/custom-widgets/natural-gas-services.php' );
			require_once( __DIR__ . '/custom-widgets/where-buy.php' );
			require_once( __DIR__ . '/custom-widgets/helpfull-resources.php' );
			require_once( __DIR__ . '/custom-widgets/organization-alt.php' );
			require_once( __DIR__ . '/custom-widgets/airpat-features.php' );
			require_once( __DIR__ . '/custom-widgets/key-numbers.php' );
			require_once( __DIR__ . '/custom-widgets/maintenance-offerings.php' );
			require_once( __DIR__ . '/custom-widgets/promo-projects.php' );
			require_once( __DIR__ . '/custom-widgets/quick-links-extended.php' );
			require_once( __DIR__ . '/custom-widgets/why-motz.php' );
			require_once( __DIR__ . '/custom-widgets/core-values.php' );
			require_once( __DIR__ . '/custom-widgets/accolades.php' );
			require_once( __DIR__ . '/custom-widgets/follow-us.php' );
			require_once( __DIR__ . '/custom-widgets/follow-us-alt.php' );
			require_once( __DIR__ . '/custom-widgets/what-is-infill.php' );
			require_once( __DIR__ . '/custom-widgets/infill-envirofill.php' );
			require_once( __DIR__ . '/custom-widgets/infill-safeshell.php' );
			require_once( __DIR__ . '/custom-widgets/compare-infills.php' );
			require_once( __DIR__ . '/custom-widgets/locations.php' );
			require_once( __DIR__ . '/custom-widgets/home-depot.php' );
			require_once( __DIR__ . '/custom-widgets/meet-the-team.php' );
			require_once( __DIR__ . '/custom-widgets/recommender.php' );
			require_once( __DIR__ . '/custom-widgets/feedback.php' );
			require_once( __DIR__ . '/custom-widgets/result.php' );
			require_once( __DIR__ . '/custom-widgets/top-projects.php' );
			require_once( __DIR__ . '/custom-widgets/post-news.php' );
			require_once( __DIR__ . '/custom-widgets/faqs.php' );
			require_once( __DIR__ . '/custom-widgets/post-blogs.php' );
			require_once( __DIR__ . '/custom-widgets/post-blogs.php' );
			require_once( __DIR__ . '/custom-widgets/post-subscribe.php' );
			require_once( __DIR__ . '/custom-widgets/post-details.php' );
			require_once( __DIR__ . '/custom-widgets/similar-blogs.php' );
			require_once( __DIR__ . '/custom-widgets/post-projects.php' );
			require_once( __DIR__ . '/custom-widgets/project-overview.php' );
			require_once( __DIR__ . '/custom-widgets/project-overview-long.php' );
			require_once( __DIR__ . '/custom-widgets/project-result.php' );
			require_once( __DIR__ . '/custom-widgets/project-testimonials.php' );
			require_once( __DIR__ . '/custom-widgets/project-content.php' );
			require_once( __DIR__ . '/custom-widgets/project-content-short.php' );
			require_once( __DIR__ . '/custom-widgets/hero-contact.php' );
			require_once( __DIR__ . '/custom-widgets/topic-select.php' );
			require_once( __DIR__ . '/custom-widgets/our-people.php' );
			require_once( __DIR__ . '/custom-widgets/capabilities.php' );
			require_once( __DIR__ . '/custom-widgets/hero-serve.php' );
			require_once( __DIR__ . '/custom-widgets/service-select.php' );
			require_once( __DIR__ . '/custom-widgets/appreciations.php' );
			require_once( __DIR__ . '/custom-widgets/appreciations-alt.php' );
			require_once( __DIR__ . '/custom-widgets/positions.php' );
			require_once( __DIR__ . '/custom-widgets/post-faq.php' );
			require_once( __DIR__ . '/custom-widgets/post-table.php' );
			require_once( __DIR__ . '/custom-widgets/post-features.php' );
			require_once( __DIR__ . '/custom-widgets/post-search.php' );
		}


		/**
		 * Init Controls
		 *
		 * Include controls files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_controls() {

			// 	// Include Control files
			// 	require_once( __DIR__ . '/controls/test-control.php' );

			// 	// Register control
			// 	\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

		}

		/**
		 * Init Functions
		 *
		 * Include functions files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_functions() {

			// Its is now safe to include Widgets files
			$this->include_functions_files();
		}

		/**
		 * Include Functions files
		 *
		 * Load widgets files
		 *
		 * @since 1.2.0
		 * @access private
		 */
		private function include_functions_files() {
            require_once( __DIR__ . '/includes/Config.php' );
		}

		public function add_elementor_widget_categories( $elements_manager ) {

			$elements_manager->add_category( 'Motz', [
					'title' => __( 'Motz', 'elementor-custom-widgets' ),
					'icon'  => 'fa fa-plug',
				] );

		}

	}

	ElementorCustomWidgets::instance();
