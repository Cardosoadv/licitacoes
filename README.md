# Petys — Sistema de Gestão Veterinária (v1.0.1)

**Petys** é um sistema moderno de gestão para clínicas e pet shops, focado em experiência do usuário, controle clínico e gestão financeira. Desenvolvido em CodeIgniter 4 com arquitetura MVCRS.

> Projeto derivado do Oralys (odontologia), migrado e rebranded para o segmento veterinário.

---

## Módulos

### 🗓️ Agenda Inteligente
- Calendário dinâmico com navegação mensal.
- Timeline interativa com carregamento via AJAX.
- Agendamento rápido com busca de pets por autocompletar.
- Visualização de próximos compromissos e estatísticas diárias.

### 🐾 Gestão de Pets
- Cadastro completo com assistente (wizard) multi-etapas (pet + tutor).
- Filtros avançados por serviço, status e termo de busca.
- Visualização em Grade (Cards com avatar) e Lista.

### 📋 Prontuário Veterinário Digital
- **Odontograma Interativo**: Registro de estados dentários do pet com salvamento em JSON/TEXT.
- **Anamnese Completa**: Dados clínicos, queixas e histórico sistêmico.
- **Evolução Clínica**: Registro cronológico de procedimentos com autoria do veterinário.
- **Galeria de Radiografias**: Upload e visualização de exames organizados por data.
- **Histórico 360°**: Visão unificada de consultas, procedimentos e faturamento.

### 💰 Faturamento
- Dashboard com KPIs: Receitas, Despesas, Saldo, A Receber, Vencidos.
- Lançamento de cobranças (receitas) e despesas com categorização.
- Controle de recebíveis pendentes e atrasados.
- Emissão de notas fiscais e recibos com preview imprimível.
- Gráficos: Receita vs. Despesas, Receita por Serviço, Formas de Pagamento, Top Pets.

### 📊 Relatórios Financeiros
- **Extrato**: Movimentações do período filtráveis por tipo e status, com export CSV.
- **DRE**: Demonstração do Resultado do Exercício com variação mensal e margem líquida.
- **Livro Caixa**: Saldo acumulado dia a dia com saldo inicial e final destacados.

### 👩‍⚕️ Equipe
- Cadastro e gerenciamento de veterinários e auxiliares.
- CRUD via modal com API REST.

### 🐾 Serviços
- Catálogo de serviços oferecidos pela clínica.
- CRUD via modal com listagem em cards.

---

## Arquitetura

O projeto segue o padrão **MVCRS** (Model → View → Controller → Repository → Service):

```
HTTP Request → Controller → Service → Repository → Model → Database
                         ↑                    ↑
              (regras de negócio)     (queries ORM)
```

```
app/
├── Controllers/       # Recebem requisições HTTP, delegam para Services
├── Services/          # Lógica de negócio e orquestração
├── Repositories/      # Queries e acesso a dados
├── Models/            # Definição ORM (CodeIgniter 4)
└── Views/             # Templates PHP com layout CodeIgniter
    ├── template/      # Layout base (layout.php)
    └── componentes/   # Sidebar, header, cadastro wizard
```

---

## Requisitos do Sistema

| Requisito | Versão |
|---|---|
| PHP | 8.1+ |
| CodeIgniter | 4.6+ |
| MySQL / MariaDB | 5.7+ / 10.3+ |
| CodeIgniter Shield | 1.2+ |

---

## Instalação

```bash
# 1. Clone o repositório
git clone <repo-url> petys
cd petys

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp env .env
# edite .env com suas credenciais de banco de dados

# 4. Execute as migrações
php spark migrate

# 5. Inicie o servidor de desenvolvimento
php spark serve
```

---

## Principais Dependências

| Pacote | Descrição |
|---|---|
| `codeigniter4/framework` | Framework MVC base |
| `codeigniter4/shield` | Autenticação e autorização |
| `firebase/php-jwt` | JSON Web Tokens (para API mobile) |
| Bootstrap 5.3 | Grid e componentes UI (CDN) |
| Bootstrap Icons 1.11 | Ícones (CDN) |

---

Para o histórico detalhado de desenvolvimento, consulte [`.agents/relatorio_evolucao.md`](.agents/relatorio_evolucao.md).
