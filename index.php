<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'one-col')); ?>

<div id="primary">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
        <div class="homepage-text"><?php echo $homepageText; ?></div>
    <?php endif; ?>

    <?php if ($exhibitPage = get_homepage_exhibit_page()): ?>
        <div id="homepage-exhibit">
            <?php echo exhibit_builder_render_exhibit_page($exhibitPage); ?>
        </div>
    <?php endif; ?>

    <?php if (get_theme_option('Display Featured Item')): ?>
        <!-- Featured Item -->
        <div id="featured-items-container" class="featured">
            <h4><?php echo __('Featured Items'); ?></h4>
            <div class="featured-items">
                <?php echo random_featured_items(15); ?>
            </div>
        </div><!--end featured-item-->
    <?php endif; ?>

    <?php if (($recent = get_theme_option('Homepage Recent Items')) > 0): ?>
        <div id="recent-items-container" class="recent">
            <h4><?php echo __("Recently Added Items"); ?></h4>
            <div class="recent-items">
                <?php foreach (get_recent_items($recent) as $item): ?>
                    <?php echo $this->partial('items/single.php', array("item" => $item)); ?>
                    <?php release_object($item); ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div><!-- end secondary -->

<?php echo foot(); ?>
