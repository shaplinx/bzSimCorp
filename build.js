// build.js
const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

// Format date as YYYYMMDD
const now = new Date();
const yyyy = now.getFullYear();
const mm = String(now.getMonth() + 1).padStart(2, '0');
const dd = String(now.getDate()).padStart(2, '0');
const buildDate = `${yyyy}${mm}${dd}`;

// Set paths
const sourceDir = 'public/build';
const archiveDir = 'build';
const archiveName = `${buildDate}.tar.gz`;
const archivePath = path.join(archiveDir, archiveName);

// Ensure /build directory exists
fs.mkdirSync(archiveDir, { recursive: true });

// Create archive: build/YYYYMMDD.tar.gz containing public/build/*
execSync(`tar -czf ${archivePath} -C public build`, { stdio: 'inherit' });

console.log(`✅ Archive created at: ${archivePath}`);
