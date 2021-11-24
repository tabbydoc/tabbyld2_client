<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class ClassForm.
 */
class ClassForm extends Model
{
    public $class_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            array(['class_name'], 'safe'),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'class_name' => Yii::t('app', 'CLASS_FORM_CLASS_NAME'),
        ];
    }
}