<?php
/*
    Plugin Name: FP .csv import
    Description: A plugin to import CSV files into the database.
    Version: 1.0
    Author: Fernando Papaqui
    Author URI: https://www.fernandopapaqui.dev
*/



// Define a function to display the CSV import form
function csv_importer_menu() {
    add_menu_page('CSV Importer', 'CSV Importer', 'manage_options', 'csv-importer', 'csv_importer_page');
}


// Define a function to display the CSV import form content
function csv_importer_page() {
    if (isset($_POST['import_csv'])) {
        $csv_file = $_FILES['csv_file'];
        if (!empty($csv_file['tmp_name'])) {
            $csv_data = file_get_contents($csv_file['tmp_name']);
            $lines = explode("\n", $csv_data);
            foreach ($lines as $line) {
                $data = str_getcsv($line);
                if (count($data) === 2) {
                    $name = sanitize_text_field($data[0]);
                    $email = sanitize_email($data[1]);
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'csv_data'; // Replace with your table name
                    $wpdb->insert(
                        $table_name,
                        array(
                            'name' => $name,
                            'email' => $email,
                        )
                    );
                }
            }
        }
    }
    ?>
    <div class="container">
        <h2>CSV Importer</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file">
            <input type="submit" name="import_csv" value="Import CSV">
        </form>
    </div>
    <?php
}

// Hook into the WordPress admin menu to add the CSV import page
add_action('admin_menu', 'csv_importer_menu');