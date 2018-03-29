<?php

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

$searchQuery = array_key_exists('q', $_GET) ? $_GET['q'] : '';
?>

<?php echo head(array('title' => __('Solr Search'), 'results' => $results, 'bodyclass' => 'search'));?>

<?php if (isset($_GET["q"]) and trim($_GET["q"]) !== "" and ($results->response->numFound)>0): ?>
    <h1><?php echo __("Search Results for \"%s\": %d", $searchQuery, $results->response->numFound); ?></h1>

	<?php echo pagination_links(); ?>

	<!-- Results. -->
	<div class="search-results">

	  <!-- Number found. -->
		<?php $db = get_db(); ?>
		<?php foreach ($results->response->docs as $doc): ?>

			<?php $record = $db->getTable("Item")->find($doc->modelid); ?>
			<?php $url = SolrSearch_Helpers_Index::getUri($record); ?>

			<?php set_current_record("item", $record); ?>

			<div class="search-result">
				<?php
					$date = metadata($record, array('Dublin Core', 'Date'));
					$place = metadata($record, array('Dublin Core', 'Publisher'));
				?>
				<a href="<?php echo $url;?>">
					<?php if ($recordImage = record_image("item")):  ?>
						<?php echo $recordImage ?>
					<?php else: ?>
						<div class="search-result-image-blank"></div>
					<?php endif; ?>
				</a>
				<div class="search-result-wrapper">
					<a class="search-result-link" href="<?php echo $url; ?>">
						<div class="search-result-title"><?php echo metadata($record, 'display_title'); ?></div>
					</a>
					<?php if (isset($date)){ $addition = $date; } ?>
					<?php if (isset($place)){ $addition = $date . " | " . $place; }?>
					<?php if($addition){echo "<p>$addition</p>";} ?>
					<p><?php echo metadata($record, array('Dublin Core', 'Description'), array('snippet'=>300)); ?></p>
					<p>Languages: <?php echo metadata($record, array('Dublin Core', 'Language')); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php echo pagination_links(); ?>

<?php else: ?>
	<div id="no-results" class="search-result-empty"><h1>
	<?php if (strlen($searchQuery)>0) { ?>
		No results for “<?php echo $searchQuery; ?>”.
	<?php } else { ?>
		Search query is empty.
	<?php } ?>
	</h1></div>
<?php endif; ?>

<script>
<!-- show sidebar -->
if ($(window).width() < 930) {
	$("#nav-bar-search-mobile").show( 0, function() {});
	$("#nav-bar-button-search").attr('class', 'nav-bar-button-search');
} else {
	$("#nav-bar-mobile-icon-search").text('close');
	$("#nav-bar-mobile-button-search").attr('class', 'nav-bar-mobile-button-search-selected');
	$("#nav-bar-search").show( 0, function() {});
	$("#container").animate({ 'margin-left': '240px'}, 0);
	$("#footer").animate({ 'margin-left': '240px' }, 0);
	$("#nav-bar-limit-search").css('display', 'none');
	$("#nav-bar-limit-shrink").css('display', 'none');
	$("#nav-bar-limit-expand").css('display', 'inline');
	$("#nav-bar-button-search").attr('class', 'nav-bar-button-search-selected');
	$("#nav-bar-button-menu" ).attr('class', 'nav-bar-button-menu');
}

$( window ).resize(function() {
	<!-- hide logo -->
	if ($(window).width() < 1000) {
		$("#site-logo").hide( 0, function() {});
	} else {
		$("#site-logo").show( 0, function() {});
	}
	
	<!-- move content -->
	if ($(window).width() > 930) {
		$("#nav-bar-search").show( 0, function() {});
		$("#nav-bar-button-search").attr('class', 'nav-bar-button-search-selected');
		$("#nav-bar-button-menu" ).attr('class', 'nav-bar-button-menu');
		$("#container").animate({ 'margin-left': '240px'}, 0);
		$("#footer").animate({ 'margin-left': '240px' }, 0);
	}	
});

</script>

<?php echo foot();
