<?php

return [
    /* Текст на главной странице */
    'WELCOME_TO_STL_CLIENT' => 'Welcome to STL Client!',
    'STL_CLIENT_DESCRIPTION' => ' is a web-based application (test client) designed to testing main functions of ',

    /* Пункты главного меню */
    'NAV_SIGN_IN' => 'Sign in',
    'NAV_SIGN_OUT' => 'Sign out',
    'NAV_LOAD_CSV_TABLE' => 'Load CSV table',
    'NAV_LOAD_JSON_TABLE' => 'Load JSON table',

    /* Пункты правого меню */
    'SIDE_NAV_POSSIBLE_ACTIONS' => 'Possible actions',

    /* Нижний колонтитул (подвал) */
    'FOOTER_INSTITUTE'=>'ISDCT SB RAS',
    'FOOTER_POWERED_BY' => 'Powered by',

    /* Общие кнопки */
    'BUTTON_SIGN_IN' => 'Sing in',
    'BUTTON_UPLOAD' => 'Upload',
    'BUTTON_SELECT' => 'Select',
    'BUTTON_CANCEL' => 'Cancel',
    'BUTTON_IDENTIFY_SUBJECT_COLUMN' => 'Identify subject column',
    'BUTTON_ANNOTATE_LITERAL_COLUMNS' => 'Annotate literal columns',
    'BUTTON_AUGMENT_KNOWLEDGE_BASE' => 'Augment knowledge base',

    /* Общие сообщения об ошибках */
    'ERROR_MESSAGE_PAGE_NOT_FOUND' => 'Page not found.',
    'ERROR_MESSAGE_ACCESS_DENIED' => 'You are not allowed to perform this action.',

    /* Общие уведомления на форме с captcha */
    'CAPTCHA_NOTICE_ONE' => 'Please enter the letters shown in the picture above.',
    'CAPTCHA_NOTICE_TWO' => 'Letters are not case sensitive.',
    'CAPTCHA_NOTICE_THREE' => 'Click on the letters to change the verification code shown in the picture above.',

    /* Общие заголовки сообщений */
    'WARNING' => 'Warning!',
    'NOTICE_TITLE' => 'Pay attention to',
    'NOTICE_TEXT' => 'this important information.',

    /* Страница ошибки */
    'ERROR_PAGE_TEXT_ONE' => 'The above error occurred while the Web server was processing your request.',
    'ERROR_PAGE_TEXT_TWO' => 'Please contact us if you think this is a server error. Thank you.',
    /* Страница входа */
    'SIGN_IN_PAGE_TITLE' => 'Sign in',
    'SIGN_IN_PAGE_TEXT' => 'Please fill out the following fields to sign in:',
    'SIGN_IN_PAGE_RESET_TEXT' => 'If you forgot your password you can',
    'SIGN_IN_PAGE_RESET_LINK' => 'reset it',
    /* Страница загрузки CSV-таблицы */
    'UPLOAD_CSV_TABLE_PAGE_TITLE' => 'Load CSV table',
    /* Страница загрузки JSON-таблицы */
    'UPLOAD_JSON_TABLE_PAGE_TITLE' => 'Load JSON table',
    /* Страница аннотирования таблицы */
    'ANNOTATE_TABLE_PAGE_TITLE' => 'Annotate table',
    'ANNOTATE_TABLE_PAGE_TABLE' => 'Table',
    'ANNOTATE_TABLE_PAGE_CLEARED_DATA' => 'Cleared data',
    'ANNOTATE_TABLE_PAGE_NER' => 'Recognized named entities',
    'ANNOTATE_TABLE_PAGE_CLASSIFIED_COLUMNS' => 'Classified columns',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_CLASSES' => 'Candidate classes for columns',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_ENTITIES' => 'Candidate entities for cells',
    'ANNOTATE_TABLE_PAGE_CANDIDATE_PROPERTIES' => 'Candidate properties for column pairs',
    'ANNOTATE_TABLE_PAGE_SUBJECT_COLUMN' => 'Subject column',
    'ANNOTATE_TABLE_PAGE_CATEGORICAL_COLUMN' => 'Categorical column',
    'ANNOTATE_TABLE_PAGE_LITERAL_COLUMN' => 'Literal column',
    'ANNOTATE_TABLE_PAGE_NO_DATA' => 'No data',
    /* Сообщения на странице аннотирования таблицы */
    'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED_AND_CONVERTED' => 'Table file loaded, converted to json and cleaned successfully!',
    'ANNOTATE_TABLE_MESSAGE_SUCCESSFULLY_LOADED' => 'Table file has been loaded and cleared successfully!',
    'ANNOTATE_TABLE_MESSAGE_STL_ERROR' => 'Error! STL service is not available.',
    /* Модальные окна на странице аннотирования таблицы */
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_SUBJECT_COLUMN' => 'Select subject column',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_CLASS' => 'Select reference class',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_ENTITY' => 'Select reference entity',
    'ANNOTATE_TABLE_MODAL_FORM_SELECT_REFERENCE_PROPERTY' => 'Select reference property',

    /* Формы */
    /* LoginForm */
    'LOGIN_FORM_USERNAME' => 'Username',
    'LOGIN_FORM_PASSWORD' => 'Password',
    'LOGIN_FORM_REMEMBER_ME' => 'Remember Me',
    /* Сообщения LoginForm */
    'LOGIN_FORM_MESSAGE_INCORRECT_USERNAME_OR_PASSWORD' => 'Username or password is incorrect.',
    'LOGIN_FORM_MESSAGE_BLOCKED_ACCOUNT' => 'Your account has been blocked.',
    'LOGIN_FORM_MESSAGE_NOT_CONFIRMED_ACCOUNT' => 'Your account is not confirmed.',
    /* CSVFileForm */
    'CSV_FILE_FORM_FILE' => 'CSV table file',
    /* JSONFileForm */
    'JSON_FILE_FORM_FILE' => 'JSON table file',
    /* ClassForm */
    'CLASS_FORM_CLASS_NAME' => 'Class name',
    /* ColumnForm */
    'COLUMN_FORM_COLUMN_NAME' => 'Column name',
    /* EntityForm */
    'ENTITY_FORM_ENTITY_NAME' => 'Entity name',
    /* PropertyForm */
    'PROPERTY_FORM_PROPERTY_NAME' => 'Property name',

    /* Модели */
    /* Lang */
    'LANG_MODEL_ID' => 'ID',
    'LANG_MODEL_CREATED_AT' => 'Created at',
    'LANG_MODEL_UPDATED_AT' => 'Updated at',
    'LANG_MODEL_URL' => 'URL',
    'LANG_MODEL_LOCAL' => 'Local',
    'LANG_MODEL_NAME' => 'Name',
    'LANG_MODEL_DEFAULT' => 'Default language',

    /* User */
    'USER_MODEL_ID' => 'ID',
    'USER_MODEL_CREATED_AT' => 'Created at',
    'USER_MODEL_UPDATED_AT' => 'Updated at',
    'USER_MODEL_USERNAME' => 'Username',
    'USER_MODEL_PASSWORD' => 'Password',
    'USER_MODEL_AUTH_KEY' => 'Auth key',
    'USER_MODEL_EMAIL_CONFIRM_TOKEN' => 'E-mail confirm token',
    'USER_MODEL_PASSWORD_HASH' => 'Password hash',
    'USER_MODEL_PASSWORD_RESET_TOKEN' => 'Password reset token',
    'USER_MODEL_STATUS' => 'Status',
    'USER_MODEL_FULL_NAME' => 'Full name',
    'USER_MODEL_EMAIL' => 'E-mail',
    /* Сообщения модели User */
    'USER_MODEL_MESSAGE_USERNAME' => 'This username has already been taken.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_DETAILS' => 'You have successfully changed your details.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_PASSWORD' => 'You have successfully changed password.',
];