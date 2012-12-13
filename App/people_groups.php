<?php
/**
 * Get the daily people group
 *
 * GET /people_groups/daily
 * @author Johnathan Pulos
 */
$app->get("/people_groups/daily_unreached.:format", function($format) use($app, $db) {
    $query = "SELECT * FROM jppeoples WHERE LRofTheDayMonth = '" . Date('n') . "' AND LRofTheDayDay = '" . Date('j') . "' LIMIT 1";
    $results = $db->query($query);
    $data = resultsToDataArray($results);
    /**
     * Render the final data
     *
     * @author Johnathan Pulos
     */
    if($format == 'json') {
        echo json_encode($data);
    }else {
        echo arrayToXML($data, "people_groups", "people_group");
    }
});