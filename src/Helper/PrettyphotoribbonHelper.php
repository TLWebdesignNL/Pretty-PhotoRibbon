<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace TlwebNamespace\Module\Prettyphotoribbon\Site\Helper;

\defined('_JEXEC') or die;

/**
 * Helper for mod_prettyphotoribbon
 *
 * @since  V1.0.0
 */
class PrettyphotoribbonHelper
{
    /**
     * Retrieve Prettyphotoribbon test
     *
     * @param   Registry        $params  The module parameters
     * @param   CMSApplication  $app     The application
     *
     * @return  string
     */
    public static function getText()
    {
        return 'PrettyphotoribbonHelpertest';
    }
}
