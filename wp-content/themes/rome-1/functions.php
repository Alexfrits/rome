<?php

function rome_setup() {
    load_theme_textdomain('rome-1', get_template_directory().'/languages');
}
add_action('after_setup_theme', 'rome_setup');

?>