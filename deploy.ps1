# Deploy script for Windows
$SERVER = "root@207.148.127.6"  # THAY YOUR_VPS_IP bang IP thuc cua ban
$PROJECT_NAME = "studioai"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "DEPLOYING $PROJECT_NAME TO PRODUCTION" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Confirm
$confirmation = Read-Host "Deploy to PRODUCTION? (y/n)"
if ($confirmation -ne 'y') {
    Write-Host "Deployment cancelled" -ForegroundColor Red
    exit
}

# Check git status
$gitStatus = git status --porcelain
if ($gitStatus) {
    Write-Host "You have uncommitted changes. Commit or stash them first." -ForegroundColor Red
    git status -s
    exit
}

# Push to GitHub
Write-Host "Pushing to GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to push to GitHub" -ForegroundColor Red
    exit
}

# Deploy on server
Write-Host "Running deployment on server..." -ForegroundColor Yellow
ssh $SERVER "/www/wwwroot/deploy.sh"

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "Deployment completed successfully!" -ForegroundColor Green
    Write-Host "Visit: https://studioai.allship.vn" -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "Deployment failed! Check the logs above." -ForegroundColor Red
    exit 1
}