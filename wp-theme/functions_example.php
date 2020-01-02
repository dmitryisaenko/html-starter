<?php
/**
 * fco functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fco
 */
ob_start();

if ( ! function_exists( 'fco_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fco_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on fco, use a find and replace
		 * to change 'fco' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fco', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size('fco-news-logo-1140px', 1140, 9999);
		add_image_size('fco-news-logo-300px', 274, 156, true);
		add_image_size('fco-players-logo-big', 9999, 380);
		add_image_size('fco-players-logo-small', 9999, 220);
		add_image_size('fco-club-logo', 150, 150);
		add_image_size('fco-club-logo-small', 40, 40);

		//Фильтр, который НЕ загружает указанные размеры изображений для указанных типов постов:
		add_filter( 'intermediate_image_sizes_advanced', function( $sizes ){
			if( isset( $_REQUEST['post_id'] ) && 'post' == get_post_type($_REQUEST['post_id'] ) ) {
				unset( $sizes['fco-players-logo-big'] );
				unset( $sizes['fco-players-logo-small'] );
				unset( $sizes['fco-club-logo'] );
				unset( $sizes['fco-club-logo-small'] );
			}
			if( isset( $_REQUEST['post_id'] ) && 'team' == get_post_type($_REQUEST['post_id'] ) ) {
				unset( $sizes['fco-news-logo-1140px'] );
				unset( $sizes['fco-news-logo-300px'] );
				unset( $sizes['fco-club-logo'] );
				unset( $sizes['fco-club-logo-small'] );
				unset( $sizes['medium'] );
				unset( $sizes['large'] );
				unset( $sizes['thumb'] );
				unset( $sizes['post-thumbnail'] );
				
			}
			if( isset( $_REQUEST['post_id'] ) && 'clubs' == get_post_type($_REQUEST['post_id'] ) ) {
				unset( $sizes['fco-players-logo-big'] );
				unset( $sizes['fco-players-logo-small'] );
				unset( $sizes['fco-news-logo-1140px'] );
				unset( $sizes['fco-news-logo-300px'] );
				unset( $sizes['medium'] );
				unset( $sizes['large'] );
				unset( $sizes['thumb'] );
				unset( $sizes['post-thumbnail'] );
			}
			return $sizes;
		 
		} );
		
		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'main-menu', 'Меню шаблона' );

		//Use Walker for customization mobile menu
		class Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
			public function start_lvl( &$output, $depth = 0, $args = array() ) {
				if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
					$t = '';
					$n = '';
				} else {
					$t = "\t";
					$n = "\n";
				}
				$indent = str_repeat( $t, $depth );
		
				// Default class.
				$classes = array( 'sub-menu' );
		
				/**
				 * Filters the CSS class(es) applied to a menu list element.
				 *
				 * @since 4.8.0
				 *
				 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
				 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
				 * @param int      $depth   Depth of menu item. Used for padding.
				 */
				$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
				$output .= "{$n}{$indent}<span class='sidebar-menu-arrow'></span><ul$class_names>{$n}";
			}
		}

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'fco_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );


		
	}
