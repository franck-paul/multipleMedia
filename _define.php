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
    '6.3',
    [
        'date'        => '2025-06-05T14:36:50+0200',
        'requires'    => [['core', '2.33']],
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
