<?php

class FormFilterVideo extends CFormModel {

    public $question;
    public $status;
    public $dateStart;
    public $dateStop;
    public $viewsDateStart;
    public $viewsDateStop;
    public $minViews;
    public $user_id;
    public $hero;
    public $tags;
    public $perPage;
    public $source;
    public $type;

    public function rules() {

        return array(
            array('question, status, perPage, source', 'required'),
            array('question, user_id, hero, minViews', 'numerical', 'integerOnly' => true),
            //array('name', 'required'),
            //array('dateStart', 'allowEmpty' => true),
            //array('perPage', 'allowEmpty' => false),
            //array('user', 'allowEmpty' => true),
            //array('admin', 'allowEmpty' => true),
            array('dateStart', 'checkStartDate'),
            array('dateStop', 'checkStopDate'),
            array('viewsDateStart', 'checkViewsStartDate'),
            array('viewsDateStop', 'checkViewsStopDate'),
            array('type,tags', 'safe')
        );

        return $rules;
    }

    public function checkStartDate($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strtotime($this->dateStart) > strtotime($this->dateStop))
                //$this->addError($attribute, '"From" date should be lest than than or equal to To date.');
                Yii::app()->user->setFlash('error', '"From" date should be lest than than or equal to To date.');
        }
    }

    public function checkStopDate($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strtotime($this->dateStop) < strtotime($this->dateStart))
                //$this->addError($attribute, '"To" date should be greater than or equal to From date.');
                Yii::app()->user->setFlash('error', '"To" date should be greater than or equal to From date.');
        }
    }
    
    public function checkViewsStartDate($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strtotime($this->viewsDateStart) > strtotime($this->viewsDateStop))
                //$this->addError($attribute, '"From" date should be lest than than or equal to To date.');
                Yii::app()->user->setFlash('error', '"From" date should be lest than than or equal to To date.');
        }
    }

    public function checkViewsStopDate($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strtotime($this->viewsDateStop) < strtotime($this->viewsDateStart))
                //$this->addError($attribute, '"To" date should be greater than or equal to From date.');
                Yii::app()->user->setFlash('error', '"To" date should be greater than or equal to From date.');
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'type' => 'Type',
            'question' => 'Question',
            'hero' => 'Hero',
            'status' => 'Status',
            'dateStart' => 'From',
            'dateStop' => 'To',
            'viewsDateStart' => 'Views From',
            'viewsDateStop' => 'Views To',
            'minViews' => 'Min Views',
            'user_id' => 'User ID',
            'tags' => 'Tags',
            'perPage' => 'Items per page',
            'source' => 'Source'
        );
    }

}