<?php

/*
 * Plugin Name:       UmrahPass
 * Description:       UmrahPass plugin.
 * Version:           1.0.0
 * Author:            Megat Muhammad Aisar Bin Azrin
 */

require_once 'functions.php';
require_once 'shortcodes.php';

//Change name accordingly
function umrahpass_menu_item(){
    $plugin_url = plugin_dir_url( __FILE__ ); // Get the URL of the current plugin directory
    $icon_url = $plugin_url . 'logo/Logo2.png'; // Adjust the path to match your plugin's logo folder structure
	add_menu_page(
		'Home', //Page title
        'UmrahPass', //Menu title
        'manage_options',
        'umrahpass', //Menu slug
        'umrahpass_menu_page', //callback name
        $icon_url
    );
}

    wp_register_style(
    'my_plugin_style',
    plugins_url( 'style.css', __FILE__ ),
    array(), // dependencies
    '1.0.2.6' // version number
    );

    // Add the inline CSS
    wp_enqueue_style( 'my_plugin_style' );

if ( !defined( 'ABSPATH' ) ) exit;

//Hook API menu item to Admin menu
add_action('admin_menu', 'umrahpass_menu_item' );
// Act on plugin activation
register_activation_hook( __FILE__, "activate_myplugin" );
// Act on plugin de-activation
register_deactivation_hook( __FILE__, "deactivate_myplugin" );
//Act on uninstallation of plugin
register_uninstall_hook( __FILE__, 'myplugin_uninstall' );