endif;
add_action( 'after_setup_theme', 'fco_setup' );



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fco_widgets_init() {
	register_sidebar( array(
		'name'          => 'Sidebar основний',
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	register_sidebar( array(
		'name'          => 'Sidebar для новин',
		'id'            => 'sidebar-news',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	register_sidebar( array(
		'name'          => 'Sidebar для учасників',
		'id'            => 'sidebar-team',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	
}
add_action( 'widgets_init', 'fco_widgets_init' );


function add_some_std_metaboxes() {
	add_post_type_support('post', 'postcustom '); // цитата
}
add_action('init', 'add_some_std_metaboxes');


/**
 * Enqueue scripts and styles.
 */
function fco_scripts() {
	// wp_enqueue_style( 'fco-style', get_stylesheet_uri() );
	wp_enqueue_style( 'fco-normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
	wp_enqueue_style( 'fco-fonts', get_template_directory_uri() . '/assets/css/fonts.css' );
	wp_enqueue_style( 'fco-fontawesome-bootstrap', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'fco-fontawesome', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css' );
	wp_enqueue_style( 'fco-slick', get_template_directory_uri() . '/assets/libs/slick/slick.css' );
	wp_enqueue_style( 'fco-slick-theme', get_template_directory_uri() . '/assets/libs/slick/slick-theme.css' );
	wp_enqueue_style( 'fco-animate', get_template_directory_uri() . '/assets/css/animate.min.css' );
	wp_enqueue_style( 'fco-main', get_template_directory_uri() . '/assets/css/main.css' );
	wp_enqueue_style( 'fco-media', get_template_directory_uri() . '/assets/css/media.css' );

	add_action( 'wp_enqueue_scripts', 'my_scripts_method', 11 );
	function my_scripts_method() {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_template_directory_uri() . '/assets/libs/jquery-3.2.1.min.js', '', '', true );
		wp_enqueue_script( 'jquery' );
	}
	wp_enqueue_script( 'fco-common', get_template_directory_uri() . '/assets/js/common.js', array('jquery'), '', true );
	wp_enqueue_script( 'fco-slick', get_template_directory_uri() . '/assets/libs/slick/slick.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'fco-carousel', get_template_directory_uri() . '/assets/js/carousel.js', array('jquery'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fco_scripts' );

//Debug mode
define('WP_DEBUG', false);

//Add plugin TGM
require_once dirname( __FILE__ ) . '/inc/tgm/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'fco_register_required_plugins' );
 
function fco_register_required_plugins() {
 
    $plugins = array( 
		array(
			'name'      => 'Unyson',
			'slug'      => 'unyson',
			'required'  => true,
		),
		array(
			'name'      => 'Classic Editor',
			'slug'      => 'classic-editor',
			'required'  => true,
		),
		array(
			'name'      => 'Advanced Custom Fields',
			'slug'      => 'advanced-custom-fields',
			'required'  => true,
		)
	 );
    $config = array( 
		'dismissable'  => true,
		'is_automatic' => true,  
	 );
 
    tgmpa( $plugins, $config );
 
}

add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type('team', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Учасники команди', // основное название для типа записи
			'singular_name'      => 'Команда', // название для одной записи этого типа
			'add_new'            => 'Додати учасника', // для добавления новой записи
			'add_new_item'       => 'Додати учасника', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редагувати дані учасника', // для редактирования типа записи
			'new_item'           => 'Новий учасник', // текст новой записи
			'view_item'          => 'Учасники', // для просмотра записи этого типа.
			'search_items'       => 'Шукати учасника', // для поиска по этим типам записи
			'not_found'          => 'Не знайдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не знайдено в корзині', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'ФК Олександрія', // название меню
		),
		'description'         => '',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		// 'exclude_from_search' => null, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => get_template_directory_uri().'/assets/img/fco-logo-micro.png', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => true,
		'supports'            => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => ['age-group,role,player_category'],
		'has_archive'         => false,
		// 'rewrite'             => array('slug' => 'team'),
		'query_var'           => true,
	) );

	register_post_type('match', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Календар турнірів', // основное название для типа записи
			'singular_name'      => 'Турнір', // название для одной записи этого типа
			'add_new'            => 'Додати турнір', // для добавления новой записи
			'add_new_item'       => 'Додати турнір', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редагувати турнір', // для редактирования типа записи
			'new_item'           => 'Новий турнір', // текст новой записи
			'view_item'          => 'Турніри', // для просмотра записи этого типа.
			'search_items'       => 'Шукати турнір', // для поиска по этим типам записи
			'not_found'          => 'Не знайдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не знайдено в корзині', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Календар турнірів', // название меню
		),
		'description'         => '',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		'exclude_from_search' => true, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-sos', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => true,
		'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => ['age-groupe-match', 'season','tournament','stadium'],
		'has_archive'         => true,
		// 'rewrite'             => array('slug' => 'team'),
		'query_var'           => true,
	) );

	register_post_type('clubs', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Футбольні команди', // основное название для типа записи
			'singular_name'      => 'Футбольна команда', // название для одной записи этого типа
			'add_new'            => 'Додати футбольну команду', // для добавления новой записи
			'add_new_item'       => 'Додати футбольну команду', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редагувати футбольну команду', // для редактирования типа записи
			'new_item'           => 'Нова футбольна команда', // текст новой записи
			'view_item'          => 'Футбольні команди', // для просмотра записи этого типа.
			'search_items'       => 'Шукати футбольну команду', // для поиска по этим типам записи
			'not_found'          => 'Не знайдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не знайдено в корзині', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Футбольні команди', // название меню
		),
		'description'         => '',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		'exclude_from_search' => true, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-id-alt', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => true,
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => null,
		'has_archive'         => false,
		// 'rewrite'             => array('slug' => 'team'),
		'query_var'           => true,
	) );

}

add_action( 'init', 'create_team_taxonomies' );

function create_team_taxonomies(){
	register_taxonomy('age-group', array('team'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Вікова група',
			'singular_name'     => 'Вікова група',
			'search_items'      => 'Пошук групи',
			'all_items'         => 'Усі групи',
			'edit_item'         => 'Редагувати групу',
			'update_item'       => 'Оновити групу',
			'add_new_item'      => 'Додати групу',
			'new_item_name'     => 'Нова група',
			'menu_name'         => 'Вікова група',
		),
		'show_ui'       => true,
		'show_admin_column' => true,
		'show_in_menu'	=> false,
		'query_var'     => true,
		// 'rewrite'       => array( 'slug' => 'team', "with_front" => false ), // свой слаг в URL
	));

	register_taxonomy('age-group-match', array('match'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Вікова група',
			'singular_name'     => 'Вікова група',
			'search_items'      => 'Пошук групи',
			'all_items'         => 'Усі групи',
			'edit_item'         => 'Редагувати групу',
			'update_item'       => 'Оновити групу',
			'add_new_item'      => 'Додати групу',
			'new_item_name'     => 'Нова група',
			'menu_name'         => 'Вікова група',
		),
		'show_ui'       => true,
		'show_admin_column' => true,
		'show_in_menu'	=> true,
		'query_var'     => true,
		'rewrite'       => array( 'slug' => 'age-group-match', "with_front" => false ), // свой слаг в URL
	));
	
	register_taxonomy('role', array('team'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Роль',
			'singular_name'     => 'Роль',
			'search_items'      => 'Пошук ролі',
			'all_items'         => 'Усі ролі',
			'edit_item'         => 'Редагувати роль',
			'update_item'       => 'Оновити роль',
			'add_new_item'      => 'Додати роль',
			'new_item_name'     => 'Нова роль',
			'menu_name'         => 'Роль',
		),
		'show_ui'       => true,
		'show_in_menu'	=> false,
		'show_admin_column' => true,
		'query_var'     => true,
		// 'rewrite'       => array( 'slug' => 'team', "with_front" => false ), // свой слаг в URL
	));

	register_taxonomy('player_category', array('team'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Амплуа гравця',
			'singular_name'     => 'Амплуа гравця',
			'search_items'      => 'Пошук амплуа',
			'all_items'         => 'Усі амплуа',
			'edit_item'         => 'Редагувати амплуа',
			'update_item'       => 'Оновити амплуа',
			'add_new_item'      => 'Додати амплуа',
			'new_item_name'     => 'Нове амплуа',
			'menu_name'         => 'Амплуа гравця',
		),
		'show_ui'       => false,
		'show_in_menu'	=> false,
		// 'show_admin_column' => true,
		'query_var'     => true,
		'rewrite'       => array( 'slug' => 'player_category' ), // свой слаг в URL
		'show_in_rest' => true
	));
	register_taxonomy('season', array('match'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Сезон',
			'singular_name'     => 'Сезон',
			'search_items'      => 'Пошук сезону',
			'all_items'         => 'Усі сезони',
			'edit_item'         => 'Редагувати сезон',
			'update_item'       => 'Оновити сезон',
			'add_new_item'      => 'Додати сезон',
			'new_item_name'     => 'Новий сезон',
			'menu_name'         => 'Сезон',
		),
		'show_ui'       => true,
		'show_admin_column' => true,
		'show_in_menu'	=> true,
		'query_var'     => true,
		// 'rewrite'       => array( 'slug' => 'team', "with_front" => false ), // свой слаг в URL
	));
	register_taxonomy('tournament', array('match'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Турнір',
			'singular_name'     => 'Турнір',
			'search_items'      => 'Пошук турніру',
			'all_items'         => 'Усі турніри',
			'edit_item'         => 'Редагувати турнір',
			'update_item'       => 'Оновити турнір',
			'add_new_item'      => 'Додати турнір',
			'new_item_name'     => 'Новий турнір',
			'menu_name'         => 'Турнір',
		),
		'show_ui'       => true,
		'show_admin_column' => true,
		'show_in_menu'	=> true,
		'query_var'     => true,
		// 'rewrite'       => array( 'slug' => 'team', "with_front" => false ), // свой слаг в URL
	));
	register_taxonomy('stadium', array('match'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => 'Стадіон',
			'singular_name'     => 'Стадіон',
			'search_items'      => 'Пошук стадіону',
			'all_items'         => 'Усі стадіони',
			'edit_item'         => 'Редагувати стадіон',
			'update_item'       => 'Оновити стадіон',
			'add_new_item'      => 'Додати стадіон',
			'new_item_name'     => 'Новий стадіон',
			'menu_name'         => 'Стадіони',
		),
		'show_ui'       => true,
		'show_admin_column' => true,
		'show_in_menu'	=> true,
		'query_var'     => true,
		// 'rewrite'       => array( 'slug' => 'team', "with_front" => false ), // свой слаг в URL
	));
	
}

add_theme_support( 'post-formats', array( 'video', 'gallery' ) );

//Делаем возможность фильтровать записи с дополнительными фильтрами (таксономией):
add_action( 'restrict_manage_posts', 'filter_by_taxonomies' , 10, 2);
function filter_by_taxonomies( $post_type, $which ) {

	// Apply this only on a specific post type
	if ( $post_type === 'team' ){
		// A list of taxonomy slugs to filter by
		$taxonomies = array( 'age-group', 'role', 'player_category' );
	}
	elseif ( $post_type === 'match' ){
		// A list of taxonomy slugs to filter by
		$taxonomies = array( 'age-group-match', 'season', 'tournament', 'stadium' );
	}
	else return;

	foreach ( $taxonomies as $taxonomy_slug ) {
		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->all_items;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
		echo '<option value="">' . $taxonomy_name . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>';
	}

}

