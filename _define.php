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

$this->registerModule(
    'multipleMedia',          // Name
    'Insert multiple media',  // Description
    'Franck Paul',     // Author
    '1.0',             // Version
    [
        'requires'    => [['core', '2.21']],        // Dependencies
        'permissions' => 'usage,contentadmin',      // Permissions
        'priority'    => 1001,                      // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
        'type'        => 'plugin',                  // Type

        'details'    => 'https://open-time.net/docs/plugins/multipleMedia',
        'support'    => 'https://github.com/franck-paul/multipleMedia',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/multipleMedia/master/dcstore.xml',
    ]
);
