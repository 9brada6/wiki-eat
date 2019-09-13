<?php
/**
 * The Carousel Class will create a Bootstrap carousel
 * based on some image input.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Post;

/**
 * Class used to create and display a Bootstrap carousel.
 *
 * Usage example:
 * Create a new object.
 *     $carousel = new Bootstrap_Carousel('custom-id');
 * Set an array with attachment images.
 *     $carousel.set_images([100,160,456]);
 * Display the carousel:
 *     $carousel.display();
 */
class Bootstrap_Carousel {

	/**
	 * @var string Id attribute of the Carousel main DIV HTML element.
	 */
	protected $carousel_id = '';

	/**
	 * @var int[] ID's of the attachments to be displayed.
	 */
	protected $images_id = array();

	/**
	 * Construct the class that will display the Bootstrap Carousel.
	 *
	 * @param string $carousel_id Optional. The Id attribute of the  wrapper
	 *                            HTML element.
	 */
	public function __construct( string $carousel_id = '' ) {
		if ( $carousel_id ) {
			$this->carousel_id = $carousel_id;
		} else {
			$this->carousel_id = uniqid( 'bootstrap-carousel-' );
		}
	}

	/**
	 * Set the images ids for the carousel to output.
	 *
	 * @param int[] $img The array with images ids.
	 *
	 * @return void
	 */
	public function set_images( array $img ) {
		$this->images_id = $img;
	}

	/**
	 * Check if the carousel can be displayed or not.
	 *
	 * @return bool Either true or false.
	 */
	public function can_be_displayed(): bool {
		foreach ( $this->images_id as $img ) :
			$image_src = wp_get_attachment_image_url( $img, 'large' );
			if ( $image_src ) {
				return true;
			}
		endforeach;

		return false;
	}

	public function num_images(): int {

		$num = 0;

		foreach ( $this->images_id as $img ) :
			$image_src = wp_get_attachment_image_url( $img, 'large' );
			if ( $image_src ) {
				$num++;
			}
		endforeach;

		return $num;
	}

	/**
	 * Display the carousel.
	 *
	 * An array with image ids must be added first to the class.
	 *
	 * @return void
	 */
	public function display() {
		if ( ! $this->can_be_displayed() ) {
			return;
		}

		?>
			<div id="<?= esc_attr( $this->carousel_id ) ?>" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<?php
						$active = 'active';
						foreach ( $this->images_id as $img ) :
							$this->create_item( (int) $img, $active );
							$active = '';
						endforeach;
					?>
				</div>

				<?php
					if ( $this->num_images() > 1 ) {
						$this->create_controls();
					}
				?>

			</div>
		<?php
	}

	/**
	 * Creates a carousel item.
	 *
	 * @param int    $img_id The id of image.
	 * @param string $active Optional. Pass 'active' for an item to display.
	 *                       In a carousel there must be only one at a time.
	 *
	 * @return bool True if the item could be created, false otherwise.
	 */
	protected function create_item( int $img_id, string $active = '' ): bool {

		$box_class = ( 'active' === $active ? 'carousel-item active' : 'carousel-item' );

		$src = wp_get_attachment_image_url( $img_id, 'large' );

		if ( ! $src ) {
			return false;
		}

		?>
			<div class="<?= esc_attr( $box_class ) ?>">
				<div class="embed-responsive embed-responsive-16by9">
					<img class="embed-responsive-item w-auto m-auto" style="right: 0" src="<?= esc_url( $src ) ?>">
				</div>
			</div>
		<?php

		return true;
	}

	/**
	 * Creates and echoes the next/previous buttons for Carousel.
	 *
	 * @return void
	 */
	protected function create_controls() {
		?>
			<a class="carousel-control-prev" href="#<?= esc_attr( $this->carousel_id ) ?>" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only"><?php _ex( 'Previous', 'carousel, accessibility text', 'wiki-eat' ); ?></span>
			</a>

			<a class="carousel-control-next" href="#<?= esc_attr( $this->carousel_id ) ?>" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only"><?php _ex( 'Next', 'carousel, accessibility text', 'wiki-eat' ); ?></span>
			</a>
		<?php
	}
}