//Выводим ID статьи в таблице со списком статей
add_filter('manage_post_posts_columns', 'add_id_column');
function add_id_column($columns) {
	$columns = $columns + array("id" => "ID");
	return $columns;
}
add_action( "manage_post_posts_custom_column", 'id_column_content', 10, 3);
function id_column_content( $column_name ){
    if ($column_name === 'id') {
		echo get_the_ID();
    }
}




//Show img in Team table
add_filter('manage_team_posts_columns', 'add_teamImg_column');
function add_teamImg_column($columns) {
	$columns = array_slice($columns, 0, 1, true) + array("img_attached" => "Фото") + array_slice($columns, 1, count($columns) - 1, true);
	return $columns;
}

add_action ( 'manage_team_posts_custom_column', 'teamImg_custom_column', 10, 2 );
function teamImg_custom_column ( $column, $post_id ) {
	if ( $column === 'img_attached' ) {
		$term_id = get_post_meta ( $post_id, 'team_photo', true );
		echo '<a href="' . get_edit_post_link() . '">';
		echo wp_get_attachment_image($term_id, [64,64], '');
		echo '</a> ';
	}
}

add_filter('admin_head', 'dashtable_style');
function dashtable_style(){
	$column_name = 'img_attached';
	echo "<style>.column-$column_name{width:10%;}</style>";
}






//Выводим превьюшку участника в таблице со списком учасников клуба
add_filter('manage_team_posts_columns', 'add_img_column');
function add_img_column($columns) {
	$columns = array_slice($columns, 0, 1, true) + array("img_attached" => "Фото", 'player_number' => 'Номер гравця') + array_slice($columns, 1, count($columns) - 1, true) + array("player_category" => "Амплуа гравця");
	return $columns;
}
//Доп. функция для вывода полей ACF:
add_action ( 'manage_team_posts_custom_column', 'team_custom_column', 10, 2 );
function team_custom_column ( $column, $post_id ) {
	switch ( $column ) {
		case 'player_number':
		echo get_post_meta ( $post_id, 'player_number', true );
		break;
	case 'player_category':
		$term_id = get_post_meta ( $post_id, 'player_category', true );
		$term = get_term( $term_id, 'player_category' );
		echo '<a href="' . home_url() . '/wp-admin/edit.php?post_type=team&player_category=' . $term->slug .'">';
		echo $term->name;
		echo '</a>';
		break;
	case 'img_attached':
		$term_id = get_post_meta ( $post_id, 'member_photo_small', true );
		$member_name = get_post_meta ( $post_id, 'member_name', true );
		$member_familyname = get_post_meta ( $post_id, 'member_familyname', true );
		$fio = $member_familyname . " " . $member_name;
		echo '<a href="' . home_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit">';
		echo wp_get_attachment_image($term_id, [64,64], '', ['title' => $fio]);
		echo '</a>';
		break;
	case 'img_thumbnail': //Если нужно вывести именно картинку превью, а не присоедененную картинку
		echo '<a href="' . get_edit_post_link() . '">';
		echo get_the_post_thumbnail($post_id, 'admin-list-thumb');
		echo '</a>';
		break;

	}
}

//Выводим превьюшку поста в таблице со списком турниров
add_filter('manage_match_posts_columns', 'add_img_match_post_column');
function add_img_match_post_column($columns) {
	$columns = array_slice($columns, 0, 1, true) + array("tour_number" => "Тур", "is_home" => "Вдома?", "img_attached" => "Превью", 'tour_date' => 'Дата') + array_slice($columns, 1, count($columns) - 1, true) + array('photo' => 'Фотозвіт', 'video' => 'Відеозвіт');
	unset($columns['date']);
	return $columns;
}
//Доп. функция для вывода превью турнира:
add_action ( 'manage_match_posts_custom_column', 'post_maych_custom_column', 10, 2 );
function post_maych_custom_column ( $column, $post_id ) {
	$present = " style = 'color: green; font-size: 26px; vertical-align: middle; margin: 0px 20%;'";
	$notpresent = " style = 'color: red; font-size: 46px; vertical-align: middle; margin: 0px 20%;'";
	$terms = get_terms(['taxonomy' => 'season', 'fields' => 'id=>name']);
        
	switch ( $column ) {
		case 'tour_number':
			echo get_field('tour_number');
			break;
		case 'is_home' :
			if (get_field('tour_is_home')) echo "<span $present>+</span>";
			break;
		case 'img_attached':
			$id_post_zvit = get_field('tour_zvit_text');
			echo '<a href="' . get_edit_post_link() . '">';
			echo get_the_post_thumbnail($id_post_zvit, [64,64]);
			echo '</a>';
			break;
		case 'tour_date' :
			echo get_field('tour_date');
			break;
		case 'photo' :
			if (get_field('tour_zvit_photo')) echo "<span $present>+</span>"; else echo "<span $notpresent>-</span>";
			break;
		case 'video' :
			if (get_field('tour_zvit_video')) echo "<span $present'>+</span>"; else echo "<span $notpresent'>-</span>";
			break;
	}
}
//Сортируем турниры по дате
add_action( 'pre_get_posts', 'sort_tours_by_date' );
function sort_tours_by_date( $query ) {
	if(  is_admin() && $query->is_main_query() && is_post_type_archive('match') ){
		 $query->set( 'meta_key', 'tour_date' );
		 $query->set( 'orderby', 'meta_value' );
	}
}
 
//Выводим превьюшку лого в таблице со списком турниров
add_filter( "manage_edit-tournament_columns", 'custom_column_header', 10);
function custom_column_header( $columns ){
    $columns['tour_logo'] = 'Лого';
    return $columns;
}

add_action( "manage_tournament_custom_column", 'custom_column_content', 10, 3);
function custom_column_content( $value, $column_name, $term_id ){
    if ($column_name === 'tour_logo') {
		$thumb = get_field('liga-logo', 'category_'.$term_id);
		echo wp_get_attachment_image($thumb, 'thumbnail');
    }
    // return $columns;
}


//Доп. функция для вывода превью поста:
add_action ( 'manage_posts_custom_column', 'post_custom_column', 10, 2 );
function post_custom_column ( $column, $post_id ) {
	if ( $column === 'img_attached' ) {
		echo get_post_type( $post_id );
		echo '<a href="' . get_edit_post_link() . '">';
		echo get_the_post_thumbnail($post_id, [64,64]);
		echo '</a>';
	}
}




//Выводим превьюшку участника в таблице со списком слайдеров
add_filter('manage_wxas_slider_posts_columns', 'add_wxas_slider_column');
function add_wxas_slider_column($columns) {
	$columns = array_slice($columns, 0, 2, true) + array("slider_id" => "Слайдер") + array_slice($columns, 1, count($columns) - 1, true);
	return $columns;
}
//Доп. функция для вывода значений id слайдера:
add_action ( 'manage_wxas_slider_posts_custom_column', 'wxas_slider_custom_column', 10, 2 );
function wxas_slider_custom_column ( $column, $post_id ) {
	switch ( $column ) {
		case 'slider_id':
			echo '[wxas id="' . get_the_ID( ) . '"]';
			break;
	
	

	}
}

//Выводим превьюшку в таблице со списком клубов
add_filter('manage_clubs_posts_columns', 'add_img_club_column');
function add_img_club_column($columns) {
	$columns = array_slice($columns, 0, 1, true) + array("img_attached" => "Лого") + array_slice($columns, 1, count($columns) - 1, true);
	return $columns;
}

