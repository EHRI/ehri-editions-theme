<?php if (plugin_is_active('Feedback')): ?>
    <div id="feedback">
        <div id="feedback-tab"><?php echo __('Feedback'); ?></div>
        <div id="feedback-form" style='display:none;' class="panel panel-default">
            <h5><?php echo __('Feedback'); ?></h5>
            <div id="feedback-close" class="material-icons">close</div>
            <form enctype="application/x-www-form-urlencoded" method="post" action="<?php echo $this->url('feedback/public/send'); ?>" class="form panel-body" role="form">
                <div class="form-group">
                    <input class="form-control" name="email" id="email" autofocus placeholder="<?php echo __('Your e-mail'); ?>" type="email" />
                    <input class="form-control" name="username" id="username" type="text" placeholder="Username" value="">
                    <input type="hidden" name="timestamp" id="timestamp" value="<?php echo (new DateTime())->format('Y-m-d H:i:s'); ?>">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="feedback" id="feedback" placeholder="<?php echo __('Your feedback...'); ?>" rows="8"></textarea>
                </div>
                <input class="form-control" name="url" id="url" type="hidden" value="<?php echo absolute_url(); ?>" />
                <input class="form-control" name="title" id="title" type="hidden" value="<?php echo metadata('item', array('Dublin Core', 'Title')); ?>" />
                <button class="feedback-button" type="submit"><?php echo __('Send'); ?></button>
            </form>
        </div>
        <div id="feedback-thanks"><?php echo __('Thanks for your feedback!'); ?></div>
    </div>
<?php endif; ?>
