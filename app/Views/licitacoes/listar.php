<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="topbar">
    <div class="page-title">
        <h1>Explorar Licitações 🏛️</h1>
        <p>Monitoramento em tempo real do PNCP</p>
    </div>
    <div class="topbar-actions">
        <a href="<?= base_url('licitacoes') ?>" class="btn btn-p-ghost">← Voltar Dashboard</a>
        <button class="btn btn-p-primary" onclick="document.getElementById('filterForm').submit()">Aplicar Filtros</button>
    </div>
</div>

<div class="content-p">
    <!-- FILTERS STRIP -->
    <div class="card-p mb-18">
        <div class="card-p-body p-14">
            <form id="filterForm" method="GET" action="<?= base_url('licitacoes/listar') ?>">
                <div class="search-bar">
                    <div class="si">
                        <span>🔍</span>
                        <input type="text" name="termo" value="<?= esc($filters['termo'] ?? '') ?>" placeholder="Buscar por objeto ou órgão..." aria-label="Buscar" title="Buscar">
                    </div>
                    <select name="modalidade" class="fsel">
                        <option value="">Modalidade</option>
                        <option value="Pregão" <?= ($filters['modalidade'] ?? '') == 'Pregão' ? 'selected' : '' ?>>Pregão</option>
                        <option value="Concorrência" <?= ($filters['modalidade'] ?? '') == 'Concorrência' ? 'selected' : '' ?>>Concorrência</option>
                        <option value="Dispensa" <?= ($filters['modalidade'] ?? '') == 'Dispensa' ? 'selected' : '' ?>>Dispensa</option>
                    </select>
                    <select name="situacao" class="fsel">
                        <option value="">Situação</option>
                        <option value="Aberta" <?= ($filters['situacao'] ?? '') == 'Aberta' ? 'selected' : '' ?>>Aberta</option>
                        <option value="Encerrada" <?= ($filters['situacao'] ?? '') == 'Encerrada' ? 'selected' : '' ?>>Encerrada</option>
                    </select>
                    <div class="filter-date">
                        <input type="date" name="data_inicio" value="<?= esc($filters['data_inicio'] ?? '') ?>" class="fsel">
                        <span>até</span>
                        <input type="date" name="data_fim" value="<?= esc($filters['data_fim'] ?? '') ?>" class="fsel">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="card-p anim">
        <div class="card-p-body p-0">
            <div class="table-responsive">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Objeto / Órgão</th>
                            <th>Modalidade</th>
                            <th>Valor Estimado</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($licitacoes)): ?>
                            <tr>
                                <td colspan="5" class="tbl-empty-msg">Nenhuma licitação encontrada com os filtros atuais.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($licitacoes as $lic): ?>
                                <tr onclick="window.location='<?= base_url('licitacoes/detalhes/' . $lic['id']) ?>'">
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-avatar ki--pink">🏛️</div>
                                            <div>
                                                <div class="item-name"><?= esc(mb_strimwidth($lic['objeto'], 0, 60, "...")) ?></div>
                                                <div class="item-meta"><?= esc($lic['codigo_pncp']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="service-tag"><?= esc($lic['modalidade']) ?></span></td>
                                    <td><strong class="valor-pos">R$ <?= number_format($lic['valor_estimado'], 2, ',', '.') ?></strong></td>
                                    <td><span class="status-pill <?= esc(strtolower($lic['situacao'])) ?>">● <?= esc($lic['situacao']) ?></span></td>
                                    <td>
                                        <div class="tbl-actions">
                                            <a href="<?= base_url('licitacoes/detalhes/' . $lic['id']) ?>" class="btn-icon bc" title="Ver Detalhes">👁️</a>
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

    <!-- PAGINATION -->
    <?php if (isset($pager)): ?>
        <div class="mt-15">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>