//Доп. функция для вывода превью лого клуба:
add_action ( 'manage_clubs_posts_custom_column', 'logo_custom_column', 10, 2 );
function logo_custom_column ( $column, $post_id ) {
	if ( $column === 'img_attached' ) {
		$term_id = get_post_meta ( $post_id, 'fclub_logo', true );
		echo '<a href="' . get_edit_post_link() . '">';
		echo wp_get_attachment_image($term_id, [64,64], '');
		echo '</a>';
	}
}

/* Подсчет количества посещений страниц
---------------------------------------------------------- */
add_action('wp_head', 'kama_postviews');
function kama_postviews() {

/* ------------ Настройки -------------- */
$meta_key       = 'viewss';  // Ключ мета поля, куда будет записываться количество просмотров.
$who_count      = 1;            // Чьи посещения считать? 0 - Всех. 1 - Только гостей. 2 - Только зарегистрированных пользователей.
$exclude_bots   = 1;            // Исключить ботов, роботов, пауков и прочую нечесть :)? 0 - нет, пусть тоже считаются. 1 - да, исключить из подсчета.

global $user_ID, $post;
	if(is_singular()) {
		$id = (int)$post->ID;
		static $post_views = false;
		if($post_views) return true; // чтобы 1 раз за поток
		$post_views = (int)get_post_meta($id,$meta_key, true);
		$should_count = false;
		switch( (int)$who_count ) {
			case 0: $should_count = true;
				break;
			case 1:
				if( (int)$user_ID == 0 )
					$should_count = true;
				break;
			case 2:
				if( (int)$user_ID > 0 )
					$should_count = true;
				break;
		}
		if( (int)$exclude_bots==1 && $should_count ){
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$notbot = "Mozilla|Opera"; //Chrome|Safari|Firefox|Netscape - все равны Mozilla
			$bot = "Bot/|robot|Slurp/|yahoo"; //Яндекс иногда как Mozilla представляется
			if ( !preg_match("/$notbot/i", $useragent) || preg_match("!$bot!i", $useragent) )
				$should_count = false;
		}

		if($should_count)
			if( !update_post_meta($id, $meta_key, ($post_views+1)) ) add_post_meta($id, $meta_key, 1, true);
	}
	return true;
}


//Шорт-коды
add_shortcode('fco_tags', 'fco_tags_function');
function fco_tags_function(){
	$header = '<div class="widget-title">Теги</div>';
	$tags = get_the_tag_list('<div class="widget-body"><div class="widget-tags-block"><span>', '</span><span>', '</span></div></div>');
	if ($tags) return $header.$tags;
	else return '';
}

add_shortcode('fco_anonse', 'fco_anonse_function');
function fco_anonse_function(){
	return showInfoBlock('futureTournament', 'main');
}

include_once('customization.php');


//Return html for team-request throw SHORT-code
add_shortcode('our_team', 'mogo_get_team_items');

function mogo_get_team_items($atts){
	$params = shortcode_atts( ['count' => -1], $atts );
	
	$args = [
		'post_type' => 'team',
		'orderby' => 'date',
		'order' => 'ASC',
		'posts_per_page'=> $params['count'],		
	];
	
	
	$query = new WP_Query( $args );
	
	$team = '';
	if ( $query->have_posts() ){
		while ( $query->have_posts() ) {
			$query->the_post();
			
			if (wp_count_posts('team')->publish > 3) {
				$more_button = '<div class="row justify-content-end">
					<a href="' . home_url() . '/allteamposts" class="btn btn-success" style="margin-bottom: 51px;">More persons...</a>
				</div>';
			}
			else $more_button = '';
			
			$team .= '<div itemscope itemtype="http://schema.org/Person" class="col-md-4 mx-auto">';
				$team .= '<div class="ourTeam-item mx-auto d-block">';
					$team .= '<div class="ourTeam-item__img">';
						$team .= '<a href="#">';
							$team .= '<img itemprop="image" src="' . get_field('team_photo')['sizes']['mogo-team-img']  . '" alt="' . get_field('team-name') . '">';
						$team .= '</a>';
						$team .= '<div class="overlay">';
							$team .= '<div class="socials">';
								$team .= '<ul>';
									$team .= '<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>';
									$team .= '<li><a href="#"><i class="fab fa-twitter"></i></a></li>';
									$team .= '<li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>';
									$team .= '<li><a href="#"><i class="fab fa-instagram"></i></a></li>';
								$team .= '</ul>';
							$team .= '</div>';
						$team .= '</div>';
					$team .= '</div>';
					$team .= '<div class="ourTeam-item__descr">';
						$team .= '<div itemprop="name" class="ourTeam-item__descr-name">';
							$team .= get_field('team-name');
						$team .= '</div>';
						$team .= '<div itemprop="jobTitle" class="ourTeam-item__descr-role">';
							$team .= get_field('team-role');
						$team .= '</div>';
					$team .= '</div>';
				$team .= '</div>';
			$team .= '</div>';
			
			
		}
		$return = '<section class="ourTeam" id="ourTeam">';
				$return .='<div class="container">';
					$return .='<div class="section-header">';
						$return .='<h3>Who we are</h3>';
						$return .='<h2>Meet our team</h2>';
					$return .='</div>';
					$return .='<div class="ourTeam-subheader">';
						$return .='Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio assumenda dignissimos rem, magnam veritatis tempora, excepturi dolores temporibus, facere sint eveniet porro aut dolorem quo recusandae repellendus cumque debitis reiciendis. Earum quia provident vitae sit tenetur culpa illum et';
					$return .='</div>';
					$return .='<div class="row">';
					
					$return .= $team;
					
					$return .='</div>';
					$return .= $more_button;
				$return .='</div>';
				
		$return .='</section>';
		return $return;	
	}
	else echo '<h3 style="margin-bottom:20px;">Результатів на знайдено</h3>';
	wp_reset_query(); 
	wp_reset_postdata();
}

// удаляет H2 из шаблона пагинации
add_filter('navigation_markup_template', 'my_navigation_template', 10, 2 );
function my_navigation_template( $template, $class ){
	/*
	Вид базового шаблона:
	<nav class="navigation %1$s" role="navigation">
		<h2 class="screen-reader-text">%2$s</h2>
		<div class="nav-links">%3$s</div>
	</nav>
	*/

	return '
	<nav class="pagination-wrapper" role="navigation">
		%3$s
	</nav>    
	';
}

