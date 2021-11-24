<?php

return [
    /* Текст на главной странице */
    'WELCOME_TO_TABBYLD2_CLIENT' => 'Добро пожаловать в TabbyLD2 Client!',
    'TABBYLD2_CLIENT_DESCRIPTION' => ' - это веб-ориентированное приложение (тестовый клиент), предназначенное для тестирования основных функций программы ',

    /* Пункты главного меню */
    'NAV_SIGN_IN' => 'Вход',
    'NAV_SIGN_OUT' => 'Выход',
    'NAV_LOAD_CSV_TABLE' => 'Загрузить CSV-таблицу',
    'NAV_LOAD_JSON_TABLE' => 'Загрузить JSON-таблицу',

    /* Пункты правого меню */
    'SIDE_NAV_POSSIBLE_ACTIONS' => 'Возможные действия',

    /* Нижний колонтитул (подвал) */
    'FOOTER_INSTITUTE'=>'ИДСТУ СО РАН',
    'FOOTER_POWERED_BY' => 'Разработано',

    /* Общие кнопки */
    'BUTTON_SIGN_IN' => 'Войти',
    'BUTTON_UPLOAD' => 'Загрузить',
    'BUTTON_SELECT' => 'Выбрать',
    'BUTTON_CANCEL' => 'Отмена',
    'BUTTON_IDENTIFY_SUBJECT_COLUMN' => 'Задать сущностный столбец',
    'BUTTON_ANNOTATE_LITERAL_COLUMNS' => 'Аннотировать литеральные столбцы',
    'BUTTON_AUGMENT_KNOWLEDGE_BASE' => 'Пополнить базу знаний',

    /* Общие сообщения об ошибках */
    'ERROR_MESSAGE_PAGE_NOT_FOUND' => 'Страница не найдена.',
    'ERROR_MESSAGE_ACCESS_DENIED' => 'Вам не разрешено производить данное действие.',

    /* Общие уведомления на форме с captcha */
    'CAPTCHA_NOTICE_ONE' => 'Пожалуйста, введите буквы, показанные на картинке выше.',
    'CAPTCHA_NOTICE_TWO' => 'Буквы вводятся без учета регистра.',
    'CAPTCHA_NOTICE_THREE' => 'Для смены проверочного кода нажмите на буквы, показанные на картинке выше.',

    /* Общие заголовки сообщений */
    'WARNING' => 'Предупреждение!',
    'NOTICE_TITLE' => 'Обратите внимание',
    'NOTICE_TEXT' => 'на эту важную информацию.',

    /* Страница ошибки */
    'ERROR_PAGE_TEXT_ONE' => 'Вышеупомянутая ошибка произошла при обработке веб-сервером вашего запроса.',
    'ERROR_PAGE_TEXT_TWO' => 'Пожалуйста, свяжитесь с нами, если Вы думаете, что это ошибка сервера. Спасибо.',
    /* Страница входа */
    'SIGN_IN_PAGE_TITLE' => 'Вход',
    'SIGN_IN_PAGE_TEXT' => 'Пожалуйста, заполните следующие поля для входа:',
    'SIGN_IN_PAGE_RESET_TEXT' => 'Если Вы забыли свой пароль, то Вы можете',
    'SIGN_IN_PAGE_RESET_LINK' => 'сбросить его',
    /* Страница загрузки CSV-таблицы */
    'UPLOAD_CSV_TABLE_PAGE_TITLE' => 'Загрузить CSV-таблицу',
    /* Страница загрузки JSON-таблицы */
    'UPLOAD_JSON_TABLE_PAGE_TITLE' => 'Загрузить JSON-таблицу',
    /* Страница аннотирования таблицы */
    'ANNOTATE_TABLE_PAGE_TITLE' => 'Аннотирование таблицы',
    'ANNOTATE_TABLE_PAGE_TABLE' => 'Таблица',
    'ANNOTATE_TABLE_PAGE_CLEARED_DATA' => 'Очищенные данные',
    'ANNOTATE_TABLE_PAGE_NER' => 'Распознанные именованные сущности',
    'ANNOTATE_TABLE_PAGE_CLASSIFIED_COLUMNS' => 'Классифицированные столбцы',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_CLASSES' => 'Наборы классов кандидатов для столбцов',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_ENTITIES' => 'Наборы сущностей кандидатов для ячеек',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_PROPERTIES' => 'Наборы свойств кандидатов для пар столбцов',
    'ANNOTATE_TABLE_PAGE_SUBJECT_COLUMN' => 'Сущностный (тематический) столбец',
    'ANNOTATE_TABLE_PAGE_CATEGORICAL_COLUMN' => 'Категориальный столбец',
    'ANNOTATE_TABLE_PAGE_LITERAL_COLUMN' => 'Литеральный столбец',
    'ANNOTATE_TABLE_PAGE_NO_DATA' => 'Нет данных',
    /* Сообщения на странице аннотирования таблицы */
    'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED_AND_CONVERTED' => 'Файл таблицы успешно загружен, преобразован в json и очищен!',
    'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED' => 'Файл таблицы успешно загружен и очищен!',
    'ANNOTATE_TABLE_MESSAGE_STL_ERROR' => 'Ошибка! Сервис STL недоступен.',
    /* Модальные окна на странице аннотирования таблицы */
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_SUBJECT_COLUMN' => 'Выбор сущностного (тематического) столбца',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_CLASS' => 'Выбор референтного класса',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_ENTITY' => 'Выбор референтной сущности',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_PROPERTY' => 'Выбор референтного свойства',

    /* Формы */
    /* LoginForm */
    'LOGIN_FORM_USERNAME' => 'Имя пользователя',
    'LOGIN_FORM_PASSWORD' => 'Пароль',
    'LOGIN_FORM_REMEMBER_ME' => 'Запомнить меня',
    /* Сообщения LoginForm */
    'LOGIN_FORM_MESSAGE_INCORRECT_USERNAME_OR_PASSWORD' => 'Неверное имя пользователя или пароль.',
    'LOGIN_FORM_MESSAGE_BLOCKED_ACCOUNT' => 'Ваш аккаунт заблокирован.',
    'LOGIN_FORM_MESSAGE_NOT_CONFIRMED_ACCOUNT' => 'Ваш аккаунт не подтвержден.',
    /* CSVFileForm */
    'CSV_FILE_FORM_FILE' => 'Файл таблицы в формате CSV',
    /* JSONFileForm */
    'JSON_FILE_FORM_FILE' => 'Файл таблицы в формате JSON',
    /* ClassForm */
    'CLASS_FORM_CLASS_NAME' => 'Название класса',
    /* ColumnForm */
    'COLUMN_FORM_COLUMN_NAME' => 'Название столбца',
    /* EntityForm */
    'ENTITY_FORM_ENTITY_NAME' => 'Название сущности',
    /* PropertyForm */
    'PROPERTY_FORM_PROPERTY_NAME' => 'Название свойства',

    /* Модели */
    /* Lang */
    'LANG_MODEL_ID' => 'ID',
    'LANG_MODEL_CREATED_AT' => 'Создан',
    'LANG_MODEL_UPDATED_AT' => 'Обновлен',
    'LANG_MODEL_URL' => 'URL',
    'LANG_MODEL_LOCAL' => 'Локаль',
    'LANG_MODEL_NAME' => 'Название',
    'LANG_MODEL_DEFAULT' => 'Язык по умолчанию',

    /* User */
    'USER_MODEL_ID' => 'ID',
    'USER_MODEL_CREATED_AT' => 'Зарегистрирован',
    'USER_MODEL_UPDATED_AT' => 'Обновлен',
    'USER_MODEL_USERNAME' => 'Логин',
    'USER_MODEL_PASSWORD' => 'Пароль',
    'USER_MODEL_AUTH_KEY' => 'Ключ аутентификации',
    'USER_MODEL_EMAIL_CONFIRM_TOKEN' => 'Метка подтверждения электронной почты',
    'USER_MODEL_PASSWORD_HASH' => 'Хэш пароля',
    'USER_MODEL_PASSWORD_RESET_TOKEN' => 'Метка сброса пароля',
    'USER_MODEL_STATUS' => 'Статус',
    'USER_MODEL_FULL_NAME' => 'Фамилия Имя Отчество',
    'USER_MODEL_EMAIL' => 'Электронная почта',
    /* Сообщения модели User */
    'USER_MODEL_MESSAGE_USERNAME' => 'Это имя пользователя уже занято.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_DETAILS' => 'Вы успешно изменили свои данные.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_PASSWORD' => 'Вы успешно изменили пароль.',
];