<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> — Licitações</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('public/dist/css/style-min.css'); ?>">

    <style>
        :root {
            --auth-bg: #f2faf8; /* var(--bg) */
        }
        body {
            background-color: var(--auth-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(244, 117, 110, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(62, 207, 184, 0.05) 0px, transparent 50%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: "Nunito", sans-serif;
        }
        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo img {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            margin-bottom: 15px;
        }
        .auth-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }
        .auth-logo span {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
        }
        .card-auth {
            background: white;
            border-radius: 24px;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-md);
            padding: 35px;
            position: relative;
            overflow: hidden;
        }
        .card-auth::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--pink), var(--purple));
        }
        .auth-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 25px;
            text-align: center;
        }
        /* Custom Input Styling */
        .form-group-custom {
            margin-bottom: 20px;
        }
        .form-group-custom label {
            display: block;
            font-size: 13px;
            font-weight: 800;
            color: var(--mid);
            margin-bottom: 8px;
            margin-left: 4px;
        }
        .input-custom {
            width: 100%;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            transition: all 0.2s;
            outline: none;
        }
        .input-custom:focus {
            border-color: var(--pink);
            background: white;
            box-shadow: 0 0 0 4px var(--pink-light);
        }
        .form-check-input:checked {
            background-color: var(--pink);
            border-color: var(--pink);
        }
        .btn-auth {
            width: 100%;
            background: linear-gradient(135deg, var(--pink), var(--purple));
            color: white;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-size: 15px;
            font-weight: 900;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(244, 117, 110, 0.25);
            transition: all 0.2s;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(244, 117, 110, 0.35);
            color: white;
        }
        .auth-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: var(--muted);
            font-weight: 700;
        }
        .auth-footer a {
            color: var(--pink);
            text-decoration: none;
            font-weight: 800;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert-custom {
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
            border: none;
        }
        .alert-danger-custom {
            background: var(--red-light);
            color: var(--red-d);
        }
        .alert-success-custom {
            background: var(--mint-light);
            color: var(--mint-d);
        }
    </style>

    <?= $this->renderSection('pageStyles') ?>
</head>

<body>

    <div class="auth-container">
        <div class="auth-logo">
            <img src="<?= base_url('public/dist/imgs/logo.jpg') ?>" alt="Logo">
            <h1>Licitações</h1>
            <span>Inteligência em Licitações</span>
        </div>

        <div class="card-auth">
            <?= $this->renderSection('main') ?>
        </div>

        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> Gestor de Licitações — Todos os direitos reservados.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('pageScripts') ?>
</body>
</html>
