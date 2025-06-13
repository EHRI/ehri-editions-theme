<?php
if ($this->pageCount > 1):
    $getParams = $_GET;
    ?>
    <nav class="pagination-nav" aria-label="<?php echo __('Pagination'); ?>">
        <div class="search-results-pagination">
            <?php if (isset($this->previous)): ?>
                <!-- Previous page link -->
                <div class="search-results-previous">
                    <?php $getParams['page'] = $previous; ?>
                    <a rel="prev" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>">
                        <div class="search-results-image-previous">keyboard_arrow_left</div>
                        <?php echo __('Previous'); ?></a>
                </div>
            <?php else: ?>
                <div class="search-results-previous" style="opacity: 0.4">
                    <div class="search-results-image-previous">keyboard_arrow_left</div>
                    <?php echo __('Previous'); ?>
                </div>
            <?php endif; ?>

            <div class="search-results-center">
                <form action="<?php echo html_escape($this->url()); ?>" method="get" accept-charset="utf-8">
                    <?php
                    $hiddenParams = array();
                    $entries = explode('&', http_build_query($getParams));
                    foreach ($entries as $entry) {
                        if (!$entry) {
                            continue;
                        }
                        list($key, $value) = explode('=', $entry);
                        $hiddenParams[urldecode($key)] = urldecode($value);
                    }

                    foreach ($hiddenParams as $key => $value) {
                        if ($key != 'page') {
                            echo $this->formHidden($key, $value);
                        }
                    }

                    // Manually create this input to allow an omitted ID
                    $pageInput = '<input type="text" name="page" title="'
                        . html_escape(__('Current Page'))
                        . '" value="'
                        . html_escape($this->current) . '">';
                    echo __('%s of %s', $pageInput, $this->last);
                    ?>
                </form>
            </div>

            <?php if (isset($this->next)): ?>
                <!-- Next page link -->
                <div class="search-results-next">
                    <?php $getParams['page'] = $next; ?>
                    <a rel="next" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>">
                        <div class="search-results-image-next">keyboard_arrow_right</div>
                        <?php echo __('Next'); ?></a>
                </div>
            <?php else: ?>
                <div class="search-results-next" style="opacity: 0.4">
                    <div class="search-results-image-next">keyboard_arrow_right</div>
                    <?php echo __('Next'); ?>
                </div>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>
