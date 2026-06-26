
// ─── CORE: NAVIGATION & UI ───────────────────────────────────
function toggleSubmenu(event, id) {
  if (event) event.preventDefault();
  const submenu = document.getElementById(id);
  if (submenu) {
    const isVisible = submenu.style.display === 'block';
    submenu.style.display = isVisible ? 'none' : 'block';
  }
}

document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', e => {
    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
    item.classList.add('active');
  });
});

// User Dropdown Logic
function toggleUserDropdown(event) {
  if (event) event.stopPropagation();
  const card = document.getElementById('userCard');
  if (card) {
    card.classList.toggle('active');
  }
}

// Close Dropdown when clicking outside
document.addEventListener('click', (e) => {
  const card = document.getElementById('userCard');
  if (card && !card.contains(e.target)) {
    card.classList.remove('active');
  }
});

// Sidebar Toggle for Mobile
const menuToggle = document.getElementById('menuToggle');
const closeSidebar = document.getElementById('closeSidebar');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

if (menuToggle && sidebar && sidebarOverlay) {
  const closeMenu = () => {
    sidebar.classList.remove('active');
  };
  menuToggle.addEventListener('click', () => sidebar.classList.add('active'));
  if (closeSidebar) closeSidebar.addEventListener('click', closeMenu);
  sidebarOverlay.addEventListener('click', closeMenu);

  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeMenu();
    });
  });
}

