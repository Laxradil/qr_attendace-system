# Clean runtime artifacts (Windows PowerShell)
Set-Location -Path $PSScriptRoot\..\
Remove-Item -Recurse -Force storage\framework\views\* -ErrorAction SilentlyContinue
php artisan view:clear
php artisan cache:clear
Write-Host "Cleaned runtime artifacts."