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
dcCore::app()->addBehavior('adminPopupMediaManager', [multipleMediaAdminBehaviors::class, 'adminPopupMediaManager']);
dcCore::app()->addBehavior('adminPostEditor', [multipleMediaAdminBehaviors::class, 'adminPostEditor']);

// Register REST methods
dcCore::app()->rest->addFunction('getMediaInfos', [multipleMediaRest::class, 'getMediaInfos']);
