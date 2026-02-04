# Projet Tea - Laravel avec Monitoring

## Services disponibles

- **Application Laravel** : http://localhost:8000
- **phpMyAdmin** : http://localhost:8090
- **Grafana** : http://localhost:3000 (admin / voir .env)
- **Prometheus** : http://localhost:9090
- **cAdvisor** : http://localhost:8080
- **Node Exporter** : http://localhost:9100

## Installation

1. Copier `.env.example` vers `.env` et configurer les mots de passe
2. Lancer les services :
```bash
   docker-compose up -d
```
3. Accéder à Grafana et importer les dashboards

## Métriques disponibles

- Requêtes HTTP par seconde
- Temps de réponse moyen et P95
- Monitoring des conteneurs Docker
- Métriques système (CPU, RAM, disque)

## Développement
```bash
# Voir les logs
docker logs tea_serveur -f

# Accéder au conteneur
docker exec -it tea_serveur bash

# Redémarrer les services
docker-compose restart
```