// ─── CORE: MODALS ─────────────────────────────────────────────
function openModal(id) {
  const modal = document.getElementById('modal-' + id);
  if (modal) {
    modal.classList.remove('hide', 'hidden');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(id) {
  const modal = document.getElementById('modal-' + id);
  if (modal) {
    modal.classList.add('hide', 'hidden');
    document.body.style.overflow = '';
  }
}

document.querySelectorAll('.ov, .modal-overlay').forEach(el => {
  el.addEventListener('click', function (e) {
    if (e.target === this) {
      this.classList.add('hide', 'hidden');
      document.body.style.overflow = '';
      
      // Limpar formulário de senha se for o caso
      if (this.id === 'modal-alterar-senha') {
        const form = document.getElementById('form-alterar-senha');
        if (form) form.reset();
        const msg = document.getElementById('msg-erro-senha');
        if (msg) msg.classList.add('hide');
      }
    }
  });
});

async function submitAlterarSenha(event) {
  event.preventDefault();
  const form = event.target;
  const btn = document.getElementById('btn-save-senha');
  const msgErro = document.getElementById('msg-erro-senha');
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';

  msgErro.classList.add('hide');
  
  const formData = new FormData(form);
  if (formData.get('new_password') !== formData.get('new_password_confirm')) {
    msgErro.textContent = 'As novas senhas não coincidem.';
    msgErro.classList.remove('hide');
    return;
  }

  btn.disabled = true;
  const originalText = btn.innerHTML;
  btn.innerHTML = 'Salvando...';

  try {
    const res = await fetch(`${baseUrl}/api/perfil/alterar-senha`, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();

    if (data.status === 'success') {
      toast('Sucesso', data.message, 'success');
      closeModal('alterar-senha');
      form.reset();
    } else {
      msgErro.textContent = data.message || 'Erro ao alterar senha.';
      msgErro.classList.remove('hide');
    }
  } catch (e) {
    msgErro.textContent = 'Erro de conexão com o servidor.';
    msgErro.classList.remove('hide');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

// ─── CORE: NOTIFICATIONS (TOAST) ─────────────────────────────
function toast(title, msg, type = 'success') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const toastEl = document.createElement('div');
  const icon = type === 'success' ? '✅' : (type === 'error' ? '❌' : 'ℹ️');
  toastEl.className = `toast ${type}`;
  toastEl.innerHTML = `
    <div class="toast-icon">${icon}</div>
    <div class="toast-content">
      <div class="toast-title">${title}</div>
      <div class="toast-msg">${msg}</div>
    </div>
  `;
  container.appendChild(toastEl);
  setTimeout(() => {
    toastEl.classList.add('fade-out');
    setTimeout(() => toastEl.remove(), 300);
  }, 4000);
}

// ─── MODULE: MODULE SWITCHING ──────────────────────────────────
let currentModule = 'pets';

function switchModule(mod) {
  document.querySelectorAll('.pn').forEach(p => p.classList.remove('a'));
  const target = document.getElementById('mod-' + mod);
  if (target) target.classList.add('a');
  currentModule = mod;

  document.querySelectorAll('.mt').forEach(t => {
    t.classList.remove('a');
    t.setAttribute('aria-selected', 'false');
  });

  const activeTab = document.getElementById('tab-' + (mod === 'pets' ? 'pet' : 'ag'));
  if (activeTab) {
    activeTab.classList.add('a');
    activeTab.setAttribute('aria-selected', 'true');
  }

  if (mod === 'agenda' && typeof initAgenda === 'function') initAgenda();
}

function showCad() {
  document.querySelectorAll('.pn').forEach(p => p.classList.remove('a'));
  const modCad = document.getElementById('mod-cad');
  if (modCad) modCad.classList.add('a');

  document.querySelectorAll('.mt').forEach(t => {
    t.classList.remove('a');
    t.setAttribute('aria-selected', 'false');
  });

  const tabCad = document.getElementById('tab-cad');
  if (tabCad) {
    tabCad.classList.add('a');
    tabCad.setAttribute('aria-selected', 'true');
  }
  goStep(0);
}

// ─── MODULE: WIZARD ENGINE ─────────────────────────────────────
let currentWizardStep = 0;
const totalWizardSteps = 5;

function renderStep() {
  document.querySelectorAll('.sp').forEach((p, i) => p.classList.toggle('a', i === currentWizardStep));
  document.querySelectorAll('.fw-step').forEach((s, i) => {
    s.classList.toggle('active', i === currentWizardStep);
    s.classList.toggle('done', i < currentWizardStep);
  });
  const stepLbl = document.getElementById('stepLbl');
  if (stepLbl) stepLbl.textContent = `Passo ${currentWizardStep + 1} de ${totalWizardSteps}`;
  
  const btnPrev = document.getElementById('btnPrev');
  if (btnPrev) btnPrev.style.visibility = currentWizardStep === 0 ? 'hidden' : 'visible';
  
  const btnNext = document.getElementById('btnNext');
  if (btnNext) btnNext.style.display = currentWizardStep === totalWizardSteps - 1 ? 'none' : '';

  if (currentWizardStep === 4) updateSummary();
}

function updateSummary() {
  const pNome = document.querySelector('input[name="pet_nome"]')?.value;
  const pEsp = document.querySelector('select[name="pet_especie"]')?.value;
  const tNome = document.querySelector('input[name="pet_resp_nome"]')?.value;
  const pNasc = document.querySelector('input[name="pet_nascimento"]')?.value;

  // Calculate age
  let ageStr = '—';
  if (pNasc) {
    const birth = new Date(pNasc);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    ageStr = age + ' anos';
  }

  const tTel = document.querySelector('input[name="pet_resp_tel"]')?.value;
  const aDate = document.querySelector('input[name="age_data"]')?.value;
  const aTime = document.querySelector('select[name="age_hora"]')?.value;
  const aServ = document.querySelector('select[name="age_servico"]')?.value;
  const aDur = document.querySelector('select[name="age_duracao"]')?.value;

  // Get reminders
  const rems = [];
  document.querySelectorAll('input[name="age_lembrete[]"]:checked').forEach(c => rems.push(c.value));

  const setT = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '—'; };

  setT('sum_nome', pNome);
  setT('sum_idade', ageStr);
  setT('sum_especie', pEsp);
  setT('sum_resp', tNome);
  setT('sum_tel', tTel);
  setT('sum_date', (aDate && aTime) ? `${aDate} às ${aTime}` : 'Não agendado');
  setT('sum_serv', aServ);
  setT('sum_dur', aDur);
  setT('sum_rem', rems.length > 0 ? rems.join(' + ') : 'Nenhum');
}

function nextStep() {
  if (currentWizardStep < totalWizardSteps - 1) {
    currentWizardStep++;
    renderStep();
  }
}

function prevStep() {
  if (currentWizardStep > 0) {
    currentWizardStep--;
    renderStep();
  }
}

function goStep(i) {
  currentWizardStep = i;
  renderStep();
}

function resetWizard() {
  const regForm = document.getElementById('regForm');
  if (regForm) regForm.reset();
  const successScreen = document.getElementById('successScreen');
  if (successScreen) successScreen.style.display = 'none';
  const sp4 = document.getElementById('sp4');
  if (sp4) {
    const fs = sp4.querySelector('.fs');
    if (fs) fs.style.display = 'block';
    const sDiv = sp4.querySelector('div:nth-child(2)');
    if (sDiv) sDiv.style.display = 'block';
  }
  goStep(0);
}

// ─── MODULE: AGENDA ENGINE ─────────────────────────────────────
let agendaDate = new Date();
let currentCalDate = new Date();
let listaServicos = []; 

function initAgenda() {
  renderCalendar();
  loadUpcoming();
  loadAgenda(formatDateIso(agendaDate));
}

function initAgendaPage() {
  renderCalendar();
  loadUpcoming();
  loadAgenda(formatDateIso(agendaDate));
}

function jumpToToday() {
  agendaDate = new Date();
  currentCalDate = new Date();
  renderCalendar();
  loadAgenda(formatDateIso(agendaDate));
}

function formatDateIso(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

async function renderCalendar() {
  const grid = document.getElementById('cal-grid-days');
  const monthYear = document.getElementById('cal-month-year');
  if (!grid || !monthYear) return;

  const headers = Array.from(grid.querySelectorAll('.cal-dn'));
  grid.innerHTML = '';
  headers.forEach(h => grid.appendChild(h));

  const year = currentCalDate.getFullYear();
  const month = currentCalDate.getMonth();
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  
  let daysWithApps = [];
  try {
    const mm = String(month + 1).padStart(2, '0');
    const res = await fetch(`${baseUrl}/api/agenda/month-days?year=${year}&month=${mm}`);
    const data = await res.json();
    daysWithApps = data.map(d => parseInt(d.age_data.split('-')[2], 10));
  } catch(e) { console.error('Error fetching agenda days', e); }

  const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
  monthYear.textContent = `${monthNames[month]} ${year}`;

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const prevDays = new Date(year, month, 0).getDate();

  for (let i = firstDay - 1; i >= 0; i--) {
    const div = document.createElement('div');
    div.className = 'cd om';
    div.textContent = prevDays - i;
    grid.appendChild(div); 
  }

  const today = new Date();
  for (let i = 1; i <= daysInMonth; i++) {
    const div = document.createElement('div');
    div.className = 'cd';
    if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) div.classList.add('td');
    const dStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
    if (dStr === formatDateIso(agendaDate)) div.classList.add('sel');
    
    if (daysWithApps.includes(i)) div.innerHTML = `${i} <span style="font-size:10px;margin-left:2px">🐾</span>`;
    else div.textContent = i;
    
    div.onclick = () => {
      agendaDate = new Date(year, month, i);
      renderCalendar();
      loadAgenda(dStr);
    };
    grid.appendChild(div);
  }
}

function changeMonth(dir) {
  currentCalDate.setMonth(currentCalDate.getMonth() + dir);
  renderCalendar();
}

async function loadAgenda(date) {
  const container = document.getElementById('timeline-container');
  const title = document.getElementById('timeline-title');
  const subtitle = document.getElementById('timeline-subtitle');
  if (!container) return;
  container.innerHTML = '<p class="timeline-msg timeline-msg--muted" style="text-align:center;padding:40px;">Carregando atendimentos...</p>';
  try {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    const res = await fetch(`${baseUrl}/api/agenda/day?date=${date}`);
    const data = await res.json();
    const d = new Date(date + 'T00:00:00');
    title.textContent = d.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
    const count = data.appointments.length;
    subtitle.textContent = `${count} agendamento${count !== 1 ? 's' : ''}`;
    if (document.getElementById('stat-hoje')) document.getElementById('stat-hoje').textContent = data.stats.hoje;
    if (document.getElementById('stat-pendentes')) document.getElementById('stat-pendentes').textContent = data.stats.pendentes;
    renderTimeline(data.appointments, date);
  } catch (e) { container.innerHTML = '<p class="timeline-msg timeline-msg--error" style="text-align:center;padding:40px;">Erro ao carregar a agenda.</p>'; }
}

function renderTimeline(appts, date) {
  const container = document.getElementById('timeline-container');
  if (!container) return;
  container.innerHTML = '';
  const hours = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';

  hours.forEach(h => {
    const row = document.createElement('div');
    row.className = 'time-row';
    const lbl = document.createElement('div');
    lbl.className = 'time-lbl';
    lbl.textContent = h;
    row.appendChild(lbl);
    const cnt = document.createElement('div');
    cnt.className = 'time-content';
    const filtered = appts.filter(a => a.age_hora.startsWith(h.substring(0, 2)));

    if (h === "12:00") {
      cnt.innerHTML = `<div class="appt-block ab-empty"><span>🌙</span><span class="slot-empty">Horário de Almoço</span></div>`;
    } else if (filtered.length > 0) {
      filtered.forEach(a => {
        const block = document.createElement('div');
        block.className = `appt-block ${a.pet_sexo === 'Fêmea' ? 'ab-pk' : 'ab-mn'}`;
        block.onclick = (e) => openEditAgendarModal(a, e);
        const av = (a.pet_avatar && (a.pet_avatar.includes('/') || a.pet_avatar.includes('\\'))) 
          ? `<img src="${baseUrl}${a.pet_avatar}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">` : (a.pet_avatar || '🐾');
        const isFaturado = a.age_faturado == 1;
        block.innerHTML = `
          <div class="ab-av" style="background:rgba(255,255,255,0.4); overflow:hidden; display:flex; align-items:center; justify-content:center;">${av}</div>
          <div class="ab-info">
            <div class="ab-name">${a.pet_nome} ${isFaturado ? '<span class="faturado-badge">FATURADO</span>' : ''}</div>
            <div class="ab-srv">${a.ser_nome || a.age_servico || '—'} — ${a.age_obs || ''}</div>
            <div class="ab-srv-id hidden">${a.age_servico}</div>
            <div class="ab-status">Status: ${a.age_status && a.age_status.toLowerCase() === 'confirmado' ? '✅ Confirmado' : '⏳ Pendente'}</div>
          </div> 
          <div class="ab-dur">${a.age_duracao}</div>
          <div class="ab-actions">
            ${!isFaturado ? `
              <button class="btn-icon bz" onclick="zapReminder('agendamento', ${a.age_id})" title="Enviar Lembrete WhatsApp">💬</button>
              <button class="btn-icon bc ${a.age_status && a.age_status.toLowerCase() === 'confirmado' ? 'active' : ''}" onclick="updateApptStatus(${a.age_id}, 'Confirmado', event)" title="Confirmar Agendamento">✅</button>
              <button class="btn-icon bf" onclick="openFaturarModal(${JSON.stringify(a).replace(/"/g, '&quot;')}, event)" title="Lançar Financeiro">💰</button>
            ` : `
              <button class="btn-icon bz" onclick="zapReminder('agendamento', ${a.age_id})" title="Enviar Lembrete WhatsApp">💬</button>
              <span class="ab-st st-conf">✓ Concluído</span>
            `}
          </div>
        `;
        cnt.appendChild(block);
      });
    } else { cnt.innerHTML = `<div class="new-slot" onclick="openAgWithDate('${date}', '${h}')">➕ Horário livre — Criar Agendamento</div>`; }
    row.appendChild(cnt);
    container.appendChild(row);
  });
}

