<?php

/**
 * This is the model class for table "t_thems".
 *
 * The followings are the available columns in table 't_thems':
 * @property integer $id
 * @property integer $theam_visible_num
 * @property string $title
 * @property integer $language
 */
class TThems extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TThems the static model class
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
		return 't_thems';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('theam_visible_num, title, language', 'required'),
			array('theam_visible_num, language', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, theam_visible_num, title, language', 'safe', 'on'=>'search'),
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
			't_questions' => array(self::HAS_MANY, 'TQuestions', 'theam_id'),
			't_them_to_modules' => array(self::HAS_MANY, 'TThemToModule', 'theam_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'theam_visible_num' => 'Theam Visible Num',
			'title' => 'Title',
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

		$criteria->compare('theam_visible_num',$this->theam_visible_num);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('language',$this->language);

		return new CActiveDataProvider('TThems', array(
			'criteria'=>$criteria,
		));
	}
}