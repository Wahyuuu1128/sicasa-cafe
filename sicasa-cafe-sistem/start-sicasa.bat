@echo off
title SICASA Cafe - Local Server

if exist "%~dp0sicasa-cafe-sistem\artisan" (
    cd /d "%~dp0sicasa-cafe-sistem"
) else if exist "%~dp0artisan" (
    cd /d "%~dp0"
)

echo ===================================================
echo   Menjalankan Server SICASA Cafe (Laravel POS)
echo ===================================================
echo.
echo URL Aplikasi: http://127.0.0.1:8000
echo.
echo Login Akun Demo:
echo - Kasir : kasir@sicasa.com / password123
echo - Owner : owner@sicasa.com / password123
echo - QR Meja 1 : http://127.0.0.1:8000/qr/meja/1
echo ===================================================
echo.

C:\laragon\bin\php\php-8.3.22-Win32-vs16-x64\php.exe artisan serve --host=127.0.0.1 --port=8000 --no-reload
pause
