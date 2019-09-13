<?php

namespace IA;
use Exception;

class One_To_Many_Post_Relationship{

	static $relationships = array();

	private $name;

	private $parent_post_type;

	private $children_post_type;

	private static $meta_name_prefix = '_otm';

	private $meta_name_used_on_parent;
	private $meta_name_used_on_children;


	/**
	 * Register a relationship between 2 different entities.
	 *
	 * @param string $name An unique name, this is used to know what relationship to call, and in meta.
	 * @param string $parent_post_type  A valid post type.
	 * @param string $children_post_type  A valid post type.
	 *
	 * @return void
	 */
	static public function register_relationship( string $name, string $parent_post_type, string $children_post_type ) {

		if ( self::name_exist( $name ) ) :
			$message = 'One to Many post relationships already have a "' . $name
			. '" that exist, called in register_relationship().';
			throw new Exception( $message );
		endif;

		$new_relationship = array(
			'name' => $name,
			'parent_post_type'  => $parent_post_type,
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
			throw new Exception( 'One to many post relationship doesn\'t exist, called on constructor.' );
		endif;

		$this->name = $relationship['name'];
		$this->parent_post_type = $relationship['parent_post_type'];
		$this->children_post_type = $relationship['children_post_type'];

		$this->meta_name_used_on_parent = self::$meta_name_prefix . '_parent_' . $relationship['name'];
		$this->meta_name_used_on_children = self::$meta_name_prefix . '_children_' . $relationship['name'];
	}


	/**
	 * Return the Parent ID of the Children.
	 *
	 * @param int $child_id
	 *
	 * @return int The parent ID.
	 */
	public function get_parent_id( int $child_id ) : int {
		$parent_id = intval( get_post_meta( $child_id, $this->meta_name_used_on_children, true ) );

		if ( $parent_id < 1 ) :
			$parent_id = 0;
		endif;

		return $parent_id;
	}

	/**
	 * Return an array of children ID's, from a specific parent.
	 *
	 * @param integer $parent_id The Parent ID to get the children from.
	 *
	 * @return array Array of int, each corresponding to a children.
	 */
	public function get_children_ids( int $parent_id ) : array {
		$children_ids = get_post_meta( $parent_id, $this->meta_name_used_on_parent, true );

		if ( ! is_array( $children_ids ) ) :
			$children_ids = array();
		endif;

		return $children_ids;
	}





	public function add_relationship( $parent_id, $child_id ) {

		// Verify if ID's are correct and exist.
		$parent_id = $this->valid_id_and_exist( $parent_id, 'parent' );
		$child_id  = $this->valid_id_and_exist( $child_id, 'children' );

		if ( ! $parent_id || ! $child_id ) :
			return;
		endif;

		// Update the meta on child.
		update_post_meta( $child_id, $this->meta_name_used_on_children, $parent_id );

		// Update the meta on parent.
		$children = $this->get_children_ids( $parent_id );

		if ( array_search( $child_id, $children ) !== false ) :
			return;
		endif;

		$children[] = $child_id;

		update_post_meta( $parent_id, $this->meta_name_used_on_parent, $children );

	}



	public function remove_relationship( $child_id ) {

		$parent_id = $this->get_parent_id( $child_id );
		delete_post_meta( $child_id, $this->meta_name_used_on_children );

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
			update_post_meta( $parent_id, $this->meta_name_used_on_parent, $children );
		else :
			delete_post_meta( $parent_id, $this->meta_name_used_on_parent );
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

		$id_type = $type === 'parent' ? $this->parent_post_type : $this->children_post_type;

		if ( $post && get_post_type( $post ) === $id_type ) :
			return $post_id;
		endif;

		return false;
	}

}
