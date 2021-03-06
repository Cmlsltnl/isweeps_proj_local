<?php $form = $this->beginWidget('CActiveForm', array(
          'id' => 'campaign-form-post',
          'htmlOptions' => array('enctype' => 'multipart/form-data'),
      ));
?>
 
 <div class='row'>
    <div class='campaign_form_element'>
    	<?php echo $form->labelEx($post, 'post_content', array('class'=>'label')); ?>
    	<br/>
        <?php echo $form->textArea($post, 'post_content', array('placeholder'=>'Limit to 140 characters', 'class'=>'input-xxlarge', 'rows'=>5));   ?>
        <div><span id='count'>140</span> character left</div>
        <?php echo $form->error($post, 'post_content'); ?>
    </div>
</div>

<div class='row'>
    <div class='campaign_form_element'>
    	<?php echo $form->labelEx($post, 'hash_tag', array('class'=>'label')); ?>
    	<br/>
        <?php echo $form->textField($post, 'hash_tag', array('placeholder'=>'#hash tag'));   ?>
        <?php echo $form->error($post, 'hash_tag'); ?>
    </div>
</div>

<div class='row'>
    <div class='campaign_form_element'>
    	<?php echo $form->labelEx($post, 'post_time', array('class'=>'label')); ?>
    	<br/>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'attribute'=>'date',
        'model'=> $post,
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>"yy-mm-dd",
    		'defaultDate'=> '+1',
            'onSelect'=>'js:function(dateText, inst) {
                  curDate = $(this).datepicker("getDate");
				  dayName = $.datepicker.formatDate("DD", curDate);
				  $("#start_day").text(dayName);
               }'
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;width:80px'
        ),
    ));?>
    <?php echo $form->dropDownList($post, 'hour', Utility::getHourArray(), array('style'=>'width:60px;'));  ?>
    <span class='label'>:</span>
    <?php echo $form->dropDownList($post, 'minute', Utility::getMinuteArray(), array('style'=>'width:60px;'));  ?>
    
    <?php echo $form->dropDownList($post, 'am', array('AM'=>'AM','PM'=>'PM'), array('style'=>'width:60px;'));  ?>
        <?php echo $form->error($post, 'post_time'); ?>
    </div>
</div>
 
 <div class='row'>
    <div class='campaign_form_element'>
    	<?php echo $form->labelEx($post, 'media_type', array('class'=>'label')); ?>
    	<br/>
        <?php echo $form->dropDownList($post, 'media_type', array('video'=>'Video', 'image'=>'Image'));   ?>
        <?php echo $form->error($post, 'media_type'); ?>
    </div>
</div>
<?php echo $form->hiddenField($post, 'media_id'); ?>
 
<div class="row">
	<div class='campaign_form_element'>
    	<label>Media</label>
    	<?php if($post->media_type == 'video' && isset($post->video) && $post->video->thumbnail):?>
    		<img width='50' height='35' src='/<?php echo  basename(Yii::app()->params['paths']['video'])."/" .$post->video->thumbnail . ".gif"; ?>'/>
    	<?php endif;?>
    	<?php if($post->media_type == 'image' &&  isset($post->image) && $post->image->filename):?>
    		<img width='50' height='35' src='/<?php echo  basename(Yii::app()->params['paths']['image'])."/" .$post->image->filename; ?>'/>
    	<?php endif;?>
    	<input type='text' id='video_name' value='<?php echo isset($post->video)? $post->video->title :( isset($post->image) ? $post->image->title : ''); ?>'style='margin-top:10px;'/>
    	<div class="btn-group video_dropdown">
        	<a class="fab-btn dropdown-toggle" data-toggle="dropdown" href="#" id='dropdown1'>
        		Choose Video<span class="caret"></span>
        	<!-- <i class="icon-angle-down"></i> -->
        	</a>
        	<ul class="fab-dropdown-menu" id='video_dropdown' aria-labelledby="dropdown1">
        	<?php foreach (eVideo::model()->getRecentVideos() as $video) :?>
        		<li>
        			<a rel="<?php echo $video->id;?>" href=""><span><img width='100' height='55' src='/<?php echo  basename(Yii::app()->params['paths']['video'])."/" .$video->thumbnail . ".gif"; ?>'/></span> 
        			<span class='title'><?php echo $video->title;?></span></a>
        		</li>
        	<?php endforeach;?>
        	</ul>
    	</div>
    	<div class='btn-group image_dropdown'>
        	<a class="fab-btn dropdown-toggle" data-toggle="dropdown" href="#" id='dropdown2'>
        		Choose Image<span class="caret"></span>
        	<!-- <i class="icon-angle-down"></i> -->
        	</a>
        	<ul class="fab-dropdown-menu" id='image_dropdown' aria-labelledby="dropdown2">
        	<?php foreach (eImage::model()->getRecentImages() as $image) :?>
        		<li>
        			<a rel="<?php echo $image->id;?>" href=""><span><img width='100' height='55' src='/<?php echo  basename(Yii::app()->params['paths']['image'])."/" .$image->filename; ?>'/></span> 
        			<span class='title'><?php echo $image->title;?></span></a>
        		</li>
        	<?php endforeach;?>
        	</ul>
    	</div>
	</div>
</div>
  
<div class="row">
    <?php echo CHtml::submitButton('Continue', array('class'=>'btn btn-primary')); ?>
</div>
<?php $this->endWidget(); ?> 

 
<script type='text/javascript'>
	$(function(){
		$('#eCampaignPost_post_content').on('keyup', function(){
			var length = $(this).val().length ;
			$('#count').html(140-length);
		});
		var type = $('#eCampaignPost_media_type');
		if($(type).val()=='video') {
			$('.video_dropdown').show();
			$('.image_dropdown').hide();
		} else {
			$('.video_dropdown').hide();
			$('.image_dropdown').show();
		}
		 
		$('#eCampaignPost_media_type').on('change', function(){
			if($(this).val()=='video') {
				$('.video_dropdown').show();
				$('.image_dropdown').hide();
			} else {
				$('.video_dropdown').hide();
				$('.image_dropdown').show();
			}
		});
		$('ul a').click(function(e){
			e.preventDefault();
			$('#video_name').val($(this).find('.title').html());
			$('#video_name').prev('img').attr('src', $(this).find('img').attr('src'));
			$('#eCampaignPost_media_id').val($(this).attr('rel'));
		});
		 
	})
</script>