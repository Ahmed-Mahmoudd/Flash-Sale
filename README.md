# 🚀 Flash Sale Backend API

A production-style Flash Sale backend built with **Laravel 12**, demonstrating inventory reservation, order creation, payment webhook processing, concurrency handling, and automatic stock release.

This project follows a clean architecture using the **Repository Pattern** and **Service Layer**, with a focus on preventing overselling during high traffic scenarios.

---

# ✨ Features

- Reserve stock using temporary Holds
- Prevent overselling with `lockForUpdate()`
- Database Transactions
- Order creation from valid Holds
- Payment Webhook handling
- Idempotency support for Webhooks
- Automatic release of expired Holds
- Scheduler Job
- Repository Pattern
- Service Layer Architecture
- Request Validation

---

# 🏗️ Project Architecture

```
Client
   │
   ▼
Controller
   │
   ▼
Request Validation
   │
   ▼
Service Layer
   │
   ▼
Repository Layer
   │
   ▼
MySQL Database
```

The business logic is isolated inside Services while database access is handled through Repositories.

---

# 🗄️ Database Design

## Products

Stores product inventory.

| Column | Description |
|---------|-------------|
| id | Product ID |
| name | Product Name |
| price | Product Price |
| total_stock | Actual Stock |
| reserved_stock | Reserved Quantity |

---

## Holds

Represents temporary reservations.

| Column | Description |
|---------|-------------|
| product_id | Reserved Product |
| qty | Reserved Quantity |
| status | active / consumed / expired / released |
| expires_at | Hold expiration time |

---

## Orders

Created only from valid Holds.

| Column | Description |
|---------|-------------|
| hold_id | Source Hold |
| product_id | Purchased Product |
| qty | Purchased Quantity |
| status | pending_payment / paid / failed |

---

## Webhook Events

Stores processed payment events.

| Column | Description |
|---------|-------------|
| idempotency_key | Unique Event ID |
| order_id | Related Order |
| status | processed |

---

# 🔄 Business Flow

```
Customer
     │
     ▼
Create Hold
     │
     ▼
Stock Reserved
     │
     ▼
Create Order
     │
     ▼
Pending Payment
     │
     ▼
Payment Webhook
```

### Payment Success

```
Webhook (paid)

↓

Order → Paid

↓

Hold → Consumed

↓

reserved_stock --

↓

total_stock --
```

---

### Payment Failed

```
Webhook (failed)

↓

Order → Failed

↓

Hold → Released

↓

reserved_stock --
```

---

### Hold Expiration

```
Hold expires

↓

Scheduler

↓

ReleaseExpiredHoldsJob

↓

Hold → Expired

↓

reserved_stock --
```

---

# 🔐 Concurrency Handling

To prevent overselling during flash sales:

- Database Transactions
- Row-level locking using `lockForUpdate()`
- Atomic stock updates

This guarantees that multiple users cannot reserve the same stock simultaneously.

---

# 🔁 Idempotency

Payment gateways may send the same webhook multiple times.

Each webhook contains an **idempotency_key**.

If the key already exists:

- The webhook is ignored.
- No duplicate updates occur.

---

# 📡 API Endpoints

## Create Hold

```
POST /api/holds
```

Request

```json
{
    "product_id": 1,
    "qty": 2
}
```

---

## Create Order

```
POST /api/orders
```

Request

```json
{
    "hold_id": 1
}
```

---

## Payment Webhook

```
POST /api/webhook
```

Request

```json
{
    "idempotency_key": "evt_001",
    "order_id": 1,
    "payment_status": "paid"
}
```

---

# ⚙️ Technologies

- Laravel 12
- PHP 8.2
- MySQL
- Eloquent ORM
- Scheduler
- Queue Jobs
- Repository Pattern
- Service Layer

---

# 📁 Folder Structure

```
app
├── Http
│   ├── Controllers
│   └── Requests
│
├── Jobs
│
├── Models
│
├── Repositories
│
└── Services
```

---

# 🚀 Installation

Clone the repository

```bash
git clone https://github.com/Ahmed-Mahmoudd/Flash-Sale.git
```

Enter the project

```bash
cd Flash-Sale
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your MySQL database inside `.env`

Run migrations and seeders

```bash
php artisan migrate:fresh --seed
```

Start the server

```bash
php artisan serve
```

Run the scheduler

```bash
php artisan schedule:work
```

---

# 🧪 Tested Scenarios

- Create Hold
- Insufficient Stock
- Create Order
- Duplicate Order Prevention
- Expired Hold
- Payment Success
- Payment Failure
- Duplicate Webhook
- Automatic Hold Expiration
- Automatic Stock Release

---

# 💡 Concepts Demonstrated

- Clean Architecture
- Repository Pattern
- Service Layer
- Dependency Injection
- Database Transactions
- Row Locking (`lockForUpdate()`)
- Race Condition Prevention
- Idempotency
- Scheduled Jobs
- RESTful APIs
- Request Validation

---

# 📜 License

This project was built for learning purposes to demonstrate backend architecture and concurrency handling using Laravel.
