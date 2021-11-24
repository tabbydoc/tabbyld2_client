<?php

/* @var $tabular_data app\controllers\SiteController */
/* @var $column_form app\models\ColumnForm */
/* @var $class_form app\models\ClassForm */
/* @var $entity_form app\models\EntityForm */
/* @var $property_form app\models\PropertyForm */
/* @var $file_name app\controllers\SiteController */

use yii\grid\GridView;
use yii\bootstrap\Button;
use yii\data\ArrayDataProvider;

// Формирование массива данных таблицы для вывода на экран
$data = array();
$headings = array();
foreach ($tabular_data as $item) {
    $row = array();
    foreach ($item as $key => $value)
        $row[$key] = $value;
    if (empty($headings))
        foreach ($item as $key => $value)
            array_push($headings, $key);
    array_push($data, $row);
}
// Формирование колонок для таблицы
$columns = array();
array_push($columns, ['class' => 'yii\grid\SerialColumn']);
foreach ($headings as $heading)
    array_push($columns, [
        'label' => $heading,
        'attribute' => $heading,
        'format' => 'raw',
        'header' => '<a class="heading-link" href="#">' . $heading . '</a>'
    ]);
// Формирвоание объекта DataProvider
$dataProvider = new ArrayDataProvider([
    'allModels' => $data,
    'pagination' => [
        'pageSize' => 10000,
    ],
]);
?>

<?= $this->render('_modal_form_select_subject_column', [
    'column_form' => $column_form,
    'headings' => $headings
]); ?>

<?= $this->render('_modal_form_select_reference_class', [
    'class_form' => $class_form,
]); ?>

<?= $this->render('_modal_form_select_reference_entity', [
    'entity_form' => $entity_form,
]); ?>

<?= $this->render('_modal_form_select_reference_property', [
    'property_form' => $property_form,
]); ?>

<div class="row">
    <div class="col-md-12">

        <h2><?= Yii::t('app', 'ANNOTATE_TABLE_PAGE_TABLE') ?>:
            <?= pathinfo($file_name)['filename'] ?></h2>

        <div class="form-group">
            <?= Button::widget([
                'label' => Yii::t('app', 'BUTTON_IDENTIFY_SUBJECT_COLUMN'),
                'options' => [
                    'id' => 'select-subject-column-button',
                    'class' => 'btn btn-success'
                ]
            ]); ?>
            <?= Button::widget([
                'label' => Yii::t('app', 'BUTTON_ANNOTATE_LITERAL_COLUMNS'),
                'options' => [
                    'id' => 'annotate-literal-column-button',
                    'class' => 'btn btn-primary',
                    'disabled' => 'disabled'
                ]
            ]); ?>
            <?= Button::widget([
                'label' => Yii::t('app', 'BUTTON_AUGMENT_KNOWLEDGE_BASE'),
                'options' => [
                    'id' => 'augment-knowledge-base-button',
                    'class' => 'btn btn-primary'
                ]
            ]); ?>
        </div>

        <div class="well">
            <div class="row">
                <div class="col-md-4">
                    <div style="display: inline-block; border: 1px solid #000000; background: #fff0f5; width: 12px; height: 12px"></div>
                    <?= Yii::t('app', 'ANNOTATE_TABLE_PAGE_SUBJECT_COLUMN') ?>
                </div>
                <div class="col-md-3">
                    <div style="display: inline-block; border: 1px solid #000000; background: #f2ffc8; width: 12px; height: 12px"></div>
                    <?= Yii::t('app', 'ANNOTATE_TABLE_PAGE_CATEGORICAL_COLUMN') ?>
                </div>
                <div class="col-md-3">
                    <div style="display: inline-block; border: 1px solid #000000; background: #e0ffff; width: 12px; height: 12px"></div>
                    <?= Yii::t('app', 'ANNOTATE_TABLE_PAGE_LITERAL_COLUMN') ?>
                </div>
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'tableOptions' => [
                'id' => 'cleared-tubular-data',
                'class' => 'table table-striped table-bordered'
            ],
        ]); ?>

    </div>
</div>