#!/usr/bin/env bash
# Relance le serveur Django de développement (utilisé pendant la migration).
LOG="${1:-/tmp/django.log}"
powershell -NoProfile -Command "Get-Process python -ErrorAction SilentlyContinue | Where-Object { \$_.Path -like '*BDM\backend\.venv*' } | Stop-Process -Force" 2>/dev/null
sleep 2
cd /c/xampp/htdocs/BDM/backend
nohup .venv/Scripts/python.exe manage.py runserver 8001 --noreload > "$LOG" 2>&1 &
for _ in $(seq 1 20); do
  sleep 1
  if curl -s -o /dev/null http://127.0.0.1:8001/login 2>/dev/null; then echo "Django pret"; exit 0; fi
done
echo "Django n'a pas demarre"; tail -20 "$LOG"; exit 1
