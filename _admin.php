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
dcCore::app()->addBehaviors([
    'adminPopupMediaManager'        => [multipleMediaAdminBehaviors::class, 'adminPopupMediaManager'],
    'adminPostEditor'               => [multipleMediaAdminBehaviors::class, 'adminPostEditor'],
    'adminBlogPreferencesFormV2'    => [multipleMediaAdminBehaviors::class, 'adminBlogPreferencesForm'],
    'adminBeforeBlogSettingsUpdate' => [multipleMediaAdminBehaviors::class, 'adminBeforeBlogSettingsUpdate'],
]);

// Register REST methods
dcCore::app()->rest->addFunction('getMediaInfos', [multipleMediaRest::class, 'getMediaInfos']);
