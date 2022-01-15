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

        return
            dcPage::jsJson('mm_select', [
                'title' => __('Insert multiple media'),
            ]) .
            dcPage::jsLoad(urldecode(dcPage::getPF('multipleMedia/js/legacy-post.js')), $core->getVersion('multipleMedia'));
    }
}
