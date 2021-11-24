<?php

namespace app\controllers;

use Yii;
use CURLFile;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\ClassForm;
use app\models\ColumnForm;
use app\models\EntityForm;
use app\models\PropertyForm;
use app\models\CSVFileForm;
use app\models\JSONFileForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Странциа отправки исходной CSV-таблицы на STL.
     *
     * @return string
     */
    public function actionUploadCsvTable()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Создание формы файла CSV
        $file_form = new CSVFileForm();
        // Если POST-запрос
        if (Yii::$app->request->isPost) {
            $file_form->csv_file = UploadedFile::getInstance($file_form, 'csv_file');
            if ($file_form->validate()) {
                // Временное сохранение загруженного файла таблицы
                $file_form->csv_file->saveAs('uploads/uploaded-table.csv');
                // Подготовка данных для удаленной отправки
                $upload_request = array(
                    'csv_file' => new CURLFile(
                        realpath(Yii::$app->basePath . '/web/uploads/uploaded-table.csv'),
                        'text/csv',
                        $file_form->csv_file
                    )
                );
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/upload-csv-file');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $upload_request);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $tabular_data = json_decode($response, true);
                // Если есть даные
                if ($tabular_data) {
                    // Если пришла ошибка
                    if (isset($tabular_data['error'])) {
                        // Вывод сообщения об ошибке загрузке файла таблицы
                        Yii::$app->getSession()->setFlash('error', 'Error! ' . $tabular_data['error']);

                        return $this->refresh();
                    } else {
                        $cleared_data = json_decode($response, true);
                        // Вывод сообщения об успехной загрузке файла таблицы
                        Yii::$app->getSession()->setFlash('success',
                            Yii::t('app', 'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED_AND_CONVERTED'));

                        return $this->render('annotate-table', [
                            'tabular_data' => $tabular_data,
                            'column_form' => new ColumnForm(),
                            'class_form' => new ClassForm(),
                            'entity_form' => new EntityForm(),
                            'property_form' => new PropertyForm(),
                            'file_name' => $file_form->csv_file
                        ]);
                    }
                } else {
                    // Вывод сообщения об ошибке загрузке файла таблицы
                    Yii::$app->getSession()->setFlash('error',
                        Yii::t('app', 'ANNOTATE_TABLE_MESSAGE_STL_ERROR'));

                    return $this->refresh();
                }
            }
        }

        return $this->render('upload-csv-table', [
            'file_form' => $file_form,
        ]);
    }

    /**
     * Странциа отправки json-файла представления таблицы на STL.
     *
     * @return string
     */
    public function actionUploadJsonTable()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Создание формы файла JSON
        $file_form = new JSONFileForm();
        // Если POST-запрос
        if (Yii::$app->request->isPost) {
            $file_form->json_file = UploadedFile::getInstance($file_form, 'json_file');
            if ($file_form->validate()) {
                // Временное сохранение загруженного файла таблицы
                $file_form->json_file->saveAs('uploads/uploaded-table.json');
                // Подготовка данных для удаленной отправки
                $upload_request = array(
                    'json_file' => new CURLFile(
                        realpath(Yii::$app->basePath . '/web/uploads/uploaded-table.json'),
                        'application/json',
                        $file_form->json_file
                    )
                );
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/upload-json-file');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $upload_request);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $tabular_data = json_decode($response, true);
                // Если есть даные
                if ($tabular_data) {
                    // Если пришла ошибка
                    if (isset($tabular_data['error'])) {
                        // Вывод сообщения об ошибке загрузке файла таблицы
                        Yii::$app->getSession()->setFlash('error', 'Error! ' . $tabular_data['error']);

                        return $this->refresh();
                    } else {
                        // Вывод сообщения об успехной загрузке файла таблицы
                        Yii::$app->getSession()->setFlash('success',
                            Yii::t('app', 'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED'));

                        return $this->render('annotate-table', [
                            'tabular_data' => $tabular_data,
                            'column_form' => new ColumnForm(),
                            'class_form' => new ClassForm(),
                            'entity_form' => new EntityForm(),
                            'property_form' => new PropertyForm(),
                            'file_name' => $file_form->json_file
                        ]);
                    }
                } else {
                    // Вывод сообщения об ошибке загрузке файла таблицы
                    Yii::$app->getSession()->setFlash('error',
                        Yii::t('app', 'ANNOTATE_TABLE_MESSAGE_STL_ERROR'));

                    return $this->refresh();
                }
            }
        }

        return $this->render('upload-json-table', [
            'file_form' => $file_form,
        ]);
    }

    /**
     * Странциа аннотирования таблицы при помощи сервиса STL.
     *
     * @return string
     */
    public function actionAnnotateTable()
    {
        return $this->render('annotate-table');
    }

    /**
     * Классификация столбцов и определение сущностного (тематического) столбца при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionSelectSubjectColumn()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $column_ordinal = Yii::$app->request->post('column');
                $cleared_data = json_decode(Yii::$app->request->post('tabular_data'), true);
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'column_ordinal' => $column_ordinal,
                    'cleared_data' => $cleared_data,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/classify-column');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $decoded_data = json_decode($json_data, true);
                $recognized_data = $decoded_data['recognized_data'];
                $classified_columns = $decoded_data['classified_data'];
                // Если есть данные по распознанным именованным сущностям
                if ($recognized_data)
                    $data['recognized_data'] = $recognized_data;
                else
                    $data['recognized_data'] = null;
                // Если есть данные по классифицированным столбцам
                if ($classified_columns)
                    $data['classified_columns'] = $classified_columns;
                else
                    $data['classified_columns'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Генерация классов кандидатов для заголовка столбца при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionGenerateCandidateClasses()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $column_name = Yii::$app->request->post('column_name');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'column_name' => $column_name,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-candidate-classes');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $candidate_classes = json_decode($json_data, true);
                // Если есть даные
                if ($candidate_classes)
                    $data['candidate_classes'] = $candidate_classes;
                else
                    $data['candidate_classes'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Выбор наиболее подходящего (референтного) класса из набора кандидатов для столбца при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionSelectReferenceClass()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $column_name = Yii::$app->request->post('column_name');
                $class_name = Yii::$app->request->post('class_name');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'column_name' => $column_name,
                    'candidate_classes' => array(),
                    'class_name' => $class_name,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-reference-class');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $reference_class = json_decode($json_data, true);
                // Если есть даные
                if ($reference_class)
                    $data['reference_class'] = $reference_class;
                else
                    $data['reference_class'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Аннотирование типами данных литеральных столбцов таблицы.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionAnnotateLiteralColumns()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $recognized_data = json_decode(Yii::$app->request->post('recognized_data'), true);
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'recognized_data' => $recognized_data,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-reference-datatype');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $reference_datatype = json_decode($json_data, true);
                // Если есть даные
                if ($reference_datatype)
                    $data['reference_datatype'] = $reference_datatype;
                else
                    $data['reference_datatype'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Генерация сущностей кандидатов для значения ячейки при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionGenerateCandidateEntities()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $cell_value = Yii::$app->request->post('cell_value');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'cell_value' => $cell_value,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-candidate-entities');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $candidate_entities = json_decode($json_data, true);
                // Если есть даные
                if ($candidate_entities)
                    $data['candidate_entities'] = $candidate_entities;
                else
                    $data['candidate_entities'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Выбор наиболее подходящей (референтной) сущности из набора кандидатов для значения ячейки при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionSelectReferenceEntity()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $cell_value = Yii::$app->request->post('cell_value');
                $entity_name = Yii::$app->request->post('entity_name');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'cell_value' => $cell_value,
                    'candidate_entities' => array(),
                    'entity_name' => $entity_name,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-reference-entity');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $reference_entity = json_decode($json_data, true);
                // Если есть даные
                if ($reference_entity)
                    $data['reference_entity'] = $reference_entity;
                else
                    $data['reference_entity'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Генерация свойств кандидатов для пары столбцов при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionGenerateCandidateProperties()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $column_name = Yii::$app->request->post('column_name');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'column_name' => $column_name,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-candidate-properties');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $candidate_properties = json_decode($json_data, true);
                // Если есть даные
                if ($candidate_properties)
                    $data['candidate_properties'] = $candidate_properties;
                else
                    $data['candidate_properties'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Выбор наиболее подходящего (референтного) свойства из набора кандидатов для пары столбцов при помощи сервиса STL.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionSelectReferenceProperty()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $column_name = Yii::$app->request->post('column_name');
                $property_name = Yii::$app->request->post('property_name');
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'column_name' => $column_name,
                    'candidate_properties' => array(),
                    'property_name' => $property_name,
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/get-reference-property');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                $json_data = curl_exec($curl);
                curl_close($curl);
                // Декодирование полученных данных из формата JSON
                $reference_property = json_decode($json_data, true);
                // Если есть даные
                if ($reference_property)
                    $data['reference_property'] = $reference_property;
                else
                    $data['reference_property'] = null;
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Пополнить базу знаний платформы TALISMAN конкретными сущностями из таблицы.
     *
     * @return false|\yii\console\Response|Response
     */
    public function actionAugmentKnowledgeBase()
    {
        // Установка времени выполнения скрипта в 1 час.
        set_time_limit(60 * 60);
        // Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Если POST-запрос
            if (Yii::$app->request->isPost) {
                // Формирование json-запроса
                $file_name = Yii::$app->request->post('file_name');
                $request = json_encode(array(
                    'file_name' => $file_name
                ));
                // Отправка POST-запроса на STL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Yii::$app->params['url'] . '/stl/augment-knowledge-base');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
                curl_exec($curl);
                curl_close($curl);
            }
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }
}