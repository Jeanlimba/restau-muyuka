# Test de Connexion Base de Données - Déploiement EC2

Ce dossier contient un script pour tester la connexion à la base de données lors du déploiement sur Amazon EC2.

## Fichiers

- `test_db_connection.php` - Script principal de test
- Ce fichier README

## Objectif

Vérifier que la base de données est correctement configurée et accessible depuis l'instance EC2 avant et après le déploiement.

## Prérequis

1. PHP installé sur l'instance EC2
2. Extension PDO MySQL activée
3. Fichier `.env` correctement configuré avec les informations de connexion

## Utilisation

### 1. Test manuel

```bash
# Se connecter à l'instance EC2
ssh -i votre-cle.pem ec2-user@votre-instance-ec2

# Naviguer vers le répertoire du projet
cd /var/www/restau-muyak

# Exécuter le test
php test_db_connection.php
```

### 2. Intégration dans le pipeline de déploiement

Ajoutez cette étape dans votre fichier `.gitlab-ci.yml` ou autre pipeline CI/CD :

```yaml
test_db_connection:
  stage: test
  script:
    - php test_db_connection.php
  only:
    - main
    - staging
```

### 3. Test avant déploiement (pré-production)

```bash
# Tester la connexion avant de déployer
php test_db_connection.php

# Si le test échoue, vérifier :
# 1. Les variables d'environnement dans .env
# 2. La sécurité du groupe (Security Group) sur AWS
# 3. Les permissions de l'utilisateur MySQL
```

## Tests effectués

Le script vérifie :

1. **Connexion à la base de données**
   - Connexion PDO réussie
   - Validation des paramètres de connexion

2. **Permissions de base**
   - Permission SELECT
   - Permission INSERT
   - Permission UPDATE
   - Permission DELETE
   - Permission CREATE (temporaire)

3. **Tables essentielles**
   - Présence des tables critiques (users, products, orders, categories)
   - Nombre d'enregistrements dans chaque table

4. **Performances**
   - Temps d'exécution d'une requête simple
   - Version de MySQL/MariaDB

## Codes de sortie

- `0` : Tous les tests passés avec succès
- `1` : Un ou plusieurs tests ont échoué

## Configuration EC2 spécifique

### 1. Sécurité du groupe (Security Group)

Assurez-vous que le Security Group de votre instance EC2 autorise :
- Entrée (Inbound) : Port 3306 depuis l'adresse IP de l'instance (ou le Security Group)
- Ou utilisez RDS avec accès depuis EC2

### 2. Variables d'environnement sur EC2

Sur EC2, vous pouvez configurer les variables dans `/etc/environment` ou utiliser AWS Systems Manager Parameter Store :

```bash
# Exemple de configuration dans .env sur EC2
DB_HOST=your-rds-endpoint.rds.amazonaws.com
DB_NAME=db_muyak
DB_USER=app_user
DB_PASS=secure_password
```

### 3. Automatisation avec User Data

Ajoutez ce test dans le script User Data de votre instance EC2 :

```bash
#!/bin/bash
# User Data script pour EC2

# Attendre que le système soit prêt
sleep 30

# Naviguer vers l'application
cd /var/www/restau-muyak

# Tester la connexion à la base de données
if php test_db_connection.php; then
    echo "✅ Test de base de données réussi"
else
    echo "❌ Test de base de données échoué"
    # Envoyer une alerte ou journaliser l'erreur
fi
```

## Dépannage

### Problème : "Connection refused"
**Solution :**
- Vérifier que MySQL/MariaDB est en cours d'exécution
- Vérifier les règles du Security Group
- Vérifier que le service écoute sur l'interface correcte

### Problème : "Access denied for user"
**Solution :**
- Vérifier le nom d'utilisateur et le mot de passe dans `.env`
- Vérifier que l'utilisateur a les permissions depuis l'adresse IP de l'EC2
- Exécuter sur MySQL : `GRANT ALL PRIVILEGES ON db_muyak.* TO 'app_user'@'%' IDENTIFIED BY 'password';`

### Problème : "Unknown database"
**Solution :**
- Créer la base de données : `CREATE DATABASE db_muyak;`
- Importer le schéma depuis `sql/schema.sql`

## Tests avancés (optionnels)

Pour des tests plus complets, vous pouvez :

1. **Tester les sauvegardes :**
   ```bash
   mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME > backup_test.sql
   ```

2. **Tester la réplication (si configurée) :**
   ```sql
   SHOW SLAVE STATUS\\G
   ```

3. **Tester les performances avec un benchmark :**
   ```bash
   mysqlslap --host=$DB_HOST --user=$DB_USER --password=$DB_PASS --concurrency=5 --iterations=10 --query="SELECT * FROM users LIMIT 10"
   ```

## Surveillance continue

Pour une surveillance continue sur EC2, configurez CloudWatch Alarms pour :
- Nombre de connexions
- Latence des requêtes
- Utilisation du CPU de la base de données

## Support

Pour toute question ou problème, consultez la documentation AWS RDS/EC2 ou contactez l'administrateur système.
```