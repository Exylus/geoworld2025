<?php
function getWikiInfos($country)
{
    // Wikipedia API URL to fetch infobox data
    $url = "https://en.wikipedia.org/w/api.php?action=query&titles=" . urlencode($country) .
        "&prop=revisions&rvprop=content&rvsection=0&format=json&formatversion=2";

    // Fetch API response
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Ensure data exists
    if (isset($data["query"]["pages"][0]["revisions"][0]["content"])) {
        $text = $data["query"]["pages"][0]["revisions"][0]["content"];

        // Extract information using regex patterns
        $info = [
            "Capital" => extractInfo($text, '/capital(?:_city)?\s*=\s*\[\[([^\]]+)\]\]/i'),
            "Population" => extractInfo($text, '/population_estimate\s*=\s*([\d,]+)/i'),
            "GDP" => extractInfo($text, '/GDP_nominal\s*=\s*([\d,]+.*?\(.*?\))/i'),
            "Area" => extractInfo($text, '/area_km2\s*=\s*([\d,]+)/i'),
            "Head of State" => extractInfo($text, '/leader_title\s*=\s*.*?\n.*?leader_name\s*=\s*\[\[([^\]]+)\]\]/i'),
            "Life Expectancy" => extractInfo($text, '/life_expectancy\s*=\s*([\d\.]+)/i')
        ];

        return $info;
    }

    return "Country information not found.";
}

// Helper function to extract specific information using regex
function extractInfo($text, $pattern)
{
    if (preg_match($pattern, $text, $matches)) {
        return trim($matches[1]);
    }
    return "";
}

// Example usage
// $country = "France"; // Change this to any country
// $info = getCountryInfo($country);

// echo "General Information about $country:\n";
// foreach ($info as $key => $value) {
//     echo "$key: $value\n";
// }
?>