async function loadUpcoming() {
  const container = document.getElementById('up-list-container');
  if (!container) return;
  try {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    const res = await fetch(`${baseUrl}/api/agenda/upcoming`);
    const data = await res.json();
    if (data.length === 0) {
      container.innerHTML = '<p class="up-list__msg up-list__msg--muted" style="text-align:center;padding:20px;">Nenhum agendamento próximo.</p>';
      return;
    }
    container.innerHTML = '';
    data.forEach(a => {
      const item = document.createElement('div');
      item.className = 'up-item';
      const d = new Date(a.age_data + 'T00:00:00');
      const av = (a.pet_avatar && (a.pet_avatar.includes('/') || a.pet_avatar.includes('\\'))) 
        ? `<img src="${baseUrl}${a.pet_avatar}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">` : (a.pet_avatar || '🐾');
      item.innerHTML = `
        <div class="up-av" style="background:var(--pink-light); overflow:hidden; display:flex; align-items:center; justify-content:center;">${av}</div>
        <div class="up-info"><strong>${a.pet_nome}</strong><span>${a.ser_nome || a.age_servico} · ${d.toLocaleDateString('pt-BR', {day:'2-digit',month:'2-digit'})}</span></div>
        <span class="up-time">${a.age_hora.substring(0, 5)}</span>
      `;
      item.onclick = () => {
        agendaDate = new Date(a.age_data + 'T00:00:00');
        currentCalDate = new Date(agendaDate);
        renderCalendar();
        loadAgenda(a.age_data);
      };
      container.appendChild(item);
    });
  } catch (e) { container.innerHTML = '<p class="up-list__msg up-list__msg--error" style="text-align:center;">Erro ao carregar próximos.</p>'; }
}

