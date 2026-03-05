pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile.ci'
      // uniquement rejoindre le réseau docker compose
      args "--network tea-app_backend"
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
      steps {
        checkout scm
      }
    }

    stage('DNS Debug') {
      steps {
        sh '''
          echo "== DNS =="
          getent hosts mysql || true
          getent hosts mysql_tea || true

          echo "== ENV DB_ =="
          env | grep DB_ || true
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
          php -m | grep -i pdo_mysql

          echo "Waiting for MySQL..."

          php -r '
            $host=getenv("DB_HOST");
            $port=getenv("DB_PORT");
            $db=getenv("DB_DATABASE");
            $user=getenv("DB_USERNAME");
            $pass=getenv("DB_PASSWORD");

            $dsn="mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $deadline=time()+90;

            while (true) {
              try {
                new PDO($dsn,$user,$pass);
                echo "MySQL OK\n";
                exit(0);
              } catch (Throwable $e) {
                if (time()>$deadline) {
                  fwrite(STDERR,"MySQL NOT READY: ".$e->getMessage()."\n");
                  exit(1);
                }
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

          if [ ! -f .env ]; then
            cp .env.example .env
          fi

          php artisan key:generate --force || true

          php artisan config:clear
          php artisan cache:clear

          rm -f bootstrap/cache/*.php || true

          echo "Sanity check"
          php -r 'echo getenv("DB_CONNECTION").PHP_EOL;'
          php -r 'echo getenv("DB_HOST").PHP_EOL;'

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
          echo "githubNotify skipped"
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
          echo "githubNotify skipped"
        }
      }
    }

    always {
      cleanWs()
    }
  }
}