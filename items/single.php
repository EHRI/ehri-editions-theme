<div class="item record">
    <div class="item-container">
        <a href="<?php echo record_url($item); ?>">
            <?php echo item_image(null, array('title' => metadata($item, 'display_title')), 0, $item); ?>
            <div class="item-title-overlay">
                <div class="item-title">
                    <?php echo metadata($item, 'display_title'); ?>
                </div>
            </div>
        </a>
    </div>
</div>
