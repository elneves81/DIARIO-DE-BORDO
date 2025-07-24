#!/usr/bin/env bash
set -o errexit

echo "Building assets..."
npm install
npm run build

echo "Build complete!"
