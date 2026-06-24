# Kakusin Vietnam - Corporate API

## 📖 Overview

This repository contains the Laravel API backend for the Kakusin Vietnam corporate website. It is designed to run in a **Hybrid Workspace** alongside a Headless WordPress CMS instance.

## 🛠 Tech Stack

- **Framework:** Laravel 11.x
- **Database:** MySQL 8.0
- **Environment:** Docker (Sail Custom)

## 🚀 Local Development Setup

To set up the full ecosystem (API + CMS) on your local machine, follow these exact steps:

### Step 1: Create the Workspace

Create a root folder to hold all microservices and move into it:
\`\`\`bash
mkdir kakusin-local
cd kakusin-local
\`\`\`

### Step 2: Clone Repositories

Clone both the API and the CMS repositories into the workspace:
\`\`\`bash

# Clone the API

git clone https://github.com/Quan-kakusin/kakusin-kvn-corporate-api.git api

# Clone the CMS (WordPress)

git clone https://github.com/kakusin/kvn-corporate-cms.git cms
\`\`\`

### Step 3: Move Docker Compose File

The root `docker-compose.yml` file is stored inside the API repository for convenience. Move it out to the root workspace:
\`\`\`bash
mv api/docker-compose.yml .
\`\`\`

### Step 4: Initialize Containers & Dependencies

Start the Docker containers in detached mode:
\`\`\`bash
docker compose up -d
\`\`\`

Once the containers are running, install the PHP dependencies and setup the Laravel environment:
\`\`\`bash

# Copy env file

cp api/.env.example api/.env

# Install dependencies

docker compose exec kakusin-api composer install

# Generate app key

docker compose exec kakusin-api php artisan key:generate
\`\`\`

### Step 5: Database Migrations

Clear the cache and run migrations to build the custom API tables:
\`\`\`bash
docker compose exec kakusin-api php artisan config:clear
docker compose exec kakusin-api php artisan migrate
\`\`\`

## 📂 API Structure

- `app/Models/Wordpress`: Contains specific models (e.g., `WpPost`, `WpPostTranslation`) that interface directly with the WordPress CMS tables.
- The API handles core business logic independently from the CMS.