function openAgWithDate(date, time) {
  if (typeof openAgendarModal === 'function') {
    openAgendarModal();
    if (document.getElementById('ag-data')) document.getElementById('ag-data').value = date;
    if (document.getElementById('ag-hora')) document.getElementById('ag-hora').value = time;
  }
}

async function updateApptStatus(id, status, event) {
  if (event) event.stopPropagation();
  try {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    const res = await fetch(`${baseUrl}/api/agenda/update-status/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `status=${status}`
    });
    const data = await res.json();
    if (data.status === 'success') {
      toast('Sucesso', 'Status atualizado!');
      loadAgenda(formatDateIso(agendaDate));
    } else toast('Erro', data.message || 'Erro ao atualizar', 'error');
  } catch (e) { toast('Erro', 'Erro de conexão', 'error'); }
}

function openFaturarModal(appt, event) {
  if (event) event.stopPropagation();
  const srvName = appt.ser_nome || appt.age_servico;
  if (document.getElementById('fat-age-id')) document.getElementById('fat-age-id').value = appt.age_id;
  if (document.getElementById('fat-pet-id')) document.getElementById('fat-pet-id').value = appt.pet_id;
  if (document.getElementById('fat-servico')) document.getElementById('fat-servico').value = srvName;
  const srv = listaServicos.find(s => s.ser_nome === srvName || s.ser_id == appt.age_servico);
  if (document.getElementById('fat-valor')) document.getElementById('fat-valor').value = srv ? srv.ser_valor : '';
  // ✅ NOVO: preenche o ID do serviço para uso no consumo do pacote
  if (document.getElementById('fat-servico-id')) document.getElementById('fat-servico-id').value = srv ? srv.ser_id : (appt.age_servico || '');

  // Reset para Pix ao abrir
  selectPaymentMethod('Pix');
  
  openModal('faturamento');
}
// Listener de Submissão Global para Faturamento Rápido
document.addEventListener('submit', async function(e) {
  const target = e.target;
  if (target && (target.id === 'formFaturamentoRapido' || target.id === 'formFaturar')) {
    e.preventDefault();
    
    const idField = target.querySelector('#fat-age-id') || document.getElementById('fat-age-id');
    const id = idField ? idField.value : null;

    if (!id) {
      toast('Erro', 'Agendamento não identificado. Reabra o modal.', 'error');
      return;
    }

    const formData = new FormData(target);
    const pm = formData.get('forma_pagamento');

    if (pm === 'Pacote' && !formData.get('pacote_id')) {
      toast('Atenção', 'Selecione um pacote antes de confirmar.', 'error');
      return;
    }

    let baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    if (baseUrl.endsWith('/')) baseUrl = baseUrl.slice(0, -1);
    
    try {
      const res = await fetch(`${baseUrl}/api/agenda/faturar/${id}`, { 
        method: 'POST', 
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      
      if (data.status === 'success') {
        toast('Sucesso', 'Faturamento realizado com sucesso!');
        closeModal('faturamento');
        if (typeof loadAgenda === 'function') loadAgenda(formatDateIso(agendaDate));
        if (typeof loadUpcoming === 'function') loadUpcoming();
      } else {
        toast('Erro', data.message || 'Erro ao faturar', 'error');
      }
    } catch (err) {
      toast('Erro', 'Falha na conexão com o servidor.', 'error');
      console.error(err);
    }
  }
});

// ─── COMPONENT: PAYMENT & PACKAGES ────────────────────────────
function selectPaymentMethod(method) {
  document.querySelectorAll('.pm-btn').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('onclick').includes(`'${method}'`));
  });
  
  const input = document.getElementById('fat-forma-pgto');
  if (input) input.value = method;

  const pacArea = document.getElementById('fat-pacote-area');
  if (method === 'Pacote') {
    if (pacArea) pacArea.classList.remove('hide');
    const petId = document.getElementById('fat-pet-id')?.value;
    if (petId) loadPetPackages(petId);
  } else {
    if (pacArea) pacArea.classList.add('hide');
    const pacIdInp = document.getElementById('fat-pacote-id');
    if (pacIdInp) pacIdInp.value = '';
  }
}

async function loadPetPackages(petId) {
  const list = document.getElementById('fat-pacote-list');
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  if (!list) return;

  list.innerHTML = '<p class="text-center text-muted fs-11 p-10">Carregando pacotes...</p>';

  try {
    const res = await fetch(`${baseUrl}/api/pacotes/disponiveis/${petId}`);
    const data = await res.json();
    
    if (data.status === 'success' && data.data.length > 0) {
      list.innerHTML = '';
      data.data.forEach(p => {
        const opt = document.createElement('div');
        opt.className = 'pac-opt';
        opt.onclick = () => setPacoteId(p.id, opt);
        
        let detail = '';
        if (p.tipo === 'Crédito') {
          detail = `Saldo: R$ ${parseFloat(p.saldo_valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        } else {
          detail = `Pacote: ${p.nome}`;
        }

        opt.innerHTML = `
          <div class="pac-opt-info">
            <div class="pac-opt-name">${p.nome} (${p.tipo})</div>
            <div class="pac-opt-detail">${detail}</div>
          </div>
          <div class="pac-opt-check"><i class="bi bi-check"></i></div>
        `;
        list.appendChild(opt);
      });
    } else {
      list.innerHTML = '<p class="text-center text-muted fs-11 p-10">Este pet não possui pacotes ativos.</p>';
    }
  } catch (e) {
    list.innerHTML = '<p class="text-center text-red fs-11 p-10">Erro ao carregar pacotes.</p>';
  }
}

