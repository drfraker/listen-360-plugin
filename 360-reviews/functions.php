<?php

//create shortag with options for placing reviews on pages and in widgets
add_shortcode('l360_reviews', array(new L360Reviews(), 'l360_reviews_shortcode'));


//add options page to the admin dashboard
function l360_reviews_admin_add_page() {
    add_options_page('L360 Reviews Options', 'L360 Reviews', 'manage_options', '360Reviews', 'plugin_options_page');
}
// display the admin options page
function plugin_options_page() {
    //include the partials options page html
   include('_partials/menu_options.php');
}




//init the options to save to the database
add_action('admin_init', 'plugin_admin_init');

function plugin_admin_init(){
    register_setting( 'plugin_options', 'plugin_options', 'plugin_options_validate' );
    add_settings_section('plugin_main', 'Plugin Settings', 'plugin_section_text', 'plugin');
    add_settings_field('l360url', 'Listen 360 Custom URL', 'l360_url', 'plugin', 'plugin_main');
    add_settings_field('l360perPage', '# Of Reviews Per Page', 'l360PerPage', 'plugin', 'plugin_main');
    add_settings_field('promoterBg', 'Color Of Promoter Reviews', 'promoterBg', 'plugin', 'plugin_main');
    add_settings_field('passiveBg', 'Color Of Passive Reviews', 'passiveBg', 'plugin', 'plugin_main');
    add_settings_field('detractorBg', 'Color Of Detractor Reviews', 'detractorBg', 'plugin', 'plugin_main');
}

function plugin_section_text() {
    echo '<p>Complete the form to set up Listen 360 on your site.</p>';
}

//set text input
function l360_url() {
    $options = get_option('plugin_options');
    echo "<input id='l360url' name='plugin_options[url]' size='150' type='text' value='{$options['url']}' /><br />
    <p class=\"description\">Find this URL in your user profile at the <a href=\"app.listen360.com\">app.listen360.com</a> website  </p>";
}

//set text input
function l360perPage() {
    $options = get_option('plugin_options');
    echo "<input id='l360PerPage' name='plugin_options[perPage]' size='1' type='text' value='{$options['perPage']}' />";
}

//set text input
function promoterBg() {
    $options = get_option('plugin_options');
    echo "<input id='promoterBg' name='plugin_options[promoterBg]' value='{$options['promoterBg']}' class='l360-color-picker' data-default-color='#beefa7' />";
}

//set text input
function passiveBg() {
    $options = get_option('plugin_options');
    echo "<input id='passiveBg' name='plugin_options[passiveBg]' value='{$options['passiveBg']}' class='l360-color-picker' data-default-color='#e8d4a7' />";
}

//set text input
function detractorBg() {
    $options = get_option('plugin_options');
    echo "<input id='detractorBg' name='plugin_options[detractorBg]' value='{$options['detractorBg']}' class='l360-color-picker' data-default-color='#ffd6d6' />";
}

// validate our options
function plugin_options_validate($input) {
    $options = get_option('plugin_options');
    $options['url'] = esc_url(trim($input['url']));
    $options['perPage'] = (trim($input['perPage']));
    $options['promoterBg'] = ($input['promoterBg']);
    $options['passiveBg'] = ($input['passiveBg']);
    $options['detractorBg'] = ($input['detractorBg']);

    return $options;
}



//add color picker functions for options page
add_action( 'admin_enqueue_scripts', 'l360_reviews_enqueue_color_picker' );

//color picker for options page to pick colors for the reviews background.
function l360_reviews_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'l360-reviews-color-picker', plugins_url('js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