//ADMIN MENU
function umrahpass_menu_page(){

    ?>
    <div class="header">
        <img class= "image-resize" src="<?php echo plugin_dir_url( __FILE__ ) . 'logo/Logo2.png'; ?>" alt="Logo">
        <a href="#Tombark" class="logo">UMRAHPASS</a>
    </div>

    <div class="header">
        <h1 class="lsc-section-title">Welcome to the homepage</h1>
    </div>

    <div class="api-header">
        <h1 class="lsc-section-title">Key Authentication</h1>
        <hr>
    </div>

    <center>
    <div class="lsc-section" style="width: 50%;">
        <form method="post" action="" class="lsc-section-content">
            <br><br>
            <label for="key"></label>
            <i class="dashicons dashicons-lock"></i>
            <input type="text" name="key" id="key" required placeholder="Key">

            <?php     
            //Validation function
            if (isset($_POST['submit'])) {
                if (validate_api_key()) {
                    echo '<span class="dashicons dashicons-yes sized"></span> Valid key';
                } else {
                    echo '<span class="dashicons dashicons-no sized"></span> Invalid key';
                }
            }

            ?>
            <br><br>
            <input type="submit" name="submit" value="Submit" class="button">
            <br><br>
        </form>
        <br>
    </div>
    </center>

    <?php 
        //Only activates when the key is valid
        if (validate_api_key()) {
            ?> 
                <div class="api-header">
                    <h1 class="lsc-section-title">Company details</h1><hr>
                </div>
            <?php

            $response = wp_remote_post('https://app.umrahpass.io/api/company');

            // Check if the request was successful
            if (is_array($response) && !is_wp_error($response)) {
                // Retrieve the response body
                $body = wp_remote_retrieve_body($response);

                // Parse the JSON response into an associative array
                $data = json_decode($body, true);


                echo '<div class="lsc-section" style="width: 60%;">';
                    // Check if the response contains the expected data
                    if (isset($data[0]['id'])) {
                        $id = $data[0]['id'];
                        echo '<i class="dashicons dashicons-post-status icony"></i><h3  class="icony-text">ID</h3>';
                        echo '<h4 class="icony-desc">' . $id . '</h4><br>';
                    }

                    if (isset($data[0]['comp_email'])) {
                        $compEmail = $data[0]['comp_email'];
                        echo '<i class="dashicons dashicons-email icony"></i><h3 class="icony-text">Email</h3>';
                        echo '<h4 class="icony-desc">' . $compEmail . '</h4><br>';
                    }

                    if (isset($data[0]['comp_mobile'])) {
                        $compMobile = $data[0]['comp_mobile'];
                        echo '<i class="dashicons dashicons-phone icony"></i><h3 class="icony-text">Phone</h3>';
                        echo '<h4 class="icony-desc">' . $compMobile . '</h4><br>';
                    }

                echo '</div>';
            } else {
                echo 'Failed to connect to the API endpoint.';
            }


            /*
            $response = wp_remote_get('http://localhost/plugin/test/db/UmrahPass/API.php');

            // Check if the request was successful
            if (is_wp_error($response)) {
                echo 'Error: ' . $response->get_error_message();
            } else {
                // Retrieve the response body
                $body = wp_remote_retrieve_body($response);

                // Decode the JSON response
                $data = json_decode($body, true);

                // Check if the data is valid
                if ($data) {
                    foreach ($data as $item) {
                        $id = $item['id'];
                        $company_name = $item['company_name'];
                        $address = $item['address'];
                        $email = $item['email'];

                        // Display the data in your desired format
                        echo '<div class="lsc-section" style="width: 60%;">';
                            echo '<i class="dashicons dashicons-post-status icony"></i><h3  class="icony-text">ID</h3>';
                            echo '<h4 class="icony-desc">' . $id . '</h4><br>';

                            echo '<i class="dashicons dashicons-building icony"></i><h3 class="icony-text">Company name</h3>';
                            echo '<h4 class="icony-desc">' . $company_name . '</h4><br>';

                            echo '<i class="dashicons dashicons-admin-home icony"></i><h3 class="icony-text">Address</h3>';
                            echo '<h4 class="icony-desc">' . $address . '</h4><br>';

                            echo '<i class="dashicons dashicons-email icony"></i><h3 class="icony-text">Email</h3>';
                            echo '<h4 class="icony-desc">' . $email . '</h4><br>';

                        echo '</div>';
                    }
                } else {
                    echo '<div class="lsc-section" style="width: 60%;">';
                        echo '<i class="dashicons dashicons-dismiss icony"></i><h3  class="icony-text">No data found!</h3>';
                    echo '</div>';
                }
            } */
        }
    ?>

    <div class="api-header">
        <h1 class="lsc-section-title">Available Shortcodes</h1><hr>
    </div>

    <div class="lsc-section" style="width: 70%;">

        <!-- FIX BUTTON APPEALING -->
        <div class="copy-link">
            <input type="text" class="copy-link-input" value="[retrieve_shortcode]" readonly>
            <button type="button" class="copy-link-button" onclick="cb()">
            <i class="dashicons dashicons-clipboard sized"></i>
            </button>
        </div>
        <h3>This shortcode is used to retrieve data from database and display it in table form</h3><br>

        <div class="copy-link">
            <input type="text" class="copy-link-input" value="[text_shortcode]" readonly>
            <button type="button" class="copy-link-button" onclick="cb()">
            <i class="dashicons dashicons-clipboard sized"></i>
            </button>
        </div>
        <h3>This shortcode is used to retrieve tracking data from database and display it in table form</h3><br>
    </div>

    <footer>
        <div class="api-header">
                <h1 class="lsc-section-title">Supports & Contacts</h1><hr>
        </div>
        <div class="lsc-section" style="width: 70%;">
            <i class="dashicons dashicons-phone"></i> +60123456789<br><br>
            <i class="dashicons dashicons-email"></i> umrahpass@gmail.com<br><br>
            <i class="dashicons dashicons-facebook"></i> UmrahPass<br><br>
            <i class="dashicons dashicons-twitter"></i> UmrahPass
        </div>
    </footer>
    <?php


}

function my_plugin_modify_admin_footer_text($text) {
    // Replace the default footer text with your own content
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_umrahpass') {
        // Replace the footer text with your own content
        $new_text = '© UmrahPass ©';
        return $new_text;
    }

    // For other admin pages, return the default footer text
    return $text;
}

function enqueue_custom_script() {
  wp_enqueue_script(
    'scripts', // A unique name for your script
    plugin_dir_url(__FILE__) . 'scripts.js', // Path to your JavaScript file
    array(), // Dependencies (if any)
    '1.0', // Script version number (optional)
    true // Set to true if you want to load the script in the footer
  );
}
add_action('admin_enqueue_scripts', 'enqueue_custom_script');



add_filter('admin_footer_text', 'my_plugin_modify_admin_footer_text');

//SHORTCODES

add_shortcode('retrieve_shortcode', 'retrieve_shortcode_callback');
add_shortcode('text_shortcode', 'text_callback');
add_shortcode('packages', 'packages_shortcode');
add_shortcode('vars', 'var_shortcode');

//Shortcode for tracking

/*
    One page only !DONE
    Authentication !DONE
    Company details !DONE
    Package details !X DONE
    Copy&Paste function for shortcodes !X DONE
    Instructions !DONE
    Add footer !DONE
    Supports/Contacts !IN PROGRESS
    Version num !IN PROGRESS
*/