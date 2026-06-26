<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <div class="page-title">
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Painel</a>
            <span class="sep">/</span>
            <span class="current">Configurações de Alertas</span>
        </div>
        <h1>Meus Alertas Personalizados 🔔</h1>
    </div>
    <div class="topbar-actions">
        <a href="<?= base_url('alertas/criar') ?>" class="btn btn-p-primary">+ Novo Alerta</a>
    </div>
</div>

<div class="content-p">
    <div class="card-p">
        <div class="card-p-head">
            <h3>Gerenciamento de Monitoramento</h3>
        </div>
        <div class="card-p-body p-0">
            <div class="table-responsive">
                <table class="table-p">
                    <thead>
                        <tr>
                            <th>Termo de Pesquisa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($alertas)): ?>
                            <tr>
                                <td colspan="3" class="text-center p-40">
                                    <div class="v-stack-16 align-items-center">
                                        <div class="big-avatar ki--blue">🔍</div>
                                        <p class="text-muted">Você ainda não configurou nenhum alerta de monitoramento.</p>
                                        <a href="<?= base_url('alertas/criar') ?>" class="btn btn-p-ghost">Criar meu primeiro alerta</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($alertas as $alerta): ?>
                                <tr>
                                    <td>
                                        <div class="fw-700 fs-15 text-dark"><?= esc($alerta['termo'] ?? 'Sem termo') ?></div>
                                        <div class="fs-12 text-muted">Criado em: <?= date('d/m/Y', strtotime($alerta['created_at'])) ?></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   <?= ($alerta['ativo'] ?? true) ? 'checked' : '' ?>
                                                   onchange="toggleAlerta(<?= $alerta['id'] ?>)">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="h-stack-8 justify-content-center">
                                            <a href="<?= base_url('alertas/editar/' . $alerta['id']) ?>" class="btn-icon blue" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button onclick="deleteAlerta(<?= $alerta['id'] ?>)" class="btn-icon pink" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleAlerta(id) {
    fetch('<?= base_url('alertas/toggle/') ?>' + id, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Notificar sucesso (opcional)
            }
        });
}

function deleteAlerta(id) {
    if (confirm('Tem certeza que deseja remover este alerta?')) {
        fetch('<?= base_url('alertas/delete/') ?>' + id, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
    }
}
</script>
<?= $this->endSection() ?>
