<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class ColumnForm.
 */
class ColumnForm extends Model
{
    public $column_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            array(['column_name'], 'safe'),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'column_name' => Yii::t('app', 'COLUMN_FORM_COLUMN_NAME'),
        ];
    }
}