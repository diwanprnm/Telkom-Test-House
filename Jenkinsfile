pipeline {
    parameters {
        string(name: 'KUBE_DEV_NAMESPACE',         description: 'Kubernetes Development Namespace',                  defaultValue: 'telkomtesthouse')
        string(name: 'KUBE_STAGING_NAMESPACE',     description: 'Kubernetes Development Namespace',                  defaultValue: '')
        string(name: 'KUBE_PROD_NAMESPACE',        description: 'Kubernetes Development Namespace',                  defaultValue: '')
        string(name: 'DOCKER_DEV_REGISTRY_URL',    description: 'docker registry',                                   defaultValue: '')
        string(name: 'DOCKER_STAGING_REGISTRY_URL',description: 'docker registry',                                   defaultValue: '')
        string(name: 'DOCKER_PROD_REGISTRY_URL',   description: 'docker registry',                                   defaultValue: '')
        string(name: 'DOCKER_IMAGE_NAME',          description: 'Docker Image Name',                                 defaultValue: 'telkomtesthouse')
    }
    agent none
    options {
        skipDefaultCheckout()
    }
    stages {
        stage('Checkout SCM') {
            agent { label "jenkins-agent-php-1" }
            steps {
                checkout scm
                script {
                    echo "get COMMIT_ID"
                    sh 'echo -n $(git rev-parse --short HEAD) > ./commit-id'
                    commitId = readFile('./commit-id')
                }
                stash(name: 'ws', includes:'**,./commit-id')
            }
        }

        stage('Initialize') {
            parallel {
                stage("Agent: PHP") {
                    agent { label "jenkins-agent-php-1" }
                    steps {
                        cleanWs()
                    }
                }
                stage("Agent: Docker") {
                    agent { label "jenkins-agent-docker-1" }
                    steps {
                        cleanWs()
                    }
                }
            }
        }

        stage('Unit Test') {
            agent { label "jenkins-agent-php-1" }
            steps {
                unstash 'ws'
                echo "Do Unit Test Here"
            }
        }

        stage('SonarQube Scan') {
            when {
                anyOf {
                    branch 'master'
                    branch 'staging'
                    branch 'development'
                }
            }
            agent { label "jenkins-agent-php-1" }
            steps {
                unstash 'ws'
                echo "Prepare Unit Test"
                echo "Run SonarQube"
                script {
                    echo "defining sonar-scanner"
                    def scannerHome = tool 'SonarQube Scanner' ;
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }
        stage('Build') {
            parallel {
                stage('Build DEV') {
                    when {
                        branch 'development'
                    }
                    agent { label "jenkins-agent-docker-1" }
                    steps {
                        unstash 'ws'
                        script {
                            echo "get COMMIT_ID"
                            commitId = readFile('./commit-id')
                        }
                        sh 'rm -rf ./commit-id'
                        // sh "docker build --rm -t '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}' ."
                    }
                }
                stage('Build STAGING') {
                    when {
                        branch 'staging'
                    }
                    agent { label "jenkins-agent-docker-1" }
                    steps {
                        unstash 'ws'
                        script {
                            echo "get COMMIT_ID"
                            commitId = readFile('./commit-id')
                        }
                        sh 'rm -rf ./commit-id'
                        // sh "docker build --rm -t '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:staging-${BUILD_NUMBER}-${commitId}' ."
                    }
                }
                stage('Build PORD') {
                    when {
                        branch 'master'
                    }
                    agent { label "jenkins-agent-docker-1" }
                    steps {
                        unstash 'ws'
                        script {
                            echo "get COMMIT_ID"
                            commitId = readFile('./commit-id')
                        }
                        sh 'rm -rf ./commit-id'
                        // sh "docker build --rm -t '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:prod-${BUILD_NUMBER}-${commitId}' ."
                    }
                }
                stage('Build Default') {
                    when {
                        not {
                            anyOf {
                                branch 'master'
                                branch 'staging'
                                branch 'development'
                            }
                        }
                    }
                    agent { label "jenkins-agent-docker-1" }
                    steps {
                        unstash 'ws'
                        script {
                            echo "get COMMIT_ID"
                            commitId = readFile('./commit-id')
                        }
                        sh 'rm -rf ./commit-id'
                        sh "docker build --rm -t '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:${BUILD_NUMBER}-${commitId}' ."
                        sh "docker rmi -f ${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:${BUILD_NUMBER}-${commitId}"
                    }
                }
            }
        }

        stage('Deploy to DEV') {
            environment {
                KUBE_DEV_TOKEN = credentials('OC_REGISTRY_TOKEN')
            }
            when {
                branch 'development'
            }
            agent { label "jenkins-agent-docker-1" }
            steps {
                unstash 'ws'
                script {
                    echo "get COMMIT_ID"
                    commitId = readFile('./commit-id')
                }
                sh 'rm -rf ./commit-id'
                // sh "docker tag '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}' '${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}' "
                // sh "docker tag '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}' '${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:latest' "
                // sh "docker login ${params.DOCKER_DEV_REGISTRY_URL} -u jenkins -p ${env.KUBE_DEV_TOKEN}"
                // sh "docker push ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:latest"
                // sh "docker push ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:latest"

            }
        }

        stage('Performance Test') {
            environment {
                KUBE_DEV_TOKEN = credentials('OC_REGISTRY_TOKEN')
            }
            when {
                anyOf {
                    branch 'staging'
                }
            }
            agent { label "jenkins-agent-docker-1" }
            steps {
                echo "Do Performance Test"
            }
        }

        stage('Deploy to STAGING') {
            when {
                branch 'staging'
            }
            agent { label "jenkins-agent-docker-1" }
            steps {
                echo "Deploy to STAGING"
            }
        }

        stage('Deploy to PRODUCTION') {
            environment {
                KUBE_PROD_TOKEN = credentials('VSAN_OC_REGISTRY_TOKEN')
            }
            agent { label "jenkins-agent-docker-1" }
            when {
                branch 'master'
            }
            steps {
                timeout(1) {
                    input message: 'Deploy to PRODUCTION?', ok: 'Deploy'
                }
                // echo "Deploy to PRODUCTION"
                // sh "docker tag '${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:prod-${BUILD_NUMBER}-${commitId}' '${params.DOCKER_PROD_REGISTRY_URL}/${params.KUBE_PROD_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:release-${BUILD_NUMBER}-${commitId}' "
                // sh "docker login ${params.DOCKER_PROD_REGISTRY_URL} -u jenkins -p ${env.KUBE_PROD_TOKEN}"
                // sh "docker push ${params.DOCKER_PROD_REGISTRY_URL}/${params.KUBE_PROD_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:release-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_PROD_REGISTRY_URL}/${params.KUBE_PROD_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:release-${BUILD_NUMBER}-${commitId}"

            }
        }

    }
    post {
        always {
            node("jenkins-agent-docker-1") {
                unstash 'ws'
                script {
                    echo "get COMMIT_ID"
                    commitId = readFile('./commit-id')
                }
                // sh "docker rmi -f ${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:staging-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:prod-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:dev-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:staging-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:prod-${BUILD_NUMBER}-${commitId}"
                // sh "docker rmi -f ${params.DOCKER_DEV_REGISTRY_URL}/${params.KUBE_DEV_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:latest"
                // sh "docker rmi -f ${params.DOCKER_PROD_REGISTRY_URL}/${params.KUBE_PROD_NAMESPACE}/${params.DOCKER_IMAGE_NAME}:release-${BUILD_NUMBER}-${commitId}"
            }
            echo "Notify Build"
            //Call slack
        }
        aborted {
            script {
                currentBuild.result = 'SUCCESS'
            }
        }
    }

}