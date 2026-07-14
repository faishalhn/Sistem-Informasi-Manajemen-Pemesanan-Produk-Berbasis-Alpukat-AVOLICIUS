<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : functions.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Global Helper Functions
 * VERSION     : 2.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

// ==========================================================
// BASE URL
// ==========================================================

function base_url(string $url = ''): string
{
    return BASE_URL . ltrim($url, '/');
}

// ==========================================================
// REDIRECT
// ==========================================================

function redirect(string $url): void
{
    header('Location: ' . base_url($url));
    exit;
}

// ==========================================================
// SANITASI INPUT
// ==========================================================

function sanitasi(string $data): string
{
    return htmlspecialchars(
        trim($data),
        ENT_QUOTES,
        'UTF-8'
    );
}

// ==========================================================
// FORMAT RUPIAH
// ==========================================================

function rupiah(int|float $nominal): string
{
    return 'Rp ' . number_format(
        $nominal,
        0,
        ',',
        '.'
    );
}

// ==========================================================
// FORMAT TANGGAL INDONESIA
// ==========================================================

function tanggal_indonesia(string $tanggal): string
{
    return date(
        'd-m-Y',
        strtotime($tanggal)
    );
}

// ==========================================================
// UPLOAD PATH
// ==========================================================

function upload_path(string $folder = ''): string
{
    return 'assets/uploads/' . trim($folder, '/') . '/';
}

// ==========================================================
// DEVELOPMENT MODE
// ==========================================================

function is_development(): bool
{
    return APP_ENV === 'development';
}

// ==========================================================
// DEBUG
// ==========================================================

function dd(mixed $data): never
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

// ==========================================================
// LOGIN STATUS
// ==========================================================

function is_admin_login(): bool
{
    return isset($_SESSION[SESSION_ADMIN]);
}

function is_customer_login(): bool
{
    return isset($_SESSION[SESSION_CUSTOMER]);
}

// ==========================================================
// FLASH MESSAGE
// ==========================================================

function set_alert(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_alert(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $alert = $_SESSION['flash'];

    unset($_SESSION['flash']);

    return $alert;
}

// ==========================================================
// REQUEST METHOD
// ==========================================================

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// ==========================================================
// ESCAPE OUTPUT
// ==========================================================

function e(string $string): string
{
    return htmlspecialchars(
        $string,
        ENT_QUOTES,
        'UTF-8'
    );
}

// ==========================================================
// CSRF TOKEN
// ==========================================================

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool
{
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

// ==========================================================
// GENERATE SLUG
// ==========================================================

function generate_slug(string $text): string
{
    $text = strtolower(trim($text));

    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}

// ==========================================================
// GENERATE KODE
// ==========================================================

function generate_kode(
    mysqli $db,
    string $table,
    string $field,
    string $prefix,
    int $digit = 4
): string {

    $sql = "
        SELECT {$field}
        FROM {$table}
        ORDER BY {$field} DESC
        LIMIT 1
    ";

    $result = mysqli_query($db, $sql);

    $nomor = 1;

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        $nomor = (int) substr($row[$field], strlen($prefix)) + 1;
    }

    return $prefix .
        str_pad(
            (string)$nomor,
            $digit,
            '0',
            STR_PAD_LEFT
        );
}

// ==========================================================
// REDIRECT BACK
// ==========================================================

function redirect_back(): never
{
    header(
        'Location: ' .
            ($_SERVER['HTTP_REFERER'] ?? BASE_URL)
    );

    exit;
}

// ==========================================================
// REQUIRED VALIDATION
// ==========================================================

function required(string $value): bool
{
    return trim($value) !== '';
}

// ==========================================================
// IS EMPTY
// ==========================================================

function is_empty(string $value): bool
{
    return trim($value) === '';
}

// ==========================================================
// CHECK EXIST
// ==========================================================

function exists(
    mysqli $db,
    string $table,
    string $field,
    string $value,
    string $excludeField = '',
    int $excludeId = 0
): bool {

    $sql = "
        SELECT COUNT(*)
        FROM {$table}
        WHERE {$field}=?
    ";

    if ($excludeField !== '') {

        $sql .= "
            AND {$excludeField}!=?
        ";
    }

    $stmt = mysqli_prepare($db, $sql);

    if ($excludeField !== '') {

        mysqli_stmt_bind_param(

            $stmt,

            "si",

            $value,

            $excludeId

        );
    } else {

        mysqli_stmt_bind_param(

            $stmt,

            "s",

            $value

        );
    }

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(

        $stmt,

        $jumlah

    );

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    return $jumlah > 0;
}
