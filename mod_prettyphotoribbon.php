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
use TlwebNamespace\Module\Prettyphotoribbon\Site\Helper\PrettyphotoribbonHelper;

// $test  = PrettyphotoribbonHelper::getText();

$itemsVisible	  = $params->get('itemsvisible');
$itemRatio		  = $params->get('itemratio');
$ribbonItems   	  = $params->get('ribbonitems');

require ModuleHelper::getLayoutPath('mod_prettyphotoribbon', $params->get('layout', 'default'));
