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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

// Register Behaviors
$core->addBehavior('adminPopupMediaManager', ['multipleMediaAdminBehaviors', 'adminPopupMediaManager']);
$core->addBehavior('adminPostEditor', ['multipleMediaAdminBehaviors', 'adminPostEditor']);

// Register REST methods
$core->rest->addFunction('getMediaInfos', ['multipleMediaRest', 'getMediaInfos']);
