<script>
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
</script>

<?php

/**
 * Get the theme's logo image tag.
 *
 * @package Omeka\Function\View\Head
 * @uses get_theme_option()
 * @return string|null
 */
function footer_logo($num = 1)
{
    if ($logo = get_theme_option("Footer Logo$num")) {
        $storage = Zend_Registry::get('storage');
        $uri = $storage->getUri($storage->getPathByType($logo, 'theme_uploads'));
        return "<img class='footer-logo' src='$uri'/>";
    }
}

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
            if (array_key_exists( $key, $slugs)) {
                $ordered[] = $slugs[$key];
            } else {
                error_log("Invalid slug in exhibit menu config: '$key'");
            }
        }
        return $ordered;
    }
    return $exhibits;
}

function get_homepage_exhibit_page()
{
    if ($slug = get_theme_option('Homepage Exhibit')) {
        if ($e = get_db()->getTable('Exhibit')->findBy(array('slug' => $slug), $limit = 1)) {
            return $e[0]->getFirstTopPage();
        }
    }
    return null;
}

function link_to_next_item_show_custom($text = null, $props = array())
{
    if (!$text) {
        $text = '<div id="next-item-icon" class="material-icons">keyboard_arrow_right</div>';
    }
    $item = get_current_record('item');
    if($next = $item->next()) {
		$next_item = metadata($item->next(), array('Dublin Core', 'Title'), array('snippet' => 40));
        return link_to($next, 'show', $text . $next_item, $props);
    }
}

function link_to_previous_item_show_custom($text = null, $props = array())
{
    if (!$text) {
        $text = '<div id="previous-item-icon" class="material-icons">keyboard_arrow_left</div>';
    }
    $item = get_current_record('item');
    if($previous = $item->previous()) {
		$previous_item = metadata($item->previous(), array('Dublin Core', 'Title'), array('snippet' => 40));
        return link_to($previous, 'show', $previous_item . $text, $props);
    }
}

function item_image_gallery_custom($attrs = array(), $imageType = 'square_thumbnail', $filesShow = false, $item = null)
{
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
            $html .= '<a ' . tag_attributes($linkAttrs) . ' data-size=' . $width . 'x' . $height . '>' . $image . '</a>';
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

function theme_header_image_url()
{
    $headerImage = get_theme_option('Header Image');
    if ($headerImage) {
        $storage = Zend_Registry::get('storage');
        $headerImage = $storage->getUri($storage->getPathByType($headerImage, 'theme_uploads'));
    } else { 
        $headerImage = WEB_ROOT . "/themes/ehri/images/header-default.jpg";
    }
    return $headerImage;
}

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
            $text = metadata($targetPage, 'title') . '<div id="next-item-icon" class="material-icons">keyboard_arrow_right</div>';
        }
        return exhibit_builder_link_to_exhibit($exhibit, $text, $props, $targetPage);
    }

    return null;
}

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
        if(!isset($props['class'])) {
            $props['class'] = 'previous-page';
        }
        if ($text === null) {
            $text = '<div id="previous-item-icon" class="material-icons">keyboard_arrow_left</div>' . metadata($previousPage, 'title');
        }
        return exhibit_builder_link_to_exhibit($exhibit, $text, $props, $previousPage);
    }

    return null;
}
?>
