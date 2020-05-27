# COVID Mobility Report to GeoJSON
For a recent presentation, [we at LGND](https://lgnd.com) needed to plot [Google's COVID-19 Community Mobility Reports](https://www.google.com/covid19/mobility/) county-by-county on a map. Since the global CSV file Google provides doesn't include FIPS codes, we needed to parse it into a GeoJSON file to plot it out. We've bundled that process together, hoping it might be useful for communities and companies in their COVID response mapping projects.

Running this script requires a relatively recent version of PHP to be installed on your machine. It will automatically download the US county boundaries file from Opendatasoft, but you should download your own copy of the report CSV from Google.

## Usage
- Click the "Download global CSV" button on the [Mobility Reports page](https://www.google.com/covid19/mobility/) and save it somewhere useful
- Download the `mobility-to-geojson.php` file from this project
- Execute `mobility-to-geojson.php Global_Mobility_Report.csv > output.geojson`

## Output
The script outputs a GeoJSON FeatureCollection with one feature per county. The report segments results by date, so the reported fields are included as properties by date and property name. E.g.:

```json                
{"properties": {
                "stusab": "AL",
                "namelsad": "Autauga County",
                "countyns": "00161526",
                "countyfp": "001",
                "2020-05-13-retail_and_recreation_percent_change_from_baseline": 12,
                "2020-05-13-grocery_and_pharmacy_percent_change_from_baseline": 14,
                "2020-05-13-parks_percent_change_from_baseline": "",
                "2020-05-13-transit_stations_percent_change_from_baseline": "",
                "2020-05-13-workplaces_percent_change_from_baseline": -29,
                "2020-05-13-residential_percent_change_from_baseline": 10,
                "2020-05-14-retail_and_recreation_percent_change_from_baseline": 1,
                "2020-05-14-grocery_and_pharmacy_percent_change_from_baseline": 19,
                "2020-05-14-parks_percent_change_from_baseline": "",
                "2020-05-14-transit_stations_percent_change_from_baseline": "",
                "2020-05-14-workplaces_percent_change_from_baseline": -30,
                "2020-05-14-residential_percent_change_from_baseline": "",
                "2020-05-15-retail_and_recreation_percent_change_from_baseline": -5,
                "2020-05-15-grocery_and_pharmacy_percent_change_from_baseline": 12,
                "2020-05-15-parks_percent_change_from_baseline": "",
                "2020-05-15-transit_stations_percent_change_from_baseline": "",
                "2020-05-15-workplaces_percent_change_from_baseline": -30,
                "2020-05-15-residential_percent_change_from_baseline": 11,
                "2020-05-16-retail_and_recreation_percent_change_from_baseline": -3,
                "2020-05-16-grocery_and_pharmacy_percent_change_from_baseline": 13,
                "2020-05-16-parks_percent_change_from_baseline": "",
                "2020-05-16-transit_stations_percent_change_from_baseline": "",
                "2020-05-16-workplaces_percent_change_from_baseline": -13,
                "2020-05-16-residential_percent_change_from_baseline": ""
                }
}
```

# Credits
The US County boundary data is under a Public Domain license from [Opendatasoft](https://public.opendatasoft.com/explore/dataset/us-county-boundaries/information/)

The COVID-19 Community Mobility Report is from Google, who [include their preferred attribution](https://www.google.com/covid19/mobility/data_documentation.html?hl=en).
