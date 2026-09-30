<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Filesystem\File;

return new class () implements InstallerScriptInterface {
    private const MINIMUM_JOOMLA = '5.4.0';

    private const MINIMUM_PHP = '8.1.0';

    public function install(InstallerAdapter $adapter): bool
    {
        echo Text::_('MOD_PRETTYPHOTORIBBON_INSTALLERSCRIPT_INSTALL');

        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        echo Text::_('MOD_PRETTYPHOTORIBBON_INSTALLERSCRIPT_UPDATE');

        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        echo Text::_('MOD_PRETTYPHOTORIBBON_INSTALLERSCRIPT_UNINSTALL');

        return true;
    }

    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        if ($type === 'uninstall') {
            return true;
        }

        if (version_compare(PHP_VERSION, self::MINIMUM_PHP, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', self::MINIMUM_PHP), Log::WARNING, 'jerror');

            return false;
        }

        if (version_compare(JVERSION, self::MINIMUM_JOOMLA, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', self::MINIMUM_JOOMLA), Log::WARNING, 'jerror');

            return false;
        }

        return true;
    }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        if ($type === 'update') {
            $this->removeLegacyEntryFile();
        }

        return true;
    }

    /**
     * Up to 0.2.x the module used a mod_prettyphotoribbon.php entry file; since 0.3.0 it boots from services/provider.php.
     */
    private function removeLegacyEntryFile(): void
    {
        $file = JPATH_SITE . '/modules/mod_prettyphotoribbon/mod_prettyphotoribbon.php';

        if (is_file($file)) {
            File::delete($file);
        }
    }
};
