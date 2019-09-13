import "core-js/modules/es6.array.find";
import "core-js/modules/es6.array.for-each";
import "core-js/modules/es6.regexp.split";
import "core-js/modules/es6.array.map";
import "core-js/modules/es6.function.bind";

/**
 * @package WikiEat
 */
import $ from 'jquery';
/* global wp */

var NAME = 'CustomMediaSelection';
var DATA = {
  SCRIPT: 'data-script',
  INPUT: 'data-hidden-input',
  THUMBNAILS_CONTAINER: 'data-thumbnails-container',
  FRAME_TITLE: 'data-frame-title',
  BTN_TEXT: 'data-frame-btn-text'
};
var DATA_TRIGGER = "[" + DATA['SCRIPT'] + "=\"we." + NAME + "\"]";
var EVENT_TRIGGER = "click.we." + NAME;
/**
 * Add a button which permits to open a WordPress media Frame.
 * The id's of the media will be saved in an input.
 *
 * Usage: Add the attribute data-script="we.CustomMediaSelection"
 * on the button, and data-input="#idOfTheInput".
 *
 */

var CustomMediaSelection =
/*#__PURE__*/
function () {
  /**
   * Construct the class and add all events that are necessary.
   *
   * @param {jQuery|string} button Will trigger the frame on click.
   * @param {jQuery|string} hiddenInput HTML input element, used remember
   * the state of the frame.
   * @param {jQuery|string} thumbnailsContainer Display thumbnails of all
   * selected images as children of this container.
   */
  function CustomMediaSelection(button, hiddenInput, thumbnailsContainer) {
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


  var _proto = CustomMediaSelection.prototype;

  _proto._addListeners = function _addListeners() {
    $(document).on(EVENT_TRIGGER, this._execBtnEvent.bind(this));
  }
  /**
   * Add the functions that will be triggered by
   * WordPress Media Frame events.
   */
  ;

  _proto._addFrameListeners = function _addFrameListeners() {
    this._frame.on('select', this._onFrameSelect.bind(this));

    this._frame.on('open', this._onFrameOpen.bind(this));
  }
  /**
   * Function that will execute when the button is clicked.
   *
   * @param {Event} event The jquery event that triggers this function.
   */
  ;

  _proto._execBtnEvent = function _execBtnEvent(event) {
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
  ;

  _proto._createFrame = function _createFrame() {
    this._frame = wp.media({
      multiple: 'toggle',
      title: this._getFrameTitle(),
      button: {
        text: this._getFrameBtnText()
      },
      frame: 'select',
      library: {
        type: ['image']
      }
    });
  }
  /**
   * Get the translated frame title if available.
   *
   * @return {string} A translated text or default English one.
   */
  ;

  _proto._getFrameTitle = function _getFrameTitle() {
    var translation = this._btn.attr(DATA['FRAME_TITLE']);

    if (translation) {
      return translation;
    }

    return 'Select images for aliment';
  }
  /**
   * Get the translated frame button text if available.
   * @return {string} A translated text or default English one.
   */
  ;

  _proto._getFrameBtnText = function _getFrameBtnText() {
    var translation = this._btn.attr(DATA['BTN_TEXT']);

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
  ;

  _proto._onFrameSelect = function _onFrameSelect() {
    // Get media attachment details from the frame state
    this._images = this._frame.state().get('selection').models;

    var imagesID = this._images.map(function (elem) {
      return elem.id;
    }).join(';');

    this._hiddenInput.val(imagesID);

    this.updateThumbnailImages();
  }
  /**
   * Executes when the 'open' event of the frame is triggered.
   */
  ;

  _proto._onFrameOpen = function _onFrameOpen() {
    var frameCurrentSelected = this._frame.state().get('selection');

    var ids = this._hiddenInput.val().split(';');

    ids.forEach(function (id) {
      if (id > 0) {
        var attachment = wp.media.attachment(id);
        attachment.fetch();
        frameCurrentSelected.add(attachment);
      }
    });
  }
  /**
   * Update the thumbnail images.
   */
  ;

  _proto.updateThumbnailImages = function updateThumbnailImages() {
    var box = this._thumbnailsContainer;

    if (box.length === 0) {
      return;
    }

    var noImgMessage = box.find('p');
    var images = this._images;
    var numImages = images.length;
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
  ;

  _proto._appendImagesToContainer = function _appendImagesToContainer(images) {
    var imageNode = $('<img class="we-aliment-image">');
    imageNode.attr('width', 150);
    imageNode.attr('height', 150);
    console.log(images);

    for (var i = 0; i < images.length; i++) {
      var currentImgNode = imageNode.clone(true);

      var imageURL = this._getThumbnailSrc(images[i]);

      var imageAlt = images[i].attributes.alt;
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
  ;

  _proto._getThumbnailSrc = function _getThumbnailSrc(image) {
    var hasSize = image && image.attributes && image.attributes.sizes;

    if (!hasSize) {
      return '';
    }

    var imageURL = '';
    var sizes = image.attributes.sizes;

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
  ;

  _proto.constructImages = function constructImages() {
    var ids = this._hiddenInput.val().split(';');

    this._images = [];

    for (var i = 0; i < ids.length; i++) {
      if (ids[i] > 0) {
        var attachment = wp.media.attachment(ids[i]);
        attachment.fetch().done(this.updateThumbnailImages.bind(this));

        this._images.push(attachment);
      }
    }
  };

  return CustomMediaSelection;
}();
/**
 * An array where all the CustomMediaSelection objects instantiated should be.
 */


var customMediaSelectionObjects = [];
/**
 * Search by data-script and initialize all the Object from the DOM.
 */

function onDocumentLoad() {
  var scriptTriggers = $(DATA_TRIGGER);

  for (var i = 0; i < scriptTriggers.length; i++) {
    var target = scriptTriggers.eq(i);
    var stateKeeper = $(target).attr('data-hidden-input');
    var thumbnailsContainer = $(target).attr('data-thumbnails-container');
    customMediaSelectionObjects.push(new CustomMediaSelection(target, stateKeeper, thumbnailsContainer));
  }
}

$(onDocumentLoad);
export { CustomMediaSelection };
export { customMediaSelectionObjects };