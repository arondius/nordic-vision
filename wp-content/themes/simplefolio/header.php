	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title>
		<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
		<?php if ( is_front_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
		<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Search Results<?php } ?>
		<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Author Archives<?php } ?>
		<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_page() && !is_front_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
		<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
		<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
		<?php if ( is_404() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Couldn't find what your looking for<?php } ?>
		<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
	</title>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
    <link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Comments Feed" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="shortcut icon" href="<?php echo sf_get_favicon(); ?>" title="Favicon" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<meta name="Keywords" content="<?php echo stripslashes(get_option('sf_seo_kw')); ?>" />
	<meta name="Description" content="<?php echo stripslashes(get_option('sf_seo_desc')); ?>" />
	<!--[if IE 6]>
		<script src="<?php bloginfo('template_url'); ?>/js/DD_belatedPNG.js"></script>
		<script>
		  /* EXAMPLE */
		  DD_belatedPNG.fix('*');
		</script>
		<link rel="stylesheet" media="screen" href="ie6.css"/>
    <![endif]-->
	<?php if (is_page_template('template-home.php')) { ?>
	<?php } ?>

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head>
 <body <?php body_class(); ?>>
<?php include_once("analyticstracking.php") ?>
	<div id="wrapper">
		<div id="header">
		<?php if ( is_front_page() ) { ?>
						<div id="logo">
							<h1>
								<a href="<?php bloginfo('home'); ?>">Fotoreizen</a>
							</h1>
						</div>
					<?php } else { ?>
						<div id="logo">
							<h2>
								<a href="<?php bloginfo('home'); ?>">Fotoreizen</a>
							</h2>
						</div>
					<?php } ?>
			<div id="pagenav">
				<ul class="sf-menu" id="nav">
					<?php wp_list_pages('title_li=&depth=1&exclude=323,1370,2032,2041'); ?>
					<li class="facebook-nav-link">
						<a href="http://www.facebook.com/pages/Nordic-Vision/159713777432136">
							<img src="<?php bloginfo('template_url'); ?>/images/facebook.png" alt="facebook-logo" title="Bezoek de Nordic Vision Fotoreizen Facebookpagina" />
						</a>
					</li>
					<li id="uk-flag-nav-link">
						<a href="http://www.photographytravels.eu/">
							<img src="<?php bloginfo('template_url'); ?>/images/icon-flag-gb.gif" width="16" height="11" alt="international-photography-tours" title="Visit our international photography tours" />
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div id="main">
			<div class="container">