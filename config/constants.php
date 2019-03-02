<?php

return [
    'TRACK_USER_FIELDS' => [
        'username',
        'email',
        'address',
        'house_number',
        'postal_code',
        'city',
        'telephone_number',
    ],

    'EMAIL_SENDER' => 'nishantsoni1228@gmail.com',
    'EMAIL_SENDER_NAME' => 'Nishant Soni',
    'EMAIL_SUBJECT_WHEN_USER_CREATED' => 'Welcome to super-admin-panel!!',

    'DEVELOPMENT_URL' => 'http://localhost:8000',

    'ERROR_MESSAGES' => [
        'CREATE_USER_ERROR_MESSAGE' => 'Something is wrong user is not created!',
        'FIND_USER_ERROR_MESSAGE' => 'Something is wrong user is not found!',
        'UPDATE_USER_ERROR_MESSAGE' => 'Something is wrong user is not updated!',
        'DELETE_USER_ERROR_MESSAGE' => 'Something is wrong user is not updated!',
    ],

    'QUEUES' => [
        'USER_CREATED_EMAIL_QUEUE' => env('USER_CREATED_EMAIL_QUEUE', 'user_created_email_queue')
    ],

    'TWO_FA_COMPANY_NAME' => 'super-Admin-By-Nish',

    'BACKUP_CODE_FILE_NAME' => 'backup_codes.txt',
    'BACKUP_FILE_CONTENT_TYPE' => 'text/plain',

    'HTTP_CODES' => [
        'SUCCESS' => 200,
        'CREATED' => 201,
        'VALIDATION_ERROR' => 422,
        'UNAUTHORIZED' => 401,
        'BAD_REQUEST' => 400,
        'FORBIDDEN' => 403,
        'INTERNAL_SERVER_ERROR' => 500,
        'METHOD_NOT_ALLOWED' => 405,
        'NOT_FOUND' => 404,
    ],

    'USER_EXPORTED_FILE_PATH' => 'exports',
    'USER_EXPORTED_FILE_TYPE' => 'csv',
];
