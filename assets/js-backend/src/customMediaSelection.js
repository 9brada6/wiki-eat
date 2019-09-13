/**
 * @package WikiEat
 */

import $ from 'jquery';

/* global wp */

const NAME = 'CustomMediaSelection';

const DATA = {
	SCRIPT: 'data-script',
	INPUT: 'data-hidden-input',
	THUMBNAILS_CONTAINER: 'data-thumbnails-container',
	FRAME_TITLE: 'data-frame-title',
	BTN_TEXT: 'data-frame-btn-text',
};

const DATA_TRIGGER = `[${DATA['SCRIPT']}="we.${NAME}"]`;

const EVENT_TRIGGER = `click.we.${NAME}`;

/**
 * Add a button which permits to open a WordPress media Frame.
 * The id's of the media will be saved in an input.
 *
 * Usage: Add the attribute data-script="we.CustomMediaSelection"
 * on the button, and data-input="#idOfTheInput".
 *
 */
class CustomMediaSelection {
	/**
	 * Construct the class and add all events that are necessary.
	 *
	 * @param {jQuery|string} button Will trigger the frame on click.
	 * @param {jQuery|string} hiddenInput HTML input element, used remember
	 * the state of the frame.
	 * @param {jQuery|string} thumbnailsContainer Display thumbnails of all
	 * selected images as children of this container.
	 */
	constructor(button, hiddenInput, thumbnailsContainer) {
		this._btn = $(button).eq(0);
		this._hiddenInput = $(hiddenInput);
		this._thumbnailsContainer = $(thumbnailsContainer).eq(0);

		this._frame = null;
		this._images = null;

		this._createFrame();
		this._addFrameListeners();
		this._addListeners();
		this.constructImages();
	}

	/**
	 * Add the global listeners.
	 */
	_addListeners() {
		$(document).on(EVENT_TRIGGER, this._execBtnEvent.bind(this));
	}

	/**
	 * Add the functions that will be triggered by
	 * WordPress Media Frame events.
	 */
	_addFrameListeners() {
		this._frame.on('select', this._onFrameSelect.bind(this));
		this._frame.on('open', this._onFrameOpen.bind(this));
	}

	/**
	 * Function that will execute when the button is clicked.
	 *
	 * @param {Event} event The jquery event that triggers this function.
	 */
	_execBtnEvent(event) {
		if (!$(this._btn).is(event.target)) {
			return;
		}

		event.preventDefault();

		if (!this._frame) {
			this._createFrame();
		}

		this._frame.open();
	}

	/**
	 * Create the WordPress media frame that will open when
	 * the button is clicked.
	 */
	_createFrame() {
		this._frame = wp.media({
			multiple: 'toggle',
			title: this._getFrameTitle(),
			button: {
				text: this._getFrameBtnText(),
			},
			frame: 'select',
			library: {
				type: ['image'],
			},
		});
	}

	/**
	 * Get the translated frame title if available.
	 *
	 * @return {string} A translated text or default English one.
	 */
	_getFrameTitle() {
		const translation = this._btn.attr(DATA['FRAME_TITLE']);
		if (translation) {
			return translation;
		}

		return 'Select images for aliment';
	}

	/**
	 * Get the translated frame button text if available.
	 * @return {string} A translated text or default English one.
	 */
	_getFrameBtnText() {
		const translation = this._btn.attr(DATA['BTN_TEXT']);
		if (translation) {
			return translation;
		}

		return 'Use this image';
	}

	/**
	 * Function that will be triggered when an image will be selected.
	 * This function will get the images ID's and will update them into
	 * the hidden input, separated by ";".
	 */
	_onFrameSelect() {
		// Get media attachment details from the frame state
		this._images = this._frame.state().get('selection').models;

		const imagesID = this._images
			.map(function(elem) {
				return elem.id;
			})
			.join(';');

		this._hiddenInput.val(imagesID);

		this.updateThumbnailImages();
	}

	/**
	 * Executes when the 'open' event of the frame is triggered.
	 */
	_onFrameOpen() {
		const frameCurrentSelected = this._frame.state().get('selection');
		const ids = this._hiddenInput.val().split(';');

		ids.forEach(function(id) {
			if (id > 0) {
				const attachment = wp.media.attachment(id);
				attachment.fetch();
				frameCurrentSelected.add(attachment);
			}
		});
	}

	/**
	 * Update the thumbnail images.
	 */
	updateThumbnailImages() {
		const box = this._thumbnailsContainer;

		if (box.length === 0) {
			return;
		}

		const noImgMessage = box.find('p');

		let images = this._images;
		const numImages = images.length;

		box.find('img').remove();

		if (!numImages) {
			noImgMessage.show();
			return;
		}
		noImgMessage.hide();

		if (typeof images.models !== 'undefined') {
			images = images.models;
		}

		this._appendImagesToContainer(images);
	}

	/**
	 * Construct the IMG HTML element and append it to container.
	 *
	 * @param {array} images An array with frame attachments.
	 */
	_appendImagesToContainer(images) {
		const imageNode = $('<img class="we-aliment-image">');
		imageNode.attr('width', 150);
		imageNode.attr('height', 150);

		console.log(images);

		for (let i = 0; i < images.length; i++) {
			const currentImgNode = imageNode.clone(true);

			const imageURL = this._getThumbnailSrc(images[i]);
			const imageAlt = images[i].attributes.alt;
			currentImgNode.attr('src', imageURL);
			currentImgNode.attr('alt', imageAlt);
			currentImgNode.attr('data-img-id', images[i].id);

			if (imageURL) {
				this._thumbnailsContainer.append(currentImgNode);
			}
		}
	}

	/**
	 * Get the SRC string of an image.
	 *
	 * @param {Attachment} image An WP frame Attachment object.
	 * @return {string} The SRC of the image thumbnail.
	 */
	_getThumbnailSrc(image) {
		const hasSize = image && image.attributes && image.attributes.sizes;

		if (!hasSize) {
			return '';
		}

		let imageURL = '';
		const sizes = image.attributes.sizes;
		if (typeof sizes['thumbnail'] !== 'undefined') {
			imageURL = sizes['thumbnail'].url;
		} else if (typeof sizes['full'] !== 'undefined') {
			imageURL = sizes['full'].url;
		}

		return imageURL;
	}

	/**
	 * Creates the WP Frame Attachments from the hidden input.
	 *
	 * Will auto-update the thumbnails when finished.
	 */
	constructImages() {
		const ids = this._hiddenInput.val().split(';');

		this._images = [];
		for (let i = 0; i < ids.length; i++) {
			if (ids[i] > 0) {
				const attachment = wp.media.attachment(ids[i]);
				attachment.fetch().done(this.updateThumbnailImages.bind(this));
				this._images.push(attachment);
			}
		}
	}
}

/**
 * An array where all the CustomMediaSelection objects instantiated should be.
 */
const customMediaSelectionObjects = [];

/**
 * Search by data-script and initialize all the Object from the DOM.
 */
function onDocumentLoad() {
	const scriptTriggers = $(DATA_TRIGGER);
	for (let i = 0; i < scriptTriggers.length; i++) {
		const target = scriptTriggers.eq(i);
		const stateKeeper = $(target).attr('data-hidden-input');
		const thumbnailsContainer = $(target).attr('data-thumbnails-container');
		customMediaSelectionObjects.push(
			new CustomMediaSelection(target, stateKeeper, thumbnailsContainer)
		);
	}
}
$(onDocumentLoad);

export { CustomMediaSelection };
export { customMediaSelectionObjects };
