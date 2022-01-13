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
if (!defined('DC_RC_PATH')) {
    return;
}

// Public and Admin

if (!defined('DC_CONTEXT_ADMIN')) {
    return false;
}

// Admin only

$__autoload['multipleMediaAdminBehaviors'] = dirname(__FILE__) . '/inc/admin.behaviors.php';
$__autoload['multipleMediaRest']           = dirname(__FILE__) . '/_services.php';
