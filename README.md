# 🛡️ NetSentinel — Enterprise Network Monitoring Suite

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.4-blue?style=for-the-badge&logo=php" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/Livewire-3.x-purple?style=for-the-badge" alt="Livewire 3">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38bdf8?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/AI_SDK-0.1.5-emerald?style=for-the-badge" alt="Laravel AI SDK">
</p>

> **NetSentinel** is an enterprise-grade, real-time network monitoring application built on the **TALL Stack** (Tailwind, Alpine.js, Laravel, Livewire). It provides a premium dark-mode dashboard for tracking the health, latency, and uptime of your network lines and ISP connections — now enhanced with **AI-powered analysis** via the Laravel AI SDK.

---

## ✨ Features

| Feature | Description |
|---|---|
| 📡 **Real-time Dashboard** | Auto-refreshing monitor grid with live latency and status indicators |
| 📊 **Interactive Charts** | Chart.js-powered latency and jitter graphs per monitor |
| 🚨 **Alert Center** | View, acknowledge, and resolve critical failures and warnings |
| 🔎 **Monitor Details** | Deep-dive view with connection quality, packet loss, and traceroute visualization |
| 💻 **DNS Operations Console** | Simulated terminal to flush DNS cache and renew DHCP leases |
| 🤖 **AI Analysis** | Natural language insights powered by the Laravel AI SDK & Prism PHP |
| 🔐 **Authentication & RBAC** | User auth via Laravel Jetstream + Fortify with Spatie Permissions |
| 📈 **Reports & Logs** | Generate uptime summaries and latency analysis reports, export to CSV |

---

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 12.x (PHP 8.4)
- **Real-time UI:** Laravel Livewire 3 + Livewire Volt
- **Authentication:** Laravel Jetstream + Fortify + Sanctum
- **Roles & Permissions:** Spatie Laravel Permission
- **AI Integration:** Laravel AI SDK v0.1.5 (Prism PHP abstraction layer)
- **Database:** SQLite (default) / MySQL (configurable)
- **Queue/Cache:** Redis (via Predis), Database queue driver

### Frontend
- **CSS Framework:** Tailwind CSS v3.x
- **UI Components:** Livewire Flux v2.x
- **Interactivity:** Alpine.js
- **Charts:** Chart.js
- **Icons:** Phosphor Icons
- **Build Tool:** Vite

### Dev Tools
- **Testing:** PHPUnit 11 + Laravel Pail (log viewer)
- **Code Style:** Laravel Pint
- **Faker:** FakerPHP (for seeders)

---

## 🚀 Getting Started

### Prerequisites

- PHP **8.4+**
- Composer **2.x**
- Node.js **18+** & npm
- A database (SQLite is used by default, no additional setup needed)

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url> netsentinel
   cd netsentinel
   ```

2. **One-command setup** (installs dependencies, generates app key, runs migrations, and builds assets):
   ```bash
   composer run setup
   ```

   Or run each step manually:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   npm install
   npm run build
   ```

3. **Configure your environment** by editing the `.env` file:
   ```env
   APP_NAME=NetSentinel
   APP_URL=http://localhost:8000
   DB_CONNECTION=sqlite

   # Optional: AI Provider Keys (add at least one)
   OPENAI_API_KEY=sk-...
   ANTHROPIC_API_KEY=sk-ant-...
   GEMINI_API_KEY=...
   ```

4. **Start the development server:**
   ```bash
   composer run dev
   ```
   This concurrently starts the PHP server, queue listener, and Vite dev server.

5. **Access the app** at: **http://localhost:8000**

---

## 📁 Project Structure

