<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%grade}}".
 *
 * @property int $id
 * @property int $score 成绩
 * @property string $student_id
 * @property int $subject_id
 * @property int $year_id
 *
 * @property Year $year
 * @property Student $student
 * @property Subject $subject
 */
class Grade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%grade}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['score', 'student_id', 'subject_id', 'year_id'], 'integer'],
            [['student_id', 'subject_id', 'year_id'], 'required'],
            [['year_id'], 'exist', 'skipOnError' => true, 'targetClass' => Year::className(), 'targetAttribute' => ['year_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Students::className(), 'targetAttribute'
            => ['student_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::className(), 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'score' => Yii::t('app', '成绩'),
            'student_id' => Yii::t('app', 'Student ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'year_id' => Yii::t('app', 'Year ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYear()
    {
        return $this->hasOne(Year::className(), ['id' => 'year_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Students::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }

    /**
     * {@inheritdoc}
     * @return GradeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GradeQuery(get_called_class());
    }
}
