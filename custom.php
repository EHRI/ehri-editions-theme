<?php
/**
 * This file contains theme-specific functions and is loaded automatically by Omeka.
 */

if (!function_exists('ehri_footer_logo')) {
    /**
     * Get the theme's logo image tag.
     *
     * @param int $num the logo number to retrieve (default is 1).
     * @return string|null
     * @throws Zend_Exception
     * @package Omeka\Function\View\Head
     */
    function ehri_footer_logo($num = 1)
    {
        if ($logo = get_theme_option("Footer Logo$num")) {
            $link = get_theme_option("Footer Logo Info$num");
            $storage = Zend_Registry::get('storage');
            $uri = $storage->getUri($storage->getPathByType($logo, 'theme_uploads'));
            if ($link && ($parts = explode('|', $link)) !== false) {
                $text = $parts[0];
                $url = $parts[1];
                $out = "<a href='$url' title='$text' target='_blank'>";
                $out .= "<img class='footer-logo' alt='$text' src='$uri'/></a>";
                return $out;
            } else {
                return "<img alt='logo' class='footer-logo' src='$uri'/>";
            }
        }
        return "";
    }
}

if (!function_exists('get_exhibit_menu_items')) {
    /**
     * Get an array of exhibits ordered by the config value
     * 'Menu Ordering', which consists of comma-separated
     * exhibit slugs. If the value is unset exhibits will be
     * returned in primary key order.
     *
     * @return array of exhibits
     */
    function get_exhibit_menu_items()
    {
        $exhibits = get_db()->getTable('Exhibit')->findAll();
        if (($menu_order = get_theme_option("Menu Ordering")) and !empty(trim($menu_order))) {
            $slugs = array();
            foreach ($exhibits as $exhibit) {
                $slugs[$exhibit->slug] = $exhibit;
            }
            $ordered = array();
            foreach (explode(',', $menu_order) as $slug) {
                $key = trim($slug);
                if (array_key_exists($key, $slugs)) {
                    $ordered[] = $slugs[$key];
                } else {
                    error_log("Invalid slug in exhibit menu config: '$key'");
                }
            }
            return $ordered;
        }
        return $exhibits;
    }
}

if (!function_exists('get_homepage_exhibit_page')) {
    /**
     * Returns the first top page of the homepage exhibit, if set.
     *
     * @return ExhibitPage|null The first top page of the homepage exhibit or null if not set.
     */
    function get_homepage_exhibit_page()
    {
        if ($slug = get_theme_option('Homepage Exhibit')) {
            if ($e = get_db()->getTable('Exhibit')->findBy(array('slug' => $slug), $limit = 1)) {
                return $e[0]->getFirstTopPage();
            }
        }
        return null;
    }
}

if (!function_exists('link_to_next_item_show_custom')) {
    /**
     * Returns a link to the next item in the collection.
     *
     * @param string $text The text for the link.
     * @param array $props Additional HTML attributes for the link.
     * @return string HTML link to the next item or an empty string if no next item exists.
     */
    function link_to_next_item_show_custom($text = null, $props = array())
    {
        if (!$text) {
            $text = '<div id="next-item-icon" class="material-icons">keyboard_arrow_right</div>';
        }
        $item = get_current_record('item');
        if ($next = $item->next()) {
            $next_item = metadata($item->next(), array('Dublin Core', 'Title'), array('snippet' => 40));
            return link_to($next, 'show', $next_item . $text, $props);
        }
        return '';
    }
}

if (!function_exists('link_to_previous_item_show_custom')) {
    /**
     * Returns a link to the previous item in the collection.
     *
     * If no previous item exists, it returns an empty string.
     *
     * @param string $text The text to display for the link.
     * @param array $props Additional HTML attributes for the link.
     * @return string HTML link to the previous item or an empty string if none exists.
     */
    function link_to_previous_item_show_custom($text = null, $props = array())
    {
        if (!$text) {
            $text = '<div id="previous-item-icon" class="material-icons">keyboard_arrow_left</div>';
        }
        $item = get_current_record('item');
        if ($previous = $item->previous()) {
            $previous_item = metadata($item->previous(), array('Dublin Core', 'Title'), array('snippet' => 40));
            return link_to($previous, 'show', $text . $previous_item, $props);
        }
        return '';
    }
}

