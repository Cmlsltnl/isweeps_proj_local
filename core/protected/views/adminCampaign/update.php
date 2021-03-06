<?php
// page specific css
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/core/webassets/css/adminCampaign/index.css');
 
 
Yii::app()->clientScript->registerScriptFile('/core/webassets/js/adminCampaign/index.js', CClientScript::POS_END);
 
?>

<div class="fab-page-content">

    <!-- flash messages -->
    <?php $this->renderPartial('/admin/_flashMessages', array()); ?>
    <!-- flash messages -->

     
    <div class="campaign_top_bar">
        <img src="<?php echo Yii::app()->request->baseUrl; ?>/core/webassets/images/campaign/campaign_manager_icon.png" />
        <a href='<?php echo $this->createUrl('/adminCampaign'); ?>' >Campaign Manager</a>
    </div>
     
    <div class="fab-container-fluid">
    	<div class='campaign_container'>
    		 <?php $this->renderPartial('/adminCampaign/_campaign_menu'); ?>
             
              
             
            <div class="campaign_subtitle">Edit Campaign</div>
            <div class="campaign_divider"></div>
            <?php $this->renderPartial('_campaign_form', array('campaign'=>$campaign)); ?>
        </div>
         
    </div>
    <!-- END PAGE CONTAINER-->
</div>

 
 