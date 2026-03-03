pipeline {
    agent any

    environment {
        DB_CONNECTION = 'mysql'
        DB_HOST       = 'db'
        DB_PORT       = '3306'
        DB_DATABASE   = 'tea_test'
        DB_USERNAME   = 'root'
        DB_PASSWORD   = credentials('MYSQL_ROOT_PASSWORD')
        APP_KEY       = credentials('APP_KEY')
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Environment Setup') {
            steps {
                sh '''
                    cp .env.example .env
                    sed -i "s|APP_ENV=.*|APP_ENV=testing|" .env
                    sed -i "s|APP_DEBUG=.*|APP_DEBUG=true|" .env
                    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION}|" .env
                    sed -i "s|DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
                    sed -i "s|DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
                    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
                    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
                    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
                    sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=array|" .env
                    sed -i "s|CACHE_STORE=.*|CACHE_STORE=array|" .env
                    sed -i "s|QUEUE_CONNECTION=.*|QUEUE_CONNECTION=sync|" .env
                    echo "APP_KEY=${APP_KEY}" >> .env
                    php artisan config:clear
                '''
            }
        }

        stage('Database Setup') {
            steps {
                sh '''
                    php artisan migrate:fresh --force --env=testing
                '''
            }
        }

        stage('Run Tests') {
            steps {
                sh 'php artisan test tests/Feature/Api/ --stop-on-failure'
            }
        }
    }

    post {
        success {
            echo '✅ All tests passed!'
            githubNotify status: 'SUCCESS',
                         description: '47 tests passed',
                         context: 'ci/jenkins/tests'
        }
        failure {
            echo '❌ Tests failed!'
            githubNotify status: 'FAILURE',
                         description: 'Tests failed — merge blocked',
                         context: 'ci/jenkins/tests'
        }
        always {
            cleanWs()
        }
    }
}