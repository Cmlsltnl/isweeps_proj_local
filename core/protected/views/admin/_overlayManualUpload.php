<div style="display: none;" id="fileUploadOverlayManual">
    <div id="fileUploadOverlayContent">
        <h2 style="font-size: 18px;">Upload user manual:</h2>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'manual-upload-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'action' => '/admin/uploadmanual',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => false,
            )
                ));
        ?>

        <label class="fab-left">Manual file (required):</label>
        <div class="fab-clear" style="height:6px;"></div>
        <div><?php echo $form->fileField($formUploadManualModel, 'uploadfile'); ?></div>
        <div><?php echo $form->error($formUploadManualModel, 'uploadfile'); ?></div>
        <br/><br/>


        <?php echo CHtml::submitButton('Submit'); ?>

        <?php $this->endWidget(); ?>
    </div>
</div>