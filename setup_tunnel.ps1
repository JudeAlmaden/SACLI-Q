$tunnelProcess = Start-Process cloudflared -ArgumentList "tunnel --url http://127.0.0.1:8000" -NoNewWindow -PassThru -RedirectStandardError "tunnel.log"

Write-Host "Waiting for Cloudflare Tunnel URL..." -ForegroundColor Cyan

$url = $null
$timeout = 30 # seconds
$elapsed = 0

while ($null -eq $url -and $elapsed -lt $timeout) {
    if (Test-Path "tunnel.log") {
        $content = Get-Content "tunnel.log" -Raw
        if ($content -match "https://[a-zA-Z0-9-]+\.trycloudflare\.com") {
            $url = $matches[0]
        }
    }
    Start-Sleep -Seconds 1
    $elapsed++
}

if ($null -ne $url) {
    Write-Host "Captured URL: $url" -ForegroundColor Green
    
    $envFile = ".env"
    if (Test-Path $envFile) {
        $lines = Get-Content $envFile
        $found = $false
        $newLines = foreach ($line in $lines) {
            if ($line -match "^APP_EXTERNAL_URL=") {
                "APP_EXTERNAL_URL=$url"
                $found = $true
            } else {
                $line
            }
        }
        
        if (-not $found) {
            $newLines += "APP_EXTERNAL_URL=$url"
        }
        
        $newLines | Set-Content $envFile
        Write-Host "Updated .env with APP_EXTERNAL_URL" -ForegroundColor Green
    } else {
        Write-Host ".env file not found!" -ForegroundColor Red
    }
} else {
    Write-Host "Failed to capture Cloudflare URL within $timeout seconds." -ForegroundColor Red
    # Optionally stop the process if failed
    # Stop-Process -Id $tunnelProcess.Id
}
