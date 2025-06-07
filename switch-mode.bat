@echo off
echo ========================================
echo   Laravel Asset Loading Mode Switcher
echo ========================================
echo.
echo Choose your preferred loading mode:
echo.
echo 1. FAST MODE (Static files - fastest loading)
echo 2. VITE MODE (Build system - with optimization)
echo 3. DEV MODE (Vite dev server - with hot reload)
echo.
set /p choice="Enter your choice (1-3): "

if "%choice%"=="1" (
    echo.
    echo Switching to FAST MODE...
    copy "resources\views\admin\index-fast.blade.php" "resources\views\admin\index.blade.php" >nul
    copy "resources\js\main.js" "public\js\main.js" >nul
    copy "resources\js\admin-actions.js" "public\js\admin-actions.js" >nul
    echo ✓ Using static JS/CSS files for maximum speed
    echo ✓ No build process required
    echo ✓ CSS/JS loaded directly from public folder
    echo.
    echo FAST MODE activated! 🚀
) else if "%choice%"=="2" (
    echo.
    echo Switching to VITE MODE...
    copy "resources\views\admin\index-vite.blade.php" "resources\views\admin\index.blade.php" >nul
    echo Building assets with Vite...
    npm run build
    echo ✓ Using Vite built assets with CSS processing
    echo ✓ Optimized, minified, and cache-busted
    echo ✓ CSS processed through Vite pipeline
    echo.
    echo VITE MODE activated! ⚡
) else if "%choice%"=="3" (
    echo.
    echo Switching to DEV MODE...
    copy "resources\views\admin\index-vite.blade.php" "resources\views\admin\index.blade.php" >nul
    echo ✓ Hot module replacement enabled
    echo ✓ Development mode with live reload
    echo ✓ CSS/JS processed through Vite dev server
    echo.
    echo DEV MODE activated! 🔥
    echo Starting dev server...
    start "Vite Dev Server" cmd /k "npm run dev"
) else (
    echo Invalid choice. Please run the script again.
    pause
    exit /b 1
)

echo.
echo ========================================
echo Mode switch completed!
echo ========================================
pause