//Вывод бомбардиров в табличном виде по заданным критериям
function fco_view_scorers($age_group){
	$args = [
        'post_type' => 'team',
        'posts_per_page'=>-1,
		'tax_query' => [
			'relation' => 'AND',
			[
				'taxonomy' => 'age-group',
				'field'    => 'slug',
				'terms'    => $age_group,
			],
			[
				'taxonomy' => 'role',
				'field'    => 'slug',
				'terms'    => 'players',
			]
		],
		'meta_query' => [[
				'key' => 'fco_scorers',
				'value' => true
			]]
	];
	
	$query = new WP_Query( $args );
	if ( $query->have_posts() ){
		$result = '<table class="scorers-table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>М<span class="">\'ячів</span></th>
                    <th>М<span class="">\'ячів (з пен.)</span></th>
                    <th>Іг<span class="">ор</span></th>
                    <th>Хв<span class="">илини</span></th>
                    <th>УПЛ</th>
                    <th>К<span class="">убок</span> У<span class="">країни</span></th>
                    <th>Л<span class="">іга</span> Є<span class="">вропи</span></th>
                </tr>
            </thead>
            <tbody>';
		while ( $query->have_posts() ) {
			$query->the_post();
			$result .= '<tr>
                    <td class="scorer_img"><a href="' . get_the_permalink() . '"><img title="' . get_the_title() . '" src="' . get_field('member_photo_small') . '" alt="' . get_the_title() . '"></a></td>
                    <td class="scorer_name"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></td>
                    <td>' . get_field('fco_scorers_goals') . '</td>
                    <td>' . get_field('fco_scorers_goals_penalti') . '</td>
                    <td>' . get_field('fco_scorers_games') . '</td>
                    <td>' . get_field('fco_scorers_minutes') . '</td>
                    <td>' . get_field('fco_scorers_upl') . '</td>
                    <td>' . get_field('fco_scorers_uc') . '</td>
                    <td>' . get_field('fco_scorers_le') . '</td>
                </tr>';
		} //End WHILE
		$result .= '</tbody>
        </table>';
	} //End IF
	else return '<h3 style="margin-bottom:20px;">Результатів на знайдено</h3>';
	return $result;
}



//Вывод записей по указанным таксономиям и формирование кода для отображения на странице с участниками команды
function fco_member_items($age_group, $role, $player_category = '', $block_width = '23', $excluded = false){
	$meta_query = '';
	if ($excluded){
		$meta_query =
				[[
					'key' => 'fco_excluded',
					'value' => true
				]];
	}
		
	if ($role === 'players'){
		if ($age_group == 'main') {
            $meta_key = 'player_number'; 
            $orderby = 'meta_value_num';
        }
        else {
            $meta_key = 'member_familyname';
            $orderby = 'meta_value';
        }
		$args = [
			'post_type' => 'team',
			'meta_key' => $meta_key,
			'orderby' => $orderby,
			'order' => 'ASC',
            'posts_per_page'=>-1,
			'tax_query' => [
				'relation' => 'AND',
				[
					'taxonomy' => 'age-group',
					'field'    => 'slug',
					'terms'    => $age_group,
				],
				[
					'taxonomy' => 'role',
					'field'    => 'slug',
					'terms'    => $role,
				],
				[
					'taxonomy' => 'player_category',
					'field'    => 'slug',
					'terms'    => $player_category,
				]
			],
			'meta_query' => $meta_query
		];
	}
	else {
		$args = [
            'post_type' => 'team',
			'posts_per_page'=>-1,
			'meta_query' => [
				'member_role' => [
					'key' 		=> 'member_role',
				],
				'member_familyname' => [
					'key' 		=> 'member_familyname',
				],
            ],
            'orderby' 	=> [
                'member_role' => 'ASC',
                'member_familyname' => 'ASC'
            ],
			'tax_query' => [
				'relation' => 'AND',
				[
					'taxonomy' => 'age-group',
					'field'    => 'slug',
					'terms'    => $age_group,
				],
				[
					'taxonomy' => 'role',
					'field'    => 'slug',
					'terms'    => $role,
				]
			]
		];
	}
	$query = new WP_Query( $args );
	$img = '';
	if ( $query->have_posts() ){
		while ( $query->have_posts() ) {
			$query->the_post();
				$img = (get_field('member_photo_small')) ? get_field('member_photo_small') : get_template_directory_uri() . '/assets/img/no-photo-260-220.png';
				if ($role === 'players'){
					echo '<div class="w' . $block_width .' team-block-item">';
					echo '            <a href="' . get_the_permalink() . '">';
					echo '                <div class="team-block-item-img">';
					echo '                    <img class="img-responsive" src="' . $img . '" width="260" height="220" alt="">';
					echo '                </div>';
					echo '                <div class="team-block-item-content">';
					echo '                    <div class="team-block-item-content-fio-wrapper">';
					echo '                        <div class="team-block-item-content-name">';
					echo                             get_field('member_name');
					echo '                        </div>';
					echo '                        <div class="team-block-item-content-familyname">';
					echo                             get_field('member_familyname');
					echo '                        </div>';
					echo '                    </div>';
					
					if (get_field('player_number') != '') {
						echo '<div class="team-block-item-content-number">';
						echo get_field('player_number');
					}
					else {
						echo '<div class="team-block-item-content-number without-number">';
					}
					echo '                    </div>';
					echo '                </div>';
					echo '            </a>';
					echo '        </div>';
				}
				else {
					echo '<div class="w' . $block_width .' team-block-item">';
					echo '            <a href="' . get_the_permalink() . '">';
					echo '                <div class="team-block-item-img">';
					echo '                    <img class="img-responsive" src="' . $img . '" width="260" height="220" alt="' . get_field('member_familyname') . ' " " ' . get_field('member_name') . '">';
					echo '                </div>';
					echo '					<div class="team-block-item-content-nonplayers">';
					echo '				<div class="team-block-item-content-nonplayers-firstname">';
					echo                     get_field('member_name');
					echo '				</div>';
					echo '				<div class="team-block-item-content-nonplayers-familyname">';
					echo                             get_field('member_familyname');
					echo '				</div>';

					echo '				<div class="team-block-item-content-nonplayers-title">';
					echo                             get_field('member_role');
					echo '				</div>';
					echo '			</div>';
					echo '            </a>';
					echo '        </div>';
				}
		}
	}
	else echo '<h3 style="margin-bottom:20px;">Результатів на знайдено</h3>';
	wp_reset_query(); 
	wp_reset_postdata();
}



//Вывод превью записей по заданным критериям + пагинация
function fco_view_items($cat, $number_posts, $user_cat, $current_post_ID = '', $day = '', $month = '', $year = ''){
	global $wp_query;

	$args = array(
		'posts_per_page' => $number_posts,
		'post__not_in' => array($current_post_ID),
		'order' => 'DESC',
		'orderby' => 'date',
		'cat' => $cat,
		'paged' => get_query_var('paged') ?: 1,
		'year'     => $year,
		'monthnum' => $month,
		'day'      => $day,
	);

	$wp_query = new WP_Query( $args );

	if ( $wp_query->have_posts() ){
		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();
			
			if ($user_cat == '') {
				$category = get_the_category();
				$cat_name = $category[0]->cat_name;
			}
			else $cat_name = get_the_category_by_ID($user_cat);
			
			if (get_post_format() === "video") $postFormat = 'youtube-news';
			elseif (get_post_format() === "gallery") $postFormat = 'foto-news';
			else $postFormat = 'self-news';

			echo '<div class="w23 news-item">';
			echo '	<div class="news-item-media-block">';
			if (get_field('google_photo_url')) echo '<div class="has-google-url" title="Присутнє посилання на Google-Фото"></div>';
			echo '		<div class="news-item-image ' . $postFormat . '">';
			echo '			<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">';
							if (get_post_format() === "video") {
								$youtube_id = get_field('youtube_link');
								echo "<img src='https://img.youtube.com/vi/$youtube_id/maxresdefault.jpg'>";
							}
							else {
								if (has_post_thumbnail()){
									echo '<img src="' .get_the_post_thumbnail_url(get_the_ID(), 'fco-news-logo-300px' ). '" alt="' .get_the_title(). '">';
									
								}
								else {
									echo "<img src='". get_template_directory_uri() . "/assets/img/no-photo-available.jpg' >";
								}
							}
			echo '			</a>';
			echo '		</div>';
			echo '		<div class="news-item-meta">';
			echo '			<div class="news-date">';
							echo get_the_date('d.m.Y');
			echo '			</div>';
			echo '			<div class="news-category">';
							echo $cat_name;
			echo '			</div>';
			echo '		</div>';
			echo '	</div>';
			echo '	<div class="news-item-title">';
			echo '		<span>';
			echo '			<a href="' . get_the_permalink() . '"';
			echo '				title="' . get_the_title() . '">' . get_the_title() . '</a>';
			echo '		</span>';
			echo '	</div>';
			echo '</div>';
		} //endwhile
	} //endif
	else echo "<h2 style='margin-bottom:30px;'>За вказаними критеріями записів не знайдено!</h2>";
}

