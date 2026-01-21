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
$this->registerModule(
    'multipleMedia',
    'Insert multiple media',
    'Franck Paul',
    '7.5.1',
    [
        'date'        => '2026-01-21T10:28:08+0100',
        'requires'    => [['core', '2.36']],
        'permissions' => 'My',
        'priority'    => 1010,  // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
        'type'        => 'plugin',
        'settings'    => [
            'self' => false,                    // index.php is only used for popup action
            'blog' => '#params.multiplemedia',
        ],

        'details'    => 'https://github.com/franck-paul/multipleMedia',
        'support'    => 'https://github.com/franck-paul/multipleMedia',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/multipleMedia/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
