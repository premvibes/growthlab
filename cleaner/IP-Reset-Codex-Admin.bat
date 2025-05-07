@echo off
chcp 65001 >nul
color 0A
title ⚡ NetWork Reset ⚡

:: Boot animation
cls
echo 🔄 Initializing Secure Terminal...
ping localhost -n 2 >nul
echo 🧠 Loading modules [██████████] 100%% Complete
ping localhost -n 1 >nul
cls

echo 🛡️ Checking administrator access...
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ACCESS DENIED: Please run this script as Administrator.
    pause
    exit
)
echo ✅ Access granted.
ping localhost -n 1 >nul
cls

:: Begin operations
echo 🔧 Starting System Maintenance...
ping localhost -n 1 >nul

echo.
echo 🌐 [1/3] Resetting Network Stack...
ping localhost -n 1 >nul
echo 🔌 Resetting Winsock...
netsh winsock reset
ping localhost -n 1 >nul
echo 🌐 Resetting TCP/IP Stack...
netsh int ip reset
ping localhost -n 1 >nul
echo 🔄 Releasing IP Address...
ipconfig /release
ping localhost -n 1 >nul
echo ♻️ Renewing IP...
ipconfig /renew
ping localhost -n 1 >nul
echo 🧹 Flushing DNS...
ipconfig /flushdns
echo ✅ Network Reset Complete.
ping localhost -n 2 >nul

echo.
echo 💻 Finalizing...
ipconfig /flushdns
ping localhost -n 1 >nul

:: Creator Credit
echo.
echo ----------------------------------------------
echo ✅ All Tasks Completed Successfully.
echo 👨‍💻 Made with ❤️ by @codexadmin
echo ----------------------------------------------
echo. & pause