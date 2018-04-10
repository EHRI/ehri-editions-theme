<?php

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

$searchQuery = array_key_exists('q', $_GET) ? $_GET['q'] : '';
?>

<?php echo head(array('title' => __('Search'), 'results' => $results, 'bodyclass' => 'search-menu'));?>

<?php if ($results->response->numFound > 0): ?>
    <h1>
        <?php if ($searchQuery): ?>
            <?php echo __('Documents found: '.$results->response->numFound); ?>
        <?php else: ?>
            <?php echo __('All Documents'); ?>
        <?php endif; ?>
    </h1>

	<?php echo pagination_links(); ?>

	<!-- Results. -->
	<div class="search-results">

	  <!-- Number found. -->
		<?php $db = get_db(); ?>
		<?php foreach ($results->response->docs as $doc): ?>
            <?php if ($item = $db->getTable("Item")->find($doc->modelid)): ?>
                <?php echo tei_editions_render_item_summary($item); ?>
            <?php endif; ?>
		<?php endforeach; ?>
	</div>

	<?php echo pagination_links(); ?>

<?php else: ?>
	<div id="no-results" class="search-results-empty"><h1>
	<?php if (strlen($searchQuery)>0) { ?>
		<?php echo __("No results for \"%s\"", $searchQuery); ?>.
	<?php } else { ?>
		<?php echo __('Search query is empty.'); ?>
	<?php } ?>
	</h1></div>
<?php endif; ?>

<script>
jQuery(function($) {
    // Show/hide sidebar
    // if ($(window).width() < 930) {
		// //$("#nav-bar-search").show();
		// $("#nav-bar-mobile-icon-search").text('close');
		// $("#nav-bar-mobile-button-menu").addClass('nav-bar-mobile-button-menu');
		// $("#nav-bar-mobile-button-search").addClass('selected');
		// $("#nav-bar-mobile-icon-menu")
    //         .text('menu')
    //         .css({fontSize: 48, marginTop: 0});
		// $("#nav-bar-limit-toggle").css('cursor', 'pointer');
		// $("#nav-bar-limit-search").hide();
		// $("#nav-bar-limit-shrink").hide();
		// $("#nav-bar-limit-expand").show();
		// $("#header-image").hide();
    // } else {
    //     $("#nav-bar-mobile-icon-search").text('close');
    //     $("#nav-bar-mobile-button-search").addClass('selected');
    //     //$("#nav-bar-search").show();
    //     //$("#container").animate({marginLeft: 240}, 0);
    //     //$("#footer").animate({marginLeft: 240}, 0);
    //     //$("#nav-bar-button-search").addClass('nav-bar-button-search-selected');
    //     //$("#nav-bar-button-menu").addClass('nav-bar-button-menu');
    // }
    //
    // $(window).resize(function () {
    //     <!-- hide logo -->
    //     $("#site-logo").toggle(window.offsetWidth > 1000);
    //     $("#header-image").toggle(window.offsetWidth > 930);
    //
    //     <!-- move content -->
    //     if (window.offsetWidth > 930) {
    //         $("#nav-bar-search").show();
    //         $("#nav-bar-button-search").addClass('selected');
    //         $("#nav-bar-button-menu").attr('class', 'nav-bar-button-menu');
    //         $("#container").animate({marginLeft: 240}, 0);
    //         $("#footer").animate({marginLeft: 240}, 0);
    //     }
    // });
});
</script>

<?php echo foot();
