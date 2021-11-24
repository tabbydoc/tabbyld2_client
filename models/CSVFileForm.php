<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class CSVFileForm.
 */
class CSVFileForm extends Model
{
    public $csv_file;

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return array(
            array(['csv_file'], 'required'),
            array(['csv_file'], 'file', 'extensions'=>'csv', 'checkExtensionByMimeType'=>false),
        );
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return array(
            'csv_file' => Yii::t('app', 'CSV_FILE_FORM_FILE'),
        );
    }
}