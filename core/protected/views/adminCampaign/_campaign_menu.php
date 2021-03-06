<div class="fab-container-fluid campaign_menu">
	<div class='fab-row-fluid'>
		<div class="btn-group">
			<a class="fab-btn" href="<?php echo  $this->createUrl('adminCampaign/'); ?>">Home</a>
		</div>
	<!-- 		 
		<div class="btn-group">
			<a class="fab-btn dropdown-toggle" data-toggle="dropdown" href="#">
			Campaign <span class="caret"></span>
			 
			</a>
			<ul class="fab-dropdown-menu">
			 
				<li><a href="#">New Campaign</a></li>
			 
			 
				<li><a href="#" data-toggle="modal" data-target="#stop_campaign" >Stop Campaign</a></li>
				<li><a href="#">View Campaign</a></li>
				<li><a href="#">Edit Campaign</a></li>
			 
			</ul>
		</div>
	-->
		<div class="btn-group">
			<a class="fab-btn fab-green" href="<?php echo  $this->createUrl('/adminCampaign/create'); ?>">New Campaign</a>
		</div>
        <div class="btn-group">
			<a class="fab-btn dropdown-toggle" data-toggle="dropdown" href="#">
			Edit <span class="caret"></span>
			<!-- <i class="icon-angle-down"></i> -->
			</a>
			<ul class="fab-dropdown-menu">
				<li><a href="<?php echo $this->createUrl('/adminCampaign/editAccount');?>">Edit Accounts</a></li>
	<!-- 		<li><a href="#">Edit Show Details</a></li>
				<li><a href="#">Edit Package</a></li>
	-->
			</ul>
		</div>
	<!-- 
		<div class="btn-group">
			<a class="fab-btn fab-green" href="<?php echo  $this->createUrl('adminCampaign/viewPost'); ?>">View Post </a>
		</div>
	-->
	</div>
</div>


<div id="stop_campaign" class="modal hide fade upgrade_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header upgrade_modal_header red"> 
    <div id="myModalLabel" class='text-center'>Stop Campaign</div>
  </div>
  <div class="modal-body text-center">
    <p>Are you sure you want to stop the current campaign? </p>
    <p>(All your scheduled posts will be stopped)</p>
  </div>
  <div class="text-center row">
  <?php /*echo CHtml::ajaxButton('Yes', $this->createUrl('adminCampaign/stopCampaign'),
                  array('type'=>'post', 'dataType'=>'json',  
                      'success'=>'js:function(data){ window.location.reload();}'
                  ),
                  array('class'=>'fab-btn btn-default red white_font modal_button',
                      'data-loading-text'=>'Wait, loading',
                  )
       );*/
  ?>
	<button type="button" class="fab-btn red white_font modal_button" onclick="$.ajax({url:'<?php echo Yii::app()->createUrl('adminCampaign/stopCampaign'); ?>'}).done(function(){window.location.reload();})">
    		Yes
    </button>
  	<button type="button" class="fab-btn btn-default modal_button " data-dismiss="modal" aria-hidden="true">No</button>
    
  </div>
</div>