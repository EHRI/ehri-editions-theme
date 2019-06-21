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

<div id="content-share">
    <div id="content-share-subtitle"><?php echo __('Share'); ?></div>
    <div class="addthis_inline_share_toolbox"></div>
</div>

<h1 id="exhibit-title"><?php echo $title; ?></h1>

<div id="exhibit-blocks" class="clearfix">
    <?php exhibit_builder_render_exhibit_page(); ?>
</div>

<?php echo foot(); ?>
