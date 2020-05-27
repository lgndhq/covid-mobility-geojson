<?php

ini_set('memory_limit', -1);
if (!file_exists(__DIR__.'/us-county-boundaries.geojson')) {
	fwrite(STDERR, "Downloading US County Boundaries from Opendatasoft...");
	file_put_contents(__DIR__.'/us-county-boundaries.geojson', fopen('https://public.opendatasoft.com/explore/dataset/us-county-boundaries/download/?format=geojson&timezone=America/Denver&lang=en', 'r'));
	fwrite(STDERR, " Done\n");
}

if (count($argv) != 2) {
	fwrite(STDERR, "You must specify the path to the Mobility Report CSV");
	fwrite(STDERR, "\nDownload the global CSV from https://www.google.com/covid19/mobility/ and pass the path\n\n");
	die();
}

fwrite(STDERR, "Processing county boundary GeoJSON...");
$json = json_decode(file_get_contents(__DIR__.'/us-county-boundaries.geojson'), true);
$features = [];
foreach ($json['features'] as $feature) {
	$features[$feature['properties']['stusab'] . '-' . $feature['properties']['namelsad']] = $feature;
	$features[$feature['properties']['stusab'] . '-' . $feature['properties']['name']] = $feature;
}
fwrite(STDERR, " Done\n");

$states = ['Alabama' => 'AL','Alaska' => 'AK','Arizona' => 'AZ','Arkansas' => 'AR','California' => 'CA','Colorado' => 'CO','Connecticut' => 'CT','Delaware' => 'DE','Florida' => 'FL','Georgia' => 'GA','Hawaii' => 'HI','Idaho' => 'ID','Illinois' => 'IL','Indiana' => 'IN','Iowa' => 'IA','Kansas' => 'KS','Kentucky' => 'KY','Louisiana' => 'LA','Maine' => 'ME','Maryland' => 'MD','Massachusetts' => 'MA','Michigan' => 'MI','Minnesota' => 'MN','Mississippi' => 'MS','Missouri' => 'MO','Montana' => 'MT','Nebraska' => 'NE','Nevada' => 'NV','New Hampshire' => 'NH','New Jersey' => 'NJ','New Mexico' => 'NM','New York' => 'NY','North Carolina' => 'NC','North Dakota' => 'ND','Ohio' => 'OH','Oklahoma' => 'OK','Oregon' => 'OR','Pennsylvania' => 'PA','Rhode Island' => 'RI','South Carolina' => 'SC','South Dakota' => 'SD','Tennessee' => 'TN','Texas' => 'TX','Utah' => 'UT','Vermont' => 'VT','Virginia' => 'VA','Washington' => 'WA','West Virginia' => 'WV','Wisconsin' => 'WI','Wyoming' => 'WY','Virgin Islands' => 'V.I.','Guam' => 'GU','Puerto Rico' => 'PR'];

fwrite(STDERR, "Processing CSV...");
$fh = fopen($argv[1], 'r');
$counties = [];
while (($data = fgetcsv($fh)) !== false) {
	if ($data[0] !== 'US') continue;
	if ($data[3] == '') continue;
	$date = $data[4];
	$abbrev = $states[$data[2]];
	$county = $data[3];
	$county = $abbrev == 'LA' ? str_replace('La Salle', 'LaSalle', $county) : $county;
	if (isset($features[$abbrev.'-'.$county])) {
		if (!isset($counties[$abbrev.'-'.$county]))
			$counties[$abbrev.'-'.$county] = $features[$abbrev.'-'.$county];
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'retail_and_recreation_percent_change_from_baseline'] = strlen($data[5]) > 0 ? (int)$data[5] : '';
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'grocery_and_pharmacy_percent_change_from_baseline'] = strlen($data[6]) > 0 ? (int)$data[6] : '';
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'parks_percent_change_from_baseline'] = strlen($data[7]) > 0 ? (int)$data[7] : '';
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'transit_stations_percent_change_from_baseline'] = strlen($data[8]) > 0 ? (int)$data[8] : '';
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'workplaces_percent_change_from_baseline'] = strlen($data[9]) > 0 ? (int)$data[9] : '';
		$counties[$abbrev.'-'.$county]['properties'][$date.'-'.'residential_percent_change_from_baseline'] = strlen($data[10]) > 0 ? (int)$data[10] : '';
	} else {
		throw new \Exception("Could not find {$county} in {$abbrev}");
	}
}
fwrite(STDERR, " Done\n");
fclose($fh);

echo json_encode(['type' => 'FeatureCollection', 'features' => array_values($counties)], JSON_PRETTY_PRINT);
