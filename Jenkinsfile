pipeline {
    agent {
        docker {
            image 'ubuntu:xenial'
            args '-p 8081:8081' 
        }
    }
    environment {
        CI = 'true'
    }
    stages {
        stage('Build') {
            steps {
                sh 'echo Installing softwares to test the app'
            }
        }
        stage('Test') {
            steps {
                sh 'echo "Hello"'
            }
        }

         stage('Deliver for development') {
            when {
                branch 'development' 
            }
            steps {
                sh 'echo "Development deployment here"'
            }
        }
        stage('Deploy for production') {
            when {
                branch 'production'  
            }
            steps {
                sh 'echo "Development production here"'
            }
        }
    }
}
