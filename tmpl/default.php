<?php
/**
 * @package		Joomla.Site
 * @subpackage  mod_prettyphotoribbon
 *
 * @copyright   Copyright (C) 2022 TLWebdesign. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

$wrapperClass = "";

if ($block == 1)
{
	$wrapperClass = "d-grid gap-2";
}
?>

<div class="d-flex flex-column pretty-photoribbon">
	<?php if ($before) : ?>
		<div class="before">
			<?php echo $before; ?>
		</div>
	<?php endif; // if before ?>
	<?php if (is_object($buttons)) : ?>
		<div class="<?php echo $wrapperClass; ?> <?php echo $customouterclass; ?> pb-button-div">
			<?php foreach ($buttons as $button) :
				if ($button->url && ($button->iconclass || $button->buttontext)) :
					?>
					<a class="
						<?php echo (isset($button->buttonclass) && is_array($button->buttonclass)) ? implode(" ", $button->buttonclass) : $button->buttonclass; ?>
						<?php echo (isset($button->custombuttonclass)) ? $button->custombuttonclass : ''; ?>"
					   href="<?php echo $button->url; ?>"
					   target="<?php echo (isset($button->buttontarget)) ? $button->buttontarget : "_blank"; ?>"
					   aria-label="<?php echo (isset($button->buttontext)) ? $button->buttontext : ''; ?>"
					>
						<?php if (isset($button->iconclass)) : ?>
							<i class="<?php echo $button->iconclass; ?>
								<?php echo (isset($button->buttontext) && $button->buttontext != "") ? 'pe-2' : ''; ?>">
							</i>
						<?php endif; // if $button->iconclass ?>
						<?php echo (isset($button->buttontext)) ? $button->buttontext : ''; ?>
					</a>
				<?php

					// If $button->buttontext
				endif;

				// Foreach $buttons as $button
			endforeach;
			?>
		</div>
	<?php endif; // if is_object(buttons) ?>
	<?php if ($after) : ?>
		<div class="after">
			<?php echo $after; ?>
		</div>
	<?php endif; // if before?>
</div>