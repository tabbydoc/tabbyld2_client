<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class EntityForm.
 */
class EntityForm extends Model
{
    public $entity_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            array(['entity_name'], 'safe'),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'entity_name' => Yii::t('app', 'ENTITY_FORM_ENTITY_NAME'),
        ];
    }
}