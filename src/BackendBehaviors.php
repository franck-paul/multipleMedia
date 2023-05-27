<?php
/**
 * @brief multipleMedia, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\multipleMedia;

use dcCore;
use dcNamespace;
use dcPage;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;

class BackendBehaviors
{
    public static function adminPopupMediaManager($editor = '')
    {
        if (empty($editor) || ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor')) {
            return;
        }

        return dcPage::jsModuleLoad(My::id() . '/js/popup_media_manager.js');
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
            dcPage::jsModuleLoad(My::id() . '/js/legacy-post.js', dcCore::app()->getVersion(My::id()));
    }

    public static function adminBlogPreferencesForm()
    {
        /**
         * @var        \dcNamespace
         */
        $settings = dcCore::app()->blog->settings->get(My::id());

        $block_combo = [
            __('None')    => '',
            __('div')     => 'div',
            __('p')       => 'p',
            __('aside')   => 'aside',
            __('article') => 'article',
            __('section') => 'section',
        ];

        echo
        (new Fieldset('multiplemedia'))
        ->legend((new Legend(My::id())))
        ->fields([
            (new Para())->items([
                (new Select('multiplemedia_block'))
                ->items($block_combo)
                ->default($settings->block)
                ->label((new Label(__('Container HTML element:'), Label::INSIDE_TEXT_BEFORE))),
            ]),
            (new Para())->items([
                (new Input('multiplemedia_class'))
                    ->size(50)
                    ->maxlength(128)
                    ->value(Html::escapeHTML($settings->class))
                    ->label((new Label(__('HTML element class(es):'), Label::OUTSIDE_TEXT_BEFORE))),
            ]),
            (new Para())->class('form-note')->items([
                (new Text(null, __('Comma separated list of classes, leave it empty to not use it.'))),
            ]),
        ])
        ->render();
    }

    public static function adminBeforeBlogSettingsUpdate()
    {
        /**
         * @var        \dcNamespace
         */
        $settings = dcCore::app()->blog->settings->get(My::id());

        $settings->put('block', $_POST['multiplemedia_block'], dcNamespace::NS_STRING);
        $settings->put('class', !empty($_POST['multiplemedia_class']) ? $_POST['multiplemedia_class'] : '', dcNamespace::NS_STRING);
    }
}