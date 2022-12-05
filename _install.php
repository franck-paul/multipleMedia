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

$new_version = dcCore::app()->plugins->moduleInfo('multipleMedia', 'version');
$old_version = dcCore::app()->getVersion('multipleMedia');

if (version_compare((string) $old_version, $new_version, '>=')) {
    return;
}

try {
    // Add default settings
    dcCore::app()->blog->settings->addNamespace('multiplemedia');
    dcCore::app()->blog->settings->multiplemedia->put('block', '', 'string', 'Container element', true, true);
    dcCore::app()->blog->settings->multiplemedia->put('class', '', 'string', 'Element class', true, true);

    dcCore::app()->setVersion('multipleMedia', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
