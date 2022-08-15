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

var_dump($itemsVisible);
var_dump($itemRatio);
var_dump($ribbonItems);
$itemsVisibleRatio = 1 / $itemsVisible;
HTMLHelper::_('bootstrap.carousel', 'carouselExampleControls');

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineStyle('
    @media (min-width: 768px) {
        .carousel-inner {
            display: flex;
            padding: 0.5em;
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
          "#carouselExampleControls"
        );
        if (window.matchMedia("(min-width: 768px)").matches) {
          var carouselWidth = $(".carousel-inner")[0].scrollWidth;
          var cardWidth = $(".carousel-item").width();
          var scrollPosition = 0;
          $("#carouselExampleControls .carousel-control-next").on("click", function () {
            if (scrollPosition < carouselWidth - cardWidth * 4) {
              scrollPosition += cardWidth;
              $("#carouselExampleControls .carousel-inner").animate(
                { scrollLeft: scrollPosition },
                600
              );
            }
          });
          $("#carouselExampleControls .carousel-control-prev").on("click", function () {
            if (scrollPosition > 0) {
              scrollPosition -= cardWidth;
              $("#carouselExampleControls .carousel-inner").animate(
                { scrollLeft: scrollPosition },
                600
              );
            }
          });
        } else {
          $(multipleCardCarousel).addClass("slide");
        }
    });
');

?>
<div id="carouselExampleControls" class="carousel" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="ratio ratio-1x1 w-100 bg-warning">Slide 1</div>
        </div>
        <div class="carousel-item">
            <div class="ratio ratio-1x1 w-100 bg-info">Slide 2</div>
        </div>
        <div class="carousel-item">
            <div class="ratio ratio-1x1 w-100 bg-danger">Slide 3</div>
        </div>
        <div class="carousel-item">
            <div class="ratio ratio-1x1 w-100 bg-success">Slide 4</div>
        </div>
        <div class="carousel-item">
            <div class="ratio ratio-1x1 w-100 bg-secondary">Slide 5</div>
        </div>
        <div class="carousel-item">
            <div class="ratio ratio-1x1 w-100 bg-primary">Slide 6</div>
        </div>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>