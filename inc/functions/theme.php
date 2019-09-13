<?php

add_action( 'after_setup_theme', '\\WE\\Functions\\add_theme_supports' );

load_theme_textdomain( 'wiki-eat', get_template_directory() . '/languages' );
