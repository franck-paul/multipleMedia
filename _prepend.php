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
 * @copyright GPL-2.0
 */

use Dotclear\Helper\Clearbricks;

// Public and Admin

if (!defined('DC_CONTEXT_ADMIN')) {
    return false;
}

// Admin only

Clearbricks::lib()->autoload([
    'multipleMediaAdminBehaviors' => __DIR__ . '/inc/admin.behaviors.php',
    'multipleMediaRest'           => __DIR__ . '/_services.php',
]);
