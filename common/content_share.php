<?php $pdf = tei_editions_get_first_file_with_extension($item, ".pdf"); ?>
<?php $epub = tei_editions_get_first_file_with_extension($item, ".epub"); ?>
<?php $xmlf = tei_editions_get_first_file_with_extension($item, ".xml"); ?>

<?php if ($pdf or $epub or $xmlf): ?>
    <div id="content-share-title"><?php echo __('Download'); ?></div>
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
<?php endif; ?>

<div id="content-share-subtitle"><?php echo __('Share'); ?></div>
<div class="addthis_inline_share_toolbox"></div>