function fco_pagination(){
	the_posts_pagination(
		array(
			'type' => 'list'
			)
		); 
}


 //Removes media buttons from post types.
add_filter( 'wp_editor_settings', function( $settings ) {
    $current_screen = get_current_screen();

    // Post types for which the media buttons should be removed.
    $post_types = array( 'team' );

    // Bail out if media buttons should not be removed for the current post type.
    if ( ! $current_screen || ! in_array( $current_screen->post_type, $post_types, true ) ) {
        return $settings;
    }

    $settings['media_buttons'] = false;

    return $settings;
} );

//Функция возвращает путь на основе страницы-родителя: main, u-21 или u-19 
function get_preUrlLink(){
	$parentName = get_parentName();
	return home_url() . '/team/' . $parentName;
}

//Функция возвращает имя страницы-родителя: main, u-21 или u-19 
function get_parentName(){
	return get_post(get_post()->post_parent)->post_name;
}

//Функция возвращает имя ФК - Александрия, U-19 или <U-21></U-21>
function fco_get_fcName(){
	$parentID = get_post(get_post()->post_parent);
	print_r($parentID);
	switch ( $parentID ) {
		case 'player_number':
			echo get_post_meta ( $post_id, 'player_number', true );
			break;

}



}

//Делаем приоритет страниц выше, чему у тегов (нужно, когда тег и Custom-page имеют одинаковое имя)
add_action( 'init', 'wpse16902_init' );
function wpse16902_init() {
    $GLOBALS['wp_rewrite']->use_verbose_page_rules = true;
}

add_filter( 'page_rewrite_rules', 'wpse16902_collect_page_rewrite_rules' );
function wpse16902_collect_page_rewrite_rules( $page_rewrite_rules )
{
    $GLOBALS['wpse16902_page_rewrite_rules'] = $page_rewrite_rules;
    return array();
}

add_filter( 'rewrite_rules_array', 'wspe16902_prepend_page_rewrite_rules' );
function wspe16902_prepend_page_rewrite_rules( $rewrite_rules )
{
    return $GLOBALS['wpse16902_page_rewrite_rules'] + $rewrite_rules;
}

function secureData($data) {
	foreach($data as $key => $value) {
		if (is_array($value)) secureData($value);
		else $data[$key] = htmlspecialchars(trim($value));
	}
	return $data;
}

//По переданной дате в формтате 20.10.2019 возвращаем 20 жовтня 2019
function ukrainianDate($date){
	$monthArray = ['', 'Січня', 'Лютого', 'Березня', 'Квітня', 'Травня', 'Червня', 'Липня', 'Серпня', 'Вересня', 'Жовтня', 'Листопада', 'Грудня'];
	$dateArray = explode('.', $date);
	return $dateArray[0] . ' ' . $monthArray[(int)$dateArray[1]] . ' ' . $dateArray[2];
}

