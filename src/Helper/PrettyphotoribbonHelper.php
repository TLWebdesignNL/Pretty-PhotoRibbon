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

\defined('_JEXEC') or die;

/**
 * Helper for mod_prettyphotoribbon
 *
 * @since  V1.0.0
 */
class PrettyphotoribbonHelper
{
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
