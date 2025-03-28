# 🏨 Simple Hotel System



## 📖 Project Overview

The **Simple Hotel System** is a full-fledged management platform designed to streamline hotel operations. Whether you're an admin, manager, receptionist, or client, this system ensures seamless interactions and role-based functionalities. Built with **Laravel, Vue.js (Inertia.js), ShadCN UI, and Chart.js**, it offers an intuitive and efficient experience for managing hotels, rooms, clients, and reservations.

## ✨ Key Features

- 🔐 **Secure Role-Based Access Control** – Each role has specific permissions to prevent unauthorized actions.
- 🏨 **Hotel, Room & Floor Management** – Organize, update, and maintain hotel rooms and floors with ease.
- 📅 **Reservation System with Secure Payments** – Clients can book rooms and make secure payments via Stripe.
- 📩 **Automated Email Notifications** – Keeps users updated on approvals, reservations, and inactivity alerts.
- 🔗 **API Security with Laravel Sanctum** – Ensures secure API interactions for data protection.

## 🎭 Roles & Responsibilities

### 👑 Admin

Admins have full control over the system and can:

- Manage **managers, receptionists, and clients** (Create, Read, Update, Delete - CRUD operations).
- Configure **system-wide settings** and enforce security measures.
- Assign **permissions** and manage access levels.
- Default admin login credentials:
  - **Email:** `admin@admin.com`
  - **Password:** `123456`
- Create additional admins using:
  ```sh
  php artisan create:admin --name=admin2@admin.com --password=123456
  ```

### 👔 Manager

Managers handle operations related to rooms, floors, and receptionists:

- Oversee **receptionist management** (CRUD, Ban/Unban access).
- Manage **hotel floors & rooms** efficiently.
- Track **client activities and reservations**.


### 🛎 Receptionist

Receptionists act as the bridge between clients and the hotel:

- Approve or reject **new client registrations**.
- Manage **room reservations** and handle booking requests.
- View and update **assigned client details** only.

### 👤 Client

Clients interact with the system primarily for booking purposes:

- **Sign up and request approval** from receptionists.
- **Browse available rooms** and check availability.
- **Make reservations & process secure payments** via Stripe.
- Receive **email notifications** about reservation status and account updates.

---

## ⚙️ Installation & Setup

### 📥 Clone the Repository

```sh
git clone https://github.com/Ghada-Emad1/Hotel_System.git
cd Hotel_System
```

### 📦 Install Dependencies

Ensure all necessary packages are installed:

```sh
composer install
npm install
```

### 🔧 Configure the Environment

Set up the `.env` file and generate the application key:

```sh
cp .env.example .env
php artisan key:generate
```

### 🗄 Set Up the Database

Run migrations to structure the database:

```sh
php artisan migrate --seed
```

### 🚀 Run the Application

Start both the backend and frontend services:

```sh
php artisan serve
npm run dev
```

### 🔑 Default Admin Login

For initial access to the system:

- **Email:** `admin@admin.com`
- **Password:** `123456`

---

## 🛠 Technologies Used

This system is powered by modern technologies:

- **Backend:** Laravel (PHP Framework), Laravel Sanctum (API Security)
- **Frontend:** Vue.js, Inertia.js, ShadCN UI (Modern UI Library)
- **Database:** MySQL (Relational Database Management System)
- **Styling:** Tailwind CSS (Lightweight CSS Framework)
- **Payment Gateway:** Stripe (Secure Online Payments)
- **Authentication:** Laravel Sanctum (Token-Based Authentication)

## 📜 System Rules & Logic

- **DataTables Integration** – All tables support server-side pagination for better performance.
- **AJAX-Powered Actions** – Delete buttons use confirmation dialogs for safety.
- **Currency Handling** – Prices are stored in cents but displayed in dollars for accuracy.
- **Scheduled Task Automation** – Inactivity reminders are sent to users via scheduled tasks.
- **API Security & Access Control** – Laravel Sanctum ensures API security and authentication.

## 📩 Get in Touch

If you have any **questions, suggestions, or want to contribute**, feel free to:

- Open an **Issue** in this repository.
- Submit a **Pull Request** with enhancements or bug fixes.

We’d love to hear from you! 😊

---

🚀 Developed with passion by **[Fatma_Ali - Aml_Mohsen - Monica_Amgad - Ghada_Emad ]**

