<?php
if ( !class_exists( 'MJCaroussel' ) ) {
	class MJCaroussel {

		/**
			* Textdomain used for translation & for created custom post type. Use the set_textdomain() method to set a custom textdomain.
			*
			* @var string $textdomain
			*/
			private static $textdomain = 'mjcaroussel';


		/**
			* Gallery is a name to create a taxonomy. Use the set_taxo() method to set a custom textdomain.
			*
			* @var string $taxo
			*/
			private static $taxo = 'gallery';


		/**
			* Global used to init class with singleton function
			*
			* @var null
			*/
			private static $_instance = null;


		/**
			* Constructor
			*
			*/
			public function __construct() {
				// Load up the localization file if we're using WordPress in a different language
	      load_plugin_textdomain( self::$textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

				//Create custom post types
				add_action( 'init', array( &$this, 'create_custom_post_type' ) );

				//Create taxonomy
				add_action( 'init', array( &$this, 'create_taxonomy' ) );

	      //Reorder metabox (thumbnail)
	      add_action( 'admin_init', array( &$this, 'reorder_meta_box' ) );

	      //Create shortcode
	      add_shortcode( self::$textdomain, array( &$this, 'shortcode' ) );

				//Add SCRIPT & CSS
				add_action( 'wp_enqueue_scripts', array( &$this, 'register_script_front' ) );

				//Enable thumbnail
				add_theme_support( 'post-thumbnails' );

				//styles to admin dashboard
				add_action( 'admin_enqueue_scripts', array( &$this, 'styles_admin_dashboard' ) );

				//tinymce
				add_action( 'wp_ajax_taxo_list_ajax', array( $this, 'taxo_list_ajax' ));
				add_action( 'wp_ajax_nopriv_taxo_list_ajax', array( $this, 'taxo_list_ajax' ));

				add_action( 'admin_init', array( &$this, 'add_tinymce_button' ) );
				add_action( 'admin_footer', array( &$this, 'get_admin_mjcaroussel' ) );

	    }

			public function styles_admin_dashboard() {
				wp_enqueue_style( 'mjcaroussel', plugins_url( self::$textdomain . '/public/css/admin_mjcaroussel.css' ), array(), '1.0.0', false );
			}

	  /**
			*
			* Function reorder_meta_box
			*
			* @return N/A
			*/
	    public function reorder_meta_box() {
	      $id_user = get_current_user_id();

				$value = array(
				    'side' => 'submitdiv,gallerydiv',
				    'normal' => 'slugdiv,postimagediv',
				    'advanced' => '',
				);
				add_user_meta( $id_user, 'meta-box-order_mjcaroussel', $value );

	      add_user_meta( $id_user, 'screen_layout_mjcaroussel', 2 );
	    }


		/**
			*
			* Singleton
			*
			* @return instance class
			*/
		  public static function get_instance() {
		  	if( self::$_instance === null ){
		  		self::$_instance = new MJCaroussel;
		  	}

		  	return self::$_instance;
		  }


		/**
			*
			* Function plugin_activation
			*
			* @return N/A
			*/
			public function plugin_activation() {
	      if ( version_compare( $GLOBALS['wp_version'], MJCAROUSSEL_MINIMUM_WP_VERSION, '<' ) ) {
	        $message = '<strong>' . sprintf(esc_html__( 'MJCaroussel %s requires WordPress %s or higher.' , 'mjcaroussel'), MJCAROUSSEL_VERSION, MJCAROUSSEL_MINIMUM_WP_VERSION ). '</strong>';
	  		}
			}


		/**
			*
			* Function plugin_deactivation
			*
			* @return N/A
			*/
			public function plugin_desactivation() {
			}


		/**
			* Register Post Type
			*
			* @see http://codex.wordpress.org/Function_Reference/register_post_type
			*/
			public function create_custom_post_type() {
				$textdomain = self::$textdomain;

			  $labels = array(
		      'name' 								=> __( 'MjCaroussel', 'mjcaroussel' ),
		      'singular_name' 			=> __( 'MjCaroussel', 'mjcaroussel' ),
		      'add_new' 						=> __( 'Add new image', 'mjcaroussel' ),
		      'add_new_item' 				=> __( 'Add new image', 'mjcaroussel' ),
		      'edit_item' 					=> __( 'Edit', 'mjcaroussel' ),
		      'new_item' 						=> __( 'New image', 'mjcaroussel' ),
		      'view_item' 					=> __( 'See the image', 'mjcaroussel' ),
		      'search_items' 				=> __( 'Find a image', 'mjcaroussel' ),
		      'not_found' 					=> __( 'No image(s) found',  'mjcaroussel' ),
		      'not_found_in_trash' 	=> __( 'No image(s) in the trash', 'mjcaroussel' ),
		    	'menu_name' 					=> __( 'MjCaroussel', 'mjcaroussel' )
			  );

				if ( ! post_type_exists( $textdomain ) ) {
			    register_post_type(
						$textdomain,
						array(
			        'labels' 						=> $labels,
			        'public' 						=> true,
			        'capability_type' 	=> 'post',
			        'show_in_nav_menus' => true,
			        'show_ui' 					=> true,
	            'menu_position'     => 40,
			        'query_var' 				=> true,
			        'hierarchical' 			=> false,
			        'menu_icon' 				=> 'dashicons-smiley',
			        'rewrite'  					=> array( 'slug' => $textdomain, 'with_front' => false ),
			        'has_archive' 			=> $textdomain,
	            'supports'          => array( 'title', 'thumbnail' )
						)
					);
				}
			}


		/**
			* Register taxonomy
			*
			* @see http://codex.wordpress.org/Function_Reference/register_taxonomy
			*/
			public function create_taxonomy() {
	      $textdomain = self::$textdomain;
	      $taxo = self::$taxo;

				$labels = array(
		        'name'                       => __( 'Gallery', 'mjcaroussel' ),
		        'singular_name'              => __( 'Gallery', 'mjcaroussel' ),
		        'search_items'               => __( 'Search Galleries', 'mjcaroussel' ),
		        'popular_items'              => __( 'Popular Galleries', 'mjcaroussel' ),
		        'all_items'                  => __( 'All Galleries', 'mjcaroussel' ),
		        'parent_item'                => null,
		        'parent_item_colon'          => null,
		        'edit_item'                  => __( 'Edit Gallery', 'mjcaroussel' ),
		        'update_item'                => __( 'Update Gallery', 'mjcaroussel' ),
		        'add_new_item'               => __( 'Add a Gallery', 'mjcaroussel' ),
		        'new_item_name'              => __( 'Add a name Gallery', 'mjcaroussel' ),
		        'separate_items_with_commas' => __( 'Separate Galleries with commas', 'mjcaroussel' ),
		        'add_or_remove_items'        => __( 'Add or remove a Gallery', 'mjcaroussel' ),
		        'choose_from_most_used'      => __( 'Choose The most used', 'mjcaroussel' ),
		        'not_found'                  => __( 'Not found', 'mjcaroussel' ),
		        'menu_name'                  => __( 'Galleries', 'mjcaroussel' )
		    );

		    register_taxonomy(
					$taxo,
					array( $textdomain ),
					array(
		        'hierarchical' 			=> true,
		        'show_ui' 					=> true,
		        'show_admin_column' => true,
		        'query_var' 				=> true,
		        'labels' 						=> $labels,
		        'rewrite' 					=> array( 'slug' => $taxo, 'with_front' => false  )
		    	)
				);
			}


		/**
			*
			* Function register_script_front
			*
			* @return N/A
			*/
			public function register_script_front() {
				//CSS
		    wp_register_style( 'mjcaroussel', plugins_url( 'public/css/mjcaroussel.min.css', __FILE__ ), array(), '', false );
				//scripts
		    wp_register_script( 'slickslider', plugins_url( 'public/js/slick.min.js', __FILE__ ), array(), SLICKSLIDER_VERSION, true );
				wp_register_script( 'mjcaroussel-functions', plugins_url( 'public/js/mjcaroussel.min.js', __FILE__ ), array(), MJCAROUSSEL_VERSION, true );
			}


    /**
      * Shortcode
      *
      * @param array $atts
      * @return html code
      */
      public function shortcode( $atts ) {
        $atts = shortcode_atts( array( 'id' => '1' ), $atts );
        $textdomain = self::$textdomain;

        $args = array(
        	'post_type' => $textdomain,
        	'tax_query' => array(
        		array(
        			'taxonomy' => self::$taxo,
        			'field'    => 'term_id',
        			'terms'    => $atts,
        		),
        	),
        );

        $query = new WP_Query( $args );

        $html = '';
        if ( $query->have_posts() ) {
					//Load scripts
					wp_enqueue_style( 'mjcaroussel' );
					wp_enqueue_script( 'slickslider' );
					wp_enqueue_script( 'mjcaroussel-functions' );

          $id_CSS = $textdomain . '_' . get_the_ID();

          $html .= '<div class="' . $textdomain . '" id="' . $id_CSS . '">';
            while ( $query->have_posts() ) {
              $query->the_post();
              $html .= '<div><span class="caption">' . get_the_title() . '</span><a href="' . get_permalink() . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '">' . get_the_post_thumbnail( get_the_ID(), 'large' ) . '</a></div>';
            }
          $html .= '</div>';
        }

        return $html;
      }


		/**
      * add_tinymce_button
      *
      * @return null
      */
			public function add_tinymce_button() {
				if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		      return false;
		    }

			  if ( get_user_option('rich_editing') == 'true') {
					add_filter( 'mce_external_plugins', array( &$this, 'tinymce_button_control' ) );
					add_filter( 'mce_buttons', array( &$this, 'register_tinymce_button' ) );
				}
			}


		/**
      * register_tinymce_button
      *
      * @param array $buttons
      * @return array
      */
			public function register_tinymce_button( $buttons ) {
				array_push( $buttons, '|', 'mjcaroussel_button' );
				return $buttons;
			}


		/**
      * tinymce_button_control
      *
      * @param array $plugin_array
      * @return array
      */
			public function tinymce_button_control( $plugin_array ) {
				$plugin_array['scriptmjcaroussel'] = plugins_url( 'public/js/tinymce.js', __FILE__ );
				return $plugin_array;
			}


		/**
			* get_admin_mjcaroussel
			*
			* @return null
			*/
			function get_admin_mjcaroussel() {
				// create nonce
				global $pagenow;
				if( $pagenow != 'admin.php' ){
					?><script type="text/javascript">
			    var list;
			        jQuery.ajax({
			          method: "POST",
			          url: ajaxurl,
			          dataType: 'JSON',
			          data: { 'action': 'taxo_list_ajax'}
			        }).done(function(data) {
			          list = data;
			        });
					</script>
			<?php
				}
			}


		/**
      * taxo_list_ajax
      *
      * @return array
      */
			public function taxo_list_ajax() {
				$args = array(
					'post_type' => self::$textdomain,
					'order' => 'ASC',
					'posts_per_page' => -1,
				);

				$terms = get_terms( array( 'taxonomy' => self::$taxo, 'hide_empty' => false ) );

				foreach ($terms as $key => $term) {
					$tab[] = array('text' => $term->name, 'value' => $term->term_id);
					$i++;
				}

				wp_reset_query();

				wp_send_json($tab);

				die();
			}
	}
}
