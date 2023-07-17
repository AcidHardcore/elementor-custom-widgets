<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Projects extends Widget_Base {

	const POST_TYPES = [
		'projects'           => 'Sports Field Construction Projects',
		'landscape_projects' => 'Infill Landscape Projects',
		'sport_projects'     => 'Infill Sport Projects',
//        'maintenance_projects' => 'Maintenance Projects',
	];

	const PROJECT_FIELD_TYPE = [
		'natural'   => 'Natural',
		'synthetic' => 'Synthetic'
	];

	const FACILITY_TYPE = [
		'collegiate'        => 'Collegiate',
		'community-complex' => 'Community Complex',
		'high-school'       => 'High School',
		'professional'      => 'Professional'
	];

	const SPORTS_FIELDS = 'sports_fields';

	const SERVICES_TYPE = [
		'installation'                  => 'Field Installation',
		'installation-track'            => 'Track Installation',
		'removal-replacement'           => 'Removal / Replacement',
		'field-construction-management' => 'Field Construction Management',
		'maintenance-repairs'           => 'Maintenance & Repairs'
	];

	const CONTENT_TYPE = [
		'project-case-study' => 'Project Case-Study',
		'project-summary'    => 'Project Summary'
	];

	const OUTDOORSINDOORS = [
		'outdoor' => 'Outdoor',
		'indoor'  => 'Indoor'
	];

//    landscape projects
	const PROJECT_INFILL = [
		'envirofill' => 'Envirofill',
		'safeshell'  => 'Safeshell'
	];

	const PROJECT_LANDSCAPE_TYPE = [
		'pets'           => 'Pets',
		'playgrounds'    => 'Playgrounds',
		'putting_greens' => 'Putting Greens',
		'commercial'     => 'Commercial',
		'Residential'    => 'Residential',
		'dog_parks'      => 'Dog Parks'
	];

	const PROJECT_SPORTS_TYPE = [
		'baseball'      => 'Baseball',
		'field_hockey'  => 'Field Hockey',
		'football'      => 'Football',
		'lacrosse'      => 'Lacrosse',
		'multi_purpose' => 'Multi-Purpose',
		'soccer'        => 'Soccer',
		'softball'      => 'Softball'
	];

	const PROJECT_LOCATION = [
		'central'   => 'Central',
		'midwest'   => 'Midwest',
		'northeast' => 'Northeast',
		'southeast' => 'Southeast',
		'southwest' => 'Southwest',
		'west'      => 'West',
		'northwest' => 'Northwest'
	];

	const STATES = [
		'AL'    => 'Alabama',
		'AK'    => 'Alaska',
		'AZ'    => 'Arizona',
		'AR'    => 'Arkansas',
		'CA'    => 'California',
		'CO'    => 'Colorado',
		'CT'    => 'Connecticut',
		'DE'    => 'Delaware',
		'FL'    => 'Florida',
		'GA'    => 'Georgia',
		'HI'    => 'Hawaii',
		'ID'    => 'Idaho',
		'IL'    => 'Illinois',
		'IN'    => 'Indiana',
		'IA'    => 'Iowa',
		'KS'    => 'Kansas',
		'KY'    => 'Kentucky',
		'LA'    => 'Louisiana',
		'ME'    => 'Maine',
		'MD'    => 'Maryland',
		'MA'    => 'Massachusetts',
		'MI'    => 'Michigan',
		'MN'    => 'Minnesota',
		'MS'    => 'Mississippi',
		'MO'    => 'Missouri',
		'MT'    => 'Montana',
		'NE'    => 'Nebraska',
		'NV'    => 'Nevada',
		'NH'    => 'New Hampshire',
		'NJ'    => 'New Jersey',
		'NM'    => 'New Mexico',
		'NY'    => 'New York',
		'NC'    => 'North Carolina',
		'ND'    => 'North Dakota',
		'OH'    => 'Ohio',
		'OK'    => 'Oklahoma',
		'OR'    => 'Oregon',
		'PA'    => 'Pennsylvania',
		'RI'    => 'Rhode Island',
		'SC'    => 'South Carolina',
		'SD'    => 'South Dakota',
		'TN'    => 'Tennessee',
		'TX'    => 'Texas',
		'UT'    => 'Utah',
		'VT'    => 'Vermont',
		'VA'    => 'Virginia',
		'WA'    => 'Washington',
		'WV'    => 'West Virginia',
		'WI'    => 'Wisconsin',
		'WY'    => 'Wyoming',
		'other' => 'Other',
	];

	public const PROJECT_FILTERS = [
		'project_field_type',
		'facility_type',
		'project_sports',
		'locations',
		'services_type',
		'content_type',
		'outdoorsindoors',
		'project_infill',
		'project_landscape_type',
		'project_sports_type',
		'project_location'
	];

	public array $input;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/css/widget-post-projects.css' );
		wp_register_style( 'widget-post-projects', get_template_directory_uri() . '/css/widget-post-projects.css', array( 'main-styles' ), $css_version );

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/js/widget-post-projects.js' );
		wp_register_script( 'widget-post-projects', get_template_directory_uri() . '/js/widget-post-projects.js', array( 'jquery' ), $js_version, true );

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/js/project-filter.js' );
		wp_register_script( 'project-filter', get_template_directory_uri() . '/js/project-filter.js', array( 'jquery' ), $js_version, true );

	}

	public function get_style_depends() {
		return [ 'widget-post-projects', 'widget-post-news', 'widget-post-blogs' ];
	}

	public function get_script_depends() {
		return [ 'widget-post-projects', 'project-filter' ];
	}

	public function get_name() {
		return 'post-projects';
	}

	public function get_title() {
		return __( 'Post Projects', 'elementor-custom-widgets' );
	}

	public function get_icon() {
		return 'eicon-columns';
	}

	public function get_categories() {
		return [ 'Motz' ];
	}

	public function get_keywords() {
		return [ 'custom' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_intro', [
			'label' => __( 'Post Projects', 'elementor-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control(
			'posts_per_page', [
				'label'   => esc_html__( 'Posts per Page', 'elementor-custom-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '6',
			]
		);

		$this->add_control(
			'post_types',
			[
				'label'    => esc_html__( 'Select Project Type', 'elementor-custom-widgets' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => false,
				'options'  => self::POST_TYPES,
				'default'  => 'projects',
			]
		);

		$this->add_control(
			'items_text', [
				'label'       => esc_html__( 'Projects CTA Text', 'elementor-custom-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Take a Look', 'elementor-custom-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'filter_label', [
				'label'   => esc_html__( 'Filter Label', 'elementor-custom-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Filter Projects',
			]
		);

		$this->add_control(
			'floating_bar',
			[
				'label'     => esc_html__( 'Floating Bar', 'elementor-custom-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'elementor-custom-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Title', 'elementor-custom-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'link_text', [
				'label'       => esc_html__( 'Link Text', 'elementor-custom-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Contact Us', 'elementor-custom-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'cta_link',
			[
				'label'       => esc_html__( 'CTA Link', 'elementor-custom-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-custom-widgets' ),
				'default'     => [
					'url'               => '',
					'is_external'       => false,
					'nofollow'          => false,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_settings', [
			'label' => __( 'Settings', 'elementor-custom-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control(
			'section_id', [
				'label' => esc_html__( 'Section ID', 'elementor-custom-widgets' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

	}

	public function get_new_projects( $args ) {

		$query = new \WP_Query( $args );

		return array(
			'posts' => $query->posts,
			'max'   => $query->max_num_pages
		);
	}

	public function get_pagination( $total_pages, $current_page ) {
		$links = false;
		if ( $total_pages > 1 ) {

			$args = array(
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( 'prev', 'elementor-custom-widgets' ),
				'next_text'          => __( 'next', 'elementor-custom-widgets' ),
				'screen_reader_text' => __( 'Posts navigation', 'elementor-custom-widgets' ),
				'type'               => 'array',
				'current'            => $current_page,
				'total'              => $total_pages,
			);

			$links = paginate_links( $args );

		}

		return $links;
	}

	/**
	 * OLD realisation, $self::STATES using now
	 * @return array
	 */
	public function get_locations(): array {
		$locations = [];

		$args     = array(
			'post_type'      => 'projects',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'orderby'        => 'date',
			'order'          => 'ASC',
			'fields'         => 'ids',
		);
		$projects = $this->get_new_projects( $args );
		if ( is_array( $projects ) ) {

			foreach ( $projects['posts'] as $project ) {
				$city  = get_field( 'project_city', $project );
				$state = get_field( 'project_state', $project );
				if ( $city && $state ) {
					$locations[] = strtolower( $city ) . ', ' . strtolower( $state );
				}
			}
			$locations = array_unique( $locations );
			sort( $locations );
		}

		return $locations;
	}


	public function get_request_parameter( $param ) {
		if ( isset( $_REQUEST[ $param ] ) && ! empty( $_REQUEST[ $param ] ) ) {
			return sanitize_text_field( $_REQUEST[ $param ] );
		} else {
			return false;
		}
	}

	public function prepare_filters_params() {
		$filter_params = [];

		if ( ! empty( $_REQUEST ) ) {
			foreach ( $_REQUEST as $item => $value ) {
				if ( in_array( $item, self::PROJECT_FILTERS ) ) {
					$filter_params[ $item ] = explode( ',', sanitize_text_field( $value ) );
				}
			}
		}

		return $filter_params;
	}

	protected function render() {
		$settings   = $this->get_active_settings();
		$section_id = isset( $settings['section_id'] ) && ! empty( $settings['section_id'] ) ? $settings['section_id'] : '';

		if ( isset( $settings['cta_link'] ) && ! empty( $settings['cta_link']['url'] ) ) {
			$this->add_link_attributes( 'cta_link', $settings['cta_link'] );
		}

		$paged_param = $this->get_request_parameter( 'pages' );
		$paged       = max( 1, $paged_param ? intval( $paged_param ) : 0 );

		$args = array(
			'post_type'      => $settings['post_types'],
			'post_status'    => 'publish',
			'posts_per_page' => $settings['posts_per_page'],
			'orderby'        => 'meta_value',
			'meta_key'       => 'sticky_project',
			'order'          => 'DESC',
			'fields'         => 'ids',
			'paged'          => $paged
		);

		$search_param = $this->get_request_parameter( 'search' );
		if ( $search_param ) {
			$args['s'] = $search_param;
		}

		$filter_params = $this->prepare_filters_params();

		if ( ! empty( $filter_params ) ) {
			foreach ( $filter_params as $index => $item ) {
				if ( in_array( $item, self::PROJECT_FILTERS ) ) {
					$args['meta_query']['relation'] = 'AND';
				}

				if ( is_array( $item ) && ! empty( $item ) ) {
					if ( $index !== 'locations' && $index !== 'tax_query' ) {
						if ( $index === 'project_sports' ) {
							$args['meta_query'][] = [
								'key'     => 'project_sports_$_sport',
								'value'   => $item,
								'compare' => 'IN',
							];
						} else {
							foreach ( $item as $value ) {

								$args['meta_query'][] = [
									'key'     => $index,
									'value'   => $value,
									'compare' => 'LIKE',
								];
							}
						}
					}

					if ( $index === 'locations' ) {
						foreach ( $item as $value ) {
							if ( $value === 'other' ) {
								$args['meta_query'][] = [
									'key'     => 'is_international_location',
									'value'   => 1,
									'compare' => 'LIKE',
								];
							} else {
								$args['meta_query'][] = [
									'key'     => 'project_state',
									'value'   => $value,
									'compare' => 'LIKE',
								];
							}
						}
					}

				}
			}
		}


		$loop = $this->get_new_projects( $args );
		if ( is_array( $loop ) ) {
			$posts = $loop['posts'];
		}
		$total_pages = $loop['max'];

		$pagination = $this->get_pagination( $total_pages, $paged );

		if ( $settings['post_types'] === 'projects' ) {
			$sport_args    = array(
				'post_type'      => self::SPORTS_FIELDS,
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',

			);
			$sports_fields = $this->get_new_projects( $sport_args );

			$locations = self::STATES;
		}

		$js_args          = $args;
		$js_args['paged'] = $paged;
		global $wp;
		$js_args['current_url'] = home_url( add_query_arg( array(), $wp->request ) );
//	    because it breaks the JS logic
		unset( $js_args['meta_query'] );
		?>


      <div id="<?= esc_attr( $section_id ) ?>" class="projects-layout" data-args='<?= json_encode( $js_args, JSON_NUMERIC_CHECK ) ?>'>
        <div class="grid-cont grid-cont--sm">
          <div class="grid-row grid-row--nog">
            <div class="grid-col grid-col--9 grid-col--sm-12">
              <div class="projects-layout__main">
                <div class="projects-list wow fadeInUp">
                  <div class="grid-row projects-list__row">

					  <?php if ( is_array( $posts ) ) : ?>
						  <?php foreach ( $posts as $post ) :

							  if ( $settings['post_types'] === 'projects' ) :
								  get_template_part( 'template-parts/project', null, array(
									  'item'     => $post,
									  'btn_text' => $settings['items_text'] ?? ''
								  ) );
							  endif;

							  if ( $settings['post_types'] === 'landscape_projects' ) :
								  get_template_part( 'template-parts/landscape-project', null, array(
									  'item'     => $post,
									  'btn_text' => $settings['items_text'] ?? ''
								  ) );
							  endif;

							  if ( $settings['post_types'] === 'sport_projects' ) :
								  get_template_part( 'template-parts/sport-project', null, array(
									  'item'     => $post,
									  'btn_text' => $settings['items_text'] ?? ''
								  ) );
							  endif;

						  endforeach; ?>
					  <?php endif; ?>

                  </div>
                </div>

				  <?php if ( $total_pages > 1 && $pagination ) : ?>
                    <nav class="mt-300 pagination wow fadeInUp">
						<?php foreach ( $pagination as $key => $link ) : ?>
							<?php echo str_replace( 'page-numbers', 'pagination__num', $link ); ?>
						<?php endforeach; ?>
                    </nav>
				  <?php endif; ?>

              </div>
            </div>

            <div class="grid-col grid-col--3 grid-col--sm-12 grid-col--sm-order-0">
              <div class="projects-layout__side">
                <div class="projects-filter wow fadeInUp">
                  <button type="button" class="projects-filter__close"></button>

                  <input type="text" placeholder="Search Projects" class="mb-150 search-input projects-filter__search">

					<?php if ( isset( $settings['filter_label'] ) ) : ?>
                      <div class="h2 tc-secondary projects-filter__title"><?= wp_kses_post( $settings['filter_label'] ) ?>:</div>
					<?php endif; ?>

                  <div class="projects-filter__tags">

					  <?php if ( ! empty( $filter_params ) ) : ?>
						  <?php foreach ( $filter_params as $index => $item ) : ?>
							  <?php if ( is_array( $item ) && ! empty( $item ) ) : ?>
								  <?php foreach ( $item as $value ) : ?>
									  <?php if ($index === 'locations') : ?>
                      <span data-type="<?= esc_attr( $index ) ?>" data-id="<?= esc_attr( $value ) ?>"><?= constant( 'self::STATES' )[ strtoupper($value) ] ?><button type="button"></button></span>
									  <?php elseif ($index === 'project_sports') : ?>
							        <?php $sport_title = get_the_title( $value ) ?>
                      <span data-type="<?= esc_attr( $index ) ?>" data-id="<?= esc_attr( $value ) ?>"><?= esc_html($sport_title) ?><button type="button"></button></span>
									  <?php else : ?>
                                <span data-type="<?= esc_attr( $index ) ?>" data-id="<?= esc_attr( $value ) ?>"><?= constant( 'self::' . strtoupper( $index ) )[ $value ] ?><button type="button"></button></span>
									  <?php endif; ?>
								  <?php endforeach; ?>
							  <?php endif; ?>
						  <?php endforeach; ?>
					  <?php endif; ?>

                  </div>

                  <button type="button" class="mt-075 projects-filter__button projects-filter__clear">Clear all</button>

                  <div class="mt-150 h5">
					  <?php if ( $settings['post_types'] === 'projects' ) : ?>
                    <button class="projects-filter__toggle active">Project Type</button>
                    <div class="projects-filter__spoiler">

						<?php foreach ( self::PROJECT_FIELD_TYPE as $index => $type ) : ?>
							<?php $checked = $filter_params['project_field_type'] ?? []; ?>
                          <div class="projects-filter__check" data-type="project_field_type" data-id="<?= esc_attr( $index ) ?>">
                            <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                            <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                            <div></div>
                          </div>
						<?php endforeach; ?>

                    </div>

                    <button class="projects-filter__toggle active">Facility Type</button>
                    <div class="projects-filter__spoiler">

						<?php foreach ( self::FACILITY_TYPE as $index => $type ) : ?>
							<?php $checked = $filter_params['facility_type'] ?? []; ?>
                          <div class="projects-filter__check" data-type="facility_type" data-id="<?= esc_attr( $index ) ?>">
                            <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                            <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                            <div></div>
                          </div>
						<?php endforeach; ?>

                    </div>

                    <button class="projects-filter__toggle active">Sport</button>
                    <div class="projects-filter__spoiler">

						<?php if ( is_array( $sports_fields ) && ! empty( $sports_fields ) ) : ?>
							<?php foreach ( $sports_fields['posts'] as $sport ) : ?>
								<?php $checked = $filter_params['project_sports'] ?? []; ?>
								<?php $sport_title = get_the_title( $sport ) ?>

                            <div class="projects-filter__check" data-type="project_sports" data-id="<?= $sport ?>">
                              <input type="checkbox" id="<?= $sport ?>" <?php checked( in_array( $sport, $checked ) ); ?>>
                              <label for="<?= $sport ?>"><?= $sport_title ?></label>
                              <div></div>
                            </div>

							<?php endforeach; ?>
						<?php endif; ?>

                    </div>

                    <div class="mt-150 tc-white">Other Filters</div>
                    <button class="projects-filter__toggle">Location / State</button>
                    <div class="projects-filter__spoiler">
						<?php if ( ! empty( $locations ) ) : ?>
							<?php foreach ( $locations as $key => $value ) : ?>
								<?php $checked = $filter_params['locations'] ?? []; ?>
                            <div class="projects-filter__check" data-type="locations" data-id="<?= esc_attr( strtolower( ucfirst( $key ) ) ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $key ) ?>" <?php checked( in_array( strtolower( ucfirst( $key ) ), $checked ) ); ?>>
                              <label for="<?= esc_attr( $key ) ?>"><?= esc_html( $value ) ?></label>
                              <div></div>
                            </div>
							<?php endforeach; ?>
						<?php endif; ?>
                    </div>
                    <button class="projects-filter__toggle">Content Type</button>
                    <div class="projects-filter__spoiler">
						<?php foreach ( self::CONTENT_TYPE as $index => $type ) : ?>
							<?php $checked = $filter_params['content_type'] ?? []; ?>
                          <div class="projects-filter__check" data-type="content_type" data-id="<?= esc_attr( $index ) ?>">
                            <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                            <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                            <div></div>
                          </div>
						<?php endforeach; ?>
                    </div>
                    <button class="projects-filter__toggle">Service Type</button>
                    <div class="projects-filter__spoiler">

						<?php foreach ( self::SERVICES_TYPE as $index => $type ) : ?>
							<?php $checked = $filter_params['services_type'] ?? []; ?>
                          <div class="projects-filter__check" data-type="services_type" data-id="<?= esc_attr( $index ) ?>">
                            <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                            <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                            <div></div>
                          </div>
						<?php endforeach; ?>

                    </div>
                    <button class="projects-filter__toggle">Out/In</button>
                    <div class="projects-filter__spoiler">

						<?php foreach ( self::OUTDOORSINDOORS as $index => $type ) : ?>
							<?php $checked = $filter_params['outdoorsindoors'] ?? []; ?>
                          <div class="projects-filter__check" data-type="outdoorsindoors" data-id="<?= esc_attr( $index ) ?>">
                            <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                            <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                            <div></div>
                          </div>
						<?php endforeach; ?>

                    </div>
                  </div>

				<?php endif; ?>

					<?php if ( $settings['post_types'] === 'landscape_projects' ) : ?>
                      <button class="projects-filter__toggle active">Infill Used</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_INFILL as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_infill'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_infill" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
                      <button class="projects-filter__toggle active">Landscape Type</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_LANDSCAPE_TYPE as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_landscape_type'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_landscape_type" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
                      <button class="projects-filter__toggle active">Location</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_LOCATION as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_location'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_location" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
					<?php endif; ?>

					<?php if ( $settings['post_types'] === 'sport_projects' ) : ?>
                      <button class="projects-filter__toggle active">Infill Used</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_INFILL as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_infill'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_infill" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
                      <button class="projects-filter__toggle active">Sport Type</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_SPORTS_TYPE as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_sports_type'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_sports_type" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
                      <button class="projects-filter__toggle active">Location</button>
                      <div class="projects-filter__spoiler">

						  <?php foreach ( self::PROJECT_LOCATION as $index => $type ) : ?>
							  <?php $checked = $filter_params['project_location'] ?? []; ?>
                            <div class="projects-filter__check" data-type="project_location" data-id="<?= esc_attr( $index ) ?>">
                              <input type="checkbox" id="<?= esc_attr( $index ) ?>" <?php checked( in_array( $index, $checked ) ); ?>>
                              <label for="<?= esc_attr( $index ) ?>"><?= esc_html( $type ) ?></label>
                              <div></div>
                            </div>
						  <?php endforeach; ?>

                      </div>
					<?php endif; ?>

                  <button type="button" class="projects-filter__apply">Apply</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="float-bar" class="float-bar removed-sm">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic">
            <div class="grid-col">
				<?php if ( isset( $settings['title'] ) ) : ?>
                  <div class="h2"><?= esc_html( $settings['title'] ) ?></div>
				<?php endif; ?>
            </div>
            <div class="grid-col grid-col--auto">
              <div class="careers-nav">

				  <?php if ( isset( $settings['link_text'] ) && ! empty( $settings['cta_link']['url'] ) ) : ?>
                    <a href="<?= esc_url( $settings['cta_link']['url'] ) ?>"
                       class="careers-nav__link" <?php echo $this->get_render_attribute_string( 'cta_link' ); ?>>
						<?= esc_html( $settings['link_text'] ) ?>
                    </a>
				  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>


      <div id="filter-bar" class="filter-bar">
        <div class="grid-cont grid-cont--xl">
          <div class="grid-row grid-row--aic grid-row--smg">
            <div class="grid-col">
              <input type="text" placeholder="Search Projects" class="search-input projects-filter__search">
            </div>
            <div class="grid-col grid-col--auto">
              <div class="careers-nav">

				  <?php if ( isset( $settings['filter_label'] ) ) : ?>
                    <button type="button" class="careers-nav__link"><?= wp_kses_post( $settings['filter_label'] ) ?></button>
				  <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div>


		<?php
	}
}


