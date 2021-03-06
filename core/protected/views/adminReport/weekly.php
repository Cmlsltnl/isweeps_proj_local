<?php
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/adminReport/_weekly.css');
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/adminReport/_weekly.js', CClientScript::POS_HEAD);
?>
<!-- BEGIN PAGE -->
<div class="fab-page-content">
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <div id="fab-top">
        <h2 class="fab-title"><img class="floatLeft marginRight10" src="<?php echo Yii::app()->request->baseUrl; ?>/core/webassets/images/reports-image.png">Weekly Reports</h2>
    </div>
     <?php if (isset(Yii::app()->params['reporting']['enableWeeklyReportOnDashBoard'])  && Yii::app()->params['reporting']['enableWeeklyReportOnDashBoard'] == true): ?>
    <div style="margin: -20px 0px 30px 20px;">
        <a href="/admin/dashboard">Dashboard</a> |
        <a href="/adminReport/weeklyReport">Daily Reports</a>
    </div>
    <?php endif; ?>
    <?php $this->renderPartial('/admin/_flashMessages', array()); ?>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="fab-container-fluid">
        <div class="fab-row-fluid">
            <div>
                <h2 style="margin-top:-10px;">Choose week start date <input id="weekSelect"></input> <button id="reportButton">Get Report</button></h2>
            </div>
            <table class="mystyle">
                <tr>
                    <th>Week of <?php echo date('M jS Y', strtotime($startDate)).' - '.date('M jS Y', strtotime($startDate." + 7 days")); ?></th>
                    <?php
                    for($i=0; $i<7; $i++){
                        echo "<th>".date('D m/d', strtotime($startDate." + ".$i." days"))."</th>";
                    }
                    ?>
                </tr>
                <?php
                $formatDescription = "<tr><td colspan='8' style='text-align:left'>%s</td></tr>";
                $formatResult = "<tr><td class='description'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
                foreach($collection as $description => $category){
                    echo sprintf($formatDescription, $description);
                    foreach($category as $title => $result){
                        echo sprintf(
                            $formatResult,
                            $title,
                            isset($result[0]) ? $result[0] : '0',
                            isset($result[1]) ? $result[1] : '0',
                            isset($result[2]) ? $result[2] : '0',
                            isset($result[3]) ? $result[3] : '0',
                            isset($result[4]) ? $result[4] : '0',
                            isset($result[5]) ? $result[5] : '0',
                            isset($result[6]) ? $result[6] : '0'
                        );
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->
