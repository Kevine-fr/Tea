pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile.ci'
      args '-v $WORKSPACE:/workspace -w /workspace'
    }
  }

  options {
    timestamps()
    disableConcurrentBuilds()
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

    stage('Environment Setup (SQLite)') {
      steps {
        sh '''
          set -e

          if [ ! -f .env ]; then
            cp .env.example .env
          fi

          # Forcer env de test
          sed -i 's/^APP_ENV=.*/APP_ENV=testing/' .env || true
          sed -i 's/^APP_DEBUG=.*/APP_DEBUG=true/' .env || true

          # SQLite in memory (stable CI)
          if grep -q '^DB_CONNECTION=' .env; then
            sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
          else
            echo 'DB_CONNECTION=sqlite' >> .env
          fi

          if grep -q '^DB_DATABASE=' .env; then
            sed -i 's/^DB_DATABASE=.*/DB_DATABASE=:memory:/' .env
          else
            echo 'DB_DATABASE=:memory:' >> .env
          fi

          # éviter dépendances redis/queue/session en CI
          grep -q '^CACHE_STORE=' .env && sed -i 's/^CACHE_STORE=.*/CACHE_STORE=array/' .env || echo 'CACHE_STORE=array' >> .env
          grep -q '^SESSION_DRIVER=' .env && sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=array/' .env || echo 'SESSION_DRIVER=array' >> .env
          grep -q '^QUEUE_CONNECTION=' .env && sed -i 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env || echo 'QUEUE_CONNECTION=sync' >> .env

          php artisan key:generate --force || true
          php artisan config:clear || true
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
        // optionnel : si plugin/credentials GitHub configurés
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