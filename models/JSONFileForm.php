<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class CSVFileForm.
 */
class JSONFileForm extends Model
{
    public $json_file;

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return array(
            array(['json_file'], 'required'),
            array(['json_file'], 'file', 'extensions'=>'json', 'checkExtensionByMimeType'=>false),
        );
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return array(
            'json_file' => Yii::t('app', 'CSV_FILE_FORM_FILE'),
        );
    }
}