function setPacoteId(id, el) {
  document.querySelectorAll('.pac-opt').forEach(opt => opt.classList.remove('selected'));
  el.classList.add('selected');
  const inp = document.getElementById('fat-pacote-id');
  if (inp) inp.value = id;
}

// ─── MODULE: FILTERS & SEARCH ─────────────────────────────────
let filterTimeout;
function filterAll() {
  clearTimeout(filterTimeout);
  filterTimeout = setTimeout(() => {
    const searchInput = document.getElementById('f-search');
    const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const s = document.getElementById('f-status')?.value.toLowerCase() || '';
    const sv = document.getElementById('f-service')?.value.toLowerCase() || '';
    const sortBy = document.getElementById('f-sort')?.value || 'recent';

    const items = Array.from(document.querySelectorAll('.pat-item'));
    
    // 1. Filter
    items.forEach(el => {
      const name = el.getAttribute('data-nome') || '';
      const resp = el.getAttribute('data-resp') || '';
      const tel = el.getAttribute('data-tel') || '';
      const status = el.getAttribute('data-status')?.toLowerCase() || '';
      const service = el.getAttribute('data-service')?.toLowerCase() || '';
      const mS = !q || name.includes(q) || resp.includes(q) || tel.includes(q);
      const mSt = !s || status === s;
      const mSv = !sv || service.includes(sv);
      el.style.display = (mS && mSt && mSv) ? '' : 'none';
    });

    // 2. Sort
    items.sort((a, b) => {
      if (sortBy === 'name') {
        return (a.getAttribute('data-nome') || '').localeCompare(b.getAttribute('data-nome') || '');
      } else if (sortBy === 'consults') {
        return parseInt(b.getAttribute('data-consultas') || 0) - parseInt(a.getAttribute('data-consultas') || 0);
      } else if (sortBy === 'visit') {
        const dateA = a.getAttribute('data-date') || '0000-00-00';
        const dateB = b.getAttribute('data-date') || '0000-00-00';
        return dateB.localeCompare(dateA);
      } else { // recent or default
        return parseInt(b.getAttribute('data-id') || 0) - parseInt(a.getAttribute('data-id') || 0);
      }
    });

    // 3. Re-append to maintain order in DOM
    const grid = document.getElementById('view-grid');
    const listBody = document.querySelector('#view-list tbody');

    if (grid) {
      const addCard = grid.querySelector('.btn-add-card');
      items.forEach(el => {
        if (el.classList.contains('pat-card')) grid.appendChild(el);
      });
      if (addCard) grid.appendChild(addCard); // Keep add card at the end
    }

    if (listBody) {
      items.forEach(el => {
        if (el.tagName === 'TR') listBody.appendChild(el);
      });
    }
  }, 150);
}

