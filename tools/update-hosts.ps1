# update-hosts.ps1
# Script to update Windows hosts file with virtual host domains from Apache config
# Run this script as Administrator when new subdomains are added to configs/local-vhosts.conf

# Path to Apache config
$configPath = Join-Path (Split-Path $PSScriptRoot -Parent) "configs\local-vhosts.conf"

# Path to hosts file
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"

# Read Apache config
if (-not (Test-Path $configPath)) {
    Write-Host "Apache config file not found at $configPath"
    exit 1
}

$content = Get-Content $configPath

# Extract domains from ServerName and ServerAlias
$domains = @()
foreach ($line in $content) {
    if ($line -match "ServerName\s+(.+)") {
        $domains += $matches[1].Trim()
    } elseif ($line -match "ServerAlias\s+(.+)") {
        $aliases = $matches[1] -split '\s+'
        foreach ($alias in $aliases) {
            $domains += $alias.Trim()
        }
    }
}

# Remove duplicates
$domains = $domains | Select-Object -Unique

# Read current hosts file
$hostsContent = Get-Content $hostsPath

# Check and add missing entries
$updated = $false
foreach ($domain in $domains) {
    $entry = "127.0.0.1 $domain"
    if ($hostsContent -notcontains $entry) {
        $hostsContent += $entry
        $updated = $true
        Write-Host "Added: $entry"
    } else {
        Write-Host "Already exists: $entry"
    }
}

# Write back if updated
if ($updated) {
    $hostsContent | Set-Content $hostsPath
    Write-Host "Hosts file updated successfully."
} else {
    Write-Host "No new entries to add."
}