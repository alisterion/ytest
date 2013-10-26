<?php

/**
 * This is the model class for table "t_questions".
 *
 * The followings are the available columns in table 't_questions':
 * @property integer $id
 * @property integer $theam_id
 * @property string $question_text
 * @property string $answ_1
 * @property string $answ_2
 * @property string $answ_3
 * @property string $answ_4
 * @property string $answ_5
 * @property integer $true_answer
 * @property integer $point
 * @property integer $language
 */
class TQuestions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TQuestions the static model class
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
		return 't_questions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('theam_id, question_text, answ_1, answ_2, answ_3, answ_4, answ_5, true_answer, point, language', 'required'),
			array('theam_id, true_answer, point, language', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, theam_id, question_text, answ_1, answ_2, answ_3, answ_4, answ_5, true_answer, point, language', 'safe', 'on'=>'search'),
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
			'theam_id' => 'Theam',
			'question_text' => 'Question Text',
			'answ_1' => 'Answ 1',
			'answ_2' => 'Answ 2',
			'answ_3' => 'Answ 3',
			'answ_4' => 'Answ 4',
			'answ_5' => 'Answ 5',
			'true_answer' => 'True Answer',
			'point' => 'Point',
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

		$criteria->compare('theam_id',$this->theam_id);

		$criteria->compare('question_text',$this->question_text,true);

		$criteria->compare('answ_1',$this->answ_1,true);

		$criteria->compare('answ_2',$this->answ_2,true);

		$criteria->compare('answ_3',$this->answ_3,true);

		$criteria->compare('answ_4',$this->answ_4,true);

		$criteria->compare('answ_5',$this->answ_5,true);

		$criteria->compare('true_answer',$this->true_answer);

		$criteria->compare('point',$this->point);

		$criteria->compare('language',$this->language);

		return new CActiveDataProvider('TQuestions', array(
			'criteria'=>$criteria,
		));
	}
}