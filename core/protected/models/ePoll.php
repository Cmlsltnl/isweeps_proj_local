<?php

class ePoll extends Poll {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, question, start_time, end_time', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('question', 'length', 'max' => 255, 'encoding' => 'UTF-8'),
            array('type', 'length', 'max' => 8),
            array('created_on,updated_on', 'default', 'value' => date("Y-m-d H:i:s"), 'setOnEmpty' => false, 'on' => 'insert'),
            array('updated_on', 'default', 'value' => date("Y-m-d H:i:s"), 'setOnEmpty' => false, 'on' => 'update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, question, start_time, end_time, created_on, updated_on', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'eUser', 'user_id'),
            'pollAnswers' => array(self::HAS_MANY, 'ePollAnswer', 'poll_id'),
            'pollResponses' => array(self::HAS_MANY, 'ePollResponse', 'poll_id'),
            'tally' => array(self::STAT, 'ePollResponse', 'poll_id', 'select' => 'COUNT(id)', 'group' => 'poll_id'),
            'entityAnswers' => array(self::HAS_MANY, 'eEntityAnswer', 'poll_id'),
            'entityResponses' => array(self::HAS_MANY, 'eEntityResponse', array('id','entity_answer_id'),'through'=>'entityAnswers'),
            'entityTally' => array(self::STAT, 'eEntityAnswer', 'poll_id', 'select' => 'COUNT(entity_response.id)', 'join' => 'INNER JOIN entity_response ON t.id = entity_response.entity_answer_id', 'group' => 'poll_id'),
        );
    }

    public function filterByDates($startDate, $endDate) {
        return DateTimeUtility::filterByDates($this, $startDate, $endDate);
    }

    public function filterById($id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'id =:pollId',
            'params' => array(':pollId' => $id,),
        ));
        return $this;
    }

    public function afterSave() {
        $polls = ePoll::model()->current()->findAll();
        if (sizeof($polls) > 0) {
            TwitterUtility::openVoting();
        } else {
            TwitterUtility::closeVoting();
        }
        return parent::afterSave();
    }

    public function scopes() {
        return array(
            'latest' => array('order' => 't.created_on desc'),
            'current' => array('condition' => "NOW() between start_time and end_time"),
            'entityType' => array('condition' => 'type = "entity"'),
            'questionType' => array('condition' => 'type = "question"'),
        );
    }

}