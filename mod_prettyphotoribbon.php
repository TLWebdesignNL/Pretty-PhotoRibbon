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

$block            = $params->get('block');
$customouterclass = $params->get('customouterclass');
$before           = $params->get('before');
$buttons          = $params->get('buttons');
$after            = $params->get('after');

require ModuleHelper::getLayoutPath('mod_prettyphotoribbon', $params->get('layout', 'default'));
