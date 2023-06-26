<?php

//FUNCTIONS TESTING

function validate_api_key() {
    global $wpdb;
    $table = $wpdb->prefix . 'umrahpass_key';
    // Check if the form has been submitted
    if(isset($_POST['submit'])) { 

        // Get the value of the input field named "api_key"
        $key = $_POST['key']; 
        $existing_key = $wpdb->get_var( $wpdb->prepare( "SELECT `key` FROM $table WHERE `key` = %s", $key ) );
        $response = wp_remote_post( 
            'https://dev.jomdonate.mova.my/api/check-false?license_no=afsdsfddfsfd',
            array(
                'body' => array(
                    'license_no' => 'afsdsfddfsfd',
                    'key' => $key
                )
            )
        );  

        //Check if the key is valid or not
        if ( $existing_key ) {

            //Valid value return 1
            $response = wp_remote_post( 
                'https://dev.jomdonate.mova.my/api/check-true?license_no=afsdsfddfsfd',
                array(
                    'body' => array(
                        'license_no' => 'afsdsfddfsfd',
                        'key' => $key
                    )
                )
            );

            if (is_array($response) && !is_wp_error($response)) {

                $body = wp_remote_retrieve_body($response);

                if ($body == '1') {
                    return true;
                } else {
                    return false;
                }
            } else {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
                return false;
            }
        } else {
            return false;
        } 
    }
}

// Initialize DB Tables
function init_db_myplugin() {

    // WP Globals
    global $table_prefix, $wpdb;

    //  Table
    $table = $table_prefix . 'umrahpass_key';

    // Create Table if not exist
    if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$table` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `key` varchar(12) NOT NULL, ";
        $sql .= " PRIMARY KEY `key_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );

        $key = '12345';
        $wpdb->insert( $table, array( 'key' => $key ) );
    }
}

//Initialize the key
/*
function init_key(){

    global $wpdb;
    $key = '12345'; // Replace 'value' with your desired key value
    $existing_key = $wpdb->get_var(
        $wpdb->prepare("SELECT `key` FROM $table WHERE `key` = %s", $key)
    );

    // Insert the key if it doesn't exist
    if (!$existing_key) {
        $wpdb->insert($table_name, array('12345' => $key));
    }
}
*/

//PLUGIN SETTINGS
// Activate Plugin
function activate_myplugin() {

    // Execute tasks on Plugin activation
    // Insert DB Tables
    init_db_myplugin();
}

// De-activate Plugin
function deactivate_myplugin() {
     
}

//Drop table in database after uninstallation
function myplugin_uninstall() {

    global $wpdb;
    $table = $wpdb->prefix . 'umrahpass_key';
    $wpdb->query( "DROP TABLE IF EXISTS $table" );

    wp_dequeue_style( 'my_plugin_style' );
}