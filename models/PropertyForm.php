<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class PropertyForm.
 */
class PropertyForm extends Model
{
    public $property_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            array(['property_name'], 'safe'),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'property_name' => Yii::t('app', 'PROPERTY_FORM_PROPERTY_NAME'),
        ];
    }
}