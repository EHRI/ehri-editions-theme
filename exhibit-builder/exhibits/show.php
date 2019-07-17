<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibit exhibits-menu show'));
?>

<?php $title = metadata('exhibit_page', 'title'); ?>

<div class="exhibit-pagination">
    <div class="item-pagination-previous"><?php echo exhibit_builder_link_to_previous_page_custom();?></div>
    <div class="item-pagination-next"><?php echo exhibit_builder_link_to_next_page_custom();?></div>
</div>

<div id="primary">
    <h1 id="exhibit-title"><?php echo $title; ?></h1>

    <div id="exhibit-blocks">
        <?php exhibit_builder_render_exhibit_page(); ?>
    </div>

    <div id="content-share">
        <?php echo $this->partial('common/content_share.php'); ?>
    </div>
</div>


<?php echo foot(); ?>
