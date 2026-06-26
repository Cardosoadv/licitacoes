<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PNCP Licitações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-4 text-center">PNCP Licitações</h3>
                        <p class="text-center mb-4">Sistema de Monitoramento de Licitações Públicas Brasileiras</p>

                        <a href="<?php echo getenv('OAUTH_SERVER_URL'); ?>/login?redirect=<?php echo urlencode(getenv('OAUTH_REDIRECT_URI')); ?>" class="btn btn-primary btn-block w-100">
                            Fazer Login com Manus OAuth
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>