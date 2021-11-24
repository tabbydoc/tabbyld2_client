<?php

/* @var $this yii\web\View */
/* @var $tabular_data app\controllers\SiteController */
/* @var $column_form app\models\ColumnForm */
/* @var $class_form app\models\ClassForm */
/* @var $entity_form app\models\EntityForm */
/* @var $property_form app\models\PropertyForm */
/* @var $file_name app\controllers\SiteController */

use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::t('app', 'ANNOTATE_TABLE_PAGE_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- JS-скрипт -->
<script type="text/javascript">
    // Название файла таблицы без расширения
    let file_name = '<?= pathinfo($file_name)['filename'] ?>';
    // Очищенные табличные данные в формате json
    let tabular_data = <?php echo json_encode($tabular_data) ?>;
    // Переменная для хранения распознанных именованных сущностей таблицы
    let recognized_named_entities = null;
    // Переменная для хранения классифицированных столбцов
    let classified_columns = null;
    // Переменная для хранения классов кандидатов
    let candidate_classes = null;
    // Переменная для хранения выбранного заголовка столбца
    let current_column_name = null;
    // Переменная для хранения сущностей кандидатов
    let candidate_entities = null;
    // Переменная для хранения выбранного значения ячейки
    let current_cell_value = null;
    // Переменная для хранения свойств кандидатов
    let candidate_properties = null;

    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        let target = document.getElementById("center");
        let spinner = new Spinner(spinner_options).spin();

        // Вывод очищенных табличных данных на вкладку
        let cleared_data = document.getElementById("cleared-data");
        cleared_data.innerText = JSON.stringify(tabular_data, undefined, 2);

        // Обработка нажатия кнопки задания сущностного столбца
        $("#select-subject-column-button").click(function(e) {
            // Открытие модального окна
            $("#selectSubjectColumnModalForm").modal("show");
        });

        // Обработка нажатия кнопки задания аннотации для литеральных столбцов
        $("#annotate-literal-column-button").click(function(e) {
            e.preventDefault();
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/annotate-literal-columns' ?>",
                type: "post",
                data: "recognized_data=" + JSON.stringify(recognized_named_entities) + '&file_name=' + file_name,
                dataType: "json",
                success: function (data) {
                    // Если есть данные
                    if (data["reference_datatype"] != null) {
                        let glob_i = 1;
                        // Получение заголовков html-таблицы
                        let th = document.getElementById("cleared-tubular-data").getElementsByTagName("th");
                        // Обход массива с классифицированными столбцами
                        $.each(classified_columns, function(index, value) {
                            $.each(data["reference_datatype"], function(inx, val) {
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
                error: function () {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("Непредвиденная ошибка!");
                }
            });
        });

        // Обработка нажатия кнопки пополнения базы знаний
        $("#augment-knowledge-base-button").click(function(e) {
            e.preventDefault();
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/augment-knowledge-base' ?>",
                type: "post",
                data: "file_name=" + file_name,
                dataType: "json",
                success: function (data) {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("База знаний TALISMAN успешно пополнина!");
                },
                error: function () {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("Непредвиденная ошибка!");
                }
            });
        });

        // Обработка нажатия ссылки заголовка столбца
        $(document).on("click", ".heading-link", function(e) {
            e.preventDefault();
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);
            // Запоминание текущего выбранного заголовка столбца
            current_column_name = this.innerText;
            // Если столбцы в таблице классифицированы
            if (classified_columns != null) {
                // Обход массива с классифицированными столбцами
                $.each(classified_columns, function(index, value) {
                    // Совпадение ключа с заголовком таблицы
                    if (index === current_column_name) {
                        // Если столбец сущностный или категориальный
                        if (value === "SUBJECT" || value === "CATEGORICAL") {
                            // Ajax-запрос
                            $.ajax({
                                url: "<?= Yii::$app->request->baseUrl . '/generate-candidate-classes' ?>",
                                type: "post",
                                data: "column_name=" + encodeURIComponent(current_column_name) +
                                    '&file_name=' + file_name,
                                success: function (data) {
                                    // Если есть данные
                                    if (data["candidate_classes"] != null) {
                                        // Если набор классов кандидатов пуст
                                        if (candidate_classes == null) {
                                            candidate_classes = {};
                                            // Формирование шаблона набора классов кандидатов (список только с ключами)
                                            $.each(classified_columns, function(inx, val) {
                                                let item = {};
                                                item[inx] = null;
                                                $.extend(candidate_classes, item);
                                            });
                                        }
                                        // Добавление значений в набор классов кандидатов
                                        $.extend(candidate_classes, data["candidate_classes"]);
                                        // Вывод данных с классами кандидатами на вкладку
                                        let candidate_class_list = document.getElementById("candidate-class-list");
                                        candidate_class_list.innerText = JSON.stringify(candidate_classes, undefined, 2);
                                        // Список с классами кандидатами
                                        let class_name = document.getElementById("classform-class_name");
                                        // Обнуление откидного списка с классами кандидатами
                                        let length = class_name.options.length;
                                        for (let i = length - 1; i >= 0; i--) {
                                            class_name.options[i] = null;
                                        }
                                        // Формирование новых значений откидного списка с классами кандидатами
                                        $.each(data["candidate_classes"], function(inx, candidate_class_set) {
                                            $.each(candidate_class_set, function(inx_cl, candidate_class) {
                                                let option = document.createElement("option");
                                                option.text = option.value = candidate_class;
                                                class_name.add(option, 0);
                                            });
                                        });
                                        // Открытие модального окна
                                        $("#selectReferenceClassModalForm").modal("show");
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
                                error: function () {
                                    // Скрытие индикатора прогресса
                                    $("#overlay").hide();
                                    spinner.stop(target);
                                    alert("Непредвиденная ошибка!");
                                }
                            });
                        } else {
                            // Скрытие индикатора прогресса
                            $("#overlay").hide();
                            spinner.stop(target);
                            alert("Столбец литеральный!");
                        }
                    }
                });
            } else {
                // Скрытие индикатора прогресса
                $("#overlay").hide();
                spinner.stop(target);
                alert("Столбцы не классифицированы! Задайте сущностный столбец.");
            }
        });

        // Обработка нажатия ссылки ячейки столбца
        $(document).on("click", ".cell-link", function(e) {
            e.preventDefault();
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);
            // Запоминание текущего выбранного значения ячейки
            current_cell_value = this.innerText;
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/generate-candidate-entities' ?>",
                type: "post",
                data: "cell_value=" + encodeURIComponent(current_cell_value) + '&file_name=' + file_name,
                success: function (data) {
                    // Если есть данные
                    if (data["candidate_entities"] != null) {
                        // Если набор сущностей кандидатов пуст
                        if (candidate_entities == null) {
                            candidate_entities = {};
                            // Формирование шаблона набора сущностей кандидатов (список только с ключами)
                            let columns = {};
                            $.each(tabular_data, function(index, item) {
                                $.each(item, function(key, value) {
                                    columns[key] = null;
                                    $.extend(candidate_entities, columns);
                                });
                            });
                            let all_candidate_entities_for_column = {}
                            $.each(candidate_entities, function(inx, itm) {
                                let row = {}
                                $.each(tabular_data, function(index, item) {
                                    $.each(item, function(key, value) {
                                        if (inx === key)
                                            row[value] = null;
                                    });
                                });
                                all_candidate_entities_for_column[inx] = row;
                                $.extend(candidate_entities, all_candidate_entities_for_column);
                            });
                        }
                        // Добавление значений в набор сущностей кандидатов
                        let candidate_entities_for_row = {}
                        $.each(candidate_entities, function(column_name, item) {
                            let row = {}
                            $.each(item, function(cell_name, cell_value) {
                                row[cell_name] = cell_value;
                                $.each(data["candidate_entities"], function(key, value) {
                                    if (cell_name === key)
                                        row[cell_name] = value;
                                });
                            });
                            candidate_entities_for_row[column_name] = row;
                            $.extend(candidate_entities, candidate_entities_for_row);
                        });
                        // Вывод данных с сущностями кандидатами на вкладку
                        let candidate_entity_list = document.getElementById("candidate-entity-list");
                        candidate_entity_list.innerText = JSON.stringify(candidate_entities, undefined, 2);
                        // Список с сущностями кандидатами
                        let entity_name = document.getElementById("entityform-entity_name");
                        // Обнуление откидного списка с сущностями кандидатами
                        let length = entity_name.options.length;
                        for (let i = length - 1; i >= 0; i--) {
                            entity_name.options[i] = null;
                        }
                        // Формирование новых значений откидного списка с сущностями кандидатами
                        $.each(data["candidate_entities"], function(inx, candidate_entity_set) {
                            $.each(candidate_entity_set, function(inx_ent, candidate_entity) {
                                let option = document.createElement("option");
                                option.text = option.value = candidate_entity;
                                entity_name.add(option, 0);
                            });
                        });
                        // Открытие модального окна
                        $("#selectReferenceEntityModalForm").modal("show");
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
                error: function () {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("Непредвиденная ошибка!");
                }
            });
        });

        // Обработка нажатия ссылки связи пары столбцов
        $(document).on("click", ".relation-link", function(e) {
            e.preventDefault();
            // Отображение индикатора прогресса
            $("#overlay").show();
            spinner.spin(target);
            // Запоминание текущего выбранного заголовка столбца
            current_column_name = this.getAttribute("title");
            // Ajax-запрос
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/generate-candidate-properties' ?>",
                type: "post",
                data: "column_name=" + encodeURIComponent(current_column_name) + '&file_name=' + file_name,
                success: function (data) {
                    // Если есть данные
                    if (data["candidate_properties"] != null) {
                        // Если набор свойств кандидатов пуст
                        if (candidate_properties == null) {
                            candidate_properties = {};
                            // Формирование шаблона набора свойств кандидатов (список только с ключами)
                            $.each(classified_columns, function(inx, val) {
                                let item = {};
                                if (val === "SUBJECT")
                                    item[inx] = val;
                                else
                                    item[inx] = null;
                                $.extend(candidate_properties, item);
                            });
                        }
                        // Добавление значений в набор свойств кандидатов
                        $.extend(candidate_properties, data["candidate_properties"]);
                        // Вывод данных со свойствами кандидатами на вкладку
                        let candidate_property_list = document.getElementById("candidate-property-list");
                        candidate_property_list.innerText = JSON.stringify(candidate_properties, undefined, 2);
                        // Список со свойствами кандидатами
                        let property_name = document.getElementById("propertyform-property_name");
                        // Обнуление откидного списка со свойствами кандидатами
                        let length = property_name.options.length;
                        for (let i = length - 1; i >= 0; i--) {
                            property_name.options[i] = null;
                        }
                        // Формирование новых значений откидного списка со свойствами кандидатами
                        $.each(data["candidate_properties"], function(inx, candidate_property_set) {
                            $.each(candidate_property_set, function(inx_cl, candidate_property) {
                                let option = document.createElement("option");
                                option.text = option.value = candidate_property;
                                property_name.add(option, 0);
                            });
                        });
                        // Открытие модального окна
                        $("#selectReferencePropertyModalForm").modal("show");
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
                error: function () {
                    // Скрытие индикатора прогресса
                    $("#overlay").hide();
                    spinner.stop(target);
                    alert("Непредвиденная ошибка!");
                }
            });
        });
    });
</script>

<div class="annotate-table">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_TABLE'),
                'content' => $this->render('_annotate_data', [
                    'tabular_data' => $tabular_data,
                    'column_form' => $column_form,
                    'class_form' => $class_form,
                    'entity_form' => $entity_form,
                    'property_form' => $property_form,
                    'file_name' => $file_name
                ])
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_CLEARED_DATA'),
                'content' => $this->render('_cleared_data')
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_NER'),
                'content' => $this->render('_recognized_data')
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_CLASSIFIED_COLUMNS'),
                'content' => $this->render('_classified_columns')
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_CANDIDATE_CLASSES'),
                'content' => $this->render('_candidate_classes')
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_CANDIDATE_ENTITIES'),
                'content' => $this->render('_candidate_entities')
            ],
            [
                'label' => Yii::t('app', 'ANNOTATE_TABLE_PAGE_CANDIDATE_PROPERTIES'),
                'content' => $this->render('_candidate_properties')
            ]
        ]
    ]); ?>

</div>