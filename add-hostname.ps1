# PowerShell script to add monitoring.local to Windows hosts file
# Run this as Administrator

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$hostname = "monitoring.local"
$ip = "172.23.190.112"
$entry = "$ip`t$hostname"

# Check if entry already exists
$hostsContent = Get-Content $hostsPath -Raw
if ($hostsContent -notmatch [regex]::Escape($hostname)) {
    # Add the entry
    Add-Content -Path $hostsPath -Value "`n$entry"
    Write-Host "✓ Added $hostname to hosts file" -ForegroundColor Green
} else {
    Write-Host "✓ $hostname already exists in hosts file" -ForegroundColor Yellow
}

Write-Host "`nYou can now access your application at: http://$hostname:8000" -ForegroundColor Cyan
