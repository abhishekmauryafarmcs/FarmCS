<?php
header('Content-Type: application/json');

// Path to the CSV file
$csvFile = '../sprinkler cost data/sprinkler_cost - Sheet1.csv';

// Check if the file exists
if (!file_exists($csvFile)) {
    echo json_encode(['success' => false, 'message' => 'CSV file not found: ' . $csvFile]);
    exit;
}

// Open the CSV file
if (($handle = fopen($csvFile, 'r')) !== false) {
    $data = [];
    
    // Skip the first two empty rows
    fgetcsv($handle, 1000, ',');
    fgetcsv($handle, 1000, ',');
    
    // Read the header row (third row)
    $header = fgetcsv($handle, 1000, ',');

    // Read each row of the CSV file
    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        if (!empty($row[0])) { // Only process rows with a Land Registry No.
            $rowData = array_combine($header, $row);
            $data[] = $rowData;
        }
    }
    fclose($handle);

    // Create mapping of Land Registry numbers to their associated Tehsils
    $tehsilMapping = [];
    $villageMapping = [];
    $plotMapping = [];

    foreach ($data as $row) {
        $regNo = trim($row['Land Registry No.']);
        $tehsil = trim($row['Tehsil']);
        $village = trim($row['Village']);
        $plotNo = trim($row['Plot No.']); // Note the change from 'Plot Number' to 'Plot No.'

        // Map Tehsils to Land Registry numbers
        if (!isset($tehsilMapping[$regNo])) {
            $tehsilMapping[$regNo] = [];
        }
        if (!in_array($tehsil, $tehsilMapping[$regNo])) {
            $tehsilMapping[$regNo][] = $tehsil;
        }

        // Map Villages to Tehsils for each Land Registry number
        $key = $regNo . '_' . $tehsil;
        if (!isset($villageMapping[$key])) {
            $villageMapping[$key] = [];
        }
        if (!in_array($village, $villageMapping[$key])) {
            $villageMapping[$key][] = $village;
        }

        // Store the complete data for each combination
        $plotMapping[$regNo . '_' . $tehsil . '_' . $village] = [
            'plotNumbers' => $plotNo,
            'area' => $row['Area (Hectares)'],
            'dimensions' => $row['Dimensions (m x m)'],
            'sprinklers' => $row['No. of Sprinklers'],
            'sensors' => $row['No. of Moisture Sensors'],
            'totalCost' => $row['Total Cost (INR)']
        ];
    }

    // Sort all arrays
    foreach ($tehsilMapping as &$tehsils) {
        sort($tehsils);
    }
    foreach ($villageMapping as &$villages) {
        sort($villages);
    }

    echo json_encode([
        'success' => true,
        'tehsilMapping' => $tehsilMapping,
        'villageMapping' => $villageMapping,
        'plotMapping' => $plotMapping
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Unable to open CSV file.']);
}
?> 