
## Старт проекта

Клонировать проект в папку
- git clone 

Поднять докер
- docker-compose up -d --build

Если ошибка сборки php сервиса
- docker-compose build php
- docker-compose up -d



Зайти в контейнер 
- docker exec -it laravel_base bash


## Команды выаолняемые в контейнере

Создать все таблицы и заполнить тестовыми данными
- php artisan migrate:fresh --seed

Если ошибка с правами на запись лог файлов - странное поведение, через раз проявляеться на разных машинах
не пофиксил.

- chmode -R 777 /var/www/laravel/storage/

## Проект доступен по адресу 

- http://localhost:8000/

 ## Точка входа апи 

- http://localhost:8000/api/
  

## Documentation Swagger
 Убрано из проекта
- **[Ref To Documentation](http://localhost:8000/api/documentation)**
