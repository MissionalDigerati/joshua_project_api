<?php
/**
 * Get the daily unreached people group for today.  You can specify a specific date using the following parameters
 * as GET vars.
 * month - two digit month
 * day - two digit day
 * 
 * For example, /people_groups/daily_unreached.json?month=01&day=31 will get the people group for Jan. 31st.
 *
 * GET /people_groups/daily_unreached
 * 
 * @api
 * @author Johnathan Pulos
 */
$app->get(
    "/people_groups/daily_unreached.:format",
    function ($format) use ($app, $db, $appRequest) {
        /**
         * Get the given parameters, and clean them
         *
         * @author Johnathan Pulos
         */
        $month = $appRequest->params('month');
        $day = $appRequest->params('day');
        if ($month) {
            $month = intval(strip_tags($month));
        } else {
            $month = Date('n');
        }
        if ((1 > $month) || ($month > 12)) {
            /**
             * They did not supply a valid month
             *
             * @author Johnathan Pulos
             */
            $app->render("/errors/400.xml.php");
            exit;
        }
        if ($day) {
            $day = intval(strip_tags($day));
        } else {
            $day = Date('j');
        }
        if ((1 > $day) || ($day > 31)) {
            /**
             * They did not supply a valid day
             *
             * @author Johnathan Pulos
             */
            $app->render("/errors/400.xml.php");
            exit;
        }
        $query = "SELECT * FROM jppeoples WHERE LRofTheDayMonth = :month AND LRofTheDayDay = :day LIMIT 1";
        $statement = $db->prepare($query);
        $statement->execute(array('month' => $month, 'day' => $day));
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($data)) {
            $app->render("/errors/404.xml.php");
            exit;
        }
        /**
         * Rename the 10_40Window to a XML friendly tagname
         *
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $val) {
            $data[$key]['Window10_40'] = $val['10_40Window'];
            unset($data[$key]['10_40Window']);
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            echo json_encode($data);
        } else {
            echo arrayToXML($data, "people_groups", "people_group");
        }
    }
);
