<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta charset="<?php bloginfo('charset'); ?>" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <!--<link rel="pingback" href="<?php // bloginfo('pingback_url');        ?>" />-->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Latest compiled and minified JavaScript -->
        <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <!-- Navigation -->
        <nav class="navbar navbar-inverse" role="navigation">

            <div class="col-md-12 top-menu-bg">
                <div class="row">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="javascript:void(0);" class="bod-link">Board of Directors</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="bod-home">ICC Home</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="bod-cdpaccess">cdpAccess</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="bod-store">Store <!--b class="caret"></b--></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="bod-premium-access">Premium Access <!--b class="caret"></b--></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="bod-public-access">Public Access<!--b class="caret"></b--></a>
                                </li>
                            </ul>
                            <div class="search-desktop">
                                <input type="text" class="search-lg-other" placeholder="Search..." value=""><span class="fa fa-search search-icon-other"></span>
                            </div>	

                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                </div>
            </div>	
            <div class="col-md-12 whtie-bg">
                <div class="row">
                    <div class="container">
                        <a class="navbar-brand" href="<?php echo site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/green-logo.png" /></a>
                        <div class="pull-right mb-width">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle xs-remove-top" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="true">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'depth' => 3,
                                'container' => 'div',
                                'container_class' => 'collapse navbar-collapse sm-mobile',
                                'container_id' => 'bs-example-navbar-collapse-2',
                                'menu_class' => 'nav navbar-nav navbar-right mobiletopremove',
                                'fallback_cb' => 'wp_page_menu',
                                //Process nav menu using our custom nav walker
                                'walker' => new wp_bootstrap_navwalker()
                            ));
                            ?>
                        </div><!-- /.navbar-collapse --> 
                    </div>

                </div>

            </div>
            <!-- /.container -->
        </nav>
        <div class="clearfix"></div>
