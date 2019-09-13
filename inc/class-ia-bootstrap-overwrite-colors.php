<?php

declare( strict_types = 1 );

/**
 * Class used to overwrite the default bootstrap distribution CSS.
 *
 * Bootstrap versions supported: 4.3.1
 */
class IA_Bootstrap_Overwrite_Colors {

	private $version = '';
	private $color_name = '';
	private $new_color = '';


	public function __construct( string $version, string $color_name, string $new_color ) {
		$this->version    = $version;
		$this->color_name = $color_name;
		$this->new_color  = $new_color;
	}

	public function get_css(): string {

		$css = '';

		if ( 'primary' === $this->color_name ) :
			$css = $this->get_primary_css();
		elseif ( 'secondary' === $this->color_name ) :
			$css = $this->get_secondary_css();
		endif;

		return $css;
	}

	private function get_primary_css(): string {
		$css = '';
		$css .= $this->get_root_color_css();

		return $css;
	}

	private function get_secondary_css(): string {
		$css = '';
		$css .= $this->get_root_color_css();

		return $css;
	}



	/**
	 * Return the CSS that overwrites root color of the Bootstrap.
	 *
	 * @return string Will be empty if version is not exactly.
	 */
	private function get_root_color_css(): string {

		$css = '';
		if ( '4.3.1' === $this->version ) :
			$css = ":root { --{$this->color_name}: {$this->new_color} }";
		endif;

		return $css;
	}

	/**
	 * @todo Table.
	 */
/* 	private function get_table_color_css(): string {

	} */

	/**
	 * @todo Forms
	 */



	private function buttons_colors(): string {
		$css = "";
	}

}
