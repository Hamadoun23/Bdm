# Image de production du backend Django.
#
# Le frontend est construit dans une première étape : Django ne sert que le
# manifeste et les fichiers déjà compilés, il n'a pas besoin de Node à l'exécution.

FROM node:24-alpine AS frontend
WORKDIR /build
COPY frontend/package*.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build


FROM python:3.13-slim

ENV PYTHONUNBUFFERED=1 \
    PYTHONDONTWRITEBYTECODE=1 \
    DJANGO_SETTINGS_MODULE=config.settings

WORKDIR /app

# `default-libmysqlclient-dev` n'est pas nécessaire : PyMySQL est en Python pur.
RUN apt-get update \
    && apt-get install -y --no-install-recommends curl \
    && rm -rf /var/lib/apt/lists/*

COPY backend/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt gunicorn

COPY backend/ .
COPY --from=frontend /build/dist /app/../frontend/dist

# Les assets compilés et les fichiers du socle sont regroupés pour être servis
# par nginx sans passer par Django.
RUN python manage.py collectstatic --noinput --clear

EXPOSE 8000

# 3 workers : suffisant pour la charge actuelle (une soixantaine de comptes),
# et compatible avec la mémoire du VPS mutualisé.
CMD ["gunicorn", "config.wsgi:application", \
     "--bind", "0.0.0.0:8000", \
     "--workers", "3", \
     "--timeout", "120", \
     "--access-logfile", "-", \
     "--error-logfile", "-"]
