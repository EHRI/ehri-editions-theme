<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <?php add_translation_source(dirname(dirname(__FILE__)) . '/languages'); ?>
    <meta charset="utf-8">
    <?php if ($copyright = option('copyright')): ?>
        <meta name="description" content="<?php echo html_escape($copyright); ?>"/>
    <?php endif; ?>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes"/>
    <?php if ($description = trim(option('description'))): ?>
        <meta name="description" content="<?php echo html_escape($description); ?>"/>
        <meta property="og:description" content="<?php echo html_escape($description); ?>"/>
    <?php endif; ?>
    <?php if ($keywords = get_theme_option('Meta Keywords')): ?>
        <meta name="keywords" content="<?php echo html_escape($keywords); ?>"/>
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <meta property="og:title" content="<?php echo html_escape(implode(' | ', $titleParts)); ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo absolute_url(); ?>"/>
    <?php if ($item = get_current_record('item', false)): ?>
        <?php if ($file = $item->getFile()): ?>
            <?php $item_img = $file->getWebPath('square_thumbnail'); ?>
            <meta property="og:image" content="<?php echo $item_img; ?>"/>
        <?php endif; ?>
    <?php elseif ($theme_img_url = theme_sharing_image_url()): ?>
        <meta property="og:image" content="<?php echo $theme_img_url; ?>"/>
    <?php endif; ?>

    <title><?php echo html_escape(implode(' &middot; ', $titleParts)); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head', array('view' => $this)); ?>

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo web_path_to('apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo web_path_to('favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo web_path_to('favicon-16x16.png'); ?>">
    <link rel="manifest" href="<?php echo web_path_to('site.webmanifest'); ?>">
    <link rel="mask-icon" href="<?php echo web_path_to('safari-pinned-tab.svg'); ?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="theme-color" content="#ffffff">

    <!-- metadata for social media -->


    <!-- css -->
    <?php if (!$colorScheme = get_theme_option('Color scheme')) {
        $colorScheme = 'wine-violet';
    } ?>
    <?php queue_css_file(array('iconfonts', 'skeleton', 'theme', "color-schemes/$colorScheme")); ?>
    <?php echo head_css(); ?>

    <!-- javascripts -->
    <?php queue_js_file('vendor/jquery.hoverIntent.min'); ?>
    <?php queue_js_file('theme.min', 'javascripts'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/bootstrap.bundle.min'); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('photoswipe.min', 'photoswipe/dist'); ?>
    <?php queue_js_file('photoswipe-ui-default.min', 'photoswipe/dist'); ?>
    <?php queue_js_file('vendor/css-vars-ponyfill.min'); ?>
    <script type="application/javascript"
            src="https://cdn.jsdelivr.net/npm/citation-js@0.7.18/build/citation.min.js"></script>
    <?php echo head_js(); ?>

    <!-- print -->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT . "/themes/ehri/css/print.css"; ?>" media="print"/>

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:400,600|Roboto|Roboto+Mono"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- slick -->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT . "/themes/ehri/slick/slick.css"; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT . "/themes/ehri/slick/slick-theme.css"; ?>"/>

    <!-- photoswipe -->
    <link rel="stylesheet" type="text/css"
          href="<?php echo WEB_ROOT . "/themes/ehri/photoswipe/dist/photoswipe.css"; ?>"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo WEB_ROOT . "/themes/ehri/photoswipe/dist/default-skin/default-skin.css"; ?>"/>

    <?php if ($custom_css = trim(get_theme_option('custom_css'))): ?>
        <style>
            <?php echo $custom_css; ?>
        </style>
    <?php endif; ?>

    <!-- site title -->
    <?php
    $textTransform = 'uppercase';
    $textTransform = get_theme_option('title_display');
    if ($textTransform == "small-caps") {
        echo '<link rel="stylesheet" type="text/css" href="' .
            WEB_ROOT . '/themes/ehri/css/site-title-small-caps.css">';
    } else {
        $textTransform = 'font-variant: normal; text-transform:' . $textTransform;
    }
    ?>

    <script>
        // Polyfill CSS variables for older browsers.
        cssVars();
    </script>

    <?php if ($plausible_domain = trim(get_theme_option('plausible_domain'))): ?>
        <script defer data-domain="<?php echo $plausible_domain; ?>" src="https://plausible.io/js/script.js"></script>
    <?php endif; ?>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>

<!-- url and customized functions-->
<?php
$searchQuery = array_key_exists('q', $_GET) ? $_GET['q'] : '';
?>

<!-- desktop navbar -->
<div id="nav-bar-buttons">
    <a href="/search" class="nav-bar-button" id="nav-bar-button-search" data-target="#nav-bar-search"
       data-class="search-menu">
        <div id="nav-bar-icon-search" class="material-icons">search</div>
        <div id="nav-bar-icon-close-search" class="material-icons">close</div>
        <div id="nav-bar-icon-text-search" class="nav-bar-button-text"><?php echo __('Search'); ?></div>
    </a>
    <a href="/exhibits" class="nav-bar-button" id="nav-bar-button-menu" data-target="#nav-bar-menu"
       data-class="exhibits-menu">
        <div id="nav-bar-icon-menu" class="material-icons">menu</div>
        <div id="nav-bar-icon-close-menu" class="material-icons">close</div>
        <div id="nav-bar-icon-text-menu" class="nav-bar-button-text"><?php echo __('Menu'); ?></div>
    </a>
</div>

<div class="nav-bar" id="nav-bar-search">
    <div class="nav-bar-back" id="nav-bar-search-back">
        <div class="nav-bar-back-icon">chevron_left</div>
    </div>
    <div id="search-container" role="search">
        <?php echo search_form(array('submit_value' => 'search')); ?>
    </div>

    <!-- Applied facets. -->
    <?php if (plugin_is_active('SolrSearch')): ?>
        <?php $facets = SolrSearch_Helpers_Facet::parseFacets(); ?>
        <?php if (!empty($facets)): ?>
            <div id="solr-applied-facets" class="clearfix">
                <h2 class="nav-bar-search-category"><?php echo __('Applied facets'); ?></h2>

                <!-- Get the applied facets. -->
                <?php foreach ($facets as $f): ?>
                    <!-- Facet label. -->
                    <?php $url = SolrSearch_Helpers_Facet::removeFacet($f[0], $f[1]); ?>
                    <div class="nav-bar-search-item"><?php echo $f[1]; ?><a class="nav-bar-search-item-close"
                                                                            rel="nofollow"
                                                                            href="<?php echo $url; ?>">close</a></div>
                <?php endforeach; ?>
            </div>

            <div class="nav-bar-search-line"></div>
        <?php endif; ?>


        <!-- Facets. -->
        <?php $counts = isset($results) ? $results->facet_counts->facet_fields : array(); ?>
        <?php if (!empty($counts)): ?>
            <div id="solr-facets" class="clearfix">
            <div id="nav-bar-limit-toggle" data-target="#nav-bar-limit-search">
                <div id="nav-bar-limit-expand">keyboard_arrow_down</div>
                <div id="nav-bar-limit-shrink">keyboard_arrow_up</div>
                <h2 class="nav-bar-search-category"><?php echo __('Limit your search'); ?></h2>
            </div>
            <div id="nav-bar-limit-search">
                <?php foreach ($counts as $name => $facets): ?>
                    <!-- Does the facet have any hits? -->
                    <?php if (!empty(get_object_vars($facets))): ?>
                        <!-- Facet label. -->
                        <?php $label = SolrSearch_Helpers_Facet::keyToLabel($name); ?>
                        <div class="nav-bar-search-group"><?php echo __($label); ?></div>

                        <!-- Facets. -->
                        <?php foreach ($facets as $value => $count): ?>
                            <div class="nav-bar-search-item">

                                <!-- Facet URL. -->
                                <?php $url = SolrSearch_Helpers_Facet::addFacet($name, $value); ?>

                                <!-- Facet link. -->
                                <a href="<?php echo $url; ?>" class="facet-value" rel="nofollow">
                                    <?php echo $value; ?>
                                    <!-- Facet count. -->
                                    (<span class="facet-count"><?php echo $count; ?></span>)
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div class="nav-bar" id="nav-bar-menu">
    <div class="nav-bar-back" id="nav-bar-menu-back">
        <div class="nav-bar-back-icon">chevron_left</div>
    </div>
    <ul>
        <?php foreach (get_exhibit_menu_items() as $exhibit): ?>
            <li>
                <a href="<?php echo record_url($exhibit); ?>"><?php echo $exhibit->title; ?></a>
                <?php echo exhibit_builder_page_tree($exhibit); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<!-- / desktop navbar -->

<div id="container">
    <a href="#content" id="skipnav" rel="nofollow"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view' => $this)); ?>
    <header id="header" role="banner" style="background-image: url('<?php echo theme_header_image_url(); ?>');">
        <div id="header-overlay"></div>
        <div id="header-text">
            <?php fire_plugin_hook('public_header', array('view' => $this)); ?>
            <a href="<?php echo WEB_ROOT; ?>">
                <h1 id="site-title" style="<?php echo $textTransform; ?>"><?php echo option('site_title'); ?></h1>
            </a>
            <div id="site-subtitle"><?php echo $description; ?></div>
            <div id="site-logo"><?php echo link_to_home_page(theme_logo()); ?></div>
        </div>
        <?php if (isset($bodyid) && $bodyid === 'home'): ?>
            <div class="header-search-background"></div>
            <div id="header-search">
                <div id="search-container" role="search">
                    <?php echo search_form(array('submit_value' => 'search')); ?>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <div id="content" role="main" tabindex="-1">
        <div id="main">
            <?php fire_plugin_hook('public_content_top', array('view' => $this)); ?>
