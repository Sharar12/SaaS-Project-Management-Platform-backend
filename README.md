# OrbitFlow Backend (Laravel + MySQL)

This folder contains the Laravel API source for the OrbitFlow project-management frontend.
It includes the uploaded API implementation plus the Laravel project scaffolding needed to install it with Composer.

## Requirements

- PHP 8.2 or newer
- Composer 2
- MySQL 8.x (or compatible MySQL/MariaDB)
- PHP extensions required by Laravel, including `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, and `fileinfo`

## Install on Windows (PowerShell)

1. Extract this folder next to the `frontend` folder, so the project has `frontend/` and `backend/` side by side.
2. Open a terminal in `backend/`.
3. Install PHP dependencies:

   ```powershell
   composer install
   ```

4. Create your local environment file and application key:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

5. Create a MySQL database named `orbitflow_saas` (or change `DB_DATABASE` in `.env`). Update `DB_USERNAME` and `DB_PASSWORD` as needed.
6. Run migrations and insert the demo records:

   ```powershell
   php artisan migrate --seed
   ```

7. Start the API:

   ```powershell
   php artisan serve --host=127.0.0.1 --port=8000
   ```

The API base URL is `http://localhost:8000/api/v1`. The root URL `http://localhost:8000/` returns a small API health response.

## Main API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api/v1/bundle` | Load workspaces, projects, tasks, members and activity |
| POST | `/api/v1/demo/reset` | Reset all demo data (development only) |
| PATCH | `/api/v1/workspaces/{workspace}` | Update workspace details |
| GET / POST | `/api/v1/projects` | List or create projects |
| GET / POST | `/api/v1/tasks` | List or create tasks |
| PATCH / DELETE | `/api/v1/tasks/{task}` | Update or delete a task |
| POST | `/api/v1/tasks/{task}/comments` | Add a task comment |
| POST | `/api/v1/members` | Add a workspace member record |
| PATCH | `/api/v1/members/{member}/role` | Change a member role |

## Connect the frontend

The supplied frontend adapter is included at `integration/apiService.laravel.ts`.
Copy its contents into `frontend/src/services/apiService.ts` when you are ready to switch away from the mock adapter. Set these values in `frontend/.env.local`:

```env
NEXT_PUBLIC_API_MODE=laravel
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

Restart the Next.js development server after changing environment variables.

## Important scope and security limitations

This is a **development starter/demo API**, not a production-ready multi-tenant SaaS backend yet.

- The provided API routes do not currently require login or verify the caller's identity.
- Role names are stored, but server-side permission checks for Owner/Admin/Member/Viewer have not been implemented.
- Several endpoints can read or modify records by ID without checking that the record belongs to the caller's workspace. Do not expose this API publicly as-is.
- There are no user registration/login endpoints or invitation-email workflow yet. `team_members` are workspace member profiles, not authenticated user accounts.
- ZIP/file upload, private file storage, download permissions, submission history, and boss review/approval workflows are **not implemented** in this backend source.
- The demo reset endpoint deletes data across all workspaces. Keep `APP_DEBUG=true` and `DEMO_RESET_ENABLED=true` only in local development; set both to `false` on any shared or deployed environment, and remove/disable the reset route before production.
- The Free/Pro/Enterprise fields are plan labels only; payment processing and subscription enforcement are not included.

Before production, implement authentication, enforce workspace scoping and role policies on every endpoint, add private file-upload/download endpoints with authorization and size/type limits, and add tests for cross-workspace access.

## Included source

- API controllers, form requests and JSON resources
- Eloquent models and MySQL migrations
- Demo database seeder
- API routes, CORS and Laravel bootstrap configuration
- Laravel 12 Composer project scaffolding
- Frontend API adapter example
