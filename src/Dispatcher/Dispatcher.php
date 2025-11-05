<?php

/**
 * @package     TLWeb.Module
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace TLWeb\Module\Prettyphotoribbon\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

\defined('_JEXEC') or die;

/**
 * Dispatcher class for the module.
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     *
     * @return  array
     */
    protected function getLayoutData(): array
    {
        $data   = parent::getLayoutData();
        $params = $data['params'];

        $helper = $this->getHelperFactory()->getHelper('PrettyphotoribbonHelper');

        $data['app']              = Factory::getApplication();
        $data['itemsVisible']     = (int) $params->get('itemsvisible', 4);
        $data['itemRatio']        = (string) $params->get('itemratio', '4x3');
        $data['ribbonItems']      = $helper->prepareRibbonItems((array) $params->get('ribbonitems', []));
        $data['moduleId']         = $data['module']->id ?? 0;
        $data['autoplay']         = (bool) $params->get('autoplay', 0);
        $data['autoplayInterval'] = (int) $params->get('autoplay_interval', 5000);

        return $data;
    }
}
