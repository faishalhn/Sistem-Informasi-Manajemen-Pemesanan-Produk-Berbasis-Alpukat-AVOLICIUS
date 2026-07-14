<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : header.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Master Header Layout
 * VERSION     : 2.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once __DIR__ . '/../config/init.php';

$page_title = $page_title ?? APP_NAME;

?>
<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        <?= e($page_title); ?>

        |

        <?= APP_NAME; ?>

    </title>

    <meta
        name="description"
        content="Administrator AVOLICIUS">

    <meta
        name="author"
        content="Kelompok AVOLICIUS">

    <link
        rel="icon"
        href="<?= base_url('assets/images/logo/favicon.ico'); ?>">

    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/bootstrap.min.css'); ?>">

    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/bootstrap-icons.css'); ?>">

    <!-- Global -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/avolicius.css'); ?>">

    <!-- Admin -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/admin.css'); ?>">

    <!-- Responsive -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/responsive.css'); ?>">

</head>

<body>