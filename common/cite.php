<?php if ($doi = metadata('item', array('Item Type Metadata', 'DOI'))): ?>
    <div id="citation-widget" class="sidebar-section">
        <h4><a href="#" id="show-citation-dialog"><?php echo __('Cite this item'); ?></a></h4>
    </div>

    <dialog id="ehri-doi-citation-widget" class="citation-dialog">
        <div class="citation-container">
            <div class="citation-loading"><?php echo __('Loading citation data...'); ?></div>
            <div class="citation-formats" style="display:none;">
                <div class="citation-controls">
                    <label for="citation-format-selector"><?php echo __('Format:'); ?></label>
                    <select id="citation-format-selector" class="form-control form-control-sm">
                        <option value="apa">APA</option>
                        <option value="mla">MLA</option>
                        <option value="chicago">Chicago</option>
                        <option value="harvard">Harvard</option>
                        <option value="bibtex">BibTeX</option>
                        <option value="ris">RIS</option>
                    </select>
                    <button id="copy-citation" class="btn btn-xs btn-primary">
                        <i class="fa fa-copy"></i>
                        <?php echo __('Copy', 'edmp'); ?>
                    </button>
                </div>
                <div class="citation-result">
                    <pre id="citation-text"></pre>
                </div>
                <div class="citation-copied" style="display:none;"><?php echo __('Citation copied!'); ?></div>
            </div>
            <div class="citation-error" style="display:none;">
                <?php echo __('Error loading citation data.'); ?>
            </div>
            <div class="citation-controls-footer">
                <button id="close-citation-dialog" class="btn btn-sm btn-default" autofocus>
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </dialog>
    <script>
        // Pass the DOI to the JavaScript
        let CitationInfo = {
            doi: "<?php echo $doi; ?>",
            resolver: "<?php echo get_theme_option('edition_doi_citation_resolver'); ?>",
        };
    </script>
<?php endif; ?>