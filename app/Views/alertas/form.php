<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <div class="page-title">
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Painel</a>
            <span class="sep">/</span>
            <a href="<?= base_url('alertas') ?>">Alertas</a>
            <span class="sep">/</span>
            <span class="current"><?= isset($alerta) ? 'Editar Alerta' : 'Novo Alerta' ?></span>
        </div>
        <h1><?= isset($alerta) ? 'Ajustar Configurações ⚙️' : 'Configurar Novo Alerta 🎯' ?></h1>
    </div>
    <div class="topbar-actions">
        <a href="<?= base_url('alertas') ?>" class="btn btn-p-ghost">Cancelar</a>
    </div>
</div>

<div class="content-p">
    <div class="card-p">
        <div class="card-p-head">
            <h3>Parâmetros de Monitoramento</h3>
        </div>
        <div class="card-p-body">
            <form id="alertaForm" onsubmit="saveAlerta(event)">
                <input type="hidden" name="id" value="<?= $alerta['id'] ?? '' ?>">
                
                <div class="anam-section">
                    <h4><i class="bi bi-search"></i> Critérios de Busca</h4>
                    <p class="fs-13 text-muted">Defina palavras-chave que a IA deve monitorar nos novos editais do PNCP.</p>
                </div>

                <div class="form-grid-p">
                    <div class="field-p full-width">
                        <label for="termo">Palavras-chave ou Termos de Interesse</label>
                        <input type="text" id="termo" name="termo" class="form-control-p" 
                               placeholder="Ex: Software jurídico, pavimentação asfáltica, consultoria..."
                               value="<?= esc($alerta['termo'] ?? '') ?>" required>
                    </div>

                    <div class="field-p">
                        <label for="esfera">Esfera de Interesse</label>
                        <select id="esfera" name="esfera" class="form-select-p">
                            <option value="todas" <?= ($alerta['esfera'] ?? '') == 'todas' ? 'selected' : '' ?>>Todas as Esferas</option>
                            <option value="federal" <?= ($alerta['esfera'] ?? '') == 'federal' ? 'selected' : '' ?>>Federal</option>
                            <option value="estadual" <?= ($alerta['esfera'] ?? '') == 'estadual' ? 'selected' : '' ?>>Estadual</option>
                            <option value="municipal" <?= ($alerta['esfera'] ?? '') == 'municipal' ? 'selected' : '' ?>>Municipal</option>
                        </select>
                    </div>

                    <div class="field-p">
                        <label for="valor_minimo">Valor Mínimo (R$)</label>
                        <input type="number" id="valor_minimo" name="valor_minimo" class="form-control-p" 
                               value="<?= esc($alerta['valor_minimo'] ?? '0') ?>">
                    </div>
                </div>

                <hr class="my-30 opacity-10">

                <div class="h-stack-12 justify-content-end">
                    <button type="submit" class="btn btn-p-primary">
                        <?= isset($alerta) ? 'Salvar Alterações' : 'Ativar Monitoramento' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function saveAlerta(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    const id = data.id;
    
    const url = id ? '<?= base_url('alertas/update/') ?>' + id : '<?= base_url('alertas/store') ?>';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = '<?= base_url('alertas') ?>';
        } else {
            alert('Erro ao salvar: ' + data.message);
        }
    });
}
</script>
<?= $this->endSection() ?>
