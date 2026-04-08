#!/bin/sh

set -e

echo "🔍 Running PHP lint..."

# Lint all PHP files except vendor
find . -type f -name "*.php" -not -path "./vendor/*" | while read file; do
    php -l "$file" > /dev/null
done

echo "✅ PHP lint OK"

# Optional: run Snyk if installed
if command -v snyk >/dev/null 2>&1; then
    echo "🔐 Running Snyk security check..."
    snyk test || {
        echo "❌ Snyk found vulnerabilities"
        exit 1
    }
    echo "✅ Snyk check OK"
else
    echo "ℹ️ Snyk not installed locally, skipping"
fi

echo "🚀 Starting test server..."

# Start PHP built-in server in background
php -S localhost:8001 -t public > /dev/null 2>&1 &
SERVER_PID=$!

# Wait briefly to ensure server is ready
sleep 1

echo "🩺 Checking /health endpoint..."

# Perform health check
if ! curl -s -f http://localhost:8001/health > /dev/null; then
    echo "❌ Health check failed"
    kill $SERVER_PID
    exit 1
fi

echo "✅ Health check OK"

# Stop the server
kill $SERVER_PID

echo "🎉 All checks passed"