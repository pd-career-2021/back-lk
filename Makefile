# ADD MORE GLOBAL VARIABLES HERE
LOCALHOST_PROJECT_DIR := $(shell pwd)
VERSION := $(shell git describe --tags 2> /dev/null || git rev-parse --short HEAD)

# IMPORT CONFIG WITH ENVS. You can change the default config with `make cnf="config_special.env" build`
cnf ?= docker/config.env
include $(cnf)

# EXPORT VARIABLES to be available in our docker-compose scripts
export VERSION := $(VERSION)
export LOCALHOST_PROJECT_DIR := $(LOCALHOST_PROJECT_DIR)
export $(shell sed 's/=.*//' $(cnf))

.PHONY: help
.DEFAULT_GOAL := help
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## This is help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# COMMANDS SECTION

### Managing docker images
.PHONY: install
install: build-dev up-dev ## Install project - all you need for quick start

.PHONY: build-dev up-dev stop-dev down-dev clear-dev
build-dev up-dev stop-dev restart-dev down-dev clear-dev: COMPOSE_FILE=./docker-compose.yml
build-dev: ## Build current version images for dev
	docker-compose -f $(COMPOSE_FILE) build --pull $(SERVICE)
up-dev: ## Up current version containers for dev
	docker-compose -f $(COMPOSE_FILE) up -d
stop-dev: ## Stop current version containers for dev
	docker-compose -f $(COMPOSE_FILE) stop
restart-dev: ## Restart current version containers for dev
	docker-compose -f $(COMPOSE_FILE) restart
down-dev: ## Down current version containers for dev and remove network
	docker-compose -f $(COMPOSE_FILE) down
clear-dev: ## Stop and clear all current version containers for dev
	docker-compose -f $(COMPOSE_FILE) rm -s -f -v

### Commands that will give you vendor folder
.PHONY: install-vendor echo-version composer-dev
install-vendor: echo-version composer-dev install ## Install project with vendor folder
echo-version:
	@echo ${VERSION}
composer-dev: COMPOSER_FLAGS?=install --ignore-platform-reqs
composer-dev: ## Run composer in docker, use arg "COMPOSER_FLAGS" to pass args
	docker run \
	--rm \
	-v $(LOCALHOST_PROJECT_DIR):/app \
	-v ${COMPOSER_HOME}:/tmp \
	-v ${SSH_AUTH_SOCK}:/ssh-auth.sock \
	--user $(id -u):$(id -g) \
	--env SSH_AUTH_SOCK=/ssh-auth.sock \
	composer \
	composer $(COMPOSER_FLAGS)

### Commands to interact with server
.PHONY: shell-php
shell-php: ## open shell in php container
	docker exec -it $(PROJECT_NAME)-php /bin/bash
