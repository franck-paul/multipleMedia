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
use Dotclear\Helper\Process\TraitProcess;
use Exception;

class Install
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Update
            $old_version = App::version()->getVersion(My::id());
            // Rename settings namespace
            if (version_compare((string) $old_version, '3.0', '<') && App::blog()->settings()->exists('multiplemedia')) {
                App::blog()->settings()->delWorkspace(My::id());
                App::blog()->settings()->renWorkspace('multiplemedia', My::id());
            }

            // Add default settings
            $settings = My::settings();
            $settings->put('block', '', App::blogWorkspace()::NS_STRING, 'Container element', true, true);
            $settings->put('class', '', App::blogWorkspace()::NS_STRING, 'Element class', true, true);
        } catch (Exception $exception) {
            App::error()->add($exception->getMessage());
        }

        return true;
    }
}
