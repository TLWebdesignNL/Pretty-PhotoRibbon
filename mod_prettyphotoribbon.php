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
use TlwebNamespace\Module\Prettyphotoribbon\Site\Helper\PrettyphotoribbonHelper;

// $test  = PrettyphotoribbonHelper::getText();

$itemsVisible = $params->get('itemsvisible');
$itemRatio    = $params->get('itemratio');
$ribbonItems  = $params->get('ribbonitems');
$moduleId     = $module->id;

foreach ($ribbonItems as &$ribbonItem)
{
	$ribbonItem->ribbonimage = HTMLHelper::_('cleanImageURL', $ribbonItem->ribbonimage);
	$ribbonItem->ribbonimage->url = Uri::root() . $ribbonItem->ribbonimage->url;
}

require ModuleHelper::getLayoutPath('mod_prettyphotoribbon', $params->get('layout', 'default'));
