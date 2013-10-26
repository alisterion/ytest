<?php

/**
 * This is the model class for table "t_them_to_module".
 *
 * The followings are the available columns in table 't_them_to_module':
 * @property integer $id
 * @property integer $modul_id
 * @property integer $theam_id
 * @property integer $theam_num
 * @property integer $language
 */
class TThemToModule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TThemToModule the static model class
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
		return 't_them_to_module';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modul_id, theam_id, theam_num, language', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, modul_id, theam_id, theam_num, language', 'safe', 'on'=>'search'),
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
			'modul' => array(self::BELONGS_TO, 'TModules', 'modul_id'),
			'theam' => array(self::BELONGS_TO, 'TThems', 'theam_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'modul_id' => 'Modul',
			'theam_id' => 'Theam',
			'theam_num' => 'Theam Num',
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

		$criteria->compare('modul_id',$this->modul_id);

		$criteria->compare('theam_id',$this->theam_id);

		$criteria->compare('theam_num',$this->theam_num);

		$criteria->compare('language',$this->language);

		return new CActiveDataProvider('TThemToModule', array(
			'criteria'=>$criteria,
		));
	}
}