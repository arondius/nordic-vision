<?php
	add_theme_support( 'post-thumbnails' );


	// Add a new image size for featured image thumbnails
	if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'nieuws-thumb', 200, 200, true ); //(cropped)
}

	/* Register Widgets */
		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => 'Sidebar Widget',
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
			));
			register_sidebar(array(
				'name' => 'Sidebar Nieuws Widget',
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
			));
			register_sidebar(array(
				'name' => 'Homepage Widget',
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
			));
		}

	/* Un-Register WP-PageNavi Style Page Include */
		function my_deregister_styles() {
			wp_deregister_style('wp-pagenavi');
		}
		add_action('wp_print_styles','my_deregister_styles',100);

	/* Function To Limit Output Of Content.*/
		function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
			$content = get_the_content($more_link_text, $stripteaser, $more_file);
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = strip_tags($content);

		   if (strlen($_GET['p']) > 0) {
			  echo $content;
		   }
		   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
				$content = substr($content, 0, $espacio);
				$content = $content;
				echo $content;
				echo "...";
		   }
		   else {
			  echo $content;
		   }
		}

	/* Exclude Portfolio Category & Child Categories From Blog Posts */
		function sf_portfolio_filter($query) {
			global $wpdb;
			if(!is_archive() && !is_admin() && !is_single()){
				$category = sf_get_category_id(get_option('sf_portfolio_category'));
				if (!empty($category) && get_option('sf_portfolio_exclude')) {
					$array = array($category => $category);
					$array2 = array();
					$categories = get_categories('child_of='.$category);
					foreach($categories as $k) {
						$array2[$k->term_id] = $k->term_id;
					}
					$array2 = array_merge($array,$array2);
					$query = sf_portfolio_remove_category($query,$array2);
				}
			}
			return $query;
		}
		function sf_portfolio_remove_category($query,$category){
			$cat = $query->get('category__in');
			$cat2 = array_merge($query->get('category__not_in'),$category);
			if($cat && $cat2){
				foreach($cat2 as $k=>$c){
					if(in_array($c,$cat)){
						unset($cat2[$k]);
					}
				}
			}
			$query->set('category__not_in',$cat2);

			return $query;
		}
		add_filter('pre_get_posts', 'sf_portfolio_filter');

	/* Exclude Portfolio Category & Child Categories From Category List And Dropdown Widget */
		function sf_category_filter($args) {
			$category = sf_get_category_id(get_option('sf_portfolio_category'));
			if (!empty($category) && get_option('sf_portfolio_exclude')) {
				$myarray = array(
						'exclude'    => $category,
						'exclude_tree'    => $category,
						);
				$args = array_merge($args, $myarray);
			}
			return $args;
		}
		add_filter('widget_categories_args', 'sf_category_filter');
		add_filter('widget_categories_dropdown_args', 'sf_category_filter');

	/* Generate a list of child categories of the portfolio category for filtering on the portfolio items by category */
		function sf_list_portfolio_child_categories($topcat,$active,$pagepermalink) {
			$categories = get_categories('child_of='.$topcat);
			if (hasQuestionMark($pagepermalink)) {
				$pagepermlinkadd = $pagepermalink."&";
			}
			else {
				$pagepermlinkadd = $pagepermalink."?";
			}
			$array2 = array();
			foreach($categories as $k) {
				$array2[$k->term_id] = $k->name;
			}
			foreach ($array2 as $x => $y) {
				if ($x == $active) { $addtoclass = " class=\"active\""; }
				echo "<li".$addtoclass."><a href=\"".$pagepermlinkadd."pcat=".$x."\">".$y."</a></li>";
				unset($addtoclass);
			}
		}

	/* Go threw a string to see if it contains a certain character */
		function hasQuestionMark($string) {
			$length = strlen($string);
			for($i = 0; $i < $length; $i++) {
				$char = $string[$i];
				if($char == '?') { return true; }
			}
			return false;
		}

	/* Get the Category ID */
		function sf_get_category_id($cat_name) {
			$categories = get_categories();
			foreach($categories as $category){ //loop through categories
				if($category->name == $cat_name){
					$cat_id = $category->term_id;
					break;
				}
			}
			if (empty($cat_id)) { return 0; }
			return $cat_id;
		}

	/**
	 * Tests if any of a post's assigned categories are descendants of target categories
	 *
	 * @param int|array $cats The target categories. Integer ID or array of integer IDs
	 * @param int|object $_post The post. Omit to test the current post in the Loop or main query
	 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
	 * @see get_term_by() You can get a category by name or slug, then pass ID to this function
	 * @uses get_term_children() Passes $cats
	 * @uses in_category() Passes $_post (can be empty)
	 * @version 2.7
	 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
	 */
		function post_is_in_descendant_category( $cats, $_post = null ) {
			foreach ( (array) $cats as $cat ) {
				// get_term_children() accepts integer ID only
				$descendants = get_term_children( (int) $cat, 'category');
				if ( $descendants && in_category( $descendants, $_post ) )
					return true;
			}
			return false;
		}

	/* Generate Custom Logo & Favicon */
		/*function sf_get_logo() {
			$default_logo = get_bloginfo('template_directory')."/images/logo.png";
			$custom_logo = get_option('sf_basic_logo');
			$logo = (empty($custom_logo)) ? $default_logo : $custom_logo;
			return $logo;
		}
		*/
		function sf_get_favicon() {
			$default_favicon = get_bloginfo('template_directory')."/images/favicon.ico";
			$custom_favicon = get_option('sf_basic_favicon');
			$favicon = (empty($custom_favicon)) ? $default_favicon : $custom_favicon;
			return $favicon;
		}

	/* RSS Custom Widget */
		function sf_rss_widget($args) {
			extract($args);
			?>
						<div class="widget widget_rssfeed">
							<ul>
								<?php if (get_option('sf_feedburner')): ?> <li class="rss"><a href="<?php echo "http://feeds2.feedburner.com/".get_option('sf_feedburner'); ?>">Subscribe to RSS Feed</a></li>
								<?php else: ?> <li class="rss"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe to RSS Feed</a></li> <?php endif; ?>

								<?php if (get_option('sf_email') && get_option('sf_feedburner')) { ?><li class="email"><a href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo get_option('sf_feedburner'); ?>&amp;loc=en_US">Subscribe by Email</a></li> <?php } ?>
								<?php if (get_option('sf_twitter')) { ?><li class="twitter"><a href="<?php echo "http://twitter.com/".get_option('sf_twitter'); ?>">Follow me on Twitter</a></li> <?php } ?>
							</ul>
						</div>
			<?php
		}
		function sf_widgets() {
			wp_register_sidebar_widget('RSS Feed Subscribe', 'sf_rss_widget', 'none');
		}
		add_action('widgets_init','sf_widgets');

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

function bookingformbutton()	{
	return '<div class="btn_wrapper"><a class="cta-boeking button button-light" href="http://fotoreizen.net/boekingsformulier"><span>Boek deze reis</span></a></div>';
}

add_shortcode('boekingsform', 'bookingformbutton');

add_shortcode('Boekingsform', 'bookingformbutton');

function winterinfobutton()	{
	return '<div class="btn_wrapper"><a class="button button-dark" href="http://fotoreizen.net/fotoreizen/veiligheid-en-winteruitrusting"><span>BELANGRIJK: VEILIGHEID EN WINTERUITRUSTING</span></a></div>';
}

add_shortcode('winterinfo', 'winterinfobutton');

add_shortcode('Winterinfo', 'winterinfobutton');

function sButton($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<div class="btn_wrapper"><a class="button button-light" href="'.$link.'"><span>' . do_shortcode($content) . '</span></a></div>';
}

add_shortcode('mybutton', 'sButton');


//Licensed under the GPL v2
//by Evan Walsh of nothingconcept.com
function copyright($year) {
    $current = date('Y');
    $site = get_bloginfo('name');
    if($year == $current) { $eyear = $year; }
    else { $eyear = "$year - $current"; }
    echo "&copy; $eyear $site";
}

function new_excerpt_more($more) {
       global $post;
	return ' <a href="'. get_permalink($post->ID) . '" class="read_more">Lees meer...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function register_my_menus() {
  register_nav_menus(
    array(
    'footer-menu-left' => __( 'Footer Menu Left' ),
    'footer-menu-center' => __( 'Footer Menu Center' ),
    'footer-menu-right' => __( 'Footer Menu Right' )
    )
  );
};

add_action( 'init', 'register_my_menus' );

// Footer scripts
function jquery_init() {
	if (!is_admin()) { 	//load scripts for non admin pages
	// load a additional js files
// 	wp_enqueue_script('altRows', get_bloginfo('template_directory') . '/js/alt-rows.js', array('jquery'), '1.0', true);
// 	wp_enqueue_script('altRows');

	wp_enqueue_script('ddRoundies', get_bloginfo('template_directory') . '/js/DD_roundies.js', array('jquery'), '1.0', true);
	wp_enqueue_script('ddRoundies');

// 	wp_enqueue_script('hoverIntent', get_bloginfo('template_directory') . '/js/hoverIntent.js', array('jquery'), '1.0', true);
	// wp_enqueue_script('hoverIntent');

// 	wp_enqueue_script('superFish', get_bloginfo('template_directory') . '/js/superfish.js', array('jquery'), '1.0', true);
// 	wp_enqueue_script('superFish');

 	wp_enqueue_script('superFish', get_bloginfo('template_directory') . '/js/plugins.js', array('jquery'), '1.0', true);
 	wp_enqueue_script('superFish');

	wp_enqueue_script('custom', get_bloginfo('template_directory') . '/js/custom.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom');
	}
}

add_action('wp_print_scripts', 'jquery_init');

// function sliderInit() {
//
// 	if(is_home() || is_front_page()){
// 	wp_enqueue_script('mySlider', get_bloginfo('template_directory') . '/js/myslider.js', array('jquery'), '1.0', true);
//
// 	wp_enqueue_script('loadSlider', get_bloginfo('template_directory') . '/js/load_slider.js', array('jquery', 'mySlider'), '1.0', true);
// 	}
// }

	// add_action('wp_print_scripts', 'sliderInit');

// END :Footer scripts
?>