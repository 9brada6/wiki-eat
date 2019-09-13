import { CustomMediaSelection, customMediaSelectionObjects } from './customMediaSelection';
import { WpLinksOnlyPostType, wpLinksOnlyPostTypeObject } from './wpLinksOnlyPostType';
import $ from 'jquery';
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
export default {
  CustomMediaSelection: CustomMediaSelection,
  customMediaSelectionObjects: customMediaSelectionObjects,
  WpLinksOnlyPostType: WpLinksOnlyPostType,
  wpLinksOnlyPostTypeObject: wpLinksOnlyPostTypeObject
};