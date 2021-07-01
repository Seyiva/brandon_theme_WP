<?php

	add_action("wp_head", "clearFunc", 1);

	function clearFunc(){
		add_filter( "xmlrpc_enabled", "__return_false" );
		remove_action( "wp_head", "feed_links" );
		remove_action( "wp_head", "feed_links_extra" );

		remove_action( "wp_head", "rsd_link" );
		remove_action( "wp_head", "wlwmanifest_link" );
		remove_action( "wp_head", "wp_generator" );

		remove_action( "wp_head", "wp_shortlink_wp_head" );
		remove_action( "wp_head", "adjacent_posts_rel_link_wp_head" );

		add_filter('rest_enabled', '__return_false');

		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'template_redirect', 'rest_output_link_header' );
		remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
		remove_action( 'auth_cookie_expired', 'rest_cookie_collect_status' );
		remove_action( 'auth_cookie_bad_username', 'rest_cookie_collect_status' );
		remove_action( 'auth_cookie_bad_hash', 'rest_cookie_collect_status' );
		remove_action( 'auth_cookie_valid', 'rest_cookie_collect_status' );
		remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors' );

		remove_action( 'init', 'rest_api_init' );
		remove_action( 'rest_api_init', 'rest_api_default_filters' );
		remove_action( 'parse_request', 'rest_api_loaded' );

		remove_action( 'rest_api_init', 'wp_oembed_register_route');
		remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request' );

		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	}

	define("B_THEME_ROOT", get_template_directory_uri());
	define("B_CSS_DIR", B_THEME_ROOT . "/css");
	define("B_JS_DIR", B_THEME_ROOT . "/js");
	define("B_IMG_DIR", B_THEME_ROOT . "/img");

	add_action( 'wp_enqueue_scripts', 'up_style');


	function up_style(){
		wp_enqueue_style( 'bootstrap', B_CSS_DIR . '/bootstrap/bootstrap.min.css');
		wp_enqueue_style( 'main', B_CSS_DIR . '/style.css');
		wp_enqueue_style( 'mediacss', B_CSS_DIR . '/media.css');

		wp_deregister_script("jquery");
		wp_enqueue_script( 'jquery', B_JS_DIR . '/jquery/jquery.min.js');
		wp_enqueue_script( 'bootstrap', B_JS_DIR . '/bootstrap/bootstrap.js');
		wp_enqueue_script( 'magnific-popup', B_JS_DIR . '/magnific-popup/magnific-popup.min.js');
		wp_enqueue_script( 'parallax', B_JS_DIR . '/parallax/parallax.min.js');
		wp_enqueue_script( 'page-scroll', B_JS_DIR . '/page-scroll-to-id/jquery.malihu.PageScroll2id.js');
		wp_enqueue_script( 'wow', B_JS_DIR . '/wow/wow.min.js');
		wp_enqueue_script( 'isotope', B_JS_DIR . '/isotope/isotope.pkgd.min.js');
		wp_enqueue_script( 'countup', B_JS_DIR . '/countUp/jquery.countup.min.js');
		wp_enqueue_script( 'waypoints', B_JS_DIR . '/waypoints/jquery.waypoints.min.js');
		wp_enqueue_script( 'functions', B_JS_DIR . '/functions.js');
		wp_enqueue_script( 'send', B_JS_DIR . '/send.js');
	}

	add_action( 'after_setup_theme', 'top_menu' );

	function top_menu() {
		register_nav_menu( 'top', 'Вернее меню' );
	}

	add_action( 'after_setup_theme', 'mobile_menu' );

	function mobile_menu() {
		register_nav_menu( 'mob', 'Мобильное меню' );
	}

	add_theme_support( 'custom-logo' );

	add_theme_support( 'custom-header', array(
		'default-image' => get_template_directory_uri() . '/img/first_screen_bg.jpg',
		'uploads'       => true,
	) );

	add_action( 'init', 'register_post_types' );

	function register_post_types(){
		register_post_type('hello', array(
			'labels' => array(
				'name'               => 'Приветствие', // основное название для типа записи
				'singular_name'      => 'Приветствие', // название для одной записи этого типа
				'add_new'            => 'Добавить приветствие', // для добавления новой записи
				'add_new_item'       => 'Добавление приветствия', // заголовка у вновь создаваемой записи в админ-панели.
				'edit_item'          => 'Редактирование приветствия', // для редактирования типа записи
				'new_item'           => 'Новое приветствие', // текст новой записи
				'view_item'          => 'Смотреть приветствие', // для просмотра записи этого типа.
				'search_items'       => 'Искать приветствие', // для поиска по этим типам записи
				'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
				'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
				'parent_item_colon'  => '', // для родителей (у древовидных типов)
				'menu_name'          => 'Приветствие в баннере', // название меню
			),
			'public'              => false,
			'show_ui'             => true, // зависит от public
			'menu_icon'           => "dashicons-format-status",
			'supports'            => array('title','editor')
		) );
	}

	function getHello(){

		$args = array(
			'orderby'     => 'date',
			'order'       => 'ASC',
			'post_type'   => 'hello'
		);

		$bannerTexts = [];

		foreach (get_posts($args) as $post) {
			$bannerText = get_fields($post->ID);
			$bannerText["title"] = $post->post_title;
			$bannerText["content"] = $post->post_content;
			$bannerTexts[] = $bannerText;
		}

		return $bannerTexts;
	}

	add_action( 'widgets_init', 'register_my_widgets' );

	function register_my_widgets(){
		register_sidebar( array(
			'name'          => 'Right sidebar',
			'id'            => "sidebar-right",
			'description'   => 'Description',
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div><div class="space x-small"></div>',
			'before_title'  => '<h5 class="blog-text-uppercase">',
			'after_title'   => "</h5>\n",
		) );
	}

	add_filter('navigation_markup_template', 'my_navigation_template', 10, 2 );

	function my_navigation_template( $template, $class ){
		return '
		<div class="col-md-12">
			<nav class="box-pagination text-center navigation %1$s" role="navigation">
				<div class="blog-pagination nav-links">%3$s</div>
			</nav>
		</div>
		';
	}

	add_shortcode('my_shortcode', 'new_content');

	function new_content(){
		return "Не забудь поделиться постом!";
	}

	function Generate_iframe( $atts ) {
		$atts = shortcode_atts( array(
			'href'   => 'http://wp-kama.com',
			'height' => '550px',
			'width'  => '300px',
		), $atts );

		return '<iframe src="'. $atts['href'] .'" width="'. $atts['width'] .'" height="'. $atts['height'] .'"> <p>Your Browser does not support Iframes.</p></iframe>';
	}
	add_shortcode('iframe', 'Generate_iframe');