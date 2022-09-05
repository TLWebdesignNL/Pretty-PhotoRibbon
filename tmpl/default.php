<?php

/**
 * @package        Joomla.Site
 * @subpackage     mod_prettyphotoribbon
 *
 * @copyright      Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.carousel', 'prettyRibbon' . $moduleId);
HTMLHelper::_('bootstrap.carousel', 'prettyRibbonModalCarousel' . $moduleId);
HTMLHelper::_('bootstrap.modal', 'prettyRibbonModal' . $moduleId);

$itemsVisibleRatio = (1 / $itemsVisible) * 100;
$slideCounter = 0;
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseScript(
    'prettyphotoribbon',
    'mod_prettyphotoribbon/prettyphotoribbon.min.js',
    [],
    ['type' => 'module']
);
$wa->registerAndUseStyle('prettyphotoribboncss', 'mod_prettyphotoribbon/prettyphotoribbon.min.css', [], [], []);
?>
<div class="prettyRibbonWrapper">
    <div id="prettyRibbonCarousel<?php echo $moduleId; ?>" class="carousel" data-bs-ride="carousel">
        <div class="carousel-inner" data-bs-toggle="modal" data-bs-target="#prettyRibbonModal<?php echo $moduleId; ?>">
            <?php
            $slideCounter = 0;
            foreach ($ribbonItems as $r) :
                ?>
                <div
                        class="carousel-item <?php echo ($slideCounter == 0) ? 'active' : ''; ?>"
                        data-bs-target="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                        data-bs-slide-to="<?php echo $slideCounter; ?>"
                        style="flex: 0 0 <?php echo $itemsVisibleRatio;?>%;
"
                >
                    <div class="ratio ratio-<?php echo $itemRatio; ?> w-100"
                         style="background:url('<?php echo $r->ribbonimage->url; ?>') center center / cover no-repeat;">
                    </div>
             </div>
                <?php
                $slideCounter++;
            endforeach;
            ?>
        </div>
        <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#prettyRibbon<?php echo $moduleId; ?>"
                data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('JPREVIOUS');?></span>
        </button>
        <button class="carousel-control-next"
                type="button"
                data-bs-target="#prettyRibbon<?php echo $moduleId; ?>"
                data-bs-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('JNEXT');?></span>
        </button>
    </div>
</div>

<div class="modal fade" id="prettyRibbonModal<?php echo $moduleId; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0">
                <div id="prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                     class="carousel slide"
                     data-bs-ride="carousel"
                >
                    <ol class="carousel-indicators">
                        <?php
                        $slideCounter = 0;
                        foreach ($ribbonItems as $r) :
                            ?>
                            <li
                                    data-bs-target="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                                    data-bs-slide-to="<?php echo $slideCounter; ?>"
                                    class="<?php echo ($slideCounter == 0) ? 'active' : ''; ?>">
                            </li>
                            <?php
                            $slideCounter++;
                        endforeach;
                        ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                        $slideCounter = 0;
                        foreach ($ribbonItems as $r) :
                            ?>
                            <div class="carousel-item <?php echo ($slideCounter == 0) ? 'active' : ''; ?>">
                                <img class="d-block w-auto mx-auto max-vh-100 mh-100"
                                     src="<?php echo $r->ribbonimage->url; ?>"
                                >
                            </div>
                            <?php
                            $slideCounter++;
                        endforeach;
                        ?>
                     </div>
                    <button
                            type="button"
                            class="btn-close position-absolute top-0 end-0 p-2 m-1 bg-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                    ></button>
                    <a class="carousel-control-prev"
                       href="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                       role="button"
                       data-bs-slide="prev"
                    >
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only"><?php echo Text::_('JPREVIOUS');?></span>
                    </a>
                    <a class="carousel-control-next"
                       href="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                       role="button"
                       data-bs-slide="next"
                    >
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only"><?php echo Text::_('JNEXT');?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>