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
            $image      = new \stdClass();
            $image->url = $folderUrl . rawurlencode($file);

            $item              = new \stdClass();
            $item->ribbonimage = $image;
            $items[]           = $item;
        }

        return $items;
    }

    /**
     * Prepare the ribbon items for rendering.
     *
     * Ensures the media paths are converted into valid URLs and filters out
     * empty items.
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
            $image->url = Uri::root() . ltrim($image->url, '/');

            $ribbonItem->ribbonimage = $image;
            $preparedItems[] = $ribbonItem;
        }

        return $preparedItems;
    }
}
