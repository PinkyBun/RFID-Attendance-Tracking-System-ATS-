# RFID Attendance Tracking System (RFID-ATS)

A modern, fast, and responsive RFID-based Attendance Tracking System built with **CodeIgniter 4** and **DaisyUI**. This system allows teachers to manage class sessions, track student attendance in real-time via RFID taps, and generate comprehensive analytical reports.

## 🚀 Key Features

- **Live RFID Tap View**: Real-time dashboard to monitor student check-ins and check-outs as they happen.
- **Automatic Attendance Logic**: Intelligently calculates "On Time", "Late", and "Incomplete" statuses based on session schedules.
- **Comprehensive Reporting**: Generate filterable attendance reports by Subject, Section, and Date with print-friendly previews and PDF downloads.
- **Theme-Aware UI**: Beautiful user interface powered by DaisyUI with multiple theme supports (Light, Dark, Synthwave, etc.).
- **Searchable Dropdowns**: Enhanced searchable selectors using Tom Select for Subjects, Sections, and Students.
- **Student Management**: Full CRUD for regular and irregular students, including RFID registration and status toggles.

## 🛠️ Technology Stack

- **Backend**: PHP 8.2+ (CodeIgniter 4)
- **Database**: MySQL / MariaDB
- **Frontend**: Tailwind CSS & DaisyUI
- **Libraries**:
  - [Tom Select](https://tom-select.js.org/) for searchable dropdowns.
  - [Dompdf](https://github.com/dompdf/dompdf) for high-quality PDF generation.
  - [Chart.js](https://www.chartjs.org/) for analytical dashboards.

## 📋 Prerequisites

- **PHP 8.2** or higher.
- **Composer** installed globally.
- **MySQL** (XAMPP / Laragon / Local MySQL).
- Extensions enabled: `intl`, `mbstring`, `curl`, `json`, `mysqlnd`.

## ⚙️ Installation & Setup

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd ATS
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   - Copy `env` to `.env`.
   - Update `database.default.hostname`, `database.default.database`, `database.default.username`, and `database.default.password`.
   - Update `app.baseURL` to your local development URL (e.g., `http://localhost:8080/`).

4. **Initialize Database**:
   - Create a blank database in MySQL (e.g., `rfid_ats`).
   - Run migrations to create tables:
     ```bash
     php spark migrate
     ```
   - Run seeders to populate initial data (including default admin):
     ```bash
     php spark db:seed DatabaseSeeder
     ```

## 🏃 How to Run

1. Start the local development server:
   ```bash
   php spark serve
   ```
2. Open your browser and navigate to `http://localhost:8080`.
3. **Default Admin Credentials**:
   - (Check `app/Database/Seeds/AdminSeeder.php` for default user/password, or use the seeded admin account).

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
