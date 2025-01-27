<?php

/**
 * @brief multipleMedia, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\multipleMedia;

use Dotclear\App;
use Dotclear\Helper\Date;
use Dotclear\Helper\File\File;
use Dotclear\Helper\File\Files;
use Exception;

class BackendRest
{
    /**
     * @param      array<string, string>   $get    The cleaned $_GET
     * @param      array<string, string>   $post   The cleaned $_POST
     *
     * @return     array<string, mixed>
     */
    public static function getMediaInfos($get, $post): array
    {
        $src_path = empty($post['path']) ? '' : $post['path'];
        $src_list = empty($post['list']) ? [] : json_decode($post['list']);
        $src_pref = empty($post['pref']) ? [] : json_decode($post['pref'], true);

        $data = [];

        try {
            $media = App::media();
            $media->chdir($src_path);
            $media->getDir();
        } catch (Exception) {
            return [
                'ret' => false,
            ];
        }

        // Get insertion settings (default or JSON local)

        $settings = My::settings();
        $defaults = [
            'block'     => $settings->block ?: '',
            'class'     => $settings->class ?: '',
            'size'      => App::blog()->settings()->system->media_img_default_size ?: 'm',
            'alignment' => App::blog()->settings()->system->media_img_default_alignment ?: 'none',
            'link'      => (bool) App::blog()->settings()->system->media_img_default_link,
            'legend'    => App::blog()->settings()->system->media_img_default_legend ?: 'legend',
            'mediadef'  => false,
        ];

        try {
            $local = $media->getPwd() . '/' . '.mediadef';
            if (!file_exists($local)) {
                $local .= '.json';
            }

            if (file_exists($local)) {
                $specifics = file_get_contents($local);
                if ($specifics !== false) {
                    $specifics = json_decode($specifics, true, 512, JSON_THROW_ON_ERROR);
                    foreach (array_keys($defaults) as $key) {
                        $defaults[$key]       = $specifics[$key] ?? $defaults[$key];
                        $defaults['mediadef'] = true;
                    }
                }
            }
        } catch (Exception) {
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

        // Function to get image alternate text
        // Copy from src/Process/Backend/MediaItem.php
        $getImageAlt = function (?File $file, bool $fallback = true): string {
            if (!$file instanceof File) {
                return '';
            }

            // Use metadata AltText if present
            if (is_countable($file->media_meta) && count($file->media_meta) && is_iterable($file->media_meta)) {
                foreach ($file->media_meta as $k => $v) {
                    if ((string) $v && ($k == 'AltText')) {
                        return (string) $v;
                    }
                }
            }

            // Fallback to title if present
            if ($fallback && $file->media_title !== '') {
                if ($file->media_title == $file->basename || Files::tidyFileName($file->media_title) == $file->basename) {
                    // Do not use media filename as title
                    return '';
                }

                return $file->media_title;
            }

            return '';
        };

        // Function to get image legend
        // Copy from src/Process/Backend/MediaItem.php
        $getImageLegend = function (?File $file, $pattern, bool $dto_first = false, bool $no_date_alone = false): string {
            if (!$file instanceof File) {
                return '';
            }

            $res     = [];
            $pattern = preg_split('/\s*;;\s*/', (string) $pattern);
            $sep     = ', ';
            $dates   = 0;
            $items   = 0;

            if ($pattern) {
                foreach ($pattern as $v) {
                    if ($v === 'Title' || $v === 'Description') { // Keep Title for compatibility purpose (since 2.29)
                        if (is_countable($file->media_meta) && count($file->media_meta) && is_iterable($file->media_meta)) {
                            foreach ($file->media_meta as $k => $v) {
                                if ((string) $v && ($k == 'Description')) {
                                    $res[] = $v;
                                    $items++;

                                    break;
                                }
                            }
                        }
                    } elseif ($file->media_meta->{$v}) {
                        $res[] = (string) $file->media_meta->{$v};
                        $items++;
                    } elseif (preg_match('/^Date\((.+?)\)$/u', $v, $m)) {
                        if ($dto_first && ($file->media_meta->DateTimeOriginal != 0)) {
                            $res[] = Date::dt2str($m[1], (string) $file->media_meta->DateTimeOriginal);
                        } else {
                            $res[] = Date::str($m[1], $file->media_dt);
                        }
                        $items++;
                        $dates++;
                    } elseif (preg_match('/^DateTimeOriginal\((.+?)\)$/u', $v, $m) && $file->media_meta->DateTimeOriginal) {
                        $res[] = Date::dt2str($m[1], (string) $file->media_meta->DateTimeOriginal);
                        $items++;
                        $dates++;
                    } elseif (preg_match('/^separator\((.*?)\)$/u', $v, $m)) {
                        $sep = $m[1];
                    }
                }
            }
            if ($no_date_alone && $dates === count($res) && $dates < $items) {
                // On ne laisse pas les dates seules, sauf si ce sont les seuls items du pattern (hors séparateur)
                return '';
            }

            return implode($sep, $res);
        };

        $list = [];
        foreach ($media->getFiles() as $file) {
            if (in_array($file->basename, $src_list) && $file->media_image) {
                // Prepare media infos
                $src = isset($file->media_thumb) ? ($file->media_thumb[$defaults['size']] ?? $file->file_url) : $file->file_url;

                $alt    = $getImageAlt($file);
                $legend = $getImageLegend($file, 'Description');

                // Add media
                $list[] = [
                    'src'         => $src,
                    'url'         => $file->file_url,
                    'title'       => ($defaults['legend'] !== 'none' ? $alt : ''),
                    'description' => ($defaults['legend'] === 'legend' ? $legend : ''),
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

        return [
            'ret'  => true,
            'info' => $data,
        ];
    }
}
