# Backend — Scandiweb Fullstack Test



## Stack & Dependencies

- PHP
- MySQL
- [webonyx/graphql-php](https://github.com/webonyx/graphql-php) — GraphQL implementation
- [Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html) — DB abstraction layer
- [FastRoute](https://github.com/nikic/FastRoute) — Lightweight routing
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) — Environment variable management
- PSR-4 autoloading via Composer

---

## Folder Structure

```
backend/
│
├── public/                  # Public index entry point
├── src/
│   ├── Controller/          # GraphQL HTTP entry controller
│   ├── Database/            # DB connection (via Doctrine DBAL)
│   ├── GraphQL/             # Schema, Types, Inputs, Mutations
│   ├── Models/              # Domain models (Attribute, Product, etc.)
│   ├── Repositories/        # Data access logic
│   ├── Resolvers/           # GraphQL resolvers
│   └── Services/            # Business logic (e.g., OrderService)
│
├── vendor/                  # Composer dependencies
├── .env                     # Environment config (DB connection, etc.)
├── bootstrap.php            # Loads env, DB connection, etc.
├── seed.php                 # Seeds database from data.json
├── data.json                # Raw data used for seeding
├── composer.json            # PHP package config & autoload
└── README.md                # You are here
```

---

## Setup Instructions

1. Clone the repo and `cd backend`
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up your `.env`:
   ```
   DB_HOST=DB_HOST
   DB_PORT=DB_PORT
   DB_NAME=DB_NAME
   DB_USER=DB_USER
   DB_PASS=DB_PASS
   ```

4. Create the database and run the seed script:
   ```bash
   php seed.php
   ```

5. Start a local server:
   ```bash
   php -S localhost:8000 -t public
   ```


---

##  Notes

- All data comes from `data.json` and is seeded into the database via `seed.php`.
- The GraphQL schema supports product querying, attribute structure, and a `placeOrder` mutation.
- Clean modular structure for types, resolvers, and mutations.

---
