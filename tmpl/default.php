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

HTMLHelper::_('bootstrap.carousel', '#prettyRibbonCarousel' . $moduleId);
HTMLHelper::_('bootstrap.carousel', '#prettyRibbonModalCarousel' . $moduleId);
HTMLHelper::_('bootstrap.modal', '#prettyRibbonModal' . $moduleId);

$itemsVisibleRatio  = round(100 / max(1, $itemsVisible), 4);
$itemCount          = count($ribbonItems);
$autoplayInterval   = max(1000, (int) $autoplayInterval);
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseScript(
    'prettyphotoribbon',
    'mod_prettyphotoribbon/prettyphotoribbon.min.js',
    [],
    ['type' => 'module']
);
$wa->registerAndUseStyle('prettyphotoribboncss', 'mod_prettyphotoribbon/prettyphotoribbon.min.css', [], [], []);

// Escape for an HTML attribute.
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

// width/height attributes, only when the intrinsic size is known.
$sizeAttributes = static fn (object $image): string => ($image->width ?? 0) > 0 && ($image->height ?? 0) > 0
    ? ' width="' . (int) $image->width . '" height="' . (int) $image->height . '"'
    : '';
?>
<div class="prettyRibbonWrapper">
    <div id="prettyRibbonCarousel<?php echo $moduleId; ?>"
         class="carousel"
         data-autoplay="<?php echo (int) $autoplay; ?>"
         data-autoplay-interval="<?php echo $autoplayInterval; ?>"
         data-items-visible="<?php echo (int) $itemsVisible; ?>"
    >
        <div class="carousel-inner">
            <?php foreach ($ribbonItems as $index => $r) : ?>
                <?php
                $label = $r->alt !== ''
                    ? Text::sprintf('MOD_PRETTYPHOTORIBBON_OPEN_PHOTO_ALT', $index + 1, $itemCount, $r->alt)
                    : Text::sprintf('MOD_PRETTYPHOTORIBBON_OPEN_PHOTO', $index + 1, $itemCount);
                ?>
                <div class="carousel-item<?php echo $index === 0 ? ' active' : ''; ?>"
                     style="flex: 0 0 <?php echo $escape($itemsVisibleRatio); ?>%;"
                >
                    <button type="button"
                            class="prettyRibbonTile"
                            data-bs-toggle="modal"
                            data-bs-target="#prettyRibbonModal<?php echo $moduleId; ?>"
                            data-photo-index="<?php echo $index; ?>"
                            aria-label="<?php echo $escape($label); ?>"
                    >
                        <span class="ratio ratio-<?php echo $escape($itemRatio); ?> d-block w-100">
                            <img src="<?php echo $escape($r->ribbonimage->url); ?>"
                                 alt=""<?php echo $sizeAttributes($r->ribbonimage); ?>
                                 <?php echo $index >= $itemsVisible ? 'loading="lazy"' : ''; ?>
                            >
                        </span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#prettyRibbonCarousel<?php echo $moduleId; ?>"
                data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('JPREVIOUS'); ?></span>
        </button>
        <button class="carousel-control-next"
                type="button"
                data-bs-target="#prettyRibbonCarousel<?php echo $moduleId; ?>"
                data-bs-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('JNEXT'); ?></span>
        </button>
    </div>
    <?php if ($autoplay) : ?>
        <?php // Shown by prettyphotoribbon.js; stays hidden without JS or with prefers-reduced-motion. ?>
        <div class="prettyRibbonAutoplay d-flex justify-content-end mt-2">
            <button type="button"
                    class="prettyRibbonToggle btn btn-sm btn-outline-secondary"
                    aria-controls="prettyRibbonCarousel<?php echo $moduleId; ?>"
                    hidden
            >
                <span class="prettyRibbonToggle-stop"><?php echo Text::_('MOD_PRETTYPHOTORIBBON_AUTOPLAY_STOP'); ?></span>
                <span class="prettyRibbonToggle-start" hidden><?php echo Text::_('MOD_PRETTYPHOTORIBBON_AUTOPLAY_START'); ?></span>
            </button>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade"
     id="prettyRibbonModal<?php echo $moduleId; ?>"
     tabindex="-1"
     aria-label="<?php echo $escape(Text::_('MOD_PRETTYPHOTORIBBON_MODAL_LABEL')); ?>"
     aria-hidden="true"
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0">
                <div id="prettyRibbonModalCarousel<?php echo $moduleId; ?>" class="carousel slide">
                    <div class="carousel-indicators">
                        <?php foreach ($ribbonItems as $index => $r) : ?>
                            <button type="button"
                                    data-bs-target="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                                    data-bs-slide-to="<?php echo $index; ?>"
                                    <?php echo $index === 0 ? 'class="active" aria-current="true"' : ''; ?>
                                    aria-label="<?php echo $escape(Text::sprintf('MOD_PRETTYPHOTORIBBON_SLIDE_LABEL', $index + 1, $itemCount)); ?>"
                            ></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach ($ribbonItems as $index => $r) : ?>
                            <div class="carousel-item<?php echo $index === 0 ? ' active' : ''; ?>">
                                <img class="d-block w-auto h-auto mw-100 mx-auto max-vh-100"
                                     src="<?php echo $escape($r->ribbonimage->url); ?>"
                                     alt="<?php echo $escape($r->alt); ?>"<?php echo $sizeAttributes($r->ribbonimage); ?>
                                     loading="lazy"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button
                            type="button"
                            class="btn-close position-absolute top-0 end-0 p-2 m-1 bg-white"
                            data-bs-dismiss="modal"
                            aria-label="<?php echo $escape(Text::_('JCLOSE')); ?>"
                    ></button>
                    <button class="carousel-control-prev"
                            type="button"
                            data-bs-target="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                            data-bs-slide="prev"
                    >
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"><?php echo Text::_('JPREVIOUS'); ?></span>
                    </button>
                    <button class="carousel-control-next"
                            type="button"
                            data-bs-target="#prettyRibbonModalCarousel<?php echo $moduleId; ?>"
                            data-bs-slide="next"
                    >
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"><?php echo Text::_('JNEXT'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
