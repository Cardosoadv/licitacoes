<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <div class="page-title">
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Painel</a>
            <span class="sep">/</span>
            <span class="current">Notificações</span>
        </div>
        <h1>Centro de Notificações 🔔</h1>
    </div>
    <div class="topbar-actions">
        <button class="btn btn-p-ghost" onclick="marcarTodasComoLidas()">Marcar todas como lidas</button>
    </div>
</div>

<div class="content-p">
    <div class="v-stack-16">
        <?php if (empty($notificacoes)): ?>
            <div class="card-p">
                <div class="card-p-body text-center p-40">
                    <div class="big-avatar ki--green">📩</div>
                    <p class="text-muted mt-10">Você não possui notificações pendentes no momento.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($notificacoes as $notificacao): ?>
                <div class="card-p <?= ($notificacao['lida'] ?? false) ? 'opacity-75' : 'border-start-4 border-primary shadow-sm' ?>" 
                     id="notif-<?= $notificacao['id'] ?>">
                    <div class="card-p-body d-flex align-items-center">
                        <div class="big-avatar ki--blue me-16">
                            <?= str_contains(strtolower($notificacao['titulo'] ?? ''), 'alerta') ? '🎯' : '📢' ?>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-5"><?= esc($notificacao['titulo'] ?? 'Notificação') ?></h4>
                            <p class="text-muted fs-14 mb-0"><?= esc($notificacao['mensagem'] ?? '') ?></p>
                            <div class="meta mt-5">
                                <span class="meta-item"><span>🕒</span> <?= date('d/m/Y H:i', strtotime($notificacao['created_at'])) ?></span>
                            </div>
                        </div>
                        <div class="h-stack-8">
                            <?php if (!($notificacao['lida'] ?? false)): ?>
                                <button onclick="marcarLida(<?= $notificacao['id'] ?>)" class="btn btn-p-primary btn-sm">Marcar como lida</button>
                            <?php endif; ?>
                            <?php if (isset($notificacao['licitacao_id'])): ?>
                                <a href="<?= base_url('licitacoes/detalhes/' . $notificacao['licitacao_id']) ?>" class="btn btn-p-ghost btn-sm">Ver Licitação</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function marcarLida(id) {
    fetch('<?= base_url('notificacoes/marcarLida/') ?>' + id, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const card = document.getElementById('notif-' + id);
                card.classList.add('opacity-75');
                card.classList.remove('border-start-4', 'border-primary', 'shadow-sm');
                // Remover botão
                card.querySelector('button')?.remove();
            }
        });
}

function marcarTodasComoLidas() {
    fetch('<?= base_url('notificacoes/marcarTodasLidas') ?>', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
}
</script>
<?= $this->endSection() ?>
