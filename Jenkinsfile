@Library('shared-library')_
def deployImage = new DeployImage()
env.nodeName = "PHP"

pipeline {
    parameters {
        string(name: 'PRODUCTION_NAMESPACE',       description: 'Production Namespace',                 defaultValue: 'telkomtesthouse-dev')
        string(name: 'STAGING_NAMESPACE',          description: 'Staging Namespace',                    defaultValue: '')
        string(name: 'DEVELOPMENT_NAMESPACE',      description: 'Development Namespace',                defaultValue: 'telkomtesthouse-dev')

        string(name: 'DOCKER_IMAGE_NAME',          description: 'Docker Image Name',                    defaultValue: 'telkomtesthouse-dev')

        string(name: 'CHAT_ID',                    description: 'chat id of telegram group',            defaultValue: '-383243277')
    }
    agent none
    options {
        skipDefaultCheckout()  // Skip default checkout behavior
    }
    stages {
        stage ( "Kill Old Build" ){
            steps{
                script{
                    KillOldBuild()
        }   }   }
        stage('Checkout SCM') {
            agent { label "PHP" }
            steps {
                checkout scm
                script {
                    echo "get COMMIT_ID"
                    sh 'echo -n $(git rev-parse --short HEAD) > ./commit-id'
                    commitId = readFile('./commit-id')
                }
                stash(name: 'ws', includes:'**,./commit-id') // stash this current workspace
        }   }
        stage('Initialize') {
            parallel {
                stage("Agent: PHP") {
                    agent { label "PHP" }
                    steps {
                        cleanWs()
                           }   }
                stage("Agent: Docker") {
                    agent { label "Docker" }
                    steps {
                        cleanWs()
                        script{
                            if ( env.BRANCH_NAME == 'master' ){
                                envStage = "Production"
                            } else if ( env.BRANCH_NAME == 'release' ){
                                envStage = "Staging"
                            } else if ( env.BRANCH_NAME == 'develop'){
                                envStage = "Development"
        }   }   }   }   }   }
        stage('Test & Build') {
            parallel {
                stage('Unit Test') {
                    agent { label "PHP" }
                    environment { 
                       SQLLITE_PATH = $/${WORKSPACE}/database/dds_db.sqlite/$
                    }
                    steps {
                        unstash 'ws'
                        script {
                            echo "Do Unit Test Here"
                            echo "Prepare Unit Test"
                            sh "/var/lib/jenkins/bin/composer install --no-scripts --no-autoloader"
                            sh "mkdir ./bootstrap/cache"
                            sh "/var/lib/jenkins/bin/composer update"
                            echo "Run Unit Test"
                            sh "mkdir -p ./storage/framework/sessions"
                            sh "mkdir -p ./storage/framework/views"
                            sh "mkdir -p ./storage/framework/cache"
                            sh "php artisan config:clear"
                            sh "php artisan cache:clear"
                            sh "php artisan view:clear"

                            echo "Run sqlite env"
                            sh "touch ${WORKSPACE}/database/dds_db.sqlite"
                            sh "php artisan migrate --database=sqlite"
                            sh "php artisan db:seed --database=sqlite"
                            sh "php artisan key:generate"
                            
                            sh "./vendor/bin/phpunit --log-junit reports/phpunit.xml --coverage-clover reports/phpunit.coverage.xml"
                            
                            echo "defining sonar-scanner"
                            def node = tool name: 'NodeJS-12', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
                            env.PATH = "${node}/bin:${env.PATH}"
                            def scannerHome = tool 'SonarScanner' ;
                            withSonarQubeEnv('SonarQube') {
                                sh "${scannerHome}/bin/sonar-scanner"
                }   }   }   }
                stage('Build') {
                    agent { label "Docker" }
                    steps {
                        unstash 'ws'
                        script{
                            env.nodeName = "${env.NODE_NAME}"
                            sh "docker build --rm --no-cache --pull -t ${params.DOCKER_IMAGE_NAME}:${BUILD_NUMBER}-${commitId} ."
        }   }   }   }   }
        stage ('Deployment'){
            steps{
                node (nodeName as String) { 
                    echo "Running on ${nodeName}"
                    script{
                        if (env.BRANCH_NAME == 'master'){
                            echo "Deploying to ${envStage} "
                            deployImage.to_vsan("${commitId}")
                        } else if (env.BRANCH_NAME == 'release'){
                            echo "Deploying to ${envStage} "
                            deployImage.to_stage("${commitId}")
                        } else if (env.BRANCH_NAME == 'develop'){
                            echo "Deploying to ${envStage} "
                            deployImage.to_vsan("${commitId}")
    }   }   }   }   }   }
    post {
        always{
            node("Docker"){
                TelegramNotif(currentBuild.currentResult) 
	}   }
	failure{
            node(nodeName as String){
                script{
                    sh "docker rmi -f ${params.DOCKER_IMAGE_NAME}:${BUILD_NUMBER}-${commitId}"
}   }   }   }   }
