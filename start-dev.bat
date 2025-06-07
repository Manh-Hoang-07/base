@echo off
echo Starting Laravel development environment...
echo.

echo Starting Vite dev server...
start "Vite Dev Server" cmd /k "npm run dev"

timeout /t 3 /nobreak >nul

echo Starting Laravel server...
start "Laravel Server" cmd /k "php artisan serve --host=0.0.0.0 --port=8000"

echo.
echo Development servers started!
echo - Laravel: http://localhost:8000
echo - Vite HMR: http://localhost:5173
echo.
echo Press any key to exit...
pause >nul
