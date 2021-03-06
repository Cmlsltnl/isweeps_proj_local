<?php
class eBrightcove extends Brightcove
{
    public $tot_views;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('video_id, brightcove_id', 'required'),
            array('video_id, tot_views', 'numerical', 'integerOnly'=>true),
            array('video_id', 'unique'),
            array('brightcove_id', 'length', 'max'=>255),
            array('status', 'length', 'max'=>4),
            array('created_on,updated_on', 'default', 'value' => date("Y-m-d H:i:s"), 'setOnEmpty' => false, 'on' => 'insert'),
            array('updated_on', 'default', 'value' => date("Y-m-d H:i:s"), 'setOnEmpty' => false, 'on' => 'update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, video_id, brightcove_id, tot_views, created_on, updated_on', 'safe', 'on'=>'search'),
        );
    }

    public function afterSave(){
        ProcessUtility::startProcess('sendvideotobrightcove');
        return parent::afterSave();
    }
}