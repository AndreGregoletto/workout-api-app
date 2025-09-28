#!/bin/bash

echo "Starting Docker containers..."
docker compose up -d

echo "Waiting for the container to be ready..."
sleep 2

echo "Executing Laravel commands inside the container..."
docker exec workout-api-app-app-1 php artisan migrate
docker exec workout-api-app-app-1 php artisan db:seed

echo "Setup complete!"
# chmod +x up.sh