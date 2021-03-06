<?php

/**
 * This is the model class for table "analytics".
 *
 * The followings are the available columns in table 'analytics':
 * @property string $project_id
 * @property string $json
 */
class eAnalytics extends Analytics {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Analytics the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'analytics';
    }

    public static function pullData($startDate, $endDate = null) {
        $endDate = is_null($endDate) ? $startDate : $endDate;
        require_once 'core/protected/components/GoogleAnalytics.php';
        $gaUserId = Yii::app()->params['analytics']['username'];
        $gaKeyfile = Yii::app()->params['analytics']['keyfile'];
        $project_id = Yii::app()->params['analytics']['projectId'];
        $ga = new gapi($gaUserId, $gaKeyfile);
        if (!$project_id)
            return false;
        if (strtotime($startDate) > time()) {//dont waste calls checking for data that wont be there
            return false;
        }
        return $ga->requestReportData($project_id, array('visitorType', 'day'), array('visits', 'pageviews', 'timeOnSite', 'visitors', 'bounces'), 'day', null, $startDate, $endDate);
    }

    public static function pullByHour($startDate, $endDate = null) {
        require_once 'core/protected/components/GoogleAnalytics.php';
        $gaUserId = Yii::app()->params['analytics']['username'];
        $gaKeyfile = Yii::app()->params['analytics']['keyfile'];
        $project_id = Yii::app()->params['analytics']['projectId'];
        $ga = new gapi($gaUserId, $gaKeyfile);
        if (!$project_id)
            return false;
        if (strtotime($startDate) > time()) {//dont waste calls checking for data that wont be there
            return false;
        }
        return $ga->requestReportData($project_id, array('dateHour'), array('visits'), 'dateHour', null, $startDate, $endDate, null, 1000);
    }

    public static function pullGAdata($startDate, $end_date) {
        require_once 'core/protected/components/GoogleAnalytics.php';
        $gaUserId = Yii::app()->params['analytics']['username'];
        $gaKeyfile = Yii::app()->params['analytics']['keyfile'];
        $project_id = Yii::app()->params['analytics']['projectId'];
        if (!$project_id || strtotime($startDate) > time())
            return false;
        $ga = new gapi($gaUserId, $gaKeyfile);

        $results = new stdClass();
        $results->startDate = $startDate;
        $results->endDate = $end_date;
        // *Example of how requestReportData method is overloaded* //
        // requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $startDate=null, $end_date=null, $start_index=1, $max_results=30)
        // *Retrieve and store data *//
        // *Total visits and Pageviews //
        $results->browsers = array();
        $results->os = array();
        $days = array();
        $ga->requestReportData($project_id, array('browser'), array('visits'), null, null, $startDate, $end_date, null, 500);

        $results->browsersTotal = 0;
        foreach ($ga->getResults() as $result) { // initialization required in case some browsers are different but have the same name was causeing % to not add up to %100
            $results->browsers[$result->getBrowser()] = 0;
        }
        foreach ($ga->getResults() as $result) {
            $results->browsers[$result->getBrowser()] += $result->getVisits();
            $results->browsersTotal += $result->getVisits();
        }
        $ga->requestReportData($project_id, array('operatingSystem'), array('visits'), null, null, $startDate, $end_date, null, 500);
        $results->osTotal = 0;
        foreach ($ga->getResults() as $result) { // initialization required in case some browsers are different but have the same name was causeing % to not add up to %100
            $results->os[$result->getOperatingSystem()] = 0;
        }
        foreach ($ga->getResults() as $result) {
            $results->os[$result->getOperatingSystem()] += $result->getVisits();
            $results->osTotal += $result->getVisits();
        }
        arsort($results->browsers);
        arsort($results->os);


        $popularDays = eAnalytics::pullByHour(date('Y-m-d', strtotime('-7 days')), date('Y-m-d', strtotime('-1 days')));
        foreach ($popularDays as $popularDay) {
            $dateTime = $popularDay->getDimensions();
            $days[substr($dateTime['dateHour'], 0, 8)][substr($dateTime['dateHour'], 8, 2)] = $popularDay->getMetrics();
        }
        if ($days) {
            foreach ($days as $date => $day) {
                $max = 0;
                $timestamp = 0;
                foreach ($day as $time => $hour) {
                    if ($hour['visits'] > $max) {
                        $max = $hour['visits'];
                        $timestamp = strtotime($date . ' + ' . $time . ' hour');
                    }
                }
                $popularHour[$timestamp] = $max;
            }
            $results->popularHour = $popularHour;
        }

        $ga->requestReportData($project_id, null, array('visits', 'pageviews', 'timeOnSite', 'visitors', 'bounces'), null, null, $startDate, $end_date);
        $results->visits = $ga->getVisits();
        $results->pageviews = $ga->getPageviews();
        $results->timeOnSite = $ga->getTimeOnSite();

        // *Unique visitors* //
        $results->uniqueVisitors = $ga->getVisitors();
        $results->bounces = $ga->getBounces();

        // *Avg. visit duration* //
        $results->avgTimeOnSite = $results->visits ? gmdate("H:i:s", round($results->timeOnSite / $results->visits)) : 0;

        // *% of new visits* //
        $ga->requestReportData($project_id, array('visitorType'), array('visits'), null, null, $startDate, $end_date);
        if ($allVisitors = $ga->getResults()) {
            $returningVisitors = $allVisitors[0]->getMetrics();
            $results->returningVisitors = $returningVisitors['visits'];
            $newVisitors = $allVisitors[1]->getMetrics();
            $results->newVisitors = $newVisitors['visits'];
            $percentOfNewVisits = $results->newVisitors / ($results->returningVisitors + $results->newVisitors);
            // *format results*//
            $results->percentNew = round(($percentOfNewVisits * 100), 2);
        }
        $results->pagesPerVisit = $results->visits ? round(($results->pageviews / $results->visits), 2) : 0;
        $results->bounceRate = $results->visits ? round((($results->bounces / $results->visits) * 100), 2) : 0;
        return $results;
    }

}