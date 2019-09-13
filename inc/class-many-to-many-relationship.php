<?php

namespace IA;
use Exception;

class Many_To_Many_Post_Relationship{

	static $relationships = array();

	private $name;

	private $parents_post_type;

	private $children_post_type;

	private static $meta_name_prefix = '_mtm';

	private $meta_name_used_on_parents;
	private $meta_name_used_on_children;


	/**
	 * Register a relationship between 2 different entities.
	 *
	 * @param string $name An unique name, this is used to know what relationship to call, and in meta.
	 * @param string $parents_post_type  A valid post type.
	 * @param string $children_post_type  A valid post type.
	 *
	 * @return void
	 */
	static public function register_relationship( string $name, string $parents_post_type, string $children_post_type ) {

		if ( self::name_exist( $name ) ) :
			$message = 'Many to Many post relationships already have a "' . $name
			. '" that exist, called in register_relationship().';
			throw new Exception( $message );
		endif;

		$new_relationship = array(
			'name' => $name,
			'parents_post_type'  => $parents_post_type,
			'children_post_type' => $children_post_type,
		);

		self::$relationships[] = $new_relationship;
	}

	/**
	 * Return an object from which we can manage the one to many
	 * links between posts.
	 *
	 * @param string $name The name of the relationship to construct the object.
	 */
	public function __construct( string $name ){

		$relationship = $this->get_relationship_info_by_name( $name );

		if ( ! $relationship ) :
			throw new Exception( 'Many to many post relationship doesn\'t exist, called on constructor.' );
		endif;

		$this->name = $relationship['name'];
		$this->parents_post_type = $relationship['parents_post_type'];
		$this->children_post_type = $relationship['children_post_type'];

		$this->meta_name_used_on_parents = self::$meta_name_prefix . '_parents_' . $relationship['name'];
		$this->meta_name_used_on_children = self::$meta_name_prefix . '_children_' . $relationship['name'];
	}


	/**
	 * Return the Parent ID of the Children.
	 *
	 * @param int $child_id
	 *
	 * @return int The parent ID.
	 */
	public function get_parents_ids( int $child_id ) : array {
		$parents_ids = get_post_meta( $child_id, $this->meta_name_used_on_children, true );

		if ( ! is_array( $parents_ids ) ) :
			$parents_ids = array();
		endif;

		return $parents_ids;
	}

	/**
	 * Return an array of children ID's, from a specific parent.
	 *
	 * @param int $parent_id The Parent ID to get the children from.
	 *
	 * @return array Array of int, each corresponding to a children.
	 */
	public function get_children_ids( int $parent_id ) : array {
		$children_ids = get_post_meta( $parent_id, $this->meta_name_used_on_parents, true );

		if ( ! is_array( $children_ids ) ) :
			$children_ids = array();
		endif;

		return $children_ids;
	}





	public function add_relationship( $parent_id, $child_id ) {

		// Verify if ID's are correct and exist.
		$parent_id   = $this->valid_id_and_exist( $parent_id, 'parent' );
		$child_id = $this->valid_id_and_exist( $child_id, 'children' );

		if ( ! $parent_id || ! $child_id ) :
			return;
		endif;


		// Update the meta on child.
		$parents = $this->get_parents_ids( $child_id );

		$update = true;
		if ( array_search( $parent_id, $parents ) !== false ) :
			$update = false;
		endif;

		$parents[] = $parent_id;

		if ( $update ) :
			update_post_meta( $child_id, $this->meta_name_used_on_children, $parents );
		endif;


		// Update the meta on parent.
		$children = $this->get_children_ids( $parent_id );

		if ( array_search( $child_id, $children ) !== false ) :
			return;
		endif;

		$children[] = $child_id;

		update_post_meta( $parent_id, $this->meta_name_used_on_parents, $children );

	}



	public function remove_relationship( $parent_id, $child_id ) {

		$parents = $this->get_parents_ids( $child_id );

		$update = true;
		if ( ! count( $parents ) ):
			$update = false;
		endif;

		$index = array_search( $parent_id, $parents );
		if ( $index !== false ) :
			unset( $parents[ $index ] );
		endif;
		$parents = array_values( $parents );


		if ( count( $parents ) ) :
			update_post_meta( $child_id, $this->meta_name_used_on_children, $parents );
		else :
			delete_post_meta( $child_id, $this->meta_name_used_on_children );
		endif;




		$children = $this->get_children_ids( $parent_id );

		if ( ! count( $children ) ):
			return;
		endif;

		$index = array_search( $child_id, $children );
		if ( $index !== false ) :
			unset( $children[ $index ] );
		endif;
		$children = array_values( $children );


		if ( count( $children ) ) :
			update_post_meta( $parent_id, $this->meta_name_used_on_parents, $children );
		else :
			delete_post_meta( $parent_id, $this->meta_name_used_on_parents );
		endif;
	}


	private static function name_exist( $new_name ) : bool {
		foreach ( self::$relationships as $relationship ) :
			if ( $relationship['name'] === $new_name ) :
				return true;
			endif;
		endforeach;

		return false;
	}

	private function get_relationship_info_by_name( $name ) {
		foreach ( self::$relationships as $relationship ) :
			if ( $relationship['name'] === $name ) :
				return $relationship;
			endif;
		endforeach;

		return null;
	}


	private function valid_id_and_exist ( $post_id, $type ) {
		$post_id = intval( $post_id, 10 );

		if ( $post_id < 1 ) :
			return false;
		endif;

		$post = get_post( $post_id );

		$id_type = $type === 'parent' ? $this->parents_post_type : $this->children_post_type;

		if ( $post && get_post_type( $post ) === $id_type ) :
			return $post_id;
		endif;

		return false;
	}

}
