<?php
$searchQuery = array_key_exists('q', $_GET) ? trim($_GET['q']) : '';
?>

<?php echo head(array('title' => __('Search'), 'results' => $results, 'bodyclass' => 'search-menu'));?>

<?php if ($results->response->numFound > 0): ?>
    <h1>
        <?php echo __("Documents found: %d", $results->response->numFound); ?>
    </h1>

	<?php echo pagination_links(); ?>

	<ol class="item-list search-results">
		<?php foreach ($results->response->docs as $doc): ?>
        <?php $item = get_db()->getTable("Item")->find($doc->modelid); ?>
            <?php if ($item): ?>
            <li>
                <?php echo get_view()->partial('items/single.php', array('item' => $item)); ?>
            </li>
            <?php endif; ?>
		<?php endforeach; ?>
	</ol>

	<?php echo pagination_links(); ?>

<?php else: ?>
	<div id="no-results" class="search-results-empty"><h1>
	<?php if ($searchQuery) { ?>
		<?php echo __("No results for \"%s\"", $searchQuery); ?>
	<?php } else { ?>
		<?php echo __('Search query is empty'); ?>
	<?php } ?>
	</h1></div>
<?php endif; ?>

<?php echo foot();