//Формируем код для показа инфоблока прошлого, текущего или будущего матча
function showInfoBlock($tournamentType, $ageGroupSlag, $single_page = false){
	
	if ($single_page === true) $ageGroupSlag = get_the_terms((int)$tournamentType, 'age-group-match')[0]->slug;
	
	$active_season_id = get_term(get_theme_mod('fco_ex_settings_season'))->term_id;

	$query = new WP_Query( [
        'post_type' => ['match'],
        'posts_per_page'=>-1,
		'tax_query' => [
			'relation' => 'AND',
			[
				'taxonomy' => 'age-group-match',
				'field'    => 'slug',
				// 'terms'    => 'main',
				'terms'    => [$ageGroupSlag],
			],
			[
				'taxonomy' => 'season',
				'field'    => 'id',
				'terms'    => [$active_season_id],
			],
			// $tournament_query,
		]
	] );

	while ( $query->have_posts() ) {
        $query->the_post();
		$postID = get_the_ID();
		$tour_date_timestamp = strtotime(get_field('tour_date') . ' ' . get_field('tour_time'));
		$tourArrayForInfoBlocks[] = ['postID' => $postID, 'date' => $tour_date_timestamp];//Массив для вычисления прошлого и будущего матча
	}
	wp_reset_query(); 
	$tourArrayForInfoBlocks[] = ['postID' => 0, 'date' => time()];//Добавляем текущую временную отметку, чтобы понимать, "где мы находимся"

	//Сортируем полученный массив по возростанию даты:
	uasort($tourArrayForInfoBlocks, function ($a, $b)
        {
            if ($a['date'] == $b['date']) return 0;
            return $a['date'] > $b['date'] ? 1 : -1;
        }
    );

    //"Выравниваем" нумерацию ключей:
    $n = 1;
    $result_tourArrayForInfoBlocks = [];
    foreach($tourArrayForInfoBlocks as $tour){
        $result_tourArrayForInfoBlocks[$n] = $tour;
        $n++;
    }
	$tourArrayForInfoBlocks = $result_tourArrayForInfoBlocks;
	// echo '<pre>';
    //     print_r($tourArrayForInfoBlocks); 
    //     echo '</pre>';
	//Определяем ID прошлого и будущего турнира
    $n = 1;
    $currentTimePosition = '';
    foreach ($tourArrayForInfoBlocks as $key => $value){
        // echo '<pre>';
        // print_r($value['postID']); 
        // echo '</pre>';
        if($value['postID'] === 0) $currentTimePosition = $n;
        $n++;
    }
    if ($currentTimePosition === 1) {
        $lastTournamentID = false;
        $futureTournamentID = $tourArrayForInfoBlocks[2]['postID'];
    }
    elseif ($currentTimePosition === count($tourArrayForInfoBlocks)){
        $lastTournamentID = $tourArrayForInfoBlocks[$currentTimePosition - 1]['postID'];
        $futureTournamentID = false;
    }
    else{
        $lastTournamentID = $tourArrayForInfoBlocks[$currentTimePosition - 1]['postID'];
        $futureTournamentID = $tourArrayForInfoBlocks[$currentTimePosition + 1]['postID'];
	}
	
	//Готовимся к выводу html-кода в зависимости о того, какой турнир нужно отобразить - прошлый, будущий или текуший (получаем lastTournament, futureTournament или ID текущего турнира):
	if ($tournamentType === 'lastTournament'){
		$tournamentID = $lastTournamentID;
	}
	elseif ($tournamentType === 'futureTournament'){
		$tournamentID = $futureTournamentID;
	}
	else{
		$tournamentID = (int)$tournamentType;
	}

	//Отдельный вид информ. окна для таблицы с одним турниром:
	if (infoBlockInfo($tournamentID)['is_home']) {
	$kickoff_single_page = '
	<div class="info-block__team-container info-block__team-container__match-center">
		<div class="info-block__team-title--wrapper">
			<span class="info-block__team-title info-block__team-title--uppercase info-block__team-title--home">
				Олександрія
			</span>
			' . goals_list('fco', $tournamentID) . '
		</div>
		<div class="info-block__team-img">
			<img src="' . get_template_directory_uri() . '/assets/img/olexandriya.png"
				alt="' . get_bloginfo('name') . '">
		</div>
	</div>
	<div class="info-block__score">
	<span>' . infoBlockInfo($tournamentID)['fco_goals'] .'</span>
	<span>' . infoBlockInfo($tournamentID)['opp_goals'] .'</span>
	</div>
	<div class="info-block__team-container info-block__team-container__match-center">
		<div class="info-block__team-img">
			' . wp_get_attachment_image(infoBlockInfo($tournamentID)['opp_logo_id'], [64,64], '', ['alt' => get_field('opp_select')->post_title]) . '
		</div>
		<div class="info-block__team-title--wrapper">
			<span class="info-block__team-title info-block__team-title--uppercase info-block__team-title--away">
			' . infoBlockInfo($tournamentID)['opp_select']->post_title . '
		</span>
		' . goals_list('opp', $tournamentID) . '
		</div>
	</div>';
	}
else {
	$kickoff_single_page = '
	<div class="info-block__team-container info-block__team-container__match-center">
		<div class="info-block__team-title--wrapper">
			<span class="info-block__team-title info-block__team-title--uppercase info-block__team-title--home">
			' . infoBlockInfo($tournamentID)['opp_select']->post_title . '
			</span>
			' . goals_list('opp', $tournamentID) . '
		</div>
		<div class="info-block__team-img">
			' . wp_get_attachment_image(infoBlockInfo($tournamentID)['opp_logo_id'], [64,64], '', ['alt' => get_field('opp_select')->post_title]) . '
		</div>
	</div>
	<div class="info-block__score">
		<span>' . infoBlockInfo($tournamentID)['opp_goals'] .'</span>
		<span>' . infoBlockInfo($tournamentID)['fco_goals'] .'</span>
	</div>
	<div class="info-block__team-container info-block__team-container__match-center">
		<div class="info-block__team-img">
		<img src="' . get_template_directory_uri() . '/assets/img/olexandriya.png"
		alt="' . get_bloginfo('name') . '">
		</div>
		<div class="info-block__team-title--wrapper">
			<span class="info-block__team-title info-block__team-title--uppercase info-block__team-title--away">
			Олександрія
			</span>
			' . goals_list('fco', $tournamentID) . '
	</div>';
	}

	//Выводим html-код:
	$infoBlockResult = '<div class="info-block__body">
	<div class="info-block__logo">
	<div class="info-block__logo--tur">';
if (get_field('tour_number', $tournamentID)) $infoBlockResult .= get_field('tour_number', $tournamentID).'-й тур<br>';
	$infoBlockResult .= 'сезон ' . get_the_terms($tournamentID, 'season')[0]->name.
	'</div>';

	$logo_data = get_the_terms($tournamentID, 'tournament');
    $thumb = get_field('liga-logo', 'category_'.$logo_data[0]->term_id);
    $url_logo = ($thumb == '') ? get_template_directory_uri().'/assets/img/tour-no-photo.jpg' : wp_get_attachment_image_url($thumb, 'full');
	$alt_logo = $logo_data[0]->name;

	$infoBlockResult .= "		<img src='$url_logo' alt='" . $alt_logo . "' title='" . $alt_logo . "'>";
	$infoBlockResult .= '	</div>';
	$infoBlockResult .= '	<div class="info-block__meta">';
	$infoBlockResult .= '		<span class="info-block__date">';
	$infoBlockResult .= 			ukrainianDate(get_field('tour_date', $tournamentID)) . 'р.';
	$infoBlockResult .= '		</span>';
	$infoBlockResult .= '		<span class="info-block__stadium">';
	$infoBlockResult .=			get_the_terms($tournamentID, 'stadium')[0]->name;
	$infoBlockResult .= '		</span>';
	$infoBlockResult .= '	</div>';
	
	if ($single_page === false){
		$infoBlockResult .= '	<div class="info-block__kickoff-container">';
		$infoBlockResult .= '		<div class="info-block__team-container">';
				if (infoBlockInfo($tournamentID)['is_home']) {
		$infoBlockResult .= '			<span class="info-block__team-title info-block__team-title--home">';
		$infoBlockResult .= '				Олександрія';
		$infoBlockResult .= '			</span>';
		$infoBlockResult .= '			<div class="info-block__team-img">';
		$infoBlockResult .= '				<img src="' . get_template_directory_uri() . '/assets/img/olexandriya_logo_mini.png" alt="' . get_bloginfo('name') . '">';
		$infoBlockResult .= '			</div>';
				}
				else {
		$infoBlockResult .= '			<span>';
		$infoBlockResult .=				infoBlockInfo($tournamentID)['opp_select']->post_title;
		$infoBlockResult .= '			</span>';
		$infoBlockResult .= '			<div class="info-block__team-img">';
		$infoBlockResult .= 				wp_get_attachment_image(infoBlockInfo($tournamentID)['opp_logo_id'], 'fco-club-logo-small', '', ['alt' => infoBlockInfo($tournamentID)['opp_select']->post_title]);
		$infoBlockResult .= '			</div>';
				}
		$infoBlockResult .= '		</div>';

		if ($tournamentType === 'futureTournament'){
			$infoBlockResult .= '		<div class="info-block__time">';
			$infoBlockResult .= '			<span class="clock-icon"></span>';
			$infoBlockResult .= '			<span class="knock_time">';
			$infoBlockResult .= 				infoBlockInfo($tournamentID)['tour_time'];
			$infoBlockResult .= '			</span>';
			$infoBlockResult .= '		</div>';
		}
		else{
			$infoBlockResult .= '		<div class="info-block__score">';
			$infoBlockResult .= '			<span>';
			$infoBlockResult .= 				(infoBlockInfo($tournamentID)['is_home']) ? infoBlockInfo($tournamentID)['fco_goals'] : infoBlockInfo($tournamentID)['opp_goals'];
			$infoBlockResult .= '			</span>';
			$infoBlockResult .= '			<span>';
			$infoBlockResult .= 				(!infoBlockInfo($tournamentID)['is_home']) ? infoBlockInfo($tournamentID)['fco_goals'] : infoBlockInfo($tournamentID)['opp_goals'];
			$infoBlockResult .= '			</span>';
			$infoBlockResult .= '		</div>';
		}

		$infoBlockResult .= '		<div class="info-block__team-container">';
				if (!infoBlockInfo($tournamentID)['is_home']) {
		$infoBlockResult .= '			<div class="info-block__team-img">';
		$infoBlockResult .= '				<img src="' . get_template_directory_uri() . '/assets/img/olexandriya_logo_mini.png"
		alt="' . get_bloginfo('name') . '">';
		$infoBlockResult .= '			</div>';
		$infoBlockResult .= '			<span class="info-block__team-title info-block__team-title--home">';
		$infoBlockResult .= '				Олександрія';
		$infoBlockResult .= '			</span>';
				}
				else {
		$infoBlockResult .= '			<div class="info-block__team-img">';
		$infoBlockResult .=				wp_get_attachment_image(infoBlockInfo($tournamentID)['opp_logo_id'], 'fco-club-logo-small', '', ['alt' => infoBlockInfo($tournamentID)['opp_select']->post_title]);
		$infoBlockResult .= '			</div>';
		$infoBlockResult .= '			<span>';
		$infoBlockResult .=				infoBlockInfo($tournamentID)['opp_select']->post_title;
		$infoBlockResult .= '			</span>';
				}
		$infoBlockResult .= '		</div>';
		$infoBlockResult .= '	</div>';

		
		
		$infoBlockResult .= '	<div class="info-block__btn-container">';
		if ($tournamentType === 'futureTournament'){
			$infoBlockResult .= '	<a href="' . get_home_url() . '/buy-ticket?tournamentID=' . $tournamentID .'" class="btn info-block__btn info-block__btn--single">';
			$infoBlockResult .= '		<span class="buy-ticket">Купити квиток</span>';
			$infoBlockResult .= '	</a>';
		}
		$infoBlockResult .= '		<a href="' . infoBlockInfo($tournamentID)['tour_url'] .'" class="btn info-block__btn info-block__btn--single">';
		$infoBlockResult .= ' 			<span class="match-center">Матч центр</span>';
		$infoBlockResult .= '		</a>';
		$infoBlockResult .= '	</div>';
		$infoBlockResult .= '</div>';
	}
	else {
		$infoBlockResult .= '	<div class="info-block__kickoff-container">';
		$infoBlockResult .= $kickoff_single_page;
		$infoBlockResult .= '</div>';
		$infoBlockResult .= '</div>';
		$infoBlockResult .= '</div>';
    }
    wp_reset_query(); 
    wp_reset_postdata();
	return $infoBlockResult;
}

