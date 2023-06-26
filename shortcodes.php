<?php

function retrieve_shortcode_callback( $atts) {


    //CHECK AUTHENTICATION FOR VALID KEY

    //Call 2nd API GET
    global $wpdb;
    $table = $wpdb->prefix . 'umrahpass_key';
    $key = $wpdb->get_var("SELECT `key` FROM $table");

        //Check if the key is valid or not
        if ( $key != '' ) {

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

                        $result = $wpdb->get_results("SELECT * FROM $table");

                        $output = '<h2 class="lsc-section-title">Key Listing</h2>';
                        $output .= '<table class="special">';
                        $output .= '<thead><tr><th>ID</th><th>Key</th></tr></thead>';
                        $output .= '<tbody>';
                        foreach ($result as $row) {
                            $output .= '<tr>';
                            $output .= '<td>' . $row->id . '</td>';
                            $output .= '<td>' . $row->key . '</td>';
                            $output .= '</tr>';
                        }
                       return $output .= '</tbody></table>';
                          //return $output .= '<div class="lsc-section1"><h1 class="red">' . $key . '</h1></div>';
                } 
                else {
                        $output = '<h2 class="lsc-section-title">Key Listing</h2>';                    
                        $output .= '<table class="special">';
                        $output .= '<thead><tr><th>ID</th><th>Key</th></tr></thead>';
                        $output .= '<tbody>';
                        $output .= '<tr>';
                            $output .= '<td rowspan="2">No data</td>';
                            $output .= '</tr>';
                           return $output .= '</tbody></table>';

                }
            } else {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            }
        }


}

function text_callback( $atts) {

    return $output = '<h3 class="lsc-section-title">This is a shortcode for retrieving tracking data</h3>';
}

/* function packages_shortcode($atts) {

    $response = wp_remote_post('https://app.umrahpass.io/api/subs');
    $response2 = wp_remote_get('https://app.umrahpass.io/api/subsName');

    // Check if the request was successful
    if (is_array($response) && !is_wp_error($response)) {
        // Retrieve the response body
        $body = wp_remote_retrieve_body($response);

        // Parse the JSON response into an associative array
        $data = json_decode($body, true);

        if ($data !== null) {
            $output = '';

            foreach ($data as $variant) {
                // Access the data fields as needed
                $planId = $variant['plan_id'];
                $description = $variant['description'];
                $price = $variant['price'];
                // ... access other fields

                // Build the output
                $output .= 'Plan ID: ' . $planId . '<br>';
                $output .= 'Description: ' . $description . '<br>';
                $output .= 'Price: ' . $price . '<br>';
                // ... build other fields
            }

            // Check if the second API request was successful
            if (is_array($response2) && !is_wp_error($response2)) {
                // Retrieve the response body
                $body2 = wp_remote_retrieve_body($response2);

                // Parse the JSON response into an associative array
                $nameData = json_decode($body2, true);

                // Access the 'name' field from the second API response
                $name = $nameData['name'];

                // Build the output with the 'name' field
                $output .= 'Name: ' . $name . '<br>';
            } else {
                $output .= 'Failed to connect to the "subsName" API endpoint.';
            }

            return $output;
        } else {
            return 'Failed to retrieve data from the API.';
        }
    } else {
        return 'Failed to connect to the API endpoint.';
    }
} */

function packages_shortcode($atts) {
    // API URLs
    $subsNameUrl = 'https://app.umrahpass.io/api/subsName';
    $subsUrl = 'https://app.umrahpass.io/api/subs';

    // Fetch data from subsName API
    $subsNameResponse = wp_remote_post($subsNameUrl);
    $subsNameData = json_decode(wp_remote_retrieve_body($subsNameResponse), true);

    // Fetch data from subs API
    $subsResponse = wp_remote_post($subsUrl);
    $subsData = json_decode(wp_remote_retrieve_body($subsResponse), true);

    // Check if the API requests were successful
    if (is_array($subsNameData) && is_array($subsData)) {
        // Create an empty output variable
        $output = '';

        // Iterate through the subsData
        foreach ($subsData as $subsItem) {
            // Find the corresponding name from the subsNameData based on the plan_id
            $name = '';
            foreach ($subsNameData as $subsNameItem) {
                if ($subsNameItem['id'] === $subsItem['plan_id']) {
                    $name = $subsNameItem['name'];
                    break;
                }
            }

            // Get the description from the subsData
            $description = $subsItem['description'];

            // Build the output for each item
            $output .= '<center>';
            $output .= 'Name: ' . $name . '<br>';
            $output .= 'Description: ' . $description . '<br>';
            $output .= '--------------------<br>';
            $output .= '</center>';
        }

        // Return the final output
        return $output;
    } else {
        return 'Failed to retrieve data from the API.';
    }
}

function var_shortcode($atts) {
    // API URL
    $subsNameUrl = 'https://app.umrahpass.io/api/subsName';

    // Fetch data from subsName API
    $subsNameResponse = wp_remote_post($subsNameUrl);
    $subsNameData = json_decode(wp_remote_retrieve_body($subsNameResponse), true);

    // Check if the API request was successful
    if (is_array($subsNameData)) {
        // Create an empty output variable
        $output = '';

        // Iterate through the subsNameData
        foreach ($subsNameData as $subsNameItem) {
            // Get the id and name
            $id = $subsNameItem['id'];
            $name = $subsNameItem['name'];

            // Build the output for each item
            $output .= '<center>';
            $output .= 'ID: ' . $id . '<br>';
            $output .= 'Name: ' . $name . '<br>';
            $output .= '--------------------<br>';
            $output .= '</center>';
        }

        // Return the final output
        return $output;
    } else {
        return 'Failed to retrieve data from the API.';
    }
}



