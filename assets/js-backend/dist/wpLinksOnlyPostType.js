import "core-js/modules/es6.array.find";
import "core-js/modules/es6.function.bind";

/**
 * @package Wiki-Eat
 */
import $ from 'jquery';
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
export { WpLinksOnlyPostType };
export { wpLinksOnlyPostTypeObject };