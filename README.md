# GadgetFlow

A trust-first, short-term gadget rental marketplace built as a Final Year Project for the BIT (Hons) programme at Asia Metropolitan University.

**Live demo:** https://gadgetflow-fyp-production.up.railway.app

## What it does

GadgetFlow lets customers browse and rent gadgets (phones, laptops, cameras, consoles) and bundle combo packages for short-term periods, with a full manual-payment-verification and QR-code handover/return flow. Administrators manage inventory, verify payments, approve rentals, and scan QR codes at handover and return. Customers also get an AI chat assistant (Google Gemini) grounded against live inventory, a loyalty/rewards points system, and review/rating support after each rental.

## Tech stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **AI:** Google Gemini API (chat assistant)
- **Email:** Mailgun (HTTPS transactional API)
- **Hosting:** Railway (app + managed MySQL)

## Key features

- Browse gadgets and combo bundles with search/filtering
- Two-step manual payment verification workflow
- QR-code verified handover and return
- Loyalty points and rewards
- AI chat assistant grounded against live inventory
- Admin dashboard: inventory, rental approvals, QR scanning, customer management, reports
- Ratings and reviews after completed rentals

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# set your database credentials and MAIL_MAILGUN_* / GEMINI_API_KEY values in .env
php artisan migrate --seed
npm run build
php artisan serve
```

## Project documentation

Full system design (architecture, ERD, DFD, use case diagrams), testing methodology, and evaluation results are documented in the accompanying Final Year Project report.

## Author

Arsath Imran — Bachelor in Information Technology (Hons), Asia Metropolitan University
