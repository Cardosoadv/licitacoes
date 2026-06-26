<aside class="sidebar" id="sidebar">
  <!-- Mobile Header -->
  <div class="sidebar-mobile-header">
    <div class="logo">
      <div class="logo-icon">🏛️</div>
      <div class="logo-text">
        <strong>Gestor de Licitações</strong>
        <span>PNCP & Monitoramento</span>
      </div>
    </div>
    <button class="close-sidebar" id="closeSidebar" title="Fechar Lateral" aria-label="Fechar Menu Lateral">✕</button>
  </div>

  <div class="logo hidden-mobile">
    <div class="logo-img"><img src="<?= base_url('public/dist/imgs/logo.jpg') ?>" alt="Logo"></div>

  </div>

  <nav class="nav">
    <div class="nav-section">Principal</div>
    <a class="nav-item" href="<?= base_url('/') ?>">
      <span class="icon">🏠</span> Dashboard
    </a>
    <a class="nav-item <?= (current_url() == base_url('agendamentos') || str_contains(current_url(), 'agendamentos')) ? 'active' : '' ?>" href="<?= base_url('agendamentos') ?>">
      <span class="icon">📅</span> Agendamentos
    </a>
    <a class="nav-item <?= (current_url() == base_url('licitacoes') || str_contains(current_url(), 'licitacoes')) ? 'active' : '' ?>" href="<?= base_url('licitacoes') ?>">
      <span class="icon">🏛️</span> Licitações
    </a>
    <a class="nav-item <?= (current_url() == base_url('prontuarios') || str_contains(current_url(), 'prontuarios')) ? 'active' : '' ?>" href="<?= base_url('prontuarios') ?>">
      <span class="icon">📋</span> Dossiês
    </a>

    <div class="nav-section">Financeiro</div>
    <a class="nav-item <?= (current_url() == base_url('faturamento') || str_contains(current_url(), 'faturamento')) ? 'active' : '' ?>" href="<?= base_url('faturamento') ?>">
      <span class="icon">💰</span> Faturamento
    </a>
    <a class="nav-item <?= (str_contains(current_url(), 'relatorios')) ? 'active' : '' ?>" href="<?= base_url('relatorios') ?>">
      <span class="icon">📊</span> Relatórios
    </a>
    <a class="nav-item <?= (current_url() == base_url('pacotes') || str_contains(current_url(), 'pacotes')) ? 'active' : '' ?>" href="<?= base_url('pacotes') ?>">
      <span class="icon">📦</span> Pacotes
    </a>

    <div class="nav-section">Clínica</div>
    <a class="nav-item <?= (current_url() == base_url('servicos')) ? 'active' : '' ?>" href="<?= base_url('servicos') ?>">
      <span class="icon">🛠️</span> Serviços
    </a>
    <a class="nav-item <?= (current_url() == base_url('equipe')) ? 'active' : '' ?>" href="<?= base_url('equipe') ?>">
      <span class="icon">👩‍⚕️</span> Equipe
    </a>
    <a class="nav-item <?= (current_url() == base_url('estoque')) ? 'active' : '' ?>" href="<?= base_url('estoque') ?>">
      <span class="icon">📦</span> Estoque
    </a>
    <?php
        $submenuConfigAtivo = str_contains(current_url(), 'lojas')
            || str_contains(current_url(), 'importar-clientes')
            || str_contains(current_url(), 'importar-dados')
            || str_contains(current_url(), 'importar-receitas')
            || str_contains(current_url(), 'importar-despesas');
    ?>
    <div class="nav-item-wrapper <?= $submenuConfigAtivo ? 'active' : '' ?>">
      <a class="nav-item <?= $submenuConfigAtivo ? 'active' : '' ?>" href="#" onclick="toggleSubmenu(event, 'submenu-config')">
        <span class="icon">⚙️</span> Configurações
      </a>
      <div class="nav-submenu" id="submenu-config" style="<?= $submenuConfigAtivo ? 'display: block;' : 'display: none;' ?>">
        <a class="nav-subitem <?= (current_url() == base_url('lojas')) ? 'active' : '' ?>" href="<?= base_url('lojas') ?>">
          <span class="icon">🏪</span> Lojas
        </a>
        <a class="nav-subitem <?= (current_url() == base_url('importar-clientes')) ? 'active' : '' ?>" href="<?= base_url('importar-clientes') ?>">
          <span class="icon">📥</span> Importar Clientes
        </a>
        <a class="nav-subitem <?= (current_url() == base_url('importar-dados')) ? 'active' : '' ?>" href="<?= base_url('importar-dados') ?>">
          <span class="icon">📥</span> Importar Dados
        </a>
        <a class="nav-subitem <?= (current_url() == base_url('importar-receitas')) ? 'active' : '' ?>" href="<?= base_url('importar-receitas') ?>">
          <span class="icon">📊</span> Importar Receitas
        </a>
        <a class="nav-subitem <?= (current_url() == base_url('importar-despesas')) ? 'active' : '' ?>" href="<?= base_url('importar-despesas') ?>">
          <span class="icon">💸</span> Importar Despesas
        </a>
      </div>
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="user-card" id="userCard" onclick="toggleUserDropdown(event)">
      <div class="avatar">🏛️</div>
      <div class="user-info">
        <strong></strong>
      </div>
      <div class="user-dropdown-icon">▾</div>
      
      <!-- Dropdown Menu -->
      <div class="user-dropdown" id="userDropdown">
        <a href="javascript:void(0)" onclick="openModal('alterar-senha')">
          <span class="icon">🔐</span> Alterar Senha
        </a>
        <a href="<?= base_url('logout') ?>" class="logout-link">
          <span class="icon">🚪</span> Logout
        </a>
      </div>
    </div>
  </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Modal Alterar Senha -->
<?= view('componentes/modal_alterar_senha') ?>