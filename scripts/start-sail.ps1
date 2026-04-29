# Start Laravel Sail on Windows (PowerShell)
# Requires Docker Desktop and Git Bash/WSL if your system needs it.

Set-Location -Path $PSScriptRoot\..\
vendor\bin\sail up -d
vendor\bin\sail artisan migrate --force

Write-Host "Sail started and migrations ran. Use 'vendor\\bin\\sail down' to stop."