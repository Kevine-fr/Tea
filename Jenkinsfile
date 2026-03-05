pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile.ci'
      // ton args est OK
      args '-v $WORKSPACE:/workspace -w /workspace'
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
    DB_HOST = 'mysql'
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

    stage('Install Dependencies') {
      steps {
        sh 'composer install --no-interaction --prefer-dist'
      }
    }

    stage('Wait for MySQL') {
      steps {
        sh '''
          set -e

          # Debug rapide
          php -v
          php -m | grep -i pdo_mysql || (echo "pdo_mysql missing" && exit 1)

          echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
          # ping MySQL via PHP (pas besoin de mysql-client)
          php -r '
            $host=getenv("DB_HOST"); $port=getenv("DB_PORT"); $db=getenv("DB_DATABASE");
            $user=getenv("DB_USERNAME"); $pass=getenv("DB_PASSWORD");
            $dsn="mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $deadline=time()+60;
            while (true) {
              try { new PDO($dsn,$user,$pass,[PDO::ATTR_TIMEOUT=>2]); echo "MySQL OK\\n"; exit(0); }
              catch (Throwable $e) { if (time()>$deadline) { fwrite(STDERR,"MySQL NOT READY: ".$e->getMessage()."\\n"); exit(1); } sleep(2); }
            }
          '
        '''
      }
    }

    stage('Environment Setup') {
      steps {
        sh '''
          set -e

          # Garantir un .env (Laravel le lit parfois selon ton code)
          if [ ! -f .env ]; then
            cp .env.example .env
          fi

          # IMPORTANT: ne pas forcer sqlite ici
          # On s'appuie sur les variables d'environnement du pipeline

          php artisan key:generate --force || true

          # Très important : éviter un ancien cache de config qui figerait sqlite
          php artisan config:clear
          php artisan cache:clear

          # Sanity check: doit afficher mysql
          php -r 'echo "DB_CONNECTION env=".getenv("DB_CONNECTION").PHP_EOL;'
          php artisan tinker --execute="dump(config('database.default'));"

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