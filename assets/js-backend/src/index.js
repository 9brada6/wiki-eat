import {
	CustomMediaSelection,
	customMediaSelectionObjects,
} from './customMediaSelection';
import {
	WpLinksOnlyPostType,
	wpLinksOnlyPostTypeObject,
} from './wpLinksOnlyPostType';

import $ from 'jquery';

const btnSelector =
	'#wp-we_ingredients_editor-editor-container .mce-btn:has(.mce-i-link)';
const showOnlyIngredientLinks = new WpLinksOnlyPostType(
	btnSelector,
	'Ingredient'
);
wpLinksOnlyPostTypeObject.push(showOnlyIngredientLinks);

const sortableObject = {
	update: function(event, ui) {
		const images = $(this).children('[data-img-id]');

		const imagesIds = [];
		for (let i = 0; i < images.length; i++) {
			const ID = images.eq(i).attr('data-img-id');
			if (isNaN(ID)) {
				continue;
			}
			imagesIds.push(ID);
		}

		const joinedIds = imagesIds.join(';');
		$('#we-aliment-images').val(joinedIds);
	},
};

$('#we-aliment-images-box').sortable(sortableObject);

export default {
	CustomMediaSelection,
	customMediaSelectionObjects,
	WpLinksOnlyPostType,
	wpLinksOnlyPostTypeObject,
};
