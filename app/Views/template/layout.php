<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Licitações — Painel</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="base-url" content="<?= base_url() ?>">
    <link rel="stylesheet" href="<?= base_url('public/dist/css/style-min.css'); ?>">

    <!-- Scripts fundamentais (defer para não bloquear a renderização) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>

<!-- SIDEBAR -->
<?= $this->include('componentes/sidebar') ?>

<!-- MAIN -->
<main class="main">
<?= $this->renderSection('content') ?>

</main>

<!-- MODAIS -->
<?= $this->renderSection('modais') ?>

<script src="<?= base_url('public/dist/js/script.js?v=' . time()) ?>"></script>
<?= $this->renderSection('scripts') ?>

</body>
</html>