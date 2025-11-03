<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'RentBase - Apartment Management System') ?></title>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.6/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.6/dist/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/franken-ui@1.1.0/dist/css/core.min.css" />
    <script src="https://unpkg.com/franken-ui@1.1.0/dist/js/core.iife.js" defer></script>
    <script src="https://unpkg.com/franken-ui@1.1.0/dist/js/icon.iife.js" defer></script>
    <style>
        :root {
            --uk-background-default: hsl(240 10% 3.9%);
            --uk-background-muted: hsl(240 3.7% 15.9%);
            --uk-background-subtle: hsl(240 5.9% 10%);
            color-scheme: dark;
        }
        body {
            background-color: hsl(240 10% 3.9%);
            color: hsl(0 0% 98%);
            min-height: 100vh;
        }
        .uk-card {
            background: hsl(240 3.7% 15.9%);
            border: 1px solid hsl(240 3.7% 25%);
        }
    </style>
</head>
<body class="uk-background-default">
    <main class="uk-section uk-section-default">
