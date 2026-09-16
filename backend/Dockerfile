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

# scripts/ est exclu du .dockerignore depuis 607ee13 (« embarque scripts/ dans
# l'image du backend ») mais aucune ligne ne le copiait encore : la procédure
# de bascule et l'installation d'un client se jouent depuis le conteneur, sur
# le serveur, et exigeaient jusqu'ici un `docker cp` manuel avant chaque usage.
COPY scripts/ /app/scripts/

RUN chmod +x /app/entrypoint.sh

EXPOSE 8000

# `collectstatic` est exécuté au démarrage, pas au build : le volume partagé
# avec nginx survit aux reconstructions et doit être resynchronisé à chaque
# lancement (cf. entrypoint.sh).
ENTRYPOINT ["/app/entrypoint.sh"]

# 3 workers : suffisant pour la charge actuelle (une soixantaine de comptes),
# et compatible avec la mémoire du VPS mutualisé.
CMD ["gunicorn", "config.wsgi:application", \
     "--bind", "0.0.0.0:8000", \
     "--workers", "3", \
     "--timeout", "120", \
     "--access-logfile", "-", \
     "--error-logfile", "-"]
