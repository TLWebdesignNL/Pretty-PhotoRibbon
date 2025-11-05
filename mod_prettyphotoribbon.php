<?php
/**
 * @package     TLWeb.Module
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

Factory::getApplication()
    ->bootModule('mod_prettyphotoribbon', 'site')
    ->getDispatcher($module, $params)
    ->dispatch();
