<?php

/**
 * This is the model class for table "t_users".
 *
 * The followings are the available columns in table 't_users':
 * @property integer $id
 * @property integer $user_usnic_num
 * @property string $last_name
 * @property string $name
 * @property string $group
 * @property integer $points
 * @property string $begin_test
 * @property string $end_test
 * @property integer $module_number
 * @property integer $them_num
 * @property integer $language
 * @property integer $max_points
 * @property integer $start_test_at
 * @property integer $end_test_at
 */
class TUsers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TUsers the static model class
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
		return 't_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_usnic_num, last_name, name, group, points, begin_test, end_test, module_number, them_num, max_points, start_test_at, end_test_at', 'required'),
			array('user_usnic_num, points, module_number, them_num, start_test_at, end_test_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_usnic_num, last_name, name, group, points, begin_test, end_test, module_number, them_num, start_test_at, end_test_at', 'safe', 'on'=>'search'),
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
			't_user_answers' => array(self::HAS_MANY, 'TUserAnswer', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_usnic_num' => 'User Usnic Num',
			'last_name' => 'Last Name',
			'name' => 'Name',
			'group' => 'Group',
			'points' => 'Points',
			'begin_test' => 'Begin Test',
			'end_test' => 'End Test',
			'module_number' => 'Module Number',
			'them_num' => 'Them Num',
                        'language' => 'language',
                        'max_points'=>'max_points',
                        'start_test_at'=>'start_test_at',
                        'end_test_at'=>'end_test_at'
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

		$criteria->compare('user_usnic_num',$this->user_usnic_num);

		$criteria->compare('last_name',$this->last_name,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('group',$this->group,true);

		$criteria->compare('points',$this->points);

		$criteria->compare('begin_test',$this->begin_test,true);

		$criteria->compare('end_test',$this->end_test,true);

		$criteria->compare('module_number',$this->module_number);

		$criteria->compare('them_num',$this->them_num);
                
		$criteria->compare('language',$this->language);
                
		$criteria->compare('max_points',$this->max_points);
                
		$criteria->compare('start_test_at',$this->start_test_at);
                
		$criteria->compare('end_test_at',$this->end_test_at);

		return new CActiveDataProvider('TUsers', array(
			'criteria'=>$criteria,
		));
	}
}