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
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

class multipleMediaRest
{
    public static function getMediaInfos($core, $get)
    {
        $src_path = !empty($get['path']) ? $get['path'] : '';
        $src_list = !empty($get['list']) ? $get['list'] : '';
        $src_pref = !empty($get['pref']) ? $get['pref'] : '';

        $rsp  = new xmlTag('mm_select');
        $data = [];
        $ret  = false;

        try {
            $media = new dcMedia();
            $media->chdir($src_path);
            $media->getDir();
        } catch (Exception $e) {
            // Something goes wrong
            $rsp->ret = $ret;

            return $rsp;
        }

        // Get insertion settings (default or JSON local)

        dcCore::app()->blog->settings->addNamespace('multiplemedia');
        $defaults = [
            'block'     => dcCore::app()->blog->settings->multiplemedia->block ?: '',
            'class'     => dcCore::app()->blog->settings->multiplemedia->class ?: '',
            'size'      => dcCore::app()->blog->settings->system->media_img_default_size ?: 'm',
            'alignment' => dcCore::app()->blog->settings->system->media_img_default_alignment ?: 'none',
            'link'      => (bool) dcCore::app()->blog->settings->system->media_img_default_link,
            'legend'    => dcCore::app()->blog->settings->system->media_img_default_legend ?: 'legend',
            'mediadef'  => false,
        ];

        try {
            $local = $media->getPwd() . '/' . '.mediadef';
            if (!file_exists($local)) {
                $local .= '.json';
            }
            if (file_exists($local) && ($specifics = json_decode(file_get_contents($local) ?? '', true))) {  // @phpstan-ignore-line
                foreach ($defaults as $key => $value) {
                    $defaults[$key]       = $specifics[$key] ?? $defaults[$key];
                    $defaults['mediadef'] = true;
                }
            }
        } catch (Exception $e) {
            // Ignore exceptions
        }

        // Merge user setting (from popup)
        $defaults['size']      = $src_pref['size'] ?: $defaults['size'];
        $defaults['alignment'] = $src_pref['alignment'] ?: $defaults['alignment'];
        $defaults['link']      = $src_pref['link'] ? ($src_pref['link'] === '1') : $defaults['link'];
        $defaults['legend']    = $src_pref['legend'] ?: $defaults['legend'];
        $defaults['mediadef']  = $src_pref['mediadef'] ? ($src_pref['mediadef'] === '1') : $defaults['mediadef'];

        // Give back selected insertion settings
        $data['settings'] = $defaults;

        // Get full information for each media in list

        $get_img_desc = function ($f, $default = '') {
            if (count($f->media_meta) > 0) {
                foreach ($f->media_meta as $k => $v) {
                    if ((string) $v && ($k == 'Description')) {
                        return (string) $v;
                    }
                }
            }

            return (string) $default;
        };

        $list = [];
        foreach ($media->dir['files'] as $file) {
            if (in_array($file->basename, $src_list) && $file->media_image) {
                // Prepare media infos
                $src   = isset($file->media_thumb) ? ($file->media_thumb[$defaults['size']] ?? $file->file_url) : $file->file_url;  // @phpstan-ignore-line
                $title = $file->media_title ?? '';
                if ($title == $file->basename || files::tidyFileName($title) == $file->basename) {
                    $title = '';
                }
                $description = $get_img_desc($file, $title);
                // Add media
                $list[] = [
                    'src'         => $src,
                    'url'         => $file->file_url,
                    'title'       => ($defaults['legend'] !== 'none' ? $title : ''),
                    'description' => ($defaults['legend'] === 'legend' ? $description : ''),
                ];
            }
        }

        // Give back selected media info
        $data['list'] = $list;

        // Other info
        $data['media'] = [
            'path' => $src_path,
            'list' => $src_list,
            'pwd'  => $media->getPwd(),
        ];

        // Prepare return values

        $rsp->data = json_encode($data);
        $rsp->ret  = true;

        return $rsp;
    }
}
