<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : process.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Process Website Settings
 * VERSION     : 1.0.0
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('index.php');
}

// ==========================================================
// UPDATE FUNCTION
// ==========================================================

function update_setting(
    mysqli $db,
    string $key,
    string $value
): void {

    $sql = "

    UPDATE setting

    SET

        setting_value = ?,

        updated_at = NOW()

    WHERE

        setting_key = ?

    ";

    $stmt = mysqli_prepare(
        $db,
        $sql
    );

    if ($stmt) {

        mysqli_stmt_bind_param(

            $stmt,

            "ss",

            $value,

            $key

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }
}
// ==========================================================
// GENERAL
// ==========================================================

update_setting(
    $db,
    'website_name',
    trim($_POST['website_name'] ?? '')
);

update_setting(
    $db,
    'tagline',
    trim($_POST['tagline'] ?? '')
);

update_setting(
    $db,
    'email',
    trim($_POST['email'] ?? '')
);

update_setting(
    $db,
    'phone',
    trim($_POST['phone'] ?? '')
);

update_setting(
    $db,
    'address',
    trim($_POST['address'] ?? '')
);

// ==========================================================
// SOCIAL
// ==========================================================

update_setting(
    $db,
    'facebook',
    trim($_POST['facebook'] ?? '')
);

update_setting(
    $db,
    'instagram',
    trim($_POST['instagram'] ?? '')
);

update_setting(
    $db,
    'whatsapp',
    trim($_POST['whatsapp'] ?? '')
);
// ==========================================================
// LOGO
// ==========================================================

if (

    isset($_FILES['logo'])

    &&

    $_FILES['logo']['error'] === UPLOAD_ERR_OK

) {

    $ext = strtolower(

        pathinfo(

            $_FILES['logo']['name'],

            PATHINFO_EXTENSION

        )

    );

    $filename =

        'logo.' .

        $ext;

    move_uploaded_file(

        $_FILES['logo']['tmp_name'],

        '../../assets/images/' . $filename

    );

    update_setting(

        $db,

        'logo',

        $filename

    );
}
// ==========================================================
// FAVICON
// ==========================================================

if (

    isset($_FILES['favicon'])

    &&

    $_FILES['favicon']['error'] === UPLOAD_ERR_OK

) {

    $ext = strtolower(

        pathinfo(

            $_FILES['favicon']['name'],

            PATHINFO_EXTENSION

        )

    );

    $filename =

        'favicon.' .

        $ext;

    move_uploaded_file(

        $_FILES['favicon']['tmp_name'],

        '../../assets/images/' . $filename

    );

    update_setting(

        $db,

        'favicon',

        $filename

    );
}
// ==========================================================
// REDIRECT
// ==========================================================

$_SESSION['success'] = 'Pengaturan berhasil diperbarui.';

header('Location: index.php');

exit;
