<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/adminVideo/videoSchedulerModalHistory.js', CClientScript::POS_LOAD);

//$cs->registerCssFile('/core/webassets/css/jquery.dataTables.css');
//$cs->registerCssFile('/core/webassets/css/adminVideo/videoSchedulerModal.css');
?>
<div style='color:#000;'>
    <div style="margin-bottom:10px">
        <?php $this->widget('CLinkPager', array('pages' => $pages, 'header' => '')); ?>
    </div>
    <table width="100%" style="border-collapse: collapse;">
        <col>
        <col>
        <col width='100%'>
        <col>
        <tr>
            <th style="padding:2px;border: 1px solid #000;">Video ID</th>
            <th style="padding:2px;border: 1px solid #000;">Image</th>
            <th style="padding:2px;border: 1px solid #000;">Title</th>
            <th style="padding:2px;border: 1px solid #000;">Created By</th>
            <th style="padding:2px;border: 1px solid #000;">Created On</th>
            <th style="padding:2px;border: 1px solid #000;">Produced By</th>
            <th style="padding:2px;border: 1px solid #000;">Produced On</th>
        </tr>
        <?php foreach ($videoDestinations as $videoDestination): ?>
            <tr>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo($videoDestination->video_id); ?></td>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo(CHtml::image('/' . basename(Yii::app()->params['paths']['video']) . '/' . $videoDestination->video->thumbnail . Yii::app()->params['video']['imageExt'], "vidoe image", array("width" => "40px"))); ?></td>
                <td style="padding:1px 3px;border: 1px solid #000;">
                    <div style="position: relative;">&nbsp;
                        <div style="position: absolute;top:0;width: 100%;overflow: hidden;white-space: nowrap;">
                            <a title="<?php echo($videoDestination->video->title); ?>" style="color:black;cursor:pointer;"><?php echo($videoDestination->video->title); ?></a>
                        </div>
                    </div>
                </td>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo($videoDestination->video->user->username); ?></td>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo(str_replace(" ", "&nbsp;", date("m/d/Y h:i:s a", strtotime($videoDestination->video->created_on)))); ?></td>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo($videoDestination->user->username); ?></td>
                <td style="padding:1px 3px;border: 1px solid #000;"><?php echo(str_replace(" ", "&nbsp;", date("m/d/Y h:i:s a", strtotime($videoDestination->created_on)))); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>