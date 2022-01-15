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
    'multipleMedia',            // Name
    'Insert multiple media',    // Description
    'Franck Paul',              // Author
    '1.1',                      // Version
    [
        'requires'    => [['core', '2.21']],
        'permissions' => 'usage,contentadmin',
        'priority'    => 1001,                  // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
        'type'        => 'plugin',
        'settings'    => ['self' => false],     // index.php is only used for popup action

        'details'    => 'https://github.com/franck-paul/multipleMedia',
        'support'    => 'https://github.com/franck-paul/multipleMedia',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/multipleMedia/main/dcstore.xml',
    ]
);