//Вспомогательная функция - список голов:
function goals_list($team, $tournamentID){
	($team === 'fco') ? $team = 'fco_goals' : $team = 'opp_goals';
	if (get_field('fco_goals', $tournamentID)) {
		$result = '';
		$goals = explode("\n", str_replace("\r", "", get_field($team, $tournamentID)));
		foreach ($goals as $goal){
			$result .= '<span class="info-block__team--goal-player">' . $goal . '\'</span>';
		}
		return $result;
	}
}

//Вспомогательная функция для заполнения информ. таблицы:
function infoBlockInfo($tourID){
    $result = [];
    $result['is_home'] = get_field('tour_is_home', $tourID);
    $result['tour_time'] = (get_field('tour_time', $tourID)) ? date('H:i', strtotime(get_field('tour_time', $tourID))) : '00:00';
    $result['opp_select'] = get_field('opp_select', $tourID);
    $result['fco_goals'] = (get_field('fco-successful-goals', $tourID) !== '') ? get_field('fco-successful-goals', $tourID) : '_';
    // $result['fco_golas'] = ($result['fco_goals'] < 0) ? "<span style='color: red;'>" . $result['fco_goals'] . "</span>" : $result['fco_golas'];
    $result['opp_goals'] = (get_field('opp-successful-goals', $tourID) !== '') ? get_field('opp-successful-goals', $tourID) : '_';
    // $result['opp_goals'] = ($result['opp_goals'] < 0) ? "<span style='color: red;'>" . $result['opp_goals'] . "</span>" : $result['opp_goals'];
    $result['opp_logo_id'] = get_post_meta( $result['opp_select']->ID, 'fclub_logo',true );
    $result['tour_url'] = get_permalink($tourID);
    return $result;
} 

//Задаем показ определенных категорий для поиска:
add_action( 'pre_get_posts', 'search_filter' );
function search_filter( $query ){
	if( ! is_admin() && $query->is_main_query() ){
		if( $query->is_search ){
			$query->set('post_type', ['post', 'page', 'match']);
			$query->set( 'posts_per_page', 16 );
			// $query->set( 'cat', '-257' ); //Слайдер
		}
	}
	
}

//Выводим турнирную таблицу
function get_tours_table($age_group){
	if ($age_group === 'main') $url = 'https://upl.ua/ex/js/stand/406.json';
	elseif ($age_group === 'u-21') $url = 'https://upl.ua/ex/js/stand/407.json';
	elseif ($age_group == 'u-19') $url = 'https://upl.ua/ex/js/stand/408.json';
	$json_string = file_get_contents($url);
	$teamArray = json_decode($json_string);
	return $teamArray;
}	   
                            
    //Ссылкин на ФБ и Твиттер:
	function fb_sharer($permalink, $title){
		return 'http://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title;
	} 
	function tw_sharer($permalink, $title){
		return 'http://twitter.com/share?text=' . $title . '. Дізнатися більше: &url=' . $permalink;
	}                          

    function showMainSlider(){
        global $wp_query;

        $args = array(
            'posts_per_page' => 4,
            'order' => 'DESC',
            'orderby' => 'date',
            'cat' => '1,257,258',
        );

    $wp_query = new WP_Query( $args );
    $newsArray = [];
    $i = 1;
    if ( $wp_query->have_posts() ){
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();

        $newsArray[$i]['title'] = get_the_title();
        $newsArray[$i]['link'] = get_the_permalink();
        if (get_field('mainSliderImg') != '') $newsArray[$i]['img'] = get_field('mainSliderImg');
        elseif (has_post_thumbnail()) $newsArray[$i]['img'] = get_the_post_thumbnail_url(get_the_ID(), 'large' );
        elseif ( (get_post_format() === "video") AND (get_field('youtube_link') !== '') ) $newsArray[$i]['img'] = 'https://img.youtube.com/vi/' . get_field('youtube_link') . '/maxresdefault.jpg';
        else $newsArray[$i]['img'] = 'https://picsum.photos/id/974/1500/570';
        $newsArray[$i]['date'] = get_the_date('d.m.Y');
        $newsArray[$i]['post'] = get_post_format();
    $i++;
        }
    }
    $result = '';

        for ($i=1; $i <= 4; $i++) { 
            $active = ($i == 1) ? 'top-news-headerblock__bg-img_active' : '';
            $result .= '<div class="top-news-headerblock__bg-img ' . $active . '" style="background-image: url(' . $newsArray[$i]['img'] . ');"></div>';
        }
        $result .= '<div class="top-news-headerblock__sidebar">';
        for ($i=1; $i <= 4; $i++) { 
            $active = ($i == 1) ? 'top-news-headerblock__sidebar-item--active' : '';
            $result .= '<div class="top-news-headerblock__sidebar-item ' . $active . '">
                    <div class="top-news-headerblock__sidebar-item--title">
                        <a href="' . $newsArray[$i]['link'] . '">' . $newsArray[$i]['title'] . '</a>
                    </div>
                    <div class="top-news-headerblock__sidebar-item--meta">
                        <span>' . $newsArray[$i]['date'] . ' Новини</span>
                    </div>
                </div>';
        };
        $result .= '</div>
                </div>';
        $result .= '<div class="top-news-bottombBlock">
                        <div class="top-news-bottomCentralBlock">
                            <div class="top-news-bottomCentralBlock__left-side">
                                <span>Головні новини</span>
                            </div>
                            <div class="top-news-bottomCentralBlock__right-side">';
                            for ($i=1; $i <= 4; $i++) { 
                                $active = ($i == 1) ? 'top-news-bottomCentralBlock__right-side-active' : '';
                                $result .= '<a href="' . $newsArray[$i]['link'] . '" class="' . $active . '">' . $newsArray[$i]['title'] . '</a>';
                            };
        $result .= '        </div>
                        </div>';
    return $result;
        }

?>