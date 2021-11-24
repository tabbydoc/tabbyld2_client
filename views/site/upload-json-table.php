<?php

/* @var $this yii\web\View */
/* @var $file_form app\models\CSVFileForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'UPLOAD_JSON_TABLE_PAGE_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- JS-скрипт -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();
        // Обработка нажатия кнопки загрузки файла
        $("button.btn-success").click(function () {
            $("#overlay").show();
            spinner.spin(target);
        });
        // Обработка события после валидации формы
        $("#upload-json-file-form").on("afterValidate", function (e, m, attr) {
            e.preventDefault();
            if(attr.length > 0) {
                // Скрытие индикатора прогресса
                $("#overlay").hide();
                spinner.stop(target);
            }
        });
    });
</script>

<div class="upload-json-table">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id'=>'upload-json-file-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($file_form); ?>

    <?= $form->field($file_form, 'json_file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'BUTTON_UPLOAD'),
            ['class' => 'btn btn-success', 'name'=>'upload-json-file-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>