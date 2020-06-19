pipeline {
  agent any
  stages {
    stage('Checkout') {
      steps {
        git(url: 'https://github.com/MissionalDigerati/joshua_project_api.git', branch: 'develop')
      }
    }

    stage('Test') {
      parallel {
        stage('PHP 5.6') {
          agent {
            docker {
              image 'allebb/phptestrunner-56:latest'
              args '-u root:sudo'
            }

          }
          steps {
            echo 'Running PHP 5.6 tests...'
            sh 'php -v'
            echo 'Installing Composer'
            sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
            echo 'Installing project composer dependencies...'
            sh 'cd $WORKSPACE && COMPOSER_MEMORY_LIMIT=-1 composer install --no-progress'
            echo 'Running PHPUnit tests...'
            sh 'php $WORKSPACE/Vendor/bin/phpunit --coverage-html $WORKSPACE/report/clover --coverage-clover $WORKSPACE/report/clover.xml --log-junit $WORKSPACE/report/junit.xml'
            sh 'chmod -R a+w $PWD && chmod -R a+w $WORKSPACE'
            junit 'report/*.xml'
          }
        }
      }
    }

    stage('Release') {
      steps {
        echo 'Ready to release etc.'
      }
    }

  }
}
