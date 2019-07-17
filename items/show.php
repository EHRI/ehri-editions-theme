<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'two-columns items show')); ?>

<?php
    $meta = '';
    $entities = '';

    $item = get_view()->{'item'};
    $texts = tei_editions_render_document_texts($item, $meta, $entities);
    $images = tei_editions_render_document_images($item);
    $related = get_related_item_documents($item);
    $refs = tei_editions_render_document_references($item);
    $exhibit = tei_editions_get_neatline_exhibit($item);
?>

<div id="primary">
	<div class="item-pagination">
        <div class="item-pagination-previous"><?php echo link_to_previous_item_show_custom(); ?></div>
        <div class="item-pagination-next"><?php echo link_to_next_item_show_custom(); ?></div>
    </div>

    <h1><?php echo metadata('item', array('Dublin Core','Title')); ?></h1>

	<div id="content-share-mobile">
        <?php echo $this->partial('common/content_share.php', array("item" => $item)); ?>
	</div>

    <!-- Items metadata -->
    <div id="item-texts">

        <div class="document-section-tabs">
            <a href="#document-metadata" class="items-content-button"><?php echo __('Metadata');?></a>

            <?php if ($texts): ?>
                <a href="#document-text" class="items-content-button"><?php echo __('Document Text'); ?></a>
            <?php endif; ?>

            <?php if ($exhibit): ?>
                <a href="#document-map" class="items-content-button"><?php echo __('Map'); ?></a>
            <?php endif; ?>

            <?php if ($related): ?>
                <a href="#document-related" class="items-content-button"><?php echo __('Related Documents'); ?></a>
            <?php endif; ?>
            <?php if ($refs): ?>
                <a href="#document-refs" class="items-content-button"><?php echo __('References'); ?></a>
            <?php endif; ?>
            <?php if (plugin_is_active('Commenting')): ?>
                <a href="#document-comments" class="items-content-button"><?php echo __('Comments'); ?></a>
            <?php endif; ?>
        </div>

        <!-- METADATA -->
        <div id="document-metadata" class="document-section">
            <h3><?php echo __('Metadata'); ?></h3>
            <?php echo $meta; ?>
        </div>

        <!-- Files, hidden in non-mobile view -->
        <div id="content-files-mobile" class="document-section">
            <?php echo $images; ?>
        </div>

        <?php if ($texts): ?>
            <div id="document-text" class="document-text document-section">
                <h3><?php echo __('Document Text');?></h3>
                <?php echo $texts; ?>
            </div>
        <?php endif; ?>

        <?php if ($exhibit): ?>
            <div id="document-map" class="document-section">
                <h3 class="document-map"><?php echo __('Map'); ?></h3>
                <a id="map-toggle-fullscreen" href="/neatline/fullscreen/<?php echo $exhibit->slug; ?>">
                    <span class="map-document-text"><?php echo __('Toggle fullscreen') ?></span>
                    <div id="map-toggle-fullscreen-icon" class="material-icons">fullscreen</div>
                </a>
                <iframe name="neatline" class="element-map" src="/neatline/fullscreen/<?php echo $exhibit->slug; ?>?neatline-embed=true"
                        width=100% height="410" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>

        <?php if ($related): ?>
            <div id="document-related" class="document-section">
                <h3><?php echo __('Related Documents'); ?></h3>
                <div class="related-documents">
                    <?php foreach ($related as $rel_item): ?>
                        <?php echo $this->partial('items/single.php', array("item" => $rel_item)); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($refs): ?>
            <div id="document-refs" class="document-section">
                <h3><?php echo __('References'); ?></h3>
                <?php echo $refs; ?>
            </div>
        <?php endif; ?>

        <?php if (plugin_is_active('Commenting')): ?>
            <div id="document-comments-container" class="document-section">
                <h4 id="document-comments"><?php echo __('Comments'); ?></h4>
                <div id="comments-empty" class="comments-empty"><?php echo __('There are no comments yet.'); ?></div>
                <?php CommentingPlugin::showComments(); ?>
                <button id="comment-expand-button" class="items-content-button"><?php echo __('Comment'); ?></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile navigation -->
	<div class="item-pagination">
        <div class="item-pagination-previous"><?php echo link_to_previous_item_show_custom(); ?></div>
        <div class="item-pagination-next"><?php echo link_to_next_item_show_custom(); ?></div>
    </div>
</div> <!-- End of Primary. -->

<div id="sidebar">
    <div id="content-share">
        <?php $pdf = tei_editions_get_first_file_with_extension($item, ".pdf"); ?>
        <?php $epub = tei_editions_get_first_file_with_extension($item, ".epub"); ?>
        <?php $xmlf = tei_editions_get_first_file_with_extension($item, ".xml"); ?>

        <?php if ($pdf or $epub or $xmlf): ?>
            <div class="sidebar-section">
                <h4><?php echo __('Download'); ?></h4>
                <div id="content-share-with">
                    <?php if ($pdf): ?>
                        <a href="<?php echo $pdf->getWebPath(); ?>" id="pdf-create" class="content-share-item">
                            <span class="content-share-item-icon">insert_drive_file</span>
                            PDF
                        </a>
                    <?php endif; ?>
                    <?php if ($epub): ?>
                        <a href="<?php echo $epub->getWebPath(); ?>" class="content-share-item">
                            <span class="content-share-item-icon">insert_drive_file</span>
                            E-pub
                        </a>
                    <?php endif; ?>
                    <?php if ($xmlf): ?>
                        <a href="<?php echo $xmlf->getWebPath(); ?>" class="content-share-item">
                            <span class="content-share-item-icon">insert_drive_file</span>
                            XML
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $this->partial('common/content_share.php'); ?>
        <?php echo $this->partial('common/feedback.php', array("item" => $item)); ?>
    </div>

    <div class="sidebar-section" id="content-files">
        <?php echo $images; ?>
    </div>
    <div id="content-info"></div>
    <?php echo $entities; ?>


</div>

<script>
    jQuery(function($) {
        $("#comment-expand-button").click(function (e) {
            $("#comment-form").show(400);
            $("#comment-expand-button").hide(0);
        });

        var $html = $('html, body');
        $("a.items-content-button").click(function(e) {
            var href = $.attr(this, "href");
            if (href && href.startsWith("#")) {
                e.preventDefault();
                $html.animate({
                    scrollTop: $(href).offset().top + 5
                }, 400, function() {
                    window.location.hash = href;
                });
            }
        })
    });
</script>
<?php echo foot(); ?>
