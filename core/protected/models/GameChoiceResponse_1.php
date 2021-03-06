<?php

/**
 * This is the model class for table "game_choice_response".
 *
 * The followings are the available columns in table 'game_choice_response':
 * @property integer $id
 * @property integer $game_choice_id
 * @property integer $game_choice_answer_id
 * @property integer $sms_id
 * @property integer $user_id
 * @property integer $transaction_id
 * @property integer $is_winner
 * @property string $source
 * @property string $ip_address
 * @property string $ip_derivedcity
 * @property string $created_on
 * @property string $updated_on
 */
class GameChoiceResponse_1 extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'game_choice_response';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('game_choice_id, game_choice_answer_id, user_id, , game_unique_id, source, created_on, updated_on', 'required'),
			array('game_choice_id, game_choice_answer_id, sms_id, user_id, transaction_id, is_winner', 'numerical', 'integerOnly'=>true),
			array('source', 'length', 'max'=>256),
                        array('game_unique_id', 'max' => 50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, game_choice_id, game_choice_answer_id, sms_id, user_id, transaction_id, is_winner, game_unique_id, source, ip_address, ip_derivedcity,created_on, updated_on', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'game_choice_id' => 'Game Choice',
			'game_choice_answer_id' => 'Game Choice Answer',
			'sms_id' => 'Sms',
			'user_id' => 'User',
                        'transaction_id' => 'Transaction',
			'is_winner' => 'Is Winner',
			'source' => 'Source',
                        'ip_address' => 'IP Address',
                        'ip_derivedcity' => 'IP Based City',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('game_choice_id',$this->game_choice_id);
		$criteria->compare('game_choice_answer_id',$this->game_choice_answer_id);
		$criteria->compare('sms_id',$this->sms_id);
		$criteria->compare('user_id',$this->user_id);
                $criteria->compare('transaction_id',$this->transaction_id);
		$criteria->compare('is_winner',$this->is_winner);
                $criteria->compare('game_unique_id',$this->game_unique_id);
		$criteria->compare('source',$this->source,true);
                $criteria->compare('ip_address',$this->ip_address?getenv('REMOTE_ADDR'):null);
                $criteria->compare('ip_derivedcity',$this->ip_derivedcity,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GameChoiceResponse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