if (!function_exists('item_image_gallery_custom')) {
    /**
     * Returns a custom image gallery for an item.
     *
     * @param array $attrs Attributes for the gallery elements.
     * @param string $imageType The type of image to display (default is 'square_thumbnail').
     * @param bool $filesShow Whether to link to the file show page (default is false).
     * @param Item|null $item The item to display images for (default is the current item).
     * @return string HTML for the image gallery.
     */
    function item_image_gallery_custom(
        $attrs = array(),
        $imageType = 'square_thumbnail',
        $filesShow = false,
        $item = null
    ) {
        if (!$item) {
            $item = get_current_record('item');
        }

        $files = $item->Files;
        if (!$files) {
            return '';
        }

        $defaultAttrs = array(
            'wrapper' => array('id' => 'item-images'),
            'linkWrapper' => array('class' => 'gallery-item'),
            'figure' => array(),
            'link' => array(),
            'image' => array()
        );
        $attrs = array_merge($defaultAttrs, $attrs);

        $html = '';
        if ($attrs['wrapper'] !== null) {
            $html .= '<div ' . tag_attributes($attrs['wrapper']) . '>';
        }
        foreach ($files as $file) {
            if ($attrs['linkWrapper'] !== null) {
                $html .= '<figure ' . tag_attributes($attrs['linkWrapper']) . '>';
            }

            $image = file_image($imageType, $attrs['image'], $file);
            list($width, $height) = getimagesize($file->getWebPath('original'));

            if ($filesShow) {
                $html .= link_to($file, 'show', $image, $attrs['link']);
            } else {
                $linkAttrs = $attrs['link'] + array('href' => $file->getWebPath('original'));
                $html .= '<a ' . tag_attributes($linkAttrs)
                    . ' data-size=' . $width . 'x' . $height . '>' . $image . '</a>';
            }

            if ($attrs['linkWrapper'] !== null) {
                $html .= '</figure>';
            }
        }
        if ($attrs['wrapper'] !== null) {
            $html .= '</div>';
        }
        return $html;
    }
}

if (!function_exists('ehri_theme_header_image_url')) {
    /**
     * Returns the URL of the header image for the theme.
     * If no header image is set, it returns a default image URL.
     *
     * @return string The URL of the header image.
     */
    function ehri_theme_header_image_url()
    {
        $headerImage = ehri_get_theme_upload_url('Header Image');
        return $headerImage ?: (WEB_ROOT . "/themes/ehri/images/header-default.jpg");
    }
}

if (!function_exists('ehri_theme_sharing_image_url')) {
    /**
     * Returns the URL of the sharing image for the theme.
     * If no sharing image is set, it returns a default image URL.
     *
     * @return string The URL of the sharing image.
     * @throws Zend_Exception
     */
    function ehri_theme_sharing_image_url()
    {
        $sharingImage = ehri_get_theme_upload_url('Sharing Image');
        return $sharingImage ?: (WEB_ROOT . "/themes/ehri/images/header-default.jpg");
    }
}

if (!function_exists('ehri_get_theme_upload_url')) {
    /**
     * Gets the upload URL for a theme option.
     *
     * @param string $option_name The name of the theme option to retrieve the upload URL for.
     * @return string|null The URL of the uploaded file or null if the option is not set.
     * @throws Zend_Exception
     */
    function ehri_get_theme_upload_url($option_name)
    {
        $optionValue = get_theme_option($option_name);
        if ($optionValue) {
            $storage = Zend_Registry::get('storage');
            return $storage->getUri($storage->getPathByType($optionValue, 'theme_uploads'));
        }
        return null;
    }
}

if (!function_exists('ehri_get_representative_image')) {
    /**
     * Fetch a representative image for the current page. If this is an
     * item page, use the first file in the item. If this is an exhibit page,
     * use the exhibit page's image. Otherwise, use the theme sharing image.
     */
    function ehri_get_representative_image()
    {
        $item = get_current_record('item', false);
        if ($item && $item->Files) {
            // Use the first file of the item
            return $item->Files[0]->getWebPath('square_thumbnail');
        }

        $exhibitPage = get_current_record('exhibit_page', false);
        if ($exhibitPage && $exhibitPage->image) {
            // Use the exhibit page's image
            return $exhibitPage->image->getWebPath('square_thumbnail');
        }

        // Fallback to the theme sharing image
        return ehri_theme_sharing_image_url();
    }
}

if (!function_exists('get_related_item_documents')) {
    /**
     * Fetch a list of items (documents) related to this one.
     *
     * This uses the 'ItemRelations' plugin and considers any
     * type of relationship of both subject and object to be
     * a related item.
     *
     * @param Item $item
     * @return array an array of related items.
     */
    function get_related_item_documents($item)
    {
        if (plugin_is_active('ItemRelations')) {
            $related = [];
            foreach (ItemRelationsPlugin::prepareSubjectRelations($item) as $so) {
                if ($other = get_record_by_id('item', $so['object_item_id'])) {
                    $related[] = $other;
                }
            }
            foreach (ItemRelationsPlugin::prepareObjectRelations($item) as $oo) {
                if ($other = get_record_by_id('item', $oo['subject_item_id'])) {
                    $related[] = $other;
                }
            }
            return $related;
        }
        return [];
    }
}

if (!function_exists('exhibit_builder_link_to_next_page_custom')) {
    /**
     * Return a link to the next exhibit page
     *
     * @param string $text Link text
     * @param array $props Link attributes
     * @param ExhibitPage $exhibitPage If null, will use the current exhibit page
     * @return string
     */
    function exhibit_builder_link_to_next_page_custom($text = null, $props = array(), $exhibitPage = null)
    {
        if (!$exhibitPage) {
            $exhibitPage = get_current_record('exhibit_page');
        }

        $exhibit = get_record_by_id('Exhibit', $exhibitPage->exhibit_id);

        $targetPage = null;

        // if page object exists, grab link to the first child page if exists. If it doesn't, grab
        // a link to the next page
        $targetPage = $exhibitPage->firstChildOrNext();
        if ($targetPage) {
            if (!isset($props['class'])) {
                $props['class'] = 'next-page';
            }
            if ($text === null) {
                $text = metadata($targetPage, 'title') .
                    '<div id="next-item-icon" class="material-icons">keyboard_arrow_right</div>';
            }
            return exhibit_builder_link_to_exhibit($exhibit, $text, $props, $targetPage);
        }

        return null;
    }
}

if (!function_exists('exhibit_builder_link_to_previous_page_custom')) {
    /**
     * Return a link to the previous exhibit page
     *
     * @param string $text Link text
     * @param array $props Link attributes
     * @param ExhibitPage $exhibitPage If null, will use the current exhibit page
     * @return string
     */

    function exhibit_builder_link_to_previous_page_custom($text = null, $props = array(), $exhibitPage = null)
    {
        if (!$exhibitPage) {
            $exhibitPage = get_current_record('exhibit_page');
        }
        $exhibit = get_record_by_id('Exhibit', $exhibitPage->exhibit_id);

        // If page object exists, grab link to previous exhibit page if exists. If it doesn't, grab
        // a link to the last page on the previous parent page, or the exhibit if at top level
        $previousPage = $exhibitPage->previousOrParent();
        if ($previousPage) {
            if (!isset($props['class'])) {
                $props['class'] = 'previous-page';
            }
            if ($text === null) {
                $text = '<div id="previous-item-icon" class="material-icons">keyboard_arrow_left</div>' .
                    metadata($previousPage, 'title');
            }
            return exhibit_builder_link_to_exhibit($exhibit, $text, $props, $previousPage);
        }

        return null;
    }
}
