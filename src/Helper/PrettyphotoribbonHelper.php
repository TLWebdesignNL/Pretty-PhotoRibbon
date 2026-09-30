<?php

/**
 * @package     TLWeb.Module
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace TLWeb\Module\Prettyphotoribbon\Site\Helper;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;

\defined('_JEXEC') or die;

/**
 * Helper for mod_prettyphotoribbon
 *
 * @since  V1.0.0
 */
class PrettyphotoribbonHelper
{
    /**
     * Maximum number of images taken from a folder (same limit as the manual subform).
     *
     * @var  int
     */
    private const MAX_FOLDER_ITEMS = 30;

    /**
     * Build ribbon items from the images in a folder below /images.
     *
     * Returns an empty array when the folder is not set, no longer exists or
     * resolves to a path outside /images.
     *
     * @param   string  $folder  Folder relative to /images, as stored by the folderlist field.
     *
     * @return  array
     */
    public function getFolderItems(string $folder): array
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');

        if ($folder === '' || $folder === '-1' || \in_array('..', explode('/', $folder), true))
        {
            return [];
        }

        $basePath   = realpath(JPATH_ROOT . '/images');
        $folderPath = realpath(JPATH_ROOT . '/images/' . $folder);

        if ($basePath === false || $folderPath === false || !is_dir($folderPath)
            || strpos($folderPath . DIRECTORY_SEPARATOR, $basePath . DIRECTORY_SEPARATOR) !== 0)
        {
            return [];
        }

        try
        {
            $files = Folder::files($folderPath, '(?i)\.(jpe?g|png|gif|webp)$');
        }
        catch (\UnexpectedValueException $e)
        {
            return [];
        }

        $folderUrl = Uri::root() . 'images/' . implode('/', array_map('rawurlencode', explode('/', $folder))) . '/';
        $items     = [];

        foreach (\array_slice($files, 0, self::MAX_FOLDER_ITEMS) as $file)
        {
            // Folder images have no alt text, so they are treated as decorative.
            $size    = @getimagesize($folderPath . '/' . $file);
            $items[] = $this->createItem(
                $folderUrl . rawurlencode($file),
                $size[0] ?? 0,
                $size[1] ?? 0,
                ''
            );
        }

        return $items;
    }

    /**
     * Prepare the ribbon items for rendering.
     *
     * Ensures the media paths are converted into valid URLs, resolves the
     * alt text (empty for decorative images) and filters out empty items.
     *
     * @param   array  $ribbonItems  The configured ribbon items.
     *
     * @return  array
     */
    public function prepareRibbonItems(array $ribbonItems): array
    {
        $preparedItems = [];

        foreach ($ribbonItems as $ribbonItem)
        {
            if (!isset($ribbonItem->ribbonimage) || empty($ribbonItem->ribbonimage))
            {
                continue;
            }

            $image = HTMLHelper::_('cleanImageURL', $ribbonItem->ribbonimage);
            $url   = $image->url;

            // Only relative paths from the media field need the site root.
            if (!preg_match('#^(?:[a-z][a-z0-9+.-]*:)?//#i', $url))
            {
                $url = Uri::root() . ltrim($url, '/');
            }

            $alt = empty($ribbonItem->alt_empty) ? trim((string) ($ribbonItem->alt ?? '')) : '';

            $preparedItems[] = $this->createItem(
                $url,
                (int) ($image->attributes['width'] ?? 0),
                (int) ($image->attributes['height'] ?? 0),
                $alt
            );
        }

        return $preparedItems;
    }

    /**
     * Build one ribbon item as used by the layout.
     *
     * @param   string  $url     Absolute image URL.
     * @param   int     $width   Intrinsic width in pixels, 0 when unknown.
     * @param   int     $height  Intrinsic height in pixels, 0 when unknown.
     * @param   string  $alt     Alt text, empty for a decorative image.
     *
     * @return  \stdClass
     */
    private function createItem(string $url, int $width, int $height, string $alt): \stdClass
    {
        $image         = new \stdClass();
        $image->url    = $url;
        $image->width  = $width;
        $image->height = $height;

        $item              = new \stdClass();
        $item->ribbonimage = $image;
        $item->alt         = $alt;

        return $item;
    }
}