```
netsentinel/
├── app/
│   ├── Ai/
│   │   ├── Agents/
│   │   │   └── MonitorAnalyzer.php       # AI agent for monitoring analysis
│   │   └── Tools/
│   │       └── FetchMonitorStatus.php    # AI tool to query monitor data
│   ├── Http/
│   │   └── Controllers/                  # Standard Laravel controllers
│   ├── Livewire/
│   │   ├── Dashboard.php                 # Main dashboard component
│   │   ├── MonitorDetails.php            # Per-monitor detail view
│   │   ├── AlertCenter.php               # Alert management
│   │   └── DnsConsole.php                # Simulated DNS terminal
│   ├── Models/
│   │   └── Monitor.php                   # Monitor Eloquent model
│   └── Jobs/
│       └── RunMonitorCheck.php           # Background ping/check job
├── resources/
│   └── views/
│       ├── components/
│       │   └── layouts/
│       │       └── app.blade.php         # Main application layout
│       └── livewire/                     # Blade templates for Livewire components
├── routes/
│   └── web.php                           # Application routes
├── database/
│   ├── migrations/                       # Database schema definitions
│   └── seeders/                          # Sample data seeders
├── config/
│   └── ai.php                            # Laravel AI SDK configuration
├── stubs/
│   ├── agent.stub                        # Template for new AI agents
│   ├── structured-agent.stub             # Template for structured AI agents
│   └── tool.stub                         # Template for new AI tools
├── AI_SDK_IMPLEMENTATION.md             # AI SDK setup & usage guide
├── PROJECT_ABSTRACT.md                  # High-level project summary
└── # Software Architecture Document.md # Original design specifications
```

---

## 🌐 Application Routes

| URL | Component | Description |
|---|---|---|
| `/` | `Dashboard` | Main monitor grid with KPI cards |
| `/monitor/{id}` | `MonitorDetails` | Detailed view for a specific monitor |
| `/alerts` | `AlertCenter` | Critical failures and warning center |
| `/reports` | `Reports` | Historical logs and export tools |
| `/config` | `Configuration` | Ping interval and notification settings |

---

## 🤖 AI Integration

NetSentinel includes AI analysis capabilities powered by the **Laravel AI SDK** (built on Prism PHP).

### Available Components

- **`MonitorAnalyzer` Agent** — Analyzes system health and provides natural language recommendations.
- **`FetchMonitorStatus` Tool** — Allows the AI agent to query live monitor data from the database.
- **Persistent Conversations** — Agent chat history stored in `agent_conversations` and `agent_conversation_messages` tables.

### Create Custom Agents & Tools

```bash
# Create a new AI agent
php artisan make:agent AlertAnalyzer --structured

# Create a new AI tool
php artisan make:tool FetchAlertHistory
```

### Supported AI Providers

Configure your provider key in `.env`:

| Provider | Environment Variable |
|---|---|
| OpenAI (GPT-4) | `OPENAI_API_KEY` |
| Anthropic (Claude) | `ANTHROPIC_API_KEY` |
| Google Gemini | `GEMINI_API_KEY` |
| Cohere | `COHERE_API_KEY` |
| Mistral AI | `MISTRAL_API_KEY` |
| xAI (Grok) | `XAI_API_KEY` |

> See [`AI_SDK_IMPLEMENTATION.md`](./AI_SDK_IMPLEMENTATION.md) for detailed usage examples.

---

## 🧪 Running Tests

```bash
composer run test
```

Or directly:
```bash
php artisan test
```

---

## 📋 Monitor Status Logic

Each monitor is checked by the `RunMonitorCheck` background job, which determines status based on:

| Condition | Status |
|---|---|
| 0% packet loss, low latency | ✅ **Online** |
| Partial packet loss OR high latency | ⚠️ **Warning** |
| 100% packet loss | ❌ **Offline** |

---

## 🔒 Authentication & Permissions

- **Authentication:** Managed by Laravel Jetstream (with Livewire stack) + Fortify.
- **API Auth:** Laravel Sanctum for token-based API authentication.
- **Roles & Permissions:** Spatie Laravel Permission package for fine-grained RBAC.

---

## 📄 License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🗺️ Roadmap

- [ ] Refine AI agent instructions for better anomaly detection
- [ ] Expand the reporting module to export historical data as CSV/PDF
- [ ] Finalize role-based access control (RBAC) for all routes
- [ ] Add email/Slack notification channels for alerts
- [ ] Implement live traceroute visualization using WebSockets
