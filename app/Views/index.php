<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

  <!-- TOPBAR -->
  <div class="topbar">
    <button class="menu-toggle" id="menuToggle" aria-label="Abrir Menu" title="Abrir Menu">☰</button>
    <div class="page-title">
      <h1>Bom dia, <?= esc(formatUsernameWithHonorific(auth()->user()->id)) ?> 🌸</h1>
      <p>Quinta-feira, <?= date('d/m/Y') ?> · <?= $cidade ?></p>
    </div>
    <div class="topbar-actions">
      <div class="search-box">
        <span>🔍</span>
        <input type="text" placeholder="Buscar licitação..." aria-label="Buscar licitação" title="Buscar licitação">
      </div>
      <a href="<?= base_url('agendamentos') ?>" class="btn btn-ghost" aria-label="Minha Agenda" title="Minha Agenda">📅</a>
      <a href="<?= base_url('agendamentos/novo') ?>" class="btn btn-green" title="Novo Agendamento">+ Novo Agendamento</a>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">

    <!-- KPIs -->
    <div class="kpis">
      <div class="kpi pink" title="Consultas agendadas para hoje">
        <div class="kpi-icon">📅</div>
        <div class="kpi-value"><?= $kpis['hoje'] ?></div>
        <div class="kpi-label">Processos Ativos</div>
        <div class="kpi-delta up">↑ <?= $kpis['hoje'] > 0 ? "Monitoramento ativo" : "Nenhum ainda" ?></div>
      </div>
      <div class="kpi mint" title="Total de licitações ativas no sistema">
        <div class="kpi-icon">📊</div>
        <div class="kpi-value"><?= $kpis['ativos'] ?></div>
        <div class="kpi-label">Licitações Ativas</div>
        <div class="kpi-delta up">↑ Base de dados</div>
      </div>
      <div class="kpi yellow" title="Faturamento total acumulado no mês">
        <div class="kpi-icon">💰</div>
        <div class="kpi-value">R$<?= number_format($kpis['receita'] / 1000, 1) ?>k</div>
        <div class="kpi-label">Receita do Mês</div>
        <div class="kpi-delta up">↑ Total faturado</div>
      </div>
      <div class="kpi purple" title="Agendamentos aguardando resposta">
        <div class="kpi-icon">⏳</div>
        <div class="kpi-value"><?= $kpis['pendentes'] ?></div>
        <div class="kpi-label">Pendentes Confirmação</div>
        <div class="kpi-delta <?= $kpis['pendentes'] > 0 ? 'down' : 'up' ?>"><?= $kpis['pendentes'] > 0 ? '↓ responder hoje' : '↑ tudo em dia' ?></div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="quick-actions">
      <a href="<?= base_url('licitacoes/novo') ?>" class="qa-btn pink">
        <span class="qa-icon">🏛️</span>
        Nova Licitação
      </a>
      <a href="<?= base_url('prontuarios') ?>" class="qa-btn mint">
        <span class="qa-icon">📋</span>
        Processo
      </a>
      <a href="<?= base_url('licitacoes') ?>" class="qa-btn yellow">
        <span class="qa-icon">💬</span>
        Enviar Lembrete
      </a>
      <a href="<?= base_url('faturamento') ?>" class="qa-btn purple">
        <span class="qa-icon">📑</span>
        Emitir Recibo
      </a>
    </div>

    <!-- ROW: APPOINTMENTS + SCHEDULE -->
    <div class="grid3">

      <!-- APPOINTMENTS TABLE -->
      <div class="card">
        <div class="card-header">
          <div>
            <h2>Alertas Recentes</h2>
            <p>Agendamentos dos próximos 7 dias</p>
          </div>
          <a href="<?= base_url('agendamentos') ?>" class="view-all" title="Ver todos os agendamentos">Ver todas →</a>
        </div>
        <div class="card-body pt-10">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Objeto</th>
                  <th>Data / Hora</th>
                  <th>Serviço</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($upcoming)): ?>
                  <tr><td colspan="4" class="tbl-empty-msg p-40">Nenhum agendamento para os próximos dias.</td></tr>
                <?php else: ?>
                  <?php foreach ($upcoming as $age): 
                    $isPath = strpos($age['pet_avatar'] ?? '', '/') !== false || strpos($age['pet_avatar'] ?? '', '\\') !== false;
                  ?>
                    <tr>
                      <td>
                        <div class="item-cell">
                          <div class="item-avatar <?= $isPath ? '' : 'ki--pink' ?>">
                            <?php if ($isPath): ?>
                                <img src="<?= base_url(esc($age['pet_avatar'])) ?>" class="img-avatar">
                            <?php else: ?>
                                <?= esc($age['pet_avatar'] ?: "🏛️") ?>
                            <?php endif; ?>
                          </div>
                          <div>
                            <div class="item-name"><?= esc($age['pet_nome']) ?></div>
                            <div class="item-meta">Monitoramento</div>
                          </div>
                        </div>
                      </td>
                      <td><?= date('d/m', strtotime($age['age_data'])) ?>, <?= substr($age['age_hora'], 0, 5) ?></td>
                      <td><span class="service-tag"><?= esc($age['age_servico']) ?></span></td>
                      <td><span class="status-pill <?= esc(strtolower($age['age_status'])) ?>">● <?= esc(ucfirst($age['age_status'])) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TODAY SCHEDULE -->
      <div class="card">
        <div class="card-header">
          <div>
            <h2>Hoje</h2>
            <p><?= count($hoje ?? []) ?> consultas programadas</p>
          </div>
          <span class="fs-12 fw-800 text-muted"><?= date('d/m') ?></span>
        </div>
        <div class="card-body">
          <div class="schedule-list">
            <?php if (empty($hoje)): ?>
              <div class="text-center p-30 text-muted fs-13">Nenhuma consulta para hoje.</div>
            <?php else: ?>
              <?php 
                $colors = ['pink', 'mint', 'yellow', 'purple', 'blue'];
                foreach ($hoje as $i => $item): 
                  $color = $colors[$i % count($colors)];
                  $emoji = $item['age_status'] == 'Confirmado' ? '✅' : ($item['age_status'] == 'Concluído' ? '✨' : '⏳');
              ?>
                <div class="schedule-item <?= esc($color) ?>">
                  <div class="sched-time"><?= substr($item['age_hora'], 0, 5) ?></div>
                  <div class="sched-info"><strong><?= esc($item['pet_nome']) ?></strong><span><?= esc($item['age_servico']) ?></span></div>
                  <div class="sched-emoji"><?= $emoji ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ROW: SERVICES + CALENDAR + PATIENTS -->
    <div class="grid2">

      <!-- SERVICES STATS -->
      <div class="card">
        <div class="card-header">
          <div>
            <h2>Serviços Realizados</h2>
            <p><?= date('F Y') ?></p>
          </div>
          <a href="#" class="view-all">Relatório →</a>
        </div>
        <div class="card-body">
          <div class="services-list">
            <?php if (empty($servicos)): ?>
              <div class="text-center p-30 text-muted fs-13">Ainda sem dados para este mês.</div>
            <?php else: ?>
              <?php 
                $maxItems = 5;
                $count = 0;
                $colors = ['var(--pink)', 'var(--mint)', 'var(--purple)', 'var(--yellow)', 'var(--blue)'];
                $colors = ['pink', 'mint', 'purple', 'yellow', 'blue'];
                $topCount = $servicos[0]['count'] ?? 1;
                
                foreach ($servicos as $i => $srv): 
                  if (++$count > $maxItems) break;
                  $pct = min(100, round(($srv['count'] / $topCount) * 100));
                  $colorClass = 'bc-' . $colors[$i % count($colors)] . '-grad';
              ?>
                <div class="service-row">
                  <div class="service-row-top"><span>🦷 <?= esc($srv['service']) ?></span><span><?= (int) $srv['count'] ?> consultas</span></div>
                  <div class="progress-bar"><div class="progress-fill <?= esc($colorClass) ?>" style="width:<?= (float) $pct ?>%"></div></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- RIGHT: CALENDAR + PATIENTS -->
      <div class="flex-col gap-20">

        <!-- MINI CALENDAR -->
        <div class="card">
          <div class="mini-cal">
            <div class="cal-header">
              <h3 id="mini-cal-month">Carregando...</h3>
              <div class="cal-nav">
                <button type="button" onclick="changeMiniMonth(-1)" aria-label="Mês Anterior" title="Mês Anterior">‹</button>
                <button type="button" onclick="changeMiniMonth(1)" aria-label="Próximo Mês" title="Próximo Mês">›</button>
              </div>
            </div>
            <div class="cal-grid" id="mini-cal-grid">
              <div class="cal-day-name">DOM</div>
              <div class="cal-day-name">SEG</div>
              <div class="cal-day-name">TER</div>
              <div class="cal-day-name">QUA</div>
              <div class="cal-day-name">QUI</div>
              <div class="cal-day-name">SEX</div>
              <div class="cal-day-name">SÁB</div>
            </div>
          </div>
        </div>

        <!-- RECENT PATIENTS -->
        <div class="card">
          <div class="card-header">
            <div><h2>Licitações Recentes</h2></div>
            <a href="<?= base_url('licitacoes') ?>" class="view-all" title="Ver todas as licitações">Ver todas →</a>
          </div>
          <div class="card-body pt-4">
            <div class="patients-list">
              <?php if (empty($recentes)): ?>
                <div class="text-center p-20 text-muted fs-12">Nenhuma licitação recente.</div>
              <?php else: ?>
                <?php foreach ($recentes as $pat): 
                    $isPath = strpos($pat['pet_avatar'] ?? '', '/') !== false || strpos($pat['pet_avatar'] ?? '', '\\') !== false;
                ?>
                  <div class="item-row">
                    <div class="item-avatar <?= $isPath ? '' : 'ki--pink' ?>">
                      <?php if ($isPath): ?>
                          <img src="<?= base_url(esc($pat['pet_avatar'])) ?>" class="img-avatar">
                      <?php else: ?>
                          <?= esc($pat['pet_avatar'] ?: "🏛️") ?>
                      <?php endif; ?>
                    </div>
                    <div class="item-meta">
                      <strong><?= esc($pat['pet_nome']) ?></strong>
                      <span>Capturado</span>
                    </div>
                    <div class="item-last">
                      <strong><?= date('d/m', strtotime($pat['created_at'])) ?></strong>
                      <span>Data</span>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div><!-- /content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let currentMiniDate = new Date();
  
  async function renderMiniCalendar() {
    const grid = document.getElementById('mini-cal-grid');
    const title = document.getElementById('mini-cal-month');
    if (!grid || !title) return;

    // Remove old day cells, keep headers
    const headers = Array.from(grid.querySelectorAll('.cal-day-name'));
    grid.innerHTML = '';
    headers.forEach(h => grid.appendChild(h));

    const year = currentMiniDate.getFullYear();
    const month = currentMiniDate.getMonth();
    
    // Fetch days with apps
    let daysWithApps = [];
    try {
        const mm = String(month + 1).padStart(2, '0');
        const res = await fetch(`<?= base_url('api/agenda/month-days') ?>?year=${year}&month=${mm}`);
        const data = await res.json();
        daysWithApps = data.map(d => parseInt(d.age_data.split('-')[2], 10));
    } catch(e) {
        console.error('Error fetching agenda days', e);
    }
    
    const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    title.textContent = `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const prevDays = new Date(year, month, 0).getDate();

    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const div = document.createElement('div');
        div.className = 'cal-day other-month';
        div.textContent = prevDays - i;
        grid.appendChild(div);
    }

    // Current month days
    const today = new Date();
    for (let i = 1; i <= daysInMonth; i++) {
        const div = document.createElement('div');
        div.className = 'cal-day';
        if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
            div.classList.add('today');
        }
        if (daysWithApps.includes(i)) {
            div.classList.add('has-appt');
            div.innerHTML = `${i} <span class="cal-status">🏛️</span>`;
        } else {
            div.textContent = i;
        }
        grid.appendChild(div);
    }
  }

  function changeMiniMonth(dir) {
      currentMiniDate.setMonth(currentMiniDate.getMonth() + dir);
      renderMiniCalendar();
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderMiniCalendar();
  });
</script>
<?= $this->endSection() ?>
