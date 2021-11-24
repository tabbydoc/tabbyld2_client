<?php

use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

/* @var $entity_form app\models\EntityForm */
?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();

        // Обработка нажатия кнопки выбора сущности
        $("#select-reference-entity-button").click(function(e) {
            e.preventDefault();
            // Закрытие модального окна
            $("#selectReferenceEntityModalForm").modal("hide");
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);

            // Форма выбора сущности
            let form = $("#select-reference-entity-form");
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/select-reference-entity' ?>",
                type: "post",
                data: form.serialize() + '&cell_value=' + encodeURIComponent(current_cell_value) +
                    '&file_name=' + file_name,
                dataType: "json",
                success: function(data) {
                    // Если есть данные с аннотированным столбцом не пустые
                    if (data["reference_entity"] != null) {
                        // Все ячейки в html-таблице
                        let cells = document.querySelectorAll("table#cleared-tubular-data td");
                        // Обновление значений ячеек таблицы
                        $.each(data["reference_entity"], function(cell_value, value) {
                            for (let i = 0; i < cells.length; i++)
                                if (cells[i].querySelector("a") != null)
                                    if (cells[i].querySelector("a").innerText === cell_value) {
                                        cells[i].innerHTML = '<a class="cell-link" href="#">' + cell_value + '</a>';
                                        cells[i].innerHTML += " [" + value + "]";
                                    }
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

<!-- Модальное окно выбора референтной сущности -->
<?php Modal::begin([
    'id' => 'selectReferenceEntityModalForm',
    'header' => '<h3>' . Yii::t('app', 'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_ENTITY') . '</h3>',
]); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'select-reference-entity-form',
        'enableClientValidation' => true,
    ]); ?>

        <?= $form->field($entity_form, 'entity_name')->dropDownList(array(),
            array('name' => 'entity_name')
        ); ?>

        <?= Button::widget([
            'label' => Yii::t('app', 'BUTTON_SELECT'),
            'options' => [
                'id' => 'select-reference-entity-button',
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