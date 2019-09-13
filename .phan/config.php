<?php
/**
 * This configuration will be read and overlaid on top of the
 * default configuration. Command line arguments will be applied
 * after this file is read.
 */

return [

	// Supported values: '7.0', '7.1', '7.2', '7.3', null.
	// If this is set to null,
	// then Phan assumes the PHP version which is closest to the minor version
	// of the php executable used to execute phan.
	'target_php_version'              => '7.3',

	'exclude_file_regex'              => '/(vendor|node_modules|noop)/',

	// A list of directories that should be parsed for class and
	// method information. After excluding the directories
	// defined in exclude_analysis_directory_list, the remaining
	// files will be statically analyzed for errors.
	//
	// Thus, both first-party and third-party code being used by
	// your application should be included in this list.
	'directory_list'                  => [
		'./',
		'./../../../wp-admin',
		'./../../../wp-includes',
	],

	'exclude_analysis_directory_list' => [
		'./../../../wp-admin',
		'./../../../wp-includes',
	],


	// How many processes to make when analyzing files.
	'processes' => 1,

	// maybe disable this as default.
	'strict_method_checking' => true,

	'strict_param_checking' => true,

	'strict_property_checking' => true,

	'strict_return_checking' => true,

	'enable_include_path_checks' => true,

	'maximum_recursion_depth' => 2,



];
