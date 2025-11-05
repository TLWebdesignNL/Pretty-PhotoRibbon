<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;

//use TlwebNamespace\Module\Prettyphotoribbon\Site\Helper\PrettyphotoribbonHelper;
// $test  = PrettyphotoribbonHelper::getText();

$itemsVisible = $params->get('itemsvisible');
$itemRatio    = $params->get('itemratio');
$ribbonItems  = $params->get('ribbonitems');
$source       = $params->get('source', '0');
$folder       = $params->get('folder');
$moduleId     = $module->id;

if ($source == 0) {
    if (isset($ribbonItems->ribbonitems0->ribbonimage) && !empty($ribbonItems->ribbonitems0->ribbonimage)) {
        foreach ($ribbonItems as &$ribbonItem) {
            $ribbonItem->ribbonimage      = HTMLHelper::_('cleanImageURL', $ribbonItem->ribbonimage);
            $ribbonItem->ribbonimage->url = Uri::root() . $ribbonItem->ribbonimage->url;
            unset($ribbonItem->ribbonimage->attributes);
        }
    } else {
        $ribbonItems = null;
    }
} elseif ($source == 1) {
    if ($folder) {
        $folderUrl  = Uri::root() . "images/" . $folder;
        $folderPath = JPATH_ROOT . "/images/" . $folder;
        $extensions = 'jpg|jpeg|png|gif|webp';

        $images = Folder::files($folderPath, '\.(' . $extensions . ')$', false, true);

        foreach ($images as $index => $image) {
            $ribbonImage = new stdClass();
            $ribbonImage->url = $folderUrl . '/' . str_replace($folderPath, '', $image);
            $ribbonItem = new stdClass();
            $ribbonItem->ribbonimage = $ribbonImage;
            $ribbonItems->{"ribbonitems" . $index} = $ribbonItem;
        }
    } else {
        $ribbonItems = null;
    }
} else {
    $ribbonItems = null;
}

require ModuleHelper::getLayoutPath('mod_prettyphotoribbon', $params->get('layout', 'default'));
