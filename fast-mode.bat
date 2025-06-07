@echo off
echo Switching to FAST MODE...
copy "resources\views\admin\index-fast.blade.php" "resources\views\admin\index.blade.php" >nul
copy "resources\js\main.js" "public\js\main.js" >nul
copy "resources\js\admin-actions.js" "public\js\admin-actions.js" >nul
echo ✓ FAST MODE activated! 🚀 (Static files, maximum speed)
