<?php

/**
 * This is the model class for table "t_test_type".
 *
 * The followings are the available columns in table 't_test_type':
 * @property integer $id
 * @property integer $test_type
 * @property integer $type_num
 * @property integer $time
 * @property integer $quest_count
 * @property integer $language
 */
class TTestType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TTestType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_test_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_type, type_num, time, quest_count, language', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, test_type, type_num, time, quest_count, language', 'safe', 'on'=>'search'),
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
			'id' => 'Id',
			'test_type' => 'Test Type',
			'type_num' => 'Type Num',
			'time' => 'Time',
			'quest_count' => 'Quest Count',
			'language' => 'Language',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('test_type',$this->test_type);

		$criteria->compare('type_num',$this->type_num);

		$criteria->compare('time',$this->time);

		$criteria->compare('quest_count',$this->quest_count);

		$criteria->compare('language',$this->language);

		return new CActiveDataProvider('TTestType', array(
			'criteria'=>$criteria,
		));
	}
}