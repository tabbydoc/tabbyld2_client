<?php

use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

/* @var $column_form app\models\ColumnForm */
/* @var $headings app\controllers\SiteController */
?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Покараска столбцов в зависимости от их типа
    function paintColumns() {
        let glob_i = 1;
        // Получение заголовков html-таблицы
        let th = document.getElementById("cleared-tubular-data").getElementsByTagName("th");
        // Все ячейки в html-таблице
        let cells = document.querySelectorAll("table#cleared-tubular-data td");
        // Количество столбцов в html-таблице
        let col_number = document.getElementById("cleared-tubular-data").rows[0].cells.length;
        // Обход массива с классифицированными столбцами
        $.each(classified_columns, function(index, value) {
            let cell_index = glob_i
            // Закрашивание сущностного (тематического) столбца
            if (value === "SUBJECT") {
                th[glob_i].style.backgroundColor = "#fff0f5";
                for (let i = 0; i < cells.length; i++ ) {
                    if (cell_index === i) {
                        cells[cell_index].style.backgroundColor = "#fff0f5";
                        let cell_value = cells[cell_index].innerHTML;
                        cells[cell_index].innerHTML = '<a class="cell-link" href="#">' + cell_value + '</a>';
                        cell_index += col_number
                    }
                }
            }
            // Закрашивание категориального столбца
            if (value === "CATEGORICAL") {
                th[glob_i].style.backgroundColor = "#f2ffc8";
                for (let i = 0; i < cells.length; i++ ) {
                    if (cell_index === i) {
                        cells[cell_index].style.backgroundColor = "#f2ffc8";
                        let cell_value = cells[cell_index].innerHTML;
                        cells[cell_index].innerHTML = '<a class="cell-link" href="#">' + cell_value + '</a>';
                        cell_index += col_number
                    }
                }
            }
            // Закрашивание литерального столбца
            if (value === "LITERAL") {
                th[glob_i].style.backgroundColor = "#e0ffff";
                for (let i = 0; i < cells.length; i++ ) {
                    if (cell_index === i) {
                        cells[cell_index].style.backgroundColor = "#e0ffff";
                        cells[cell_index].innerHTML = cells[cell_index].innerText;
                        cell_index += col_number
                    }
                }
            }
            glob_i++;
        });
    }

    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();

        // Обработка нажатия кнопки выбрать столбец
        $("#confirm-subject-column-selection-button").click(function(e) {
            e.preventDefault();
            // Закрытие модального окна
            $("#selectSubjectColumnModalForm").modal("hide");
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);

            if (candidate_classes != null) {
                // Обнуление набора классов кандидатов
                candidate_classes = null;
                // Удаление информации о классах кандидатах
                let candidate_class_list = document.getElementById("candidate-class-list");
                candidate_class_list.innerText = "Нет данных";
            }
            if (candidate_entities != null) {
                // Обнуление набора сущностей кандидатов
                candidate_entities = null;
                // Удаление информации о сущностей кандидатах
                let candidate_entity_list = document.getElementById("candidate-entity-list");
                candidate_entity_list.innerText = "Нет данных";
            }
            if (candidate_properties != null) {
                // Обнуление набора свойств кандидатов
                candidate_properties = null;
                // Удаление информации о свойствах кандидатах
                let candidate_property_list = document.getElementById("candidate-property-list");
                candidate_property_list.innerText = "Нет данных";
            }

            // Форма выбора столбца
            let form = $("#select-subject-column-form");
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/select-subject-column' ?>",
                type: "post",
                data: form.serialize() + "&tabular_data=" + encodeURIComponent(JSON.stringify(tabular_data)) +
                    '&file_name=' + file_name,
                dataType: "json",
                success: function(data) {
                    // Если есть данные с классифицированными столбцами
                    if (data["classified_columns"] != null) {
                        recognized_named_entities = data["recognized_data"];
                        classified_columns = data["classified_columns"];
                        // Вывод данных с распознанными именноваными сущностями на вкладку
                        let recognized_data = document.getElementById("recognized-data");
                        recognized_data.innerText = JSON.stringify(recognized_named_entities, undefined, 2);
                        // Вывод данных с классифицированными столбцами на вкладку
                        let classified_data = document.getElementById("classified-data");
                        classified_data.innerText = JSON.stringify(classified_columns, undefined, 2);
                        // Обновление заголовков столбцов
                        let glob_i = 1;
                        let th = document.getElementById("cleared-tubular-data").getElementsByTagName("th");
                        $.each(classified_columns, function(index, value) {
                            th[glob_i].innerHTML = '<a class="heading-link" href="#">' + index + '</a>';
                            if (value !== "SUBJECT")
                                th[glob_i].innerHTML += ' | <a class="relation-link" href="#" title="' + index + '">' +
                                    '<span class="glyphicon glyphicon-pencil"></span>\n' +
                                    '</a>';
                            glob_i++;
                        });
                        // Покараска столбцов в зависимости от их типа
                        paintColumns();
                        // Активация кнопки аннотирования литеральных столбцов
                        document.getElementById("annotate-literal-column-button").disabled = false;
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

<!-- Модальное окно выбора сущностного (тематического) столбца -->
<?php Modal::begin([
    'id' => 'selectSubjectColumnModalForm',
    'header' => '<h3>' . Yii::t('app', 'ANNOTATE_TABLE_MODAL_FORM_SELECT_SUBJECT_COLUMN') . '</h3>',
]); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'select-subject-column-form',
        'enableClientValidation' => true,
    ]); ?>

        <?= $form->field($column_form, 'column_name')->dropDownList(
                $headings,
                array('prompt' => '', 'name' => 'column')
        ); ?>

        <?= Button::widget([
            'label' => Yii::t('app', 'BUTTON_SELECT'),
            'options' => [
                'id' => 'confirm-subject-column-selection-button',
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