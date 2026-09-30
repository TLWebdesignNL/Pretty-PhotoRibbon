# Pretty PhotoRibbon Module for Joomla 5 & Joomla 6
A photo ribbon to display photos on your website.

The module shows images in a horizontal ribbon. Clicking (or pressing Enter on) a photo opens a large slideshow of all photos.

## Requirements
- Joomla 5.4 or newer, or Joomla 6
- PHP 8.1 or newer

## Features
- Up to 30 images in one ribbon
- Image source: pick images one by one, or show the images from a folder below `/images` (JPG, PNG, GIF, WebP; sorted by file name)
- Aspect ratio of the ribbon images: 1x1, 4x3, 16x9 or 21x9
- 1 to 6 images visible at once on wide screens; one image at a time on phones
- Optional autoplay with a configurable interval
- Module class suffix support

## Accessibility
- Every photo in the ribbon is a button that can be reached and opened with the keyboard; focus returns to it when the slideshow closes.
- Each image has an alternative text field, or can be marked as decorative. Images from a folder are treated as decorative.
- Nothing moves unless Autoplay is on. Autoplay pauses on hover and focus, has a visible stop/start button and does not run when the visitor's device is set to reduce motion. The large slideshow never plays by itself.
- On wide screens the arrow keys scroll the ribbon, and trackpads and touch screens can swipe it.

## Languages
English (en-GB) and Dutch (nl-NL).

## Development
The minified CSS and JavaScript are generated from the sources:

```sh
npm install
npm run build
```

Edit `media/scss/prettyphotoribbon.scss` and `media/js/prettyphotoribbon.js`, never the `.min` files. Joomla loads the unminified files when debug mode is on.

Releases are created by pushing a `V*` tag; the release workflow adds the entry (with SHA-256) to `updates.xml`.
