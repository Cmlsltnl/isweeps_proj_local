<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseurl . '/core/webassets/js/adminVideo/videoModalThumbnails.js', CClientScript::POS_END);
?>

<?php if ($video == null): ?>
    Unable to generate thumbnails for video
<?php else: ?>
    <?php foreach($thumbnails as $thumb) :?>
    <a title="<?php echo str_replace(Yii::app()->params['video']['imageExt'], "", $thumb);?>" href="#" class="videoThumbnailTrigger"><img alt="<?php echo $thumb;?>" src="<?php echo PATH_USER_VIDEOS;?>/<?php echo $thumb;?>" width="90" style="margin-bottom: 10px; margin-right: 5px; border: 1px solid #959595;" /></a>
    <?php endforeach; ?>
<?php endif; ?>
