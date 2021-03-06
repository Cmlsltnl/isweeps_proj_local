<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/adminVideo/videoSchedulerModal.js', CClientScript::POS_END);

$cs->registerCssFile('/core/webassets/css/jquery.dataTables.css');
$cs->registerCssFile('/core/webassets/css/adminVideo/videoSchedulerModal.css');
?>

<div class="fab-row-fluid" style="color: #fff; margin-top: 10px;">
    <span style="margin-top: 55px; width:200px !important; font-size: 25px;">Network Scheduler</span>
    <span style="float: right; width: 450px !important;">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'video-scheduler-filter-form',
            'enableAjaxValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => false,
            )
        ));
        ?>

        <div class="fab-clear" style="height:6px;"></div>

        <div class="fab-box fab-right" style="margin-left: 0px;">
            <button id="videoSchedulerRefresh">Refresh</button>
        </div>
        <div class="fab-box fab-right" style="margin-left:0px">&nbsp;&nbsp;</div>
        <!--
        <div class="fab-box fab-right" style="margin-left:0px">
            <label class="fab-left">&nbsp;&nbsp;&nbsp;Date: </label>
        <?php echo $form->textField($filterVideoSchedulerModel, 'date', array('id' => 'datepickerVideoSchedulerFilter', 'style' => 'width: 70px;', 'class' => 'fab-small-input fab-left datepicker')); ?>
        </div>
        -->
        <div class="fab-box fab-right" style="margin-left:0px">
            <label class="fab-left">Show:</label>
            <?php echo $form->dropDownList($filterVideoSchedulerModel, 'show', $networkShows, array('id' => 'showPickerVideoSchedulerFilter', 'style' => 'width: auto;', 'class' => 'fab-select-accept')); ?>

        </div>

        <?php $this->endWidget(); ?>

    </span>
    <hr/>
</div>

<div class="fab-row-fluid" style="color: #fff;">
    <div id="videoSchedulerModalTabs">
        <ul>
            <li><a href="#tab-fs-scheduler"><strong>Spot Scheduler</strong></a></li>
            <li><a href="#tab-schedule-history" id="tabHistoryTrigger"><strong>History</strong></a></li>
        </ul>

        <div id="tab-fs-scheduler">
            <table id="datatableScheduler">
                <thead>
                    <tr>
                        <th style="text-align: left;">Color</th>
                        <th style="text-align: left;">Show</th>
                        <th style="text-align: left;">Run Date</th>
                        <th style="text-align: left;">Run Time</th>
                        <th style="text-align: left;">Spots</th>
                        <th style="text-align: left;">Time Left</th>
                        <th style="text-align: left;">Show On</th>
                        <th style="text-align: left;">Network Show Id</th>
                        <th style="text-align: left;">Spot Type</th>
                        <th style="text-align: left;">Network Show Schedule Id</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <hr/>
            <div id="selectionContainer">
                <div id="selectedShowInformation">
                    <strong><span id="selectedShowName">No show selected</span></strong>
                </div>
                <div id="selectedVideoInformation">
                    <?php if (!is_null($video)): ?>
                        <strong><?php echo $video->title; ?></strong>
                        <img id="selectedVideoThumbnail" alt="video-image" src="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo basename(Yii::app()->params['paths']['video']); ?>/<?php echo $video->thumbnail; ?><?php echo Yii::app()->params['video']['imageExt']; ?>">
                        <span id="selectedVideoId"><?php echo $video->id; ?></span>
                    <?php else: ?>
                        <strong>No video selected</strong>
                        <span id="selectedVideoId"></span>
                    <?php endif; ?>
                </div>
            </div>
            <table id="datatableSpotScheduler" style="display: none;">
                <thead>
                    <tr>
                        <th style="text-align: left;">#</th>
                        <th style="text-align: left;">?</th>
                        <th style="text-align: left;">File</th>
                        <th style="text-align: left;">Producer</th>
                        <th style="text-align: left;">Run Time</th>
                        <th style="text-align: left;">Length</th>
                        <th style="text-align: left;">Time Left</th>
                        <th style="text-align: left;">House #</th>
                        <th style="text-align: left;">Save Time</th>
                        <th style="text-align: left;">Network Show Schedule Id</th>
                        <th style="text-align: left;">Video Id</th>
                        <th style="text-align: left;">Video Thumbnail</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


        <div id="tab-schedule-history">

        </div>


    </div>
</div>