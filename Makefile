SHELL := /bin/bash 

install: 
	git clone https://github.com/AnatolyAG/laravel_pet_project.git
	cd laravel_pet_project

build:
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php chmode -R 777 .
	docker-compose exec php php artisan migrate:fresh --seed
	docker-compose down

start:
	docker-compose up -d
restart:
	docker-compose restart
stop:
	docker-compose stop
down:
	docker-compose down -v 

