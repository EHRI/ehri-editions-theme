<?php echo $this->form('search-form', $options['form_attributes']); ?>
    <input name="query" id="query" type="search" title="<?php echo __('Search'); ?>" placeholder="<?php echo __('Search'); ?>" value="<?php echo @$_GET['q']; ?>"/>
    <?php echo $this->formHidden('query_type', 'boolean'); ?>
    <?php foreach ($filters['record_types'] as $type): ?>
    <?php echo $this->formHidden('record_types[]', $type); ?>
    <?php endforeach; ?>
    <?php echo $this->formButton('submit_search', $options['submit_value'], array('type' => 'submit')); ?>
</form>
