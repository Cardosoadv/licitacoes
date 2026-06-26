<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PNCP Licitações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">PNCP Licitações</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/licitacoes">Licitações</a>
                <a class="nav-link" href="/alertas">Alertas</a>
                <a class="nav-link" href="/notificacoes">Notificações</a>
                <a class="nav-link" href="/auth/logout">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard</h1>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 text-white">Licitações</h5>
                            <i class="bi bi-stack fs-4 text-white-50"></i>
                        </div>
                        <p class="card-text text-white-50 small">Gerencie as licitações monitoradas pelo seu perfil.</p>
                        <a href="<?= base_url('licitacoes') ?>" class="btn btn-light btn-sm w-100 rounded-pill mt-3 fw-bold">Ver Tudo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 text-white">PNCP</h5>
                            <i class="bi bi-cloud-download fs-4 text-white-50"></i>
                        </div>
                        <p class="card-text text-white-50 small">Acesse diretamente o portal de dados do Governo Federal.</p>
                        <a href="https://pncp.gov.br" target="_blank" class="btn btn-light btn-sm w-100 rounded-pill mt-3 fw-bold">Portal PNCP</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #212529;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Alertas</h5>
                            <i class="bi bi-bell-fill fs-4 opacity-50"></i>
                        </div>
                        <p class="card-text opacity-75 small">Configure termos de interesse e notificações em tempo real.</p>
                        <a href="<?= base_url('alertas') ?>" class="btn btn-dark btn-sm w-100 rounded-pill mt-3 fw-bold text-white">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0dcaf0 0%, #0bacce 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 text-white">BI & Stats</h5>
                            <i class="bi bi-graph-up-arrow fs-4 text-white-50"></i>
                        </div>
                        <p class="card-text text-white-50 small">Análises detalhadas de órgãos e modalidades.</p>
                        <a href="<?= base_url('estatisticas') ?>" class="btn btn-light btn-sm w-100 rounded-pill mt-3 fw-bold">Estatísticas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>