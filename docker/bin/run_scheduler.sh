#!/bin/bash

docker exec -i msrf-php sh -c "php artisan schedule:run"
