<?php

use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

/* @var $class_form app\models\ClassForm */
?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();

        // Обработка нажатия кнопки выбора класса
        $("#select-reference-class-button").click(function(e) {
            e.preventDefault();
            // Закрытие модального окна
            $("#selectReferenceClassModalForm").modal("hide");
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);

            // Форма выбора класса
            let form = $("#select-reference-class-form");
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/select-reference-class' ?>",
                type: "post",
                data: form.serialize() + '&column_name=' + encodeURIComponent(current_column_name) +
                    '&file_name=' + file_name,
                dataType: "json",
                success: function(data) {
                    // Если есть данные с аннотированным столбцом не пустые
                    if (data["reference_class"] != null) {
                        let glob_i = 1;
                        // Получение заголовков html-таблицы
                        let th = document.getElementById("cleared-tubular-data").getElementsByTagName("th");
                        // Обход массива с классифицированными столбцами
                        $.each(classified_columns, function(index, value) {
                            $.each(data["reference_class"], function(inx, val) {
                                if (index === inx) {
                                    // Обновление заголовка столбца
                                    let split = th[glob_i].innerHTML.split("|");
                                    th[glob_i].innerHTML = '<a class="heading-link" href="#">' + inx + '</a>';
                                    th[glob_i].innerHTML += " [" + val + "]";
                                    if (value !== "SUBJECT")
                                        th[glob_i].innerHTML += " | " + split[1];
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

<!-- Модальное окно выбора референтного класса -->
<?php Modal::begin([
    'id' => 'selectReferenceClassModalForm',
    'header' => '<h3>' . Yii::t('app', 'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_CLASS') . '</h3>',
]); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'select-reference-class-form',
        'enableClientValidation' => true,
    ]); ?>

        <?= $form->field($class_form, 'class_name')->dropDownList(array(),
            array('name' => 'class_name')
        ); ?>

        <?= Button::widget([
            'label' => Yii::t('app', 'BUTTON_SELECT'),
            'options' => [
                'id' => 'select-reference-class-button',
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