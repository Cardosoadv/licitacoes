<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <button class="menu-toggle" id="menuToggle" aria-label="Abrir Menu" title="Abrir Menu">☰</button>
    <div class="page-title">
        <h1>Dashboard de Licitações 🏛️</h1>
        <p>Visão estratégica das licitações do PNCP</p>
    </div>
    <div class="topbar-actions">
        <div class="search-box">
            <span>🔍</span>
            <input type="text" placeholder="Pesquisar editais..." aria-label="Buscar" title="Buscar">
        </div>
        <a href="<?= base_url('licitacoes/listar') ?>" class="btn btn-green">+ Ver Todas</a>
    </div>
</div>

<div class="content">
    <!-- KPIs -->
    <div class="kpis">
        <div class="kpi pink" title="Total de licitações monitoradas">
            <div class="kpi-icon">📜</div>
            <div class="kpi-value"><?= number_format($stats['gerais']['total'] ?? 0, 0, ',', '.') ?></div>
            <div class="kpi-label">Total Licitações</div>
            <div class="kpi-delta up">↑ Base PNCP</div>
        </div>
        <div class="kpi mint" title="Valor estimado total das licitações">
            <div class="kpi-icon">💰</div>
            <div class="kpi-value">R$ <?= number_format(($stats['gerais']['valor_total'] ?? 0) / 1000000, 1, ',', '.') ?>M</div>
            <div class="kpi-label">Valor Estimado</div>
            <div class="kpi-delta up">↑ Oportunidades</div>
        </div>
        <div class="kpi yellow" title="Órgãos públicos ativos">
            <div class="kpi-icon">🏢</div>
            <div class="kpi-value"><?= count($stats['orgaos'] ?? []) ?></div>
            <div class="kpi-label">Órgãos Ativos</div>
            <div class="kpi-delta up">↑ Top 5</div>
        </div>
        <div class="kpi purple" title="Insights gerados por IA">
            <div class="kpi-icon">🧠</div>
            <div class="kpi-value">Auto</div>
            <div class="kpi-label">Análise Preditiva</div>
            <div class="kpi-delta up">✨ Ativa</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid2">
        <!-- Situação Distribution -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Distribuição por Situação</h2>
                    <p>Status atual dos editais</p>
                </div>
            </div>
            <div class="card-body">
                <div class="services-list">
                    <?php 
                    $totalSituacao = array_sum(array_column($stats['situacao'], 'quantidade'));
                    foreach ($stats['situacao'] as $i => $sit): 
                        $pct = $totalSituacao > 0 ? ($sit['quantidade'] / $totalSituacao) * 100 : 0;
                        $colors = ['pink', 'mint', 'purple', 'yellow', 'blue'];
                        $colorClass = 'bc-' . $colors[$i % count($colors)] . '-grad';
                    ?>
                        <div class="service-row">
                            <div class="service-row-top">
                                <span>● <?= esc($sit['situacao']) ?></span>
                                <span><?= $sit['quantidade'] ?></span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill <?= $colorClass ?>" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Órgãos mais Ativos -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Órgãos mais Ativos</h2>
                    <p>Top órgãos com mais editais</p>
                </div>
            </div>
            <div class="card-body">
                <div class="patients-list">
                    <?php foreach ($stats['orgaos'] as $orgao): ?>
                        <div class="patient-row">
                            <div class="patient-avatar ki--pink">🏢</div>
                            <div class="patient-meta">
                                <strong><?= esc($orgao['nome']) ?></strong>
                                <span>Entidade Pública</span>
                            </div>
                            <div class="patient-last">
                                <strong><?= $orgao['quantidade'] ?></strong>
                                <span>Editais</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="<?= base_url('licitacoes/listar') ?>" class="qa-btn pink">
            <span class="qa-icon">🔍</span>
            Ver Editais
        </a>
        <a href="<?= base_url('licitacoes/detalhes') ?>" class="qa-btn mint">
            <span class="qa-icon">📄</span>
            Última Análise
        </a>
        <a href="#" class="qa-btn yellow">
            <span class="qa-icon">📊</span>
            Relatório Legal
        </a>
        <a href="#" class="qa-btn purple">
            <span class="qa-icon">⚙️</span>
            Configurar Alertas
        </a>
    </div>
</div>

<?= $this->endSection() ?>
