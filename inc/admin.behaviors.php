<?php
/**
 * @brief multipleMedia, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @copyright Franck Paul
 * @copyright GPL-2.0-only
 */
class multipleMediaAdminBehaviors
{
    public static function adminPopupMediaManager($editor = '')
    {
        if (empty($editor) || ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor')) {
            return;
        }

        return dcPage::jsLoad(dcPage::getPF('multipleMedia/js/popup_media_manager.js'));
    }

    public static function adminPostEditor($editor = '', $context = '', array $tags = [], $syntax = '')
    {
        global $core;

        if (empty($editor) || $editor != 'dcLegacyEditor') {
            return;
        }

        $defaults = [
            'size'      => $core->blog->settings->system->media_img_default_size ?: 'm',
            'alignment' => $core->blog->settings->system->media_img_default_alignment ?: 'none',
            'link'      => (bool) $core->blog->settings->system->media_img_default_link,
            'legend'    => $core->blog->settings->system->media_img_default_legend ?: 'legend',
            'mediadef'  => false,
        ];

        return
            dcPage::jsJson('mm_select', [
                'title'  => __('Insert multiple media'),
                'config' => $defaults,
            ]) .
            dcPage::jsLoad(urldecode(dcPage::getPF('multipleMedia/js/legacy-post.js')), $core->getVersion('multipleMedia'));
    }
}
