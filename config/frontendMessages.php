<?php

return [

    'ERROR' => 'error',

    'SUCCESS' => 'success',

    'EXCEPTION_MESSAGES' => [
        'SHOW_2FA_FORM' => 'something is wrong, while showing 2FA form!',
        'INSECURE_CALL_MESSAGE' => 'Something is wrong, while getting code from google API!',
        'MODEL_NOT_FOUND_MESSAGE' => 'Model is not found for inserting record!',
        'INVALID_SECRET_CODE' => 'Invalid Verification Code, Please try again !',
        'PASSWORD_INVALID' => 'Password does not match, please try again!',

        'BACKUP_CODES_ERROR' => 'Some thing is wrong while generating backup codes!',
        'BACKUP_CODES_DOWNLOAD_ERROR' => 'Some thing is wrong while downloading backup codes!',

        'DELETE_USER_MESSAGE' => 'You can not delete your self !',
        'UPDATE_USER_MESSAGE' => 'Some thing is wrong user is not updated!',
        'FIND_USER_MESSAGE' => 'Some thing is wrong user is not found!',
        'CREATE_USER_MESSAGE' => 'Some thing is wrong user is not created!',
    ],

    'SUCCESS_MESSAGES' => [
        'SECRET_KEY_GENERATED' => 'Secret Key is generated, Please verify Code to Enable 2FA !',
        '2FA_ENABLED' => 'Google authenticator synced successfully.',
        '2FA_DISABLED' => '2FA is now Disabled, for your account!',

        'BACKUP_CODES_GENERATED' => 'Backup codes are generated successfully, you can download it!',

        'USER_CREATED' => 'User has been added!',
        'USER_UPDATED' => 'User has been updated!',
        'USER_DELETED' => 'User has been deleted/deactivated !',

        'USERS_EXPORTED' => 'We have large number of data, You will get an email with download link in 15 mins,
            Once document will be prepared !!'
    ],
];
