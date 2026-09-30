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
     * Ratios the layout supports (Bootstrap .ratio-* classes).
     *
     * @var  string[]
     */
    private const ITEM_RATIOS = ['1x1', '4x3', '16x9', '21x9'];

    /**
     * Returns the layout data, or false to render nothing when there are no images.
     *
     * @return  array|false
     */
    protected function getLayoutData(): array|false
    {
        $data   = parent::getLayoutData();
        $params = $data['params'];

        $helper    = $this->getHelperFactory()->getHelper('PrettyphotoribbonHelper');
        $itemRatio = (string) $params->get('itemratio', '4x3');

        $data['app']              = Factory::getApplication();
        $data['itemsVisible']     = min(6, max(1, (int) $params->get('itemsvisible', 4)));
        $data['itemRatio']        = \in_array($itemRatio, self::ITEM_RATIOS, true) ? $itemRatio : '4x3';
        $data['ribbonItems']      = (int) $params->get('source', 0) === 1
            ? $helper->getFolderItems((string) $params->get('folder', ''))
            : $helper->prepareRibbonItems((array) $params->get('ribbonitems', []));
        $data['moduleId']         = (int) ($data['module']->id ?? 0);
        $data['autoplay']         = (bool) $params->get('autoplay', 0);
        $data['autoplayInterval'] = (int) $params->get('autoplay_interval', 5000);

        if ($data['ribbonItems'] === [])
        {
            return false;
        }

        return $data;
    }
}