function setView(v, btn) {
  document.querySelectorAll('.vb').forEach(b => b.classList.remove('a'));
  if (btn) btn.classList.add('a');
  if (document.getElementById('view-grid')) document.getElementById('view-grid').style.display = v === 'grid' ? 'grid' : 'none';
  if (document.getElementById('view-list')) document.getElementById('view-list').style.display = v === 'list' ? 'block' : 'none';
}

// ─── MODULE: FORM HELPERS ─────────────────────────────────────
function toggleChk(el) {
  const ck = el.querySelector('input[type="checkbox"]');
  if (ck) { ck.checked = !ck.checked; el.classList.toggle('ck', ck.checked); }
}
function toggleRo(el, key, val) {
  if (document.getElementById('val_' + key)) document.getElementById('val_' + key).value = val;
  el.parentElement.querySelectorAll('.ro').forEach(r => r.classList.remove('ck'));
  el.classList.add('ck');
}
function pickAv(el, av) {
  if (document.getElementById('val_avatar')) document.getElementById('val_avatar').value = av;
  el.parentElement.querySelectorAll('.av-opt').forEach(a => a.classList.remove('sel'));
  el.classList.add('sel');
  if (document.getElementById('photoIco')) document.getElementById('photoIco').textContent = av;
}

// ─── MODULE: PET REGISTRATION (Wizard Submission) ─────────────
async function completeReg(event) {
  const f = document.getElementById('regForm');
  const btn = event.currentTarget || event.target;
  const originalHtml = btn.innerHTML;
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  btn.disabled = true;
  btn.innerHTML = `Salvando...`;
  try {
    const res = await fetch(`${baseUrl}/pets/mixCreate`, { method: 'POST', body: new FormData(f) });
    const result = await res.json();
    if (result.status === 'success') {
      if (document.getElementById('successScreen')) document.getElementById('successScreen').style.display = 'block';
      const sp4 = document.getElementById('sp4');
      if (sp4) {
        if (sp4.querySelector('.fs')) sp4.querySelector('.fs').style.display = 'none';
        if (sp4.querySelector('div:nth-child(2)')) sp4.querySelector('div:nth-child(2)').style.display = 'none';
      }
    } else alert('Erro ao salvar: ' + result.message);
  } catch (e) { alert('Erro na comunicação com o servidor'); } finally { btn.disabled = false; btn.innerHTML = originalHtml; }
}

