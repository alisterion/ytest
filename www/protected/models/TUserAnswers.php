<?php

/**
 * This is the model class for table "t_user_answers".
 *
 * The followings are the available columns in table 't_user_answers':
 * @property integer $id
 * @property integer $user_id
 * @property integer $question_id
 * @property integer $question_num
 * @property integer $user_ansver_num
 * @property integer $true_answer
 * @property integer $module_num
 * @property integer $question_cost
 * @property integer $is_answered
 * @property string $begin_time
 * @property string $end_time
 */
class TUserAnswers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TUserAnswers the static model class
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
		return 't_user_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, question_id, question_num, user_ansver_num, true_answer, module_num, question_cost, is_answered, begin_time, end_time', 'required'),
			array('user_id, question_id, question_num, user_ansver_num, true_answer, module_num, question_cost, is_answered', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, question_id, question_num, user_ansver_num, true_answer, module_num, question_cost, is_answered, begin_time, end_time', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'question_id' => 'Question',
			'question_num' => 'Question Num',
			'user_ansver_num' => 'User Ansver Num',
			'true_answer' => 'True Answer',
			'module_num' => 'Module Num',
			'question_cost' => 'Question Cost',
			'is_answered' => 'Is Answered',
			'begin_time' => 'Begin Time',
			'end_time' => 'End Time',
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

		$criteria->compare('user_id',$this->user_id);

		$criteria->compare('question_id',$this->question_id);

		$criteria->compare('question_num',$this->question_num);

		$criteria->compare('user_ansver_num',$this->user_ansver_num);

		$criteria->compare('true_answer',$this->true_answer);

		$criteria->compare('module_num',$this->module_num);

		$criteria->compare('question_cost',$this->question_cost);

		$criteria->compare('is_answered',$this->is_answered);

		$criteria->compare('begin_time',$this->begin_time,true);

		$criteria->compare('end_time',$this->end_time,true);

		return new CActiveDataProvider('TUserAnswers', array(
			'criteria'=>$criteria,
		));
	}
}