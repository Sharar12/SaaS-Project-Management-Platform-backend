# Laravel + MySQL Backend (Upcoming)

This folder will contain the PHP / Laravel REST API and MySQL migrations.

## Integration Contract with Frontend
1. Start Laravel API (e.g., `http://localhost:8000/api/v1`).
2. Set `NEXT_PUBLIC_API_MODE=laravel` and `NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1` in `frontend/.env.local`.
3. Update `frontend/src/services/apiService.ts` to call the Laravel endpoints.
4. Delete `frontend/src/mock-backend/` safely with zero impact on UI or Features.