// ─── COMPONENT: MODAL AGENDAMENTO LOGIC ───────────────────────
function openAgendarModal(petId = null, petNome = null) {
  const form = document.getElementById('form-novo-ag');
  if (!form) return;
  form.reset();
  if (document.getElementById('ag-id')) document.getElementById('ag-id').value = '';
  if (document.getElementById('ag-modal-title')) document.getElementById('ag-modal-title').textContent = '📅 Novo Agendamento';
  if (document.getElementById('ag-pet-id')) document.getElementById('ag-pet-id').value = petId || '';
  if (document.getElementById('ag-search-pet')) document.getElementById('ag-search-pet').value = petNome || '';
  if (document.getElementById('ag-status')) document.getElementById('ag-status').value = 'pendente';
  if (document.getElementById('ag-data') && !document.getElementById('ag-data').value) document.getElementById('ag-data').value = new Date().toISOString().split('T')[0];
  openModal('novo-ag');
}

function openEditAgendarModal(a, e) {
  if (e) e.stopPropagation();
  const form = document.getElementById('form-novo-ag');
  if (!form) return;
  form.reset();
  if (document.getElementById('ag-id')) document.getElementById('ag-id').value = a.age_id;
  if (document.getElementById('ag-modal-title')) document.getElementById('ag-modal-title').textContent = '📝 Editar Agendamento';
  if (document.getElementById('ag-pet-id')) document.getElementById('ag-pet-id').value = a.pet_id;
  if (document.getElementById('ag-search-pet')) document.getElementById('ag-search-pet').value = a.pet_nome;
  if (document.getElementById('ag-data')) document.getElementById('ag-data').value = a.age_data;
  if (document.getElementById('ag-hora')) {
      const h = a.age_hora.substring(0, 5);
      document.getElementById('ag-hora').value = h;
  }
  if (document.getElementById('ag-servico')) document.getElementById('ag-servico').value = a.age_servico;
  if (document.getElementById('ag-duracao')) document.getElementById('ag-duracao').value = a.age_duracao;
  if (document.getElementById('ag-status')) document.getElementById('ag-status').value = a.age_status || 'pendente';
  if (document.getElementById('ag-obs')) document.getElementById('ag-obs').value = a.age_obs || '';
  openModal('novo-ag');
}

async function submitNovoAg(event) {
  const form = document.getElementById('form-novo-ag');
  const btn = document.getElementById('btn-confirm-ag');
  if (!form || !btn) return;
  const formData = new FormData(form);
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  if (!formData.get('pet_id')) { alert('Por favor, selecione um pet da lista para agendar.'); return; }
  
  const id = formData.get('age_id');
  const url = id ? `${baseUrl}/api/agenda/update/${id}` : `${baseUrl}/api/agenda/create`;
  
  btn.disabled = true;
  btn.innerHTML = `Salvando...`;
  try {
    const res = await fetch(url, { method: 'POST', body: formData });
    const result = await res.json();
    if (result.status === 'success') {
      closeModal('novo-ag');
      form.reset();
      if (typeof initAgendaPage === 'function') initAgendaPage();
      else if (typeof initAgenda === 'function') initAgenda();
      else toast('Sucesso', 'Operação realizada!');
    } else alert('Erro: ' + (result.message || 'Erro desconhecido'));
  } catch (e) { alert('Erro ao salvar agendamento'); } finally { btn.disabled = false; btn.innerHTML = 'Confirmar Agendamento'; }
}

// Global listeners
document.addEventListener('change', (e) => {
  if (e.target.id === 'ag-servico') {
    const opt = e.target.options[e.target.selectedIndex];
    const t = opt.getAttribute('data-tempo');
    const s = document.getElementById('ag-duracao');
    if (t && s) {
      s.value = t + ' min';
      if (s.value !== t + ' min') { s.add(new Option(t + ' min', t + ' min')); s.value = t + ' min'; }
    }
  }
});

