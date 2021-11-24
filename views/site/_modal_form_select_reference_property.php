<?php

use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

/* @var $property_form app\models\PropertyForm */
?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();

        // Обработка нажатия кнопки выбора свойства
        $("#select-reference-property-button").click(function(e) {
            e.preventDefault();
            // Закрытие модального окна
            $("#selectReferencePropertyModalForm").modal("hide");
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);

            // Форма выбора свойства
            let form = $("#select-reference-property-form");
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/select-reference-property' ?>",
                type: "post",
                data: form.serialize() + '&column_name=' + encodeURIComponent(current_column_name) +
                    '&file_name=' + file_name,
                dataType: "json",
                success: function(data) {
                    // Если есть данные с аннотированным свойством (отношением) между парой столбцов не пустые
                    if (data["reference_property"] != null) {
                        let glob_i = 1;
                        // Получение заголовков html-таблицы
                        let th = document.getElementById("cleared-tubular-data").getElementsByTagName("th");
                        // Обход массива с классифицированными столбцами
                        $.each(classified_columns, function(index, value) {
                            $.each(data["reference_property"], function(inx, val) {
                                if (index === inx) {
                                    // Обновление заголовка столбца
                                    let split = th[glob_i].innerHTML.split("|");
                                    th[glob_i].innerHTML = split[0];
                                    if (value !== "SUBJECT") {
                                        th[glob_i].innerHTML += ' | <a class="relation-link" href="#" title="' +
                                            index + '"> ' + '<span class="glyphicon glyphicon-pencil"></span></a>';
                                        th[glob_i].innerHTML += " [" + val + "]";
                                    }
                                }
                            });
                            glob_i++;
                        });
                        // Скрытие индикатора прогресса
                        $("#overlay").hide();
                        spinner.stop(target);
                    } else {
                        // Скрытие индикатора прогресса
                        $("#overlay").hide();
                        spinner.stop(target);
                        alert("Ошибка! Сервис STL недоступен.");
                    }
                },
                error: function() {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("Непредвиденная ошибка!");
                }
            });
        });
    });
</script>

<!-- Модальное окно выбора референтного свойства -->
<?php Modal::begin([
    'id' => 'selectReferencePropertyModalForm',
    'header' => '<h3>' . Yii::t('app', 'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_PROPERTY') . '</h3>',
]); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'select-reference-property-form',
        'enableClientValidation' => true,
    ]); ?>

        <?= $form->field($property_form, 'property_name')->dropDownList(array(),
            array('name' => 'property_name')
        ); ?>

        <?= Button::widget([
            'label' => Yii::t('app', 'BUTTON_SELECT'),
            'options' => [
                'id' => 'select-reference-property-button',
                'class' => 'btn-success',
                'style' => 'margin:5px'
            ]
        ]); ?>

        <?= Button::widget([
            'label' => Yii::t('app', 'BUTTON_CANCEL'),
            'options' => [
                'class' => 'btn-danger',
                'style' => 'margin:5px',
                'data-dismiss'=>'modal'
            ]
        ]); ?>

    <?php ActiveForm::end(); ?>

<?php Modal::end(); ?>