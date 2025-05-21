<p align="center">
  <img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&weight=600&size=22&pause=1000&color=3B82F6&center=true&vCenter=true&width=435&lines=Welcome+to+UniDorm!;Manage+Dorms+Like+a+Pro!;Multi-Tenant+Laravel+SaaS+System" alt="Typing SVG" />
</p>

<p align="center">
  <img src="https://github.com/yourusername/unidorm/assets/yourimageid/demo-banner.gif" alt="UniDorm Banner" />
</p>

<h1 align="center">🏫 UniDorm - Multi-Tenant Dormitory Management System</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/MySQL-Database-blue?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/Multi--Tenancy-SaaS-yellow?style=for-the-badge" />
</p>

---

## 📌 Key Features

- 🔐 **Subscription & Approval Flow**
  - 📝 Clients subscribe via the main UniDorm website.
  - 👨‍💼 Admins approve or reject requests.
  - ✅ On approval:
    - 📧 Confirmation email is sent
    - 🗃️ Separate **database** is generated
    - 🌐 Unique **subdomain** is created (e.g., `clientname.unidorm.com`)

- 🏘️ **Dormitory Management**
  - Clients can:
    - ➕ Add and manage rooms
    - 📊 View room availability
    - 📅 Manage bookings

- 🛏️ **Room Booking System**
  - Tenants can:
    - 🌍 Visit client subdomain
    - 👀 View available rooms
    - 🛎️ Book directly online

---

## 🛠️ Tech Stack

| 💻 Component      | ⚙️ Description                            |
|------------------|--------------------------------------------|
| Laravel          | PHP Web Framework (v11+)                   |
| MySQL            | Relational Database                        |
| Tenancy for Laravel | Multi-Tenancy SaaS Management            |
| Laravel Mail     | Email Notifications                        |
| Bootstrap/CSS    | Frontend Styling                           |

---

## 🚀 Project Flow

```mermaid
graph TD
    A[User Subscribes] --> B[Admin Reviews Request]
    B -->|Approved| C[Email Sent to Client]
    C --> D[Client Gets Subdomain + DB]
    D --> E[Client Logs In]
    E --> F[Adds Rooms & Manages Booking]
    F --> G[Tenants Book Online]

```
## 📸 Preview

![Form Preview](./pic/1.png)

---
![Form Preview](./pic/2.png)

---
![Form Preview](./pic/3.png)

---
![Form Preview](./pic/4.png)

---
![Form Preview](./pic/5.png)

---
![Form Preview](./pic/6.png)

---
![Form Preview](./pic/7.png)

---
![Form Preview](./pic/8.png)

---




