<?php
header('Content-Type: application/json');

// Get the POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Path to the CSV file
$csvFile = '../data/sprinkler_cost.csv';

// Initialize response
$response = [
    'success' => false,
    'message' => ''
];

try {
    if (!file_exists($csvFile)) {
        throw new Exception('CSV file not found');
    }

    // Read CSV file
    $handle = fopen($csvFile, 'r');
    $header = fgetcsv($handle, 1000, ',');
    $found = false;

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        $rowData = array_combine($header, $row);
        
        // Match the record based on input criteria
        if ($rowData['Land Registry No.'] == $data['landRegistryNo'] &&
            $rowData['Tehsil'] == $data['tehsil'] &&
            $rowData['Village'] == $data['village'] &&
            $rowData['Plot Number'] == $data['plotNumber']) {
            
            $response = [
                'success' => true,
                'area' => $rowData['Area (Hectares)'],
                'dimensions' => $rowData['Dimensions (m x m)'],
                'sprinklers' => $rowData['No. of Sprinklers'],
                'sensors' => $rowData['No. of Moisture Sensors'],
                'totalCost' => $rowData['Total Cost (INR)']
            ];
            $found = true;
            break;
        }
    }

    fclose($handle);

    if (!$found) {
        throw new Exception('No matching record found');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?> 