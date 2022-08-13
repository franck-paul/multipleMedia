<?php
/**
 * @brief multipleMedia, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}
// Only in popup mode
if (empty($_REQUEST['popup'])) {
    return;
}
?>
<html>
<head>
  <title><?php echo __('Insert multiple media'); ?></title>
<?php
echo dcPage::jsModuleLoad('multipleMedia/js/popup_media_prefs.js');
?>
</head>

<body>
<?php
echo dcPage::breadcrumb(
    [
        html::escapeHTML(dcCore::app()->blog->name) => '',
        __('Insert multiple media')                 => '',
    ]
) .
dcPage::notices();

$src_path = !empty($_REQUEST['d']) ? $_REQUEST['d'] : '';

try {
    $media = new dcMedia(dcCore::app());
    $media->chdir($src_path);
    $media->getDir();
} catch (Exception $e) {
    return;
}

// Get insertion settings (default or JSON local)

$defaults = [
    'size'      => dcCore::app()->blog->settings->system->media_img_default_size ?: 'm',
    'alignment' => dcCore::app()->blog->settings->system->media_img_default_alignment ?: 'none',
    'link'      => (bool) dcCore::app()->blog->settings->system->media_img_default_link,
    'legend'    => dcCore::app()->blog->settings->system->media_img_default_legend ?: 'legend',
    'mediadef'  => false,
];

try {
    $local = $media->getPwd() . '/' . '.mediadef';
    if (!file_exists($local)) {
        $local .= '.json';
    }
    if (file_exists($local)) {
        if ($specifics = json_decode(file_get_contents($local) ?? '', true)) {  // @phpstan-ignore-line
            foreach ($defaults as $key => $value) {
                $defaults[$key]       = $specifics[$key] ?? $defaults[$key];
                $defaults['mediadef'] = true;
            }
        }
    }
} catch (Exception $e) {
}

$img_sizes = [];
foreach ($media->thumb_sizes as $code => $size) {
    $img_sizes[__($size[2])] = $code;
}

echo
'<div id="media-insert" class="multi-part" title="' . __('Insertion preferences') . '">' .
'<form id="media-insert-pref" action="" method="get">' .
'<div class="two-boxes">' .
'<h3>' . __('Image size') . '</h3> ';
$s_checked = false;
foreach (array_reverse($img_sizes) as $s => $v) {
    $s_checked = ($v == $defaults['size']);
    echo '<label class="classic">' .
    form::radio(['src'], html::escapeHTML($v), $s_checked) . ' ' .
    $s . '</label><br /> ';
}
echo '<label class="classic">' .
form::radio(['src'], 'o') . ' ' . __('original') . '</label><br /> ';
echo '</p>';
echo '</div>';

echo
'<div class="two-boxes">' .
'<h3>' . __('Image legend and title') . '</h3>' .
'<p>' .
'<label for="legend1" class="classic">' . form::radio(
    ['legend', 'legend1'],
    'legend',
    ($defaults['legend'] == 'legend')
) .
__('Legend and title') . '</label><br />' .
'<label for="legend2" class="classic">' . form::radio(
    ['legend', 'legend2'],
    'title',
    ($defaults['legend'] == 'title')
) .
__('Title') . '</label><br />' .
'<label for="legend3" class="classic">' . form::radio(
    ['legend', 'legend3'],
    'none',
    ($defaults['legend'] == 'none')
) .
__('None') . '</label>' .
'</p>' .
'</div>';

echo
'<div class="two-boxes">' .
'<h3>' . __('Image alignment') . '</h3>';
$i_align = [
    'none'   => [__('None'), ($defaults['alignment']   == 'none' ? 1 : 0)],
    'left'   => [__('Left'), ($defaults['alignment']   == 'left' ? 1 : 0)],
    'right'  => [__('Right'), ($defaults['alignment']  == 'right' ? 1 : 0)],
    'center' => [__('Center'), ($defaults['alignment'] == 'center' ? 1 : 0)],
];

echo '<p>';
foreach ($i_align as $k => $v) {
    echo '<label class="classic">' .
    form::radio(['alignment'], $k, $v[1]) . ' ' . $v[0] . '</label><br /> ';
}
echo '</p>';
echo '</div>';

echo
'<div class="two-boxes">' .
'<h3>' . __('Image insertion') . '</h3>' .
'<p>' .
'<label for="insert1" class="classic">' . form::radio(['insertion', 'insert1'], 'simple', !$defaults['link']) .
__('As a single image') . '</label><br />' .
'<label for="insert2" class="classic">' . form::radio(['insertion', 'insert2'], 'link', $defaults['link']) .
__('As a link to the original image') . '</label>' .
    '</p>' .
    '</div>';

echo
'<p>' .
form::hidden(['mediadef'], $defaults['mediadef']) .
'<button type="button" id="media-insert-ok" class="submit">' . __('Insert') . '</button> ' .
'<button type="button" id="media-insert-cancel">' . __('Cancel') . '</button>' .
'</p>';

echo '</form></body></html>';

return;