document.addEventListener('input', (e) => {
  if (e.target.id === 'ag-search-pet' || e.target.id === 'pac-search-pet') {
    const inp = e.target;
    const prefix = e.target.id.split('-')[0]; // ag ou pac
    const resDiv = document.getElementById(`${prefix}-search-results`);
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    
    clearTimeout(window[`${prefix}SearchTimeout`]);
    const term = inp.value.trim();
    if (term.length < 2) { if (resDiv) resDiv.classList.add('hide'); return; }
    
    window[`${prefix}SearchTimeout`] = setTimeout(async () => {
      try {
        const res = await fetch(`${baseUrl}/api/agenda/search-pets?term=${term}`);
        const data = await res.json();
        if (!resDiv) return;
        resDiv.innerHTML = '';
        if (data.length > 0) {
          data.forEach(p => {
            const d = document.createElement('div');
            d.className = 'search-item';
            d.innerHTML = `<strong>${p.pet_nome}</strong> <span style="font-size:11px;color:var(--muted);display:block;">${p.pet_resp_nome || ''} · ${p.pet_resp_tel || ''}</span>`;
            d.onclick = () => {
              const idInp = document.getElementById(`${prefix}-pet-id`);
              if (idInp) idInp.value = p.pet_id;
              inp.value = p.pet_nome;
              resDiv.classList.add('hide');
            };
            resDiv.appendChild(d);
          });
          resDiv.classList.remove('hide');
        } else { resDiv.innerHTML = '<div style="padding:10px;font-size:12px;color:var(--muted)">Nenhum pet encontrado</div>'; resDiv.classList.remove('hide'); }
      } catch (err) { console.error(err); }
    }, 300);
  }
});

document.addEventListener('click', (e) => {
  ['ag', 'pac'].forEach(prefix => {
    const inp = document.getElementById(`${prefix}-search-pet`);
    const res = document.getElementById(`${prefix}-search-results`);
    if (inp && res && !inp.contains(e.target) && !res.contains(e.target)) res.classList.add('hide');
  });
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' && e.target.form?.id === 'regForm' && e.target.tagName !== 'TEXTAREA') e.preventDefault();
});

// ─── MODULE: ESTOQUE ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const movTipo = document.getElementById('mov-tipo');
  const checkFin = document.getElementById('lancar-financeiro');
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  if (movTipo) {
    const hT = () => {
      const f = document.getElementById('form-financeiro');
      if (f) (checkFin?.checked) ? f.classList.remove('hide') : f.classList.add('hide');
    };
    const hM = () => {
      const t = movTipo.value;
      const f = document.getElementById('form-movimentacao');
      const dF = document.getElementById('div-financeiro');
      const dV = document.getElementById('div-valor-total');
      if (t === 'Entrada') {
        if (f) f.action = `${baseUrl}/estoque/registrarEntrada`;
        if (dF) dF.classList.remove('hide');
        if (dV) dV.classList.remove('hide');
      } else {
        if (f) f.action = `${baseUrl}/estoque/registrarSaida`;
        if (dF) dF.classList.add('hide');
        if (dV) dV.classList.add('hide');
        if (checkFin) { checkFin.checked = false; hT(); }
      }
    };
    movTipo.addEventListener('change', hM);
    if (checkFin) checkFin.addEventListener('change', hT);
    hM();
  }
});

// ─── MODULE: COMMUNICATION (WhatsApp Reminders) ───────────────
async function zapReminder(type, id) {
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  try {
    const res = await fetch(`${baseUrl}/api/comunicacao/whatsapp/${type}/${id}`);
    const data = await res.json();
    if (data.status === 'success' && data.link) {
      window.open(data.link, '_blank');
      toast('WhatsApp', 'Mensagem gerada com sucesso!', 'success');
    } else {
      toast('Erro', data.message || 'Erro ao gerar o lembrete', 'error');
    }
  } catch (e) {
    console.error(e);
    toast('Erro', 'Não foi possível conectar ao servidor para gerar o link.', 'error');
  }
}

async function saveZapTemplate(key, value) {
  const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
  const formData = new FormData();
  formData.append('meta_key', key);
  formData.append('meta_value', value);

  try {
    const res = await fetch(`${baseUrl}/configuracoes/updateTemplate`, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.status === 'success') {
      toast('Configurações', 'Template salvo com sucesso!');
    } else {
      toast('Erro', data.message || 'Erro ao salvar template', 'error');
    }
  } catch (e) {
    toast('Erro', 'Erro de conexão.', 'error');
  }
}
