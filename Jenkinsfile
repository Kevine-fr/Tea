pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile.ci'
      // Rejoint le réseau compose pour résoudre mysql + conserve workspace
      args "--network tea-app_backend -v ${WORKSPACE}:/workspace -w /workspace"
    }
  }

  options {
    timestamps()
    disableConcurrentBuilds()
  }

  environment {
    APP_ENV = 'testing'
    APP_DEBUG = 'true'

    DB_CONNECTION = 'mysql'
    DB_HOST = 'mysql'      // à confirmer via DNS Debug (mysql vs mysql_tea)
    DB_PORT = '3306'
    DB_DATABASE = 'tea_test'
    DB_USERNAME = 'root'
    DB_PASSWORD = 'secret'

    CACHE_STORE = 'array'
    SESSION_DRIVER = 'array'
    QUEUE_CONNECTION = 'sync'
    MAIL_MAILER = 'array'
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('DNS Debug') {
      steps {
        sh '''
          set -e
          echo "== DNS =="
          getent hosts mysql || true
          getent hosts mysql_tea || true
          echo "== ENV DB_ =="
          env | grep -E '^DB_' || true
        '''
      }
    }

    stage('Install Dependencies') {
      steps {
        sh 'composer install --no-interaction --prefer-dist'
      }
    }

    stage('Wait for MySQL') {
      steps {
        sh '''
          set -e

          php -v
          php -m | grep -i pdo_mysql || (echo "pdo_mysql missing" && exit 1)

          echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT} (db=${DB_DATABASE})..."
          php -r '
            $host=getenv("DB_HOST"); $port=getenv("DB_PORT"); $db=getenv("DB_DATABASE");
            $user=getenv("DB_USERNAME"); $pass=getenv("DB_PASSWORD");
            $dsn="mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $deadline=time()+90;
            while (true) {
              try { new PDO($dsn,$user,$pass,[PDO::ATTR_TIMEOUT=>2]); echo "MySQL OK\n"; exit(0); }
              catch (Throwable $e) {
                if (time()>$deadline) { fwrite(STDERR,"MySQL NOT READY: ".$e->getMessage()."\n"); exit(1); }
                sleep(2);
              }
            }
          '
        '''
      }
    }

    stage('Environment Setup') {
      steps {
        sh '''
          set -e

          # Garantir un .env (certains projets lisent .env même en CI)
          if [ ! -f .env ]; then
            cp .env.example .env
          fi

          php artisan key:generate --force || true

          # Nettoyer caches (sinon ça peut "bloquer" sqlite)
          php artisan config:clear || true
          php artisan cache:clear || true
          rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/services.php || true

          echo "Sanity:"
          php -r 'echo "DB_CONNECTION env=".getenv("DB_CONNECTION").PHP_EOL;'
          php -r 'echo "DB_HOST env=".getenv("DB_HOST").PHP_EOL;'

          # Vérifier ce que Laravel voit réellement
          php artisan env || true
          php artisan config:show database.default || true

          php artisan migrate:fresh --seed --force
        '''
      }
    }

    stage('Run Tests') {
      steps {
        sh 'php artisan test --stop-on-failure'
      }
    }
  }

  post {
    success {
      echo '✅ Tests passed'
      script {
        try {
          githubNotify status: 'SUCCESS',
                       description: 'Tests passed',
                       context: 'ci/jenkins/tests'
        } catch (e) {
          echo "githubNotify skipped: ${e}"
        }
      }
    }
    failure {
      echo '❌ Tests failed'
      script {
        try {
          githubNotify status: 'FAILURE',
                       description: 'Tests failed — merge blocked',
                       context: 'ci/jenkins/tests'
        } catch (e) {
          echo "githubNotify skipped: ${e}"
        }
      }
    }
    always {
      cleanWs()
    }
  }
}