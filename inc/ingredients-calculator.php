<?php


namespace WE\Blogroll;


class Ingredients_Calculator {

	const NATURAL = 'natural';
	const ARTIFICIAL = 'artificial';

	const HEALTHY = 'healthy';
	const UNHEALTHY = 'unhealthy';
	const MODERATE = 'moderate';

	const CAT_NATURAL_NAME = 'Naturale';
	const CAT_ARTIFICIAL_NAME = 'Artificiale';

	const CAT_HEALTHY_NAME = 'Sănătoase';
	const CAT_UNHEALTHY_NAME = 'Nesănătoase';
	const CAT_MODERATE_NAME = 'Moderate';


	private $ingredients_status;
	private $post;

	public function __construct ( $post = null) {

		$this->post = get_post( $post );
		$this->ingredients_status = $this->calculate_ingredients_status();
	}

	public function get_ingredients_status(): array {
		return $this->ingredients_status;
	}

	public function get_ingredients_num(): int {
		$sum = 0;

		$sum += $this->ingredients_status[ self::NATURAL ][ self::HEALTHY ];
		$sum += $this->ingredients_status[ self::NATURAL ][ self::UNHEALTHY ];
		$sum += $this->ingredients_status[ self::NATURAL ][ self::MODERATE ];

		$sum += $this->ingredients_status[ self::ARTIFICIAL ][ self::HEALTHY ];
		$sum += $this->ingredients_status[ self::ARTIFICIAL ][ self::UNHEALTHY ];
		$sum += $this->ingredients_status[ self::ARTIFICIAL ][ self::MODERATE ];

		return $sum;
	}

	public function get_natural_healthy_num(): int {
		return $this->ingredients_status[ self::NATURAL ][ self::HEALTHY ];
	}

	public function get_natural_moderate_num(): int {
		return $this->ingredients_status[ self::NATURAL ][ self::MODERATE ];
	}

	public function get_natural_unhealthy_num(): int {
		return $this->ingredients_status[ self::NATURAL ][ self::UNHEALTHY ];
	}

	public function get_artificial_healthy_num(): int {
		return $this->ingredients_status[ self::ARTIFICIAL ][ self::HEALTHY ];
	}

	public function get_artificial_moderate_num(): int {
		return $this->ingredients_status[ self::ARTIFICIAL ][ self::MODERATE ];
	}

	public function get_artificial_unhealthy_num(): int {
		return $this->ingredients_status[ self::ARTIFICIAL ][ self::UNHEALTHY ];
	}

	private function calculate_ingredients_status() {

		$ingredients_ids = $this->get_ingredients_ids();
		$ingredients_status = $this->get_empty_ingredients_status();

		foreach ( $ingredients_ids as $ingredient ) {

			// Take ingredients categories.
			$terms = wp_get_post_terms( $ingredient, 'ingredients_categories' );

			foreach ( $terms as $term ) {

				// take first unhealthy, moderate or healthy.
				if (
					$term->name === Ingredients_Calculator::CAT_HEALTHY_NAME ||
					$term->name === Ingredients_Calculator::CAT_UNHEALTHY_NAME ||
					$term->name === Ingredients_Calculator::CAT_MODERATE_NAME
				) {
					if ( $term->parent ) {
						// take his parent.
						$parent_term = get_term( $term->parent );

						$ingredients_status = $this->update_ingredients_status( $ingredients_status, $parent_term, $term );
					}
				}

			}
		}

		return $ingredients_status;
	}


	private function update_ingredients_status( $ingredients_status, $parent, $child ): array {

		if( $parent->name === Ingredients_Calculator::CAT_NATURAL_NAME ) {

			if( $child->name === Ingredients_Calculator::CAT_HEALTHY_NAME ) {
				$ingredients_status[ Ingredients_Calculator::NATURAL ][ Ingredients_Calculator::HEALTHY ]++;
			} elseif( $child->name === Ingredients_Calculator::CAT_UNHEALTHY_NAME ) {
				$ingredients_status[ Ingredients_Calculator::NATURAL ][ Ingredients_Calculator::UNHEALTHY ]++;
			} elseif( $child->name === Ingredients_Calculator::CAT_MODERATE_NAME ) {
				$ingredients_status[ Ingredients_Calculator::NATURAL ][ Ingredients_Calculator::MODERATE ]++;
			}

		} elseif( $parent->name === Ingredients_Calculator::CAT_ARTIFICIAL_NAME ) {

			if( $child->name === Ingredients_Calculator::CAT_HEALTHY_NAME ) {
				$ingredients_status[ Ingredients_Calculator::ARTIFICIAL ][ Ingredients_Calculator::HEALTHY ]++;
			} elseif( $child->name === Ingredients_Calculator::CAT_UNHEALTHY_NAME ) {
				$ingredients_status[ Ingredients_Calculator::ARTIFICIAL ][ Ingredients_Calculator::UNHEALTHY ]++;
			} elseif( $child->name === Ingredients_Calculator::CAT_MODERATE_NAME ) {
				$ingredients_status[ Ingredients_Calculator::ARTIFICIAL ][ Ingredients_Calculator::MODERATE ]++;
			}

		}

		return $ingredients_status;
	}


	private function get_empty_ingredients_status(): array {
		$ingredients_status = array(
			Ingredients_Calculator::NATURAL => array(
				Ingredients_Calculator::HEALTHY => 0,
				Ingredients_Calculator::UNHEALTHY => 0,
				Ingredients_Calculator::MODERATE => 0,
			),

			Ingredients_Calculator::ARTIFICIAL => array(
				Ingredients_Calculator::HEALTHY => 0,
				Ingredients_Calculator::UNHEALTHY => 0,
				Ingredients_Calculator::MODERATE => 0,
			),
		);

		return $ingredients_status;
	}

	private function get_ingredients_ids(): array {

		$post = $this->post;

		$ingredients_text = \WE\Aliment\get_ingredients_text( $post );
		$matches = null;

		preg_match_all( '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $ingredients_text, $matches );

		$url_array = array();

		if ( ! empty( $matches['href'] ) ) :
			$url_array = $matches['href'];
		endif;

		$post_type_allowed = 'ingredient';

		$url_array_count = count( $url_array );
		$ingredients_ids = array();
		for ( $i = 0; $i < $url_array_count; $i++ ) :

			$post_id = url_to_postid( $url_array[ $i ] );

			$post_type = false;
			if ( $post_id ) :
				$post_type = get_post_type( $post_id );
			endif;

			if ( $post_type === $post_type_allowed ) :
				array_push( $ingredients_ids, $post_id );
			endif;

		endfor;

		return $ingredients_ids;
	}

}
