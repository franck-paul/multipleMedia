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
use Dotclear\App;
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
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
            $old_version = dcCore::app()->getVersion(My::id());
            if (version_compare((string) $old_version, '3.0', '<')) {
                // Rename settings namespace
                if (App::blog()->settings()->exists('multiplemedia')) {
                    App::blog()->settings()->delNamespace(My::id());
                    App::blog()->settings()->renNamespace('multiplemedia', My::id());
                }
            }

            // Add default settings
            $settings = My::settings();
            $settings->put('block', '', dcNamespace::NS_STRING, 'Container element', true, true);
            $settings->put('class', '', dcNamespace::NS_STRING, 'Element class', true, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
