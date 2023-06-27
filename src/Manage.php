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
use dcMedia;
use dcNsProcess;
use dcPage;
use Dotclear\Helper\Html\Form\Button;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Hidden;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Radio;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::MANAGE)
            // Only in popup mode
            && !empty($_REQUEST['popup']);

        return static::$init;
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        $head = dcPage::jsModuleLoad(My::id() . '/js/popup_media_prefs.js');

        $src_path = !empty($_REQUEST['d']) ? $_REQUEST['d'] : '';

        try {
            $media = new dcMedia();
            $media->chdir($src_path);
            $media->getDir();
        } catch (Exception $e) {
            return;
        }

        dcPage::openModule(__('Insert multiple media'), $head);

        echo dcPage::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                __('Insert multiple media')                 => '',
            ]
        );
        echo dcPage::notices();

        // Form
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
                $specifics = file_get_contents($local);
                if ($specifics !== false) {
                    $specifics = json_decode($specifics, true, 512, JSON_THROW_ON_ERROR);
                    foreach ($defaults as $key => $value) {
                        $defaults[$key]       = $specifics[$key] ?? $defaults[$key];
                        $defaults['mediadef'] = true;
                    }
                }
            }
        } catch (Exception $e) {
            // Ignore exceptions
        }

        $img_sizes = [];
        foreach ($media->thumb_sizes as $code => $size) {
            $img_sizes[__($size[2])] = $code;
        }
        $sizes = [];
        $i     = 0;
        foreach (array_reverse($img_sizes, true) as $k => $v) {
            $sizes[] = (new Radio(['src', 'src' . ++$i], $v == $defaults['size']))
                    ->value(Html::escapeHTML($v))
                    ->label((new Label($k, Label::INSIDE_TEXT_AFTER)));
        }
        $sizes[] = (new Radio(['src', 'src' . ++$i]))
                ->value(Html::escapeHTML('o'))
                ->label((new Label(__('original'), Label::INSIDE_TEXT_AFTER)));

        $i_align = [
            'none'   => [__('None'), ($defaults['alignment'] == 'none' ? 1 : 0)],
            'left'   => [__('Left'), ($defaults['alignment'] == 'left' ? 1 : 0)],
            'right'  => [__('Right'), ($defaults['alignment'] == 'right' ? 1 : 0)],
            'center' => [__('Center'), ($defaults['alignment'] == 'center' ? 1 : 0)],
        ];
        $aligns = [];
        $i      = 0;
        foreach ($i_align as $k => $v) {
            $aligns[] = (new Radio(['alignment', 'alignment' . ++$i], (bool) $v[1]))
                ->value($k)
                ->label((new Label($v[0], Label::INSIDE_TEXT_AFTER)));
        }

        echo (new Div('media-insert'))->class('multi-part')->title(__('Insertion preferences'))
            ->items([
                (new Form('media-insert-pref'))
                    ->method('get')
                    ->fields([
                        (new Div())->class('two-boxes')->items([
                            (new Text('h3', __('Image size'))),
                            ...$sizes,
                        ]),

                        (new Div())->class('two-boxes')->items([
                            (new Text('h3', __('Image legend and title'))),
                            (new Radio(['legend', 'legend1'], $defaults['legend'] == 'legend'))
                                ->value('legend')
                                ->label((new Label(__('Legend and title'), Label::INSIDE_TEXT_AFTER))),
                            (new Radio(['legend', 'legend2'], $defaults['legend'] == 'title'))
                                ->value('title')
                                ->label((new Label(__('Title'), Label::INSIDE_TEXT_AFTER))),
                            (new Radio(['legend', 'legend3'], $defaults['legend'] == 'none'))
                                ->value('none')
                                ->label((new Label(__('None'), Label::INSIDE_TEXT_AFTER))),
                        ]),

                        (new Div())->class('two-boxes')->items([
                            (new Text('h3', __('Image alignment'))),
                            ...$aligns,
                        ]),

                        (new Div())->class('two-boxes')->items([
                            (new Text('h3', __('Image insertion'))),
                            (new Radio(['insertion', 'insert1'], (bool) !$defaults['link']))
                                ->value('simple')
                                ->label((new Label(__('As a single image'), Label::INSIDE_TEXT_AFTER))),
                            (new Radio(['insertion', 'insert2'], (bool) $defaults['link']))
                                ->value('link')
                                ->label((new Label(__('As a link to the original image'), Label::INSIDE_TEXT_AFTER))),
                        ]),

                        (new Para())->separator(' ')->items([
                            (new Hidden(['mediadef'], (string) $defaults['mediadef'])),
                            (new Button('media-insert-ok'))
                                ->class('submit')
                                ->value(__('Insert')),
                            (new Button('media-insert-cancel'))
                                ->class('submit')
                                ->value(__('Cancel')),
                        ]),
                    ]),
            ])
        ->render();

        dcPage::closeModule();
    }
}
