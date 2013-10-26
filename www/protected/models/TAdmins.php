<?php

/**
 * This is the model class for table "t_admins".
 *
 * The followings are the available columns in table 't_admins':
 * @property integer $id
 * @property string $admKey
 * @property string $name
 * @property string $password
 */
class TAdmins extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TAdmins the static model class
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
		return 't_admins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admKey, name, password', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, admKey, name, password', 'safe', 'on'=>'search'),
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
			'admKey' => 'Adm Key',
			'name' => 'Name',
			'password' => 'Password',
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

		$criteria->compare('admKey',$this->admKey,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider('TAdmins', array(
			'criteria'=>$criteria,
		));
	}
}