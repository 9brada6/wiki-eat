<?php
/**
 * Small and basic functions that are theme specific.
 *
 * @package Wiki_Eat
 */

declare ( strict_types = 1 );

namespace WE\Functions;

/**
 * Get the location of the logo that is in SVG format.
 *
 * @return string The logo location.
 */
function get_svg_icon_uri(): string {

	$uri = get_theme_file_uri( 'assets/images/icon.png' );

	return $uri;
}

/**
 * Get the location of the logo that is in SVG format.
 *
 * @return string The logo location.
 */
function get_svg_icon_text_uri(): string {

	$uri = get_theme_file_uri( 'assets/images/text.svg' );

	return $uri;
}

function get_404_src(): string {

	$uri = get_theme_file_uri( 'assets/images/error_404.png' );

	return $uri;
}



function display_footer_blockquote() {
	$footer_blockquote = get_random_quote();

	if ( isset( $footer_blockquote['quote'] ) ) :
		?>
			<blockquote class="m-0 text-center text-white px-3 py-4 py-lg-4 px-lg-5">
				<p class="h3 font-weight-light"><?= esc_html( $footer_blockquote["quote"] ) ?></p>

				<?php if ( isset( $footer_blockquote['cite'] ) ) : ?>
					<cite class="d-block font-weight-light">
						<?= esc_html( '~ ' . $footer_blockquote['cite'] . ' ~' ) ?>
					</cite>
				<?php endif; ?>
			</blockquote>
		<?php
	endif;
}

function get_random_quote() {
	$quotes = get_quotes();

	$number_of_quotes = count( $quotes );
	$random_number_quote = rand( 0, ( $number_of_quotes - 1 ) );

	return get_quote( $random_number_quote );
}

function get_quote( int $quote_num ): array {
	$quotes = get_quotes();

	if ( ! isset( $quotes[ $quote_num ], $quotes[ $quote_num ]['quote'] ) ) {
		return array();
	}

	return $quotes[ $quote_num ];
}

function get_quotes() {
	$quotes = array();
	return apply_filters( 'we_get_quotes', $quotes );
}

function add_theme_supports(){
	add_theme_support( 'post-thumbnails', array( 'post', 'ingredient' ) );
}

function default_quotes( $quotes ) {
	$new_quotes = array(

		// 1.
		array(
			'quote' => __( 'The greatest project you\'ll ever work on is you.', 'wiki-eat' ),
			'cite' => __( 'Unknown', 'wiki-eat' ),
		),

		// 2.
		array(
			'quote' => __( 'Your worst enemy cannot harm you as much as your own unguarded thoughts.', 'wiki-eat' ),
			'cite' => 'Buddha',
		),

		// 3.
		array(
			'quote' => __( 'Success is the sum of small efforts—repeated day-in and day-out.', 'wiki-eat' ),
			'cite' => 'Robert Collier',
		),

		// 4.
		array(
			'quote' => __( 'Take care of your body. It’s the only place you have to live.', 'wiki-eat' ),
			'cite' => 'Jim Rohn',
		),

		// 5.
		array(
			'quote' => __( 'You may have to fight a battle more than once to win it.', 'wiki-eat' ),
			'cite' => 'Margaret Thatcher',
		),

		// 6.
		array(
			'quote' => __( 'Even if you fall on your face, you’re still moving forward.', 'wiki-eat' ),
			'cite' => 'Victor Kiam',
		),

		// 7.
		array(
			'quote' => __( 'A journey of a thousand miles begins with a single step.', 'wiki-eat' ),
			'cite' => 'Lao Tzu',
		),

		// 8.
		array(
			'quote' => __( 'Let food be thy medicine, thy medicine shall be thy food.', 'wiki-eat' ),
			'cite' => 'Hippocrates',
		),

		// 9.
		array(
			'quote' => __( 'If you keep good food in your fridge, you will eat good food.', 'wiki-eat' ),
			'cite' => 'Errick McAdams',
		),

		// 10.
		array(
			'quote' => __( 'Your diet is a bank account. Good food choices are good investments. ', 'wiki-eat' ),
			'cite' => 'Bethenny Frankel',
		),

		// 11.
		array(
			'quote' => __( 'Early to bed and early to rise, makes a man healthy wealthy and wise', 'wiki-eat' ),
			'cite' => 'Benjamin Franklin',
		),

		// 12.
		array(
			'quote' => __( 'Physical fitness is not only one of the most important keys to a healthy body, it is the basis of dynamic and creative intellectual activity.', 'wiki-eat' ),
			'cite' => 'John F. Kennedy',
		),

		// 13.
		array(
			'quote' => __( 'The greatest wealth is Health.', 'wiki-eat' ),
			'cite' => 'Ralph Waldo Emerson',
		),

		// 14.
		array(
			'quote' => __( 'Just because you\'re not sick doesn\'t mean you\'re healthy.', 'wiki-eat' ),
			'cite' => __( 'Unknown', 'wiki-eat' ),
		),

		// 15.
		array(
			'quote' => __( 'If you don\'t take care of your body, where are you going to live?', 'wiki-eat' ),
			'cite' => __( 'Unknown', 'wiki-eat' ),
		),

		// 16.
		array(
			'quote' => __( 'Health is a state of complete harmony of the body, mind and spirit. When one is free from physical disabilities and mental distractions, the gates of the soul open.', 'wiki-eat' ),
			'cite' => 'B.K.S. Iyengar',
		),

		// 18.
		array(
			'quote' => __( 'Health is like money, we never have a true idea of its value until we lose it.', 'wiki-eat' ),
			'cite' => 'Josh Billings',
		),

		// 19.
		array(
			'quote' => __( 'Time And health are two precious assets that we don\'t recognize and appreciate until they have been depleted.', 'wiki-eat' ),
			'cite' => 'Denis Waitley',
		),

		// 20.
		array(
			'quote' => __( 'From the bitterness of disease man learns the sweetness of health.', 'wiki-eat' ),
			'cite' => __( 'Catalan Proverb', 'wiki-eat' ),
		),

		// 21.
		array(
			'quote' => __( 'Health and cheerfulness naturally beget each other.', 'wiki-eat' ),
			'cite' => 'Joseph Addison',
		),

		// 22.
		array(
			'quote' => __( 'Your body is a temple, but only if you treat it as one.', 'wiki-eat' ),
			'cite' => 'Astrid Alauda',
		),

		// 23.
		array(
			'quote' => __( 'Our bodies are our gardens - our wills are our gardeners.', 'wiki-eat' ),
			'cite' => 'William Shakespeare',
		),

		// 24.
		array(
			'quote' => __( 'Health is not simply the absence of sickness.', 'wiki-eat' ),
			'cite' => 'Hannah Green',
		),

		// 25.
		array(
			'quote' => __( 'An apple a day keeps the doctor away.', 'wiki-eat' ),
			'cite' => __( 'Proverb', 'wiki-eat' ),
		),

		// 26.
		array(
			'quote' => __( 'True healthcare reform starts in your kitchen, not in Washington.', 'wiki-eat' ),
			'cite' => __( 'Unknown', 'wiki-eat' ),
		),

		// 27.
		array(
			'quote' => __( 'The only way to keep your health is to eat what you don\'t want, drink what you don\'t like, and do what you\'d rather not.', 'wiki-eat' ),
			'cite' => 'Mark Twain',
		),

		// 28.
		array(
			'quote' => __( 'To keep the body in good health is a duty, for otherwise we shall not be able to trim the lamp of wisdom, and keep our mind strong and clear. Water surrounds the lotus flower, but does not wet its petals.', 'wiki-eat' ),
			'cite' => 'Buddha',
		),

		// 29.
		array(
			'quote' => __( 'Now there are more overweight people in America than average-weight people. So overweight people are now average.', 'wiki-eat' ),
			'cite' => 'Jay Leno',
		),

		// 30.
		array(
			'quote' => __( 'Health is a relationship between you and your body.', 'wiki-eat' ),
			'cite' => 'Terri Guillemets',
		),

		// 31.
		array(
			'quote' => __( 'He who takes medicine and neglects to diet wastes the skill of his doctors.', 'wiki-eat' ),
			'cite' => __( 'Chinese Proverb', 'wiki-eat' ),
		),

		// 32.
		array(
			'quote' => __( 'Health of body and mind is a great blessing, if we can bear it.', 'wiki-eat' ),
			'cite' => 'John Henry Cardinal Newman',
		),

		// 34.
		array(
			'quote' => __( 'It is health that is the real wealth and not pieces of gold and silver.', 'wiki-eat' ),
			'cite' => 'Mahatma Gandhi.',
		),

		// 35.
		array(
			'quote' => __( 'It is easier to change a man\'s religion than to change his diet.', 'wiki-eat' ),
			'cite' => 'Margaret Mead',
		),

		// 36.
		array(
			'quote' => __( 'Most people work hard and spend their health trying to achieve wealth. Then they retire and spend their wealth trying to get back their health.', 'wiki-eat' ),
			'cite' => 'Kevin Gianni',
		),

		// 37.
		array(
			'quote' => __( 'As I see it, every day you do one of two things: build health or produce disease in yourself.', 'wiki-eat' ),
			'cite' => 'Adelle Davis',
		),

		// 38.
		array(
			'quote' => __( 'Keeping your body healthy is an expression of gratitude to the whole cosmos - the trees, the clouds, everything.', 'wiki-eat' ),
			'cite' => 'Thich Nhat Hanh',
		),

		// 39.
		array(
			'quote' => __( 'The healthy person has many wishes, but the sick person has only one.', 'wiki-eat' ),
			'cite' => __( 'Indian Proverb', 'wiki-eat' ),
		),

		// 40.
		array(
			'quote' => __( 'They say, "Money talks.", but what is your health saying?', 'wiki-eat' ),
			'cite' => __( 'Unknown', 'wiki-eat' ),
		),

		// 41.
		array(
			'quote' => __( 'The wise man should consider that human health is the greatest of human blessings.', 'wiki-eat' ),
			'cite' => 'Hippocrates',
		),

		// 42.
		array(
			'quote' => __( 'Good health shapes an expression of experiences.', 'wiki-eat' ),
			'cite' => 'Arif Naseem',
		),

		// 43.
		array(
			'quote' => __( 'Muddy water is best cleared by leaving it alone.', 'wiki-eat' ),
			'cite' => 'Alan Watts',
		),

		// 44.
		array(
			'quote' => __( 'If we wait for the moment when everything, absolutely everything is ready, we shall never begin.', 'wiki-eat' ),
			'cite' => 'Ivan Turgenev',
		),

		// 45.
		array(
			'quote' => __( 'Nothing is so fatiguing as the eternal hanging on of an uncompleted task.', 'wiki-eat' ),
			'cite' => 'William James',
		),

		// 46.
		array(
			'quote' => __( 'You are stronger than your challenges and your challenges are making you stronger.', 'wiki-eat' ),
			'cite' => 'Karen Salmansohn',
		),

		// 47.
		array(
			'quote' => __( 'Know well what leads you forward and what holds you back, and choose the path that leads you to wisdom.', 'wiki-eat' ),
			'cite' => 'Buddha',
		),

		// 48.
		array(
			'quote' => __( 'The best way to predict the future is to create it.', 'wiki-eat' ),
			'cite' => 'Peter Drucker',
		),

		// 49.
		array(
			'quote' => __( 'Setting goals is the first step in turning the invisible into the visible.', 'wiki-eat' ),
			'cite' => 'Tony Robbins',
		),


	);

	return array_values( array_merge( $quotes, $new_quotes ) );
}
add_filter( 'we_get_quotes', '\\WE\\Functions\\default_quotes' );
