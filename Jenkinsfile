pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile.ci'
      args "--network backend"
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
    DB_HOST = 'db'
    DB_PORT = '3306'
    DB_DATABASE = 'tea_test'
    DB_USERNAME = 'root'
    DB_PASSWORD = credentials('tea_mysql_root_password')  // <-- on met le secret Jenkins ici

    CACHE_STORE = 'array'
    SESSION_DRIVER = 'array'
    QUEUE_CONNECTION = 'sync'
    MAIL_MAILER = 'array'
  }

  stages {
    stage('Checkout') { steps { checkout scm } }

    stage('Install Dependencies') {
      steps { sh 'composer install --no-interaction --prefer-dist' }
    }

    stage('Wait for MySQL') {
      steps {
        sh '''
          set -e
          echo "DNS:"
          getent hosts db || true

          php -m | grep -i pdo_mysql

          php -r '
            $host=getenv("DB_HOST"); $port=getenv("DB_PORT"); $db=getenv("DB_DATABASE");
            $user=getenv("DB_USERNAME"); $pass=getenv("DB_PASSWORD");
            $dsn="mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $deadline=time()+120;
            while (true) {
              try { new PDO($dsn,$user,$pass,[PDO::ATTR_TIMEOUT=>2]); echo "MySQL OK\\n"; exit(0); }
              catch (Throwable $e) {
                if (time()>$deadline) { fwrite(STDERR,"MySQL NOT READY: ".$e->getMessage()."\\n"); exit(1); }
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
          [ -f .env ] || cp .env.example .env

          php artisan key:generate --force || true
          php artisan config:clear || true
          php artisan cache:clear || true
          rm -f bootstrap/cache/*.php || true

          php artisan migrate:fresh --seed --force
        '''
      }
    }

    stage('Run Tests') {
      steps { sh 'php artisan test --stop-on-failure' }
    }
  }

  post {
    always { cleanWs() }
  }
}