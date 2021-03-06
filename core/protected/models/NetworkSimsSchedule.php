<?php

/**
 * This is the model class for table "network_sims_schedule".
 *
 * The followings are the available columns in table 'network_sims_schedule':
 * @property string $id
 * @property integer $block3
 * @property integer $block4
 * @property string $block5
 * @property string $block6
 * @property string $block7
 * @property string $block8
 * @property string $block9
 * @property string $block10
 * @property string $block11
 * @property integer $block12
 * @property integer $block13
 * @property integer $block14
 * @property string $block15
 * @property string $block16
 * @property string $block17
 * @property string $block18
 * @property string $block19
 * @property string $block20
 * @property string $block21
 * @property string $block22
 * @property string $block23
 * @property string $block24
 * @property string $block25
 * @property string $block26
 * @property string $block27
 * @property string $block28
 * @property string $block29
 * @property string $block30
 * @property string $block31
 * @property string $block32
 * @property string $block33
 * @property string $block34
 * @property string $block35
 * @property string $block36
 * @property string $block37
 * @property string $block38
 * @property string $block39
 * @property string $block40
 * @property string $block41
 * @property string $block42
 * @property string $block43
 * @property string $block44
 * @property string $block45
 * @property string $block46
 * @property string $block47
 * @property string $block48
 * @property string $block49
 * @property string $block50
 * @property string $block51
 * @property string $block52
 * @property string $block53
 * @property string $block54
 * @property string $block55
 * @property string $block56
 * @property string $block57
 * @property string $block58
 * @property string $block59
 * @property string $block60
 * @property string $block61
 */
class NetworkSimsSchedule extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'network_sims_schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('block3, block4, block12, block13, block14', 'numerical', 'integerOnly'=>true),
			array('block5, block17, block18', 'length', 'max'=>5),
			array('block6, block7, block16, block28, block29, block30, block31, block32, block34, block35, block36, block37, block38, block48, block49', 'length', 'max'=>255),
			array('block8', 'length', 'max'=>15),
			array('block11', 'length', 'max'=>12),
			array('block9, block10, block15, block19, block20, block21, block22, block23, block24, block25, block26, block27, block33, block39, block40, block41, block42, block43, block44, block45, block46, block47, block50, block51, block52, block53, block54, block55, block56, block57, block58, block59, block60, block61', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, block3, block4, block5, block6, block7, block8, block9, block10, block11, block12, block13, block14, block15, block16, block17, block18, block19, block20, block21, block22, block23, block24, block25, block26, block27, block28, block29, block30, block31, block32, block33, block34, block35, block36, block37, block38, block39, block40, block41, block42, block43, block44, block45, block46, block47, block48, block49, block50, block51, block52, block53, block54, block55, block56, block57, block58, block59, block60, block61', 'safe', 'on'=>'search'),
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
			'block3' => 'Block3',
			'block4' => 'Block4',
			'block5' => 'Block5',
			'block6' => 'Block6',
			'block7' => 'Block7',
			'block8' => 'Block8',
			'block9' => 'Block9',
			'block10' => 'Block10',
			'block11' => 'Block11',
			'block12' => 'Block12',
			'block13' => 'Block13',
			'block14' => 'Block14',
			'block15' => 'Block15',
			'block16' => 'Block16',
			'block17' => 'Block17',
			'block18' => 'Block18',
			'block19' => 'Block19',
			'block20' => 'Block20',
			'block21' => 'Block21',
			'block22' => 'Block22',
			'block23' => 'Block23',
			'block24' => 'Block24',
			'block25' => 'Block25',
			'block26' => 'Block26',
			'block27' => 'Block27',
			'block28' => 'Block28',
			'block29' => 'Block29',
			'block30' => 'Block30',
			'block31' => 'Block31',
			'block32' => 'Block32',
			'block33' => 'Block33',
			'block34' => 'Block34',
			'block35' => 'Block35',
			'block36' => 'Block36',
			'block37' => 'Block37',
			'block38' => 'Block38',
			'block39' => 'Block39',
			'block40' => 'Block40',
			'block41' => 'Block41',
			'block42' => 'Block42',
			'block43' => 'Block43',
			'block44' => 'Block44',
			'block45' => 'Block45',
			'block46' => 'Block46',
			'block47' => 'Block47',
			'block48' => 'Block48',
			'block49' => 'Block49',
			'block50' => 'Block50',
			'block51' => 'Block51',
			'block52' => 'Block52',
			'block53' => 'Block53',
			'block54' => 'Block54',
			'block55' => 'Block55',
			'block56' => 'Block56',
			'block57' => 'Block57',
			'block58' => 'Block58',
			'block59' => 'Block59',
			'block60' => 'Block60',
			'block61' => 'Block61',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('block3',$this->block3);
		$criteria->compare('block4',$this->block4);
		$criteria->compare('block5',$this->block5,true);
		$criteria->compare('block6',$this->block6,true);
		$criteria->compare('block7',$this->block7,true);
		$criteria->compare('block8',$this->block8,true);
		$criteria->compare('block9',$this->block9,true);
		$criteria->compare('block10',$this->block10,true);
		$criteria->compare('block11',$this->block11,true);
		$criteria->compare('block12',$this->block12);
		$criteria->compare('block13',$this->block13);
		$criteria->compare('block14',$this->block14);
		$criteria->compare('block15',$this->block15,true);
		$criteria->compare('block16',$this->block16,true);
		$criteria->compare('block17',$this->block17,true);
		$criteria->compare('block18',$this->block18,true);
		$criteria->compare('block19',$this->block19,true);
		$criteria->compare('block20',$this->block20,true);
		$criteria->compare('block21',$this->block21,true);
		$criteria->compare('block22',$this->block22,true);
		$criteria->compare('block23',$this->block23,true);
		$criteria->compare('block24',$this->block24,true);
		$criteria->compare('block25',$this->block25,true);
		$criteria->compare('block26',$this->block26,true);
		$criteria->compare('block27',$this->block27,true);
		$criteria->compare('block28',$this->block28,true);
		$criteria->compare('block29',$this->block29,true);
		$criteria->compare('block30',$this->block30,true);
		$criteria->compare('block31',$this->block31,true);
		$criteria->compare('block32',$this->block32,true);
		$criteria->compare('block33',$this->block33,true);
		$criteria->compare('block34',$this->block34,true);
		$criteria->compare('block35',$this->block35,true);
		$criteria->compare('block36',$this->block36,true);
		$criteria->compare('block37',$this->block37,true);
		$criteria->compare('block38',$this->block38,true);
		$criteria->compare('block39',$this->block39,true);
		$criteria->compare('block40',$this->block40,true);
		$criteria->compare('block41',$this->block41,true);
		$criteria->compare('block42',$this->block42,true);
		$criteria->compare('block43',$this->block43,true);
		$criteria->compare('block44',$this->block44,true);
		$criteria->compare('block45',$this->block45,true);
		$criteria->compare('block46',$this->block46,true);
		$criteria->compare('block47',$this->block47,true);
		$criteria->compare('block48',$this->block48,true);
		$criteria->compare('block49',$this->block49,true);
		$criteria->compare('block50',$this->block50,true);
		$criteria->compare('block51',$this->block51,true);
		$criteria->compare('block52',$this->block52,true);
		$criteria->compare('block53',$this->block53,true);
		$criteria->compare('block54',$this->block54,true);
		$criteria->compare('block55',$this->block55,true);
		$criteria->compare('block56',$this->block56,true);
		$criteria->compare('block57',$this->block57,true);
		$criteria->compare('block58',$this->block58,true);
		$criteria->compare('block59',$this->block59,true);
		$criteria->compare('block60',$this->block60,true);
		$criteria->compare('block61',$this->block61,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NetworkSimsSchedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
