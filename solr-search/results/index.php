<?php
$searchQuery = array_key_exists('q', $_GET) ? trim($_GET['q']) : '';
?>

<?php echo head(array('title' => __('Search'), 'results' => $results, 'bodyclass' => 'search-menu')); ?>

<?php if ($results->response->numFound > 0): ?>
    <h1>
        <?php echo __("Documents found: %d", $results->response->numFound); ?>
    </h1>

    <?php echo pagination_links(); ?>

    <ol class="item-list search-results">
        <?php $doc_ids = array_map(function($doc) { return $doc->modelid; }, $results->response->docs); ?>
        <?php $items = get_records("Item", array("range" => implode(',', $doc_ids)), $results->response->numFound); ?>
        <?php $items_id = array_column($items, null, 'id'); ?>
        <?php foreach ($results->response->docs as $doc): ?>
            <?php $item = $items_id[$doc->modelid]; ?>
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
