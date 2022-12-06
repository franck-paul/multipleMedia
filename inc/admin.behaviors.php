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

        return dcPage::jsModuleLoad('multipleMedia/js/popup_media_manager.min.js');
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
            dcPage::jsModuleLoad('multipleMedia/js/legacy-post.min.js', dcCore::app()->getVersion('multipleMedia'));
    }

    public static function adminBlogPreferencesForm($settings)
    {
        $block_combo = [
            __('None')    => '',
            __('div')     => 'div',
            __('p')       => 'p',
            __('aside')   => 'aside',
            __('article') => 'article',
            __('section') => 'section',
        ];

        $settings->addNameSpace('multiplemedia');
        echo
        '<div class="fieldset"><h4 id="multiplemedia">multipleMedia</h4>' .
        '<p class="field"><label for="multiplemedia_block" class="classic">' . __('Container HTML element:') . '</label> ' .
        form::combo(['multiplemedia_block'], $block_combo, $settings->multiplemedia->block) . '</p>' .
        '<p><label>' .
        __('HTML element class(es):') . ' ' .
        form::field('multiplemedia_class', 25, 50, $settings->multiplemedia->class) .
        '</label></p>' .
        '<p class="form-note">' .
        __('Comma separated list of classes, leave it empty to not use it.') . '&nbsp;</p>' .
        '</div>';
    }

    public static function adminBeforeBlogSettingsUpdate($settings)
    {
        $settings->addNameSpace('multiplemedia');
        $settings->multiplemedia->put('block', $_POST['multiplemedia_block'], 'string');
        $settings->multiplemedia->put('class', !empty($_POST['multiplemedia_class']) ? $_POST['multiplemedia_class'] : '', 'string');
    }
}
