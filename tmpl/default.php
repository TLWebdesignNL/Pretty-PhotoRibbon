<?php
/**
 * @package        Joomla.Site
 * @subpackage     mod_prettyphotoribbon
 *
 * @copyright      Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$itemsVisibleRatio = (1 / $itemsVisible) * 100;
HTMLHelper::_('bootstrap.carousel', 'carouselExampleControls');
HTMLHelper::_('jquery.framework');

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineStyle('
    @media (min-width: 768px) {
        .carousel-inner {
            display: flex;
            padding-right: 0.5em;
        }
        .carousel-item {
            margin-right: 0;
            flex: 0 0 ' . $itemsVisibleRatio . '%;
            display: block;
        }
        .carousel-inner .carousel-item:first-child {
            margin-left: -0.5rem
        }
    }
');
$wa->addInlineScript('
    document.addEventListener("DOMContentLoaded", () => {
        var multipleCardCarousel = document.querySelector(
          "#prettyRibbon'.$moduleId.'"
        );
        if (window.matchMedia("(min-width: 768px)").matches) {
          var carouselWidth = $(".carousel-inner")[0].scrollWidth;
          var cardWidth = $(".carousel-item").width();
          var scrollPosition = 0;
          $("#prettyRibbon'.$moduleId.' .carousel-control-next").on("click", function () {
            if (scrollPosition < carouselWidth - cardWidth * 4) {
              scrollPosition += cardWidth;
              $("#prettyRibbon'.$moduleId.' .carousel-inner").animate(
                { scrollLeft: scrollPosition },
                600
              );
            }
          });
          $("#prettyRibbon'.$moduleId.' .carousel-control-prev").on("click", function () {
            if (scrollPosition > 0) {
              scrollPosition -= cardWidth;
              $("#prettyRibbon'.$moduleId.' .carousel-inner").animate(
                { scrollLeft: scrollPosition },
                600
              );
            }
          });
        } else {
          $("#prettyRibbon'.$moduleId.'").addClass("slide");
        }
    });
');
?>
<div id="prettyRibbon<?php echo $moduleId; ?>" class="carousel" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($ribbonItems as $i => $r) : ;?>
            <div class="carousel-item <?php echo (preg_replace('/\D/', '', $i) == 0) ? 'active': ''; ?>">
                <div class="ratio ratio-<?php echo $itemRatio; ?> w-100"
                     style="background:url('<?php echo $r->ribbonimage->url; ?>') center center / cover no-repeat;">
                </div>
         </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#prettyRibbon<?php echo $moduleId; ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo Text::_('PREVIOUS');?></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#prettyRibbon<?php echo $moduleId; ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo Text::_('NEXT');?></span>
    </button>
</div>