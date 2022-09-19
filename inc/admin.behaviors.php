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

        return dcPage::jsModuleLoad('multipleMedia/js/popup_media_manager.js');
    }

    public static function adminPostEditor($editor = '')
    {
        if (empty($editor) || $editor != 'dcLegacyEditor') {
            return;
        }

        return
            dcPage::jsJson('mm_select', [
                'title' => __('Insert multiple media'),
            ]) .
            dcPage::jsModuleLoad('multipleMedia/js/legacy-post.js', dcCore::app()->getVersion('multipleMedia'));
    }
}
