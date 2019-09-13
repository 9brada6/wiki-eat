var WikiEat = (function (es6_array_find, es6_array_forEach, es6_regexp_split, es6_array_map, es6_function_bind, $) {
  'use strict';

  $ = $ && $.hasOwnProperty('default') ? $['default'] : $;

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

  /**
   * Hide all, but except one posts type, when searched on WordPress
   * backend editor for links to insert into textarea.
   *
   * All the WordPress editors, including the visual TinyMCE and default Text
   * have a button that let users easily insert a hyperlink into editor. After
   * this button is clicked, either a small box to search a link, or a modal will
   * open(when clicked on the gear). This class will ensure that when searched
   * for links, the links will be of only one specific type or custom post type.
   *
   * Example usage:
   * const buttonSelector = '#wp-custom-editor-container .mce-btn:has(.mce-i-link)'
   * const postType = 'customPostType';
   * const hideType = new WpLinksOnlyPostType(buttonSelector, postType);
   *
   * Note: The button selector string is a little trickier to get. It should have
   * ".mce-btn" class, and must have an "aria-pressed" attribute, otherwise the
   * class will not work.
   */

  var WpLinksOnlyPostType =
  /*#__PURE__*/
  function () {
    /**
     * Initialize the class and sets all the default
     * properties that we need.
     *
     * @param {string} linkButtonSelector Selector to target the TinyMCE link button.
     * @param {string} postType The post type that the links will be filtered.
     */
    function WpLinksOnlyPostType(linkButtonSelector, postType) {
      this._linkButtonSelector = linkButtonSelector;
      this._postType = postType;
      this._linksInlineObservers = [];
      this._linksModalObserver = null;
      this._observersWatching = false;
      this._hideLinksClass = 'we-js-wp-link-hide-non-ingredient';

      this._addEvent();
    }
    /**
     * Add the document event that will check when to hide
     * or show the links.
     *
     * @access private
     */


    var _proto = WpLinksOnlyPostType.prototype;

    _proto._addEvent = function _addEvent() {
      $(document).on('click', this._startOrStopObserversWatching.bind(this));
    }
    /**
     * Starts or stop the document Observers to hide
     * what links we don't need.
     *
     * @access private
     */
    ;

    _proto._startOrStopObserversWatching = function _startOrStopObserversWatching() {
      var buttonPressed = $(this._linkButtonSelector).attr('aria-pressed');

      if (buttonPressed === 'true') {
        this._startObserversWatching();
      } else {
        this._stopObserverWatching();
      }
    }
    /**
     * Starts the mutation observers to watch when the lists are being updated.
     *
     * @access private
     */
    ;

    _proto._startObserversWatching = function _startObserversWatching() {
      if (!this._observersWatching) {
        this._observersWatching = true;

        this._watchLinksFromInlineSearch();

        this._watchLinksFromModalSearch();
      }
    }
    /**
     * Starts the mutation observers that watch when the lists are being updated.
     *
     * @access private
     */
    ;

    _proto._stopObserverWatching = function _stopObserverWatching() {
      if (this._observersWatching) {
        this._stopWatchingLinksFromInlineSearch();

        this._stopWatchingLinksFromModalSearch();

        this._observersWatching = false;
      } // We need a special function for modals,
      // to restore the hidden links.


      this._restoreLinksFromModalSearch();
    }
    /**
     * Creates the Observers that watch the links when
     * searched in the inline "search links" box.
     *
     * @access private
     */
    ;

    _proto._watchLinksFromInlineSearch = function _watchLinksFromInlineSearch() {
      var allTheLinksInput = $('.wp-link-input input');

      for (var i = 0; i < allTheLinksInput.length; i++) {
        var theListWithUrlID = allTheLinksInput.eq(i).attr('aria-owns');
        var theListWithUrl = $('#' + theListWithUrlID);

        if (theListWithUrl.length === 0) {
          continue;
        }

        var mutObsFct = this._removeLinksFromInlineSearch;
        mutObsFct = mutObsFct.bind(this, theListWithUrl);
        var observer = new MutationObserver(mutObsFct);

        this._linksInlineObservers.push(observer);

        var target = theListWithUrl.get(0);
        var config = {
          attributes: true,
          characterData: true,
          childList: true,
          subtree: true
        };
        observer.observe(target, config);
      }
    }
    /**
     * Creates the MutationObserver that will watch the list of
     * links from the modal that open.
     *
     * @access private
     */
    ;

    _proto._watchLinksFromModalSearch = function _watchLinksFromModalSearch() {
      var mutObsFct = this._removeLinksFromModalSearch;
      mutObsFct = mutObsFct.bind(this);
      this._linksModalObserver = new MutationObserver(mutObsFct);
      var target = document.getElementById('wp-link');
      var config = {
        attributes: true,
        characterData: true,
        childList: true,
        subtree: true
      };

      this._linksModalObserver.observe(target, config);
    }
    /**
     * Stop All the Observers that are watching the links
     * from the inline "search links" box.
     *
     * @access private
     */
    ;

    _proto._stopWatchingLinksFromInlineSearch = function _stopWatchingLinksFromInlineSearch() {
      while (this._linksInlineObservers.length > 0) {
        var mutationToRemove = this._linksInlineObservers.pop();

        mutationToRemove.disconnect();
        mutationToRemove = null;
      }
    }
    /**
     * Stop the Observer that watch the links from the modal.
     *
     * @access private
     */
    ;

    _proto._stopWatchingLinksFromModalSearch = function _stopWatchingLinksFromModalSearch() {
      if (this._linksModalObserver instanceof MutationObserver) {
        this._linksModalObserver.disconnect();

        this._linksModalObserver = null;
      }
    }
    /**
     * Hide all the links from the inline search, that are not the wanted
     * post type.
     *
     * @access private
     * @param {jQuery} list A jquery UL element, containing the list of links.
     */
    ;

    _proto._removeLinksFromInlineSearch = function _removeLinksFromInlineSearch(list) {
      var links = list.children('li');

      for (var j = 0; j < links.length; j++) {
        var currentLink = links.eq(j);
        var currentPostType = currentLink.find('.wp-editor-float-right').text();

        if (currentPostType !== this._postType) {
          currentLink.addClass(this._hideLinksClass);
        }
      }
    }
    /**
     * Hide all the unwanted links from the modal search.
     *
     * @access private
     */
    ;

    _proto._removeLinksFromModalSearch = function _removeLinksFromModalSearch() {
      var listsSelector = '#wp-link #most-recent-results, #wp-link #search-results';
      var searchedPostsLinks = $(listsSelector).children('ul').children('li');
      var searchedPostsType = searchedPostsLinks.children('.item-info');

      for (var i = 0; i < searchedPostsType.length; i++) {
        var currentElement = searchedPostsType.eq(i);

        if (currentElement.text() !== this._postType) {
          currentElement.parent().addClass(this._hideLinksClass);
        }
      }
    }
    /**
     * Restore the links hidden from the Modal Search.
     *
     * This function is needed because the modal links elements do not refresh completely.
     *
     * @access private
     */
    ;

    _proto._restoreLinksFromModalSearch = function _restoreLinksFromModalSearch() {
      var listsSelector = '#wp-link #most-recent-results, #wp-link #search-results';
      var searchedPostsLinks = $(listsSelector).children('ul').children('li');
      searchedPostsLinks.removeClass('we-js-wp-link-hide-non-ingredient');
    };

    return WpLinksOnlyPostType;
  }();

  var wpLinksOnlyPostTypeObject = [];

  var btnSelector = '#wp-we_ingredients_editor-editor-container .mce-btn:has(.mce-i-link)';
  var showOnlyIngredientLinks = new WpLinksOnlyPostType(btnSelector, 'Ingredient');
  wpLinksOnlyPostTypeObject.push(showOnlyIngredientLinks);
  var sortableObject = {
    update: function update(event, ui) {
      var images = $(this).children('[data-img-id]');
      var imagesIds = [];

      for (var i = 0; i < images.length; i++) {
        var ID = images.eq(i).attr('data-img-id');

        if (isNaN(ID)) {
          continue;
        }

        imagesIds.push(ID);
      }

      var joinedIds = imagesIds.join(';');
      $('#we-aliment-images').val(joinedIds);
    }
  };
  $('#we-aliment-images-box').sortable(sortableObject);
  var index = {
    CustomMediaSelection: CustomMediaSelection,
    customMediaSelectionObjects: customMediaSelectionObjects,
    WpLinksOnlyPostType: WpLinksOnlyPostType,
    wpLinksOnlyPostTypeObject: wpLinksOnlyPostTypeObject
  };

  return index;

}(null, null, null, null, null, jQuery));
