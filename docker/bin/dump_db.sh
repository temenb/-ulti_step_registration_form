#!/usr/bin/env bash

CONTAINER_NAME="msrf-pgsql";

bash -c "docker exec -i ${CONTAINER_NAME} bash -c \"pg_dump -U postgres -c app_db > /dumps/latest_dump.sql\"";
bash -c "docker exec -i ${CONTAINER_NAME} bash -c \"pg_dump -U postgres -c app_db > /dumps/$(date +%Y-%m-%d_%H:%M:%S)_dump.sql\"";
