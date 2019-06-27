<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%year}}".
 *
 * @property int $id
 * @property string $des
 *
 * @property Grade[] $grades
 */
class Year extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%year}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['des'], 'required'],
            [['des'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'des' => Yii::t('app', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrades()
    {
        return $this->hasMany(Grade::className(), ['year_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return YearQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new YearQuery(get_called_class());
    }
}
