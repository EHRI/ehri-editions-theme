<?php set_current_record('item', $item); ?>

<div class="item record">
    <h2><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title')), array('class' => 'permalink')); ?></h2>
    <?php if (metadata('item', 'has files')): ?>
        <div class="item-img">
            <?php echo link_to_item(item_image()); ?>
        </div>
    <?php endif; ?>
    <div class="item-meta">
        <?php if ($identifier = metadata('item', array('Dublin Core', 'Identifier'), array())): ?>
            <div class="item-description">
                <?php echo $identifier; ?>
            </div>
        <?php endif; ?>
        <?php
        $fields = array();
        foreach (array('Date', 'Creator', 'Coverage') as $field) {
            if ($fieldVal = trim(metadata('item', array('Dublin Core', $field), array()))) {
                $fields[] = $fieldVal;
            }
        }
        ?>
        <?php if ($fields): ?>
            <div class="item-description">
                <?php echo implode(' | ', $fields); ?>
            </div>
        <?php endif; ?>
        <?php if ($source = metadata('item', array('Dublin Core', 'Source'), array())): ?>
            <div class="item-description">
                <?php echo $source; ?>
            </div>
        <?php endif; ?>
        <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet' => 250))): ?>
            <div class="item-description">
                <?php echo $description; ?>
            </div>
        <?php endif; ?>

        <?php if (metadata('item', 'has tags')): ?>
            <div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
                    <?php echo tag_string('items'); ?></p>
            </div>
        <?php endif; ?>

        <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' => $item)); ?>
    </div>
</div>

