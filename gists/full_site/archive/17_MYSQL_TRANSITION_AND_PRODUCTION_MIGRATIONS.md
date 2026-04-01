---
description: L'Artisan Marketplace - MySQL 8.4 Transition & Migration Ordering Fixes
---

# MySQL 8.4 Transition & Production Migration Ordering

This document records the critical configuration and structural changes required to move the platform from a local SQLite development environment to a production-ready MySQL 8.4 infrastructure (Laravel Cloud), specifically addressing strict foreign key constraints.

## 1. Environment Configuration (MySQL 8.4)

The database driver was switched from `sqlite` to `mysql`. This enables the application to leverage the high-performance relational features of MySQL 8.4 provided by Laravel Cloud.

### `.env`
```env
DB_CONNECTION=mysql
DB_HOST=your-laravel-cloud-host.com
DB_PORT=3306
DB_DATABASE=lartisan
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 2. Migration Ordering & Foreign Key Constraints

MySQL (unlike SQLite) strictly enforces that a parent table must exist **before** a child table can define a foreign key referencing it. 

The original migration for `produits` was dated after `favoris`, `conversations`, and `signalements`, which caused a `SQLSTATE[HY000]: General error: 1824` during the initial cloud deployment.

**The Fix**: The `produits` table migration was moved earlier in the chronological order to ensure it is created before any dependent tables.

### Current Migration Sequence (Core Tables):
1. `0001_01_01_000000_create_users_table.php` (Users)
2. `2026_02_11_112808_create_categories_table.php` (Categories)
3. `2026_02_11_112818_create_produits_table.php` (**Moved**: Produits now exists before dependents)
4. `2026_02_11_112830_create_favoris_table.php` (Favoris - references produits)
5. `2026_02_11_113524_create_conversations_table.php` (Conversations - references produits)

## 3. Production Deployment Commands

To deploy these changes and initialize the cloud database, the following commands are used. The `--force` flag is mandatory in production environments to bypass Laravel's "destructive action" protection.

### Migrate and Seed (Fresh):
```bash
php artisan migrate:fresh --seed --force
```

## 4. Admin User Creation Utility

A one-liner utility for creating an administrative user directly in the cloud database via Tinker, bypassing mass-assignment protection on the `role` field.

### Create Admin via Tinker:
```bash
php artisan tinker --execute="App\Models\User::forceCreate(['name' => 'admin', 'email' => 'ethan1989nj@gmail.com', 'password' => bcrypt('your_password'), 'role' => 'admin'])"
```

## 5. Summary of Schema Hardening
*   **Sequential Indexing**: All `merge()` operations on collections now use `->values()` to ensure JSON array parsing compatibility between PHP and Alpine.js.
*   **Cascade Protection**: All foreign keys use `cascadeOnDelete()` or `nullOnDelete()` to maintain referential integrity in a multi-tenant MySQL environment.
