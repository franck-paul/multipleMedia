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

use Dotclear\App;
use Dotclear\Core\Backend\Page;
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
    public static function adminPopupMediaManager(string $editor = ''): string
    {
        if ($editor === '' || ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor')) {
            return '';
        }

        return
        Page::jsJson('mm_media_manager', [
            'url' => App::backend()->url()->get('admin.plugin.' . My::id(), [
                'popup' => 1,
                'd'     => '',
            ], '&'),
        ]) .
        My::jsLoad('popup_media_manager.js');
    }

    public static function adminPostEditor(string $editor = ''): string
    {
        if ($editor === '' || $editor != 'dcLegacyEditor') {
            return '';
        }

        $data = [
            'title'    => __('Insert multiple media'),
            'icon'     => urldecode(Page::getPF(My::id() . '/icon.svg')),
            'open_url' => App::backend()->url()->get('admin.media', [
                'popup'     => 1,
                'plugin_id' => 'dcLegacyEditor',
                'select'    => 2,   // sélection multiple
            ], '&'),
            'style' => [  // List of classes used
                'class'  => true,
                'left'   => 'media-left',
                'center' => 'media-center',
                'right'  => 'media-right',
            ],
        ];

        return
            Page::jsJson('mm_select', $data) .
            My::jsLoad('legacy-post.js');
    }

    public static function adminBlogPreferencesForm(): string
    {
        $settings = My::settings();

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

        return '';
    }

    public static function adminBeforeBlogSettingsUpdate(): string
    {
        $settings = My::settings();

        $settings->put('block', $_POST['multiplemedia_block'], App::blogWorkspace()::NS_STRING);
        $settings->put('class', empty($_POST['multiplemedia_class']) ? '' : $_POST['multiplemedia_class'], App::blogWorkspace()::NS_STRING);

        return '';
    }
}
