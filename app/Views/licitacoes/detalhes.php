<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <div class="page-title">
        <div class="breadcrumb">
            <a href="<?= base_url('licitacoes') ?>">Licitações</a>
            <span class="sep">/</span>
            <span class="current">Detalhes do Edital</span>
        </div>
        <h1>Edital #<?= esc($licitacao['id']) ?> 📄</h1>
    </div>
    <div class="topbar-actions">
        <button class="btn btn-p-ghost" onclick="window.print()">🖨️ Imprimir</button>
        <a href="<?= base_url('licitacoes/listar') ?>" class="btn btn-p-primary">← Voltar</a>
    </div>
</div>

<div class="content-p">
    <div class="grid-main">
        <!-- Main Info -->
        <div class="v-stack-16">
            <div class="card-p">
                <div class="card-p-head">
                    <h3>Informações Gerais</h3>
                    <span class="status-pill <?= esc(strtolower($licitacao['situacao'])) ?>">● <?= esc($licitacao['situacao']) ?></span>
                </div>
                <div class="card-p-body">
                    <div class="anam-section">
                        <h4><i class="bi bi-info-circle"></i> Objeto da Licitação</h4>
                        <p class="fs-15 fw-600"><?= esc($licitacao['objeto']) ?></p>
                    </div>

                    <div class="form-grid-p">
                        <div class="field-p">
                            <label>Código PNCP</label>
                            <div class="fw-700"><?= esc($licitacao['codigo_pncp']) ?></div>
                        </div>
                        <div class="field-p">
                            <label>Modalidade</label>
                            <div class="fw-700"><?= esc($licitacao['modalidade']) ?></div>
                        </div>
                        <div class="field-p">
                            <label>Valor Estimado</label>
                            <div class="fw-700 text-success">R$ <?= number_format($licitacao['valor_estimado'], 2, ',', '.') ?></div>
                        </div>
                        <div class="field-p">
                            <label>Data de Publicação</label>
                            <div class="fw-700"><?= date('d/m/Y H:i', strtotime($licitacao['data_publicacao'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-p">
                <div class="card-p-head">
                    <h3>Órgão Comprador</h3>
                </div>
                <div class="card-p-body">
                    <div class="item-cell">
                        <div class="big-avatar ki--pink">🏢</div>
                        <div class="item-info">
                            <h2><?= esc($licitacao['orgao_nome'] ?? 'Não informado') ?></h2>
                            <div class="meta">
                                <div class="meta-item"><span>📍</span> Esfera Pública</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: AI Insights -->
        <div class="v-stack-16">
            <div class="card-p pink-bg text-white">
                <div class="card-p-head border-0">
                    <h3 class="text-white">✨ Insights da IA</h3>
                </div>
                <div class="card-p-body">
                    <div class="kpi pink m-0 p-14 mb-18 border-0 bg-white shadow-sm">
                        <div class="kpi-label">Score de Oportunidade</div>
                        <div class="kpi-value text-pink"><?= $licitacao['oportunidade_score'] ?? '0' ?>/10</div>
                        <div class="progress-bar mt-5">
                            <div class="progress-fill bc-pink-grad" style="width: <?= ($licitacao['oportunidade_score'] ?? 0) * 10 ?>%"></div>
                        </div>
                    </div>

                    <div class="anam-section">
                        <h4 class="text-white border-white opacity-75">Resumo Executivo</h4>
                        <p class="fs-13"><?= esc($licitacao['resumo'] ?? 'Análise em processamento...') ?></p>
                    </div>

                    <div class="anam-section">
                        <h4 class="text-white border-white opacity-75">Palavras-Chave</h4>
                        <div class="check-group-p">
                            <?php 
                                $tags = json_decode($licitacao['palavras_chave'] ?? '[]', true);
                                foreach ($tags as $tag):
                            ?>
                                <span class="badge-pill bg-white text-pink"><?= esc($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="anam-section">
                        <h4 class="text-white border-white opacity-75">Setor Classificado</h4>
                        <div class="badge-pill bg-white text-pink d-inline-block"><?= esc($licitacao['setor'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
