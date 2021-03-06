<?php
// page specific css
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/chosen.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/jquery.tagsinput.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/bootstrap-toggle-buttons.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/DT_bootstrap.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/jquery-ui-1.10.0.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/adminVoting/index.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/spectrum.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/jquery-ui-timepicker-addon.css');
$cs->registerCssFile('/core/webassets/css/jquery.dataTables_themeroller.css');

// page specific js
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/adminVoting/index.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/spectrum.js', CClientScript::POS_END);
$cs->registerScriptFile('/core/webassets/js/jquery.dataTables.min.js', CClientScript::POS_END);
$this->renderPartial('/admin/_csrfToken');
?>


<!-- BEGIN PAGE -->
<div class="fab-page-content">

    <!-- flash messages -->
    <?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        $messageFormat = '<div class="flashes"><div class="flash-%s">%s</div></div>';
        foreach ($flashMessages as $key => $message) {
            echo sprintf($messageFormat, $key, $message);
        }
    }
    ?>
    <!-- flash messages -->

    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <div id="fab-top" style="background:#852b99;margin-bottom:0px;">
        <h2 class="fab-title" style="color:white"><img class="marginRight10 floatLeft" src="<?php echo Yii::app()->request->baseUrl; ?>/core/webassets/images/voting-image.png"/>Voting Admin</h2>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="fab-container-fluid">
        <!-- END PAGE HEADER-->
        <div class="fab-row-fluid">
            <div id="fab-voting">
                <div class="fab-tab-content">
                    <div class="pollHolder" style="clear:both;padding-top:20px;">
                        <h2>Create/Edit Poll</h2>
                        <?php
                        /* @var $this AdminController */
                        /* @var $model Poll PollAnswers */
                        /* @var $form CActiveForm */
                        ?>

                        <?php
                        $poll = $models['poll'];
                        $numberOfAnswers = 0;
                        $pollAnswers = $models['pollAnswers'];

                        foreach ($pollAnswers as $k => $v) {
                            if (!empty($v->answer)) {
                                 $numberOfAnswers++;
                            }
                        }
                        $numberOfAnswers = ($numberOfAnswers > 2) ? $numberOfAnswers : 2;
                        $cs = Yii::app()->clientScript;
                        $cs->registerScript('numberOfAnswers', "showAnswers({$numberOfAnswers})", CClientScript::POS_END);
                        ?>

                        <div class="form">
                            <div style="width:600px" class="fab-left fab-voting-left">
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'admin-voting-form',
                                    'enableAjaxValidation' => true,
                                ));
                                ?>

                                <div>
                                    <?php echo $form->labelEx($poll, 'question'); ?>
                                    <?php echo $form->textField($poll, 'question', array('maxlength' => '140', 'class' => 'counter')); ?>
                                    <?php echo $form->error($poll, 'question'); ?>
                                </div>
                                <div>
                                    <table>
                                    <tr>
                                        <td style="color:inherit;border: 0px;"><?php echo $form->labelEx($poll, 'start_time'); ?></td>
                                        <td style="color:inherit;border: 0px;"></td>
                                        <td style="color:inherit;border: 0px;"><?php echo $form->labelEx($poll, 'end_time'); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="color:inherit;border: 0px;"><?php echo $form->textField($poll, 'start_time', array('class'=>'ui-timepicker-input')); ?> <?php echo(date('T'));?></td>
                                        <td style="color:inherit;border: 0px;">-</td>
                                        <td style="color:inherit;border: 0px;"><?php echo $form->textField($poll, 'end_time', array('class'=>'ui-timepicker-input')); ?> <?php echo(date('T'));?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px;"><?php echo $form->error($poll, 'start_time'); ?></td>
                                        <td style="border: 0px;"></td>
                                        <td style="border: 0px;"><?php echo $form->error($poll, 'end_time'); ?></td>
                                    </tr>
                                    </table>
                                </div>
                                <div>
                                    <div>Number of Bars (Answers)</div>
                                    <div>
                                            <select  name="numberOfAnswers" class="fab-bars"   >
                                    <?php
                                   /* $checkboxFormat = '
                                    <div class="fab-help-inline" >
                                      <div class="fab-left" >
                                        <input type="checkbox" value="%d" %s name="numberOfAnswers" class="fab-chk" id="fab-check_box%d" />
                                        <label for="fab-check_box%d"></label>
                                      </div>
                                      <div class="fab-left fab-vote-text">%d bars</div>
                                      <div style="width:130px" class="fab-left"></div>
                                    </div>
                                ';*/

                                    $checkboxFormat = '<option value="%d" %s >%d Bars</option>';


                                    for ($i = $numberOfAnswers; $i <= 10; $i++) {
                                       // echo sprintf($checkboxFormat, $i, $checked = ($numberOfAnswers == $i) ? 'checked' : '', $i, $i, $i, $i, $checked = ($numberOfAnswers == $i) ? 'fab-blue' : ''
                                             echo sprintf($checkboxFormat, $i, $checked = ($numberOfAnswers == $i) ? ' selected ' : '', $i);
                                    }
                                    ?>
                                        </select>
                                    </div>
                                </div>


                                <?php
                                $answerFormat = '
                                <div  id="pollAnswer%d" class="pollAnswer" style="clear:both;padding-top:20px">
                                    <div>
                                        %s %s %s
                                    </div>
                                    <div>
                                        <span class="floatLeft marginRight10">
                                            %s %s %s
                                        </span>
                                        <span class="floatLeft marginRight10">
                                            %s %s %s
                                        </span>
                                        <span class="floatLeft marginRight10">
                                            %s %s %s
                                        </span>
                                    </div>
                                </div>
                                ';

                                foreach ($pollAnswers as $i => $answer) {
                                    echo sprintf($answerFormat, $i,
                                                    $form->labelEx($answer, "[$i]answer"),
                                                    $form->textField($answer, "[$i]answer", array('maxlength' => '25', 'class' => 'counter')),
                                                    $form->error($answer, "[$i]answer"), $form->labelEx($answer, "[$i]color"),
                                                    $form->textField($answer, "[$i]color", array('id' => 'colorpickerField' . $i, 'style' => 'width: 50px', 'maxlength' => 5)),
                                                    $form->error($answer, "[$i]color"), $form->labelEx($answer, "[$i]point_value"),
                                                    $form->textField($answer, "[$i]point_value", array('id' => 'fab-point' . $i, 'style' => 'width: 30px', 'maxlength' => 19, 'placeholder' => '0', 'class' => 'fab-m-wrap xsmall')),
                                                    $form->error($answer, "[$i]point_value"), $form->labelEx($answer, "[$i]hashtag"),
                                                    $form->textField($answer, "[$i]hashtag", array('id' => 'fab-hashtag' . $i, 'style' => 'width: 60px', 'maxlength' => 10, 'class' => 'fab-m-wrap small counter linkToePoll_question')),
                                                    $form->error($answer, "[$i]hashtag")
                                                );
                                }
                                ?>
                                <div style="clear:both">
                                    <?php echo CHtml::submitButton('Submit'); ?>
                                    <button type="button" onclick="window.location.href = '/adminVoting/index';">Reset</button>
                                </div>
                            </div>

                            <div style="float:left;">
                                <div class="fab-control-group">
                                    <div class="fab-controls">
                                        <label class="vote-text-big fab-control-label" style="width: 300px;">VOTE: <span id="questionPreview"><?php echo $poll->question; ?></span></label>
                                    </div>
                                </div>
                                <div class="fab-left fab-voting-right">
                                    <?php
                                    $graphFormat = '
                                    <div class="fab-control-group">
                                      <label id="answer%dPreview" class="fab-control-label fab-vote-text" style=" word-wrap: break-word;">%s</label>
                                      <div class="fab-controls">
                                        <div id="fab-chart%d" class="fab-vote-%s"></div>
                                      </div>
                                    </div>
                                ';

                                    foreach ($pollAnswers as $i => $answer) {
                                        echo sprintf($graphFormat, $i, $answer->answer, $i, $answer->color
                                        );
                                    }
                                    ?>
                                </div>
                                <div>
                                    <div>
                                        <div>Current Results:</div>
                                        <?php foreach ($pollAnswers as $i => $answer): ?>
                                            <?php if ($answer->answer != ''): ?>
                                                <div>
                                                    <span class="bold"><?php echo $answer->answer; ?></span>:
                                                    <span><?php echo $answer->tally; ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>

                    </div>
                    <div style="clear:both;padding-top:40px">
                        <h2>Previous/Current Polls</h2>
                        <table id="votingTable">
                            <thead>
                                <tr>
                                    <th style="width:10%">State</th>
                                    <th>Question</th>
                                    <th style="width:5%">Type</th>
                                    <th style="width:15%">Duration</th>
                                    <th style="width:10%">XML/RSS<?php echo !empty(Yii::app()->params['cloudGraphicAppearanceSetting']['enablePollCloudGraphicSetting'])?'/CG':''; ?></th>
                                    <th style="width:40%">Responses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rowFormat = "
                            <tr>
                                <td class='%s' style='text-align:center'>
                                    <button type='button' class='setPollState' rel='%s' rev='%s'>%s</button>
                                </td>
                                <td><a href='/adminVoting/index/%s' rel='%s'>%s</a></td>
                                <td>%s</td>
                                <td>%s</td>
                                <td>
                                    <a target='_blank' href='/XML/voting/%s'>%s</a> -
                                    <a target='_blank' href='/XML/votingRSS/%s'>%s</a> %s
                                    <a href='/admin/tvscreensetting?e_type=poll&refid=%s' rel='#tvScreenOverlay' class='tvScreenSettingOverlayLink' rev='%s'>%s</a>
                                     - <a class='linkPopUp' href='#' rel='%s'>Links</a>
                                </td>
                                <td style='background-color:transparent !important;border:0px;padding:0px !important'>
                                    <table style='width:100%%'>
                                    <tr style='background:#FFF'>
                                        <th style='width:24%%'>Answer</th>
                                        <th style='width:24%%'>Hashtag</th>
                                        <th style='width:24%%'>Percentage</th>
                                        <th style='width:24%%'>Number of Votes</th>
                                        <th style='width:4%%'>Color</th>
                                    </tr>
                                    %s
                                    </table>
                                </td>
                            </tr>
                        ";
                                $answerFormat = "
                            <tr>
                                <td>%s</td>
                                <td>%s</td>
                                <td>%s</td>
                                <td>%s</td>
                                <td style='background-color:%s !important'></td>
                            </tr>
                        ";
                                $i = 0;
                                $xml = 'xml';
                                $rss = 'rss';
                                $preview = !empty(Yii::app()->params['cloudGraphicAppearanceSetting']['enablePollCloudGraphicSetting'])?'Cloud Graphics':'';
                                $dash = !empty(Yii::app()->params['cloudGraphicAppearanceSetting']['enablePollCloudGraphicSetting'])?' - ':'';
                                foreach ($polls as $poll) {
                                    $answers = '';
                                    $votes = Array();
                                    foreach ($poll->pollAnswers as $answer) {
                                        if($poll->tally) {
                                            $answers .= sprintf($answerFormat, $answer->answer, $answer->hashtag, round($answer->tally / $poll->tally * 100) . '%', $answer->tally, $answer->color);
                                        }
                                    }
                                    $active = strtotime($poll->start_time) <= time() && time() <= strtotime($poll->end_time) ? 'active' : 'inactive';
                                    echo sprintf($rowFormat, $active, $poll->id, ($active == 'active') ? date('Y-m-d', time()) : date('Y-m-d', time() + 84600 * 365 * 10), ($active == 'active') ? 'Stop' : 'Start', $poll->id, $poll->id, (!empty($poll->question)) ? $poll->question : '*', $poll->type, date("Y-m-d h:i a",strtotime($poll->start_time))." ".date('T')." to ".date("Y-m-d h:i a",strtotime($poll->end_time))." ".date('T'), $poll->id, $xml, $poll->id, $rss, ($active == 'active') ? $dash : '',$poll->id, $poll->id, ($active == 'active') ? $preview : '', $poll->id, $answers
                                    );
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="clearFix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<?php $this->renderPartial('/adminTvScreenAppearSetting/_tvScreenAppearSettingForm', array()); ?>
<?php $this->renderPartial('/adminQuestion/_linksOverlay'); ?>
<!-- END PAGE -->