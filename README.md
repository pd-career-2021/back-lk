## Требования

### Минимальные

- ```docker```
- ```docker-compose```
[Скачать](https://www.docker.com/products/docker-desktop/)

### Рекомендуемые

- Минимальные +
- ```make```
- ```wsl2``` (только для Windows)

## Первый запуск проекта

- В директории проекта создайте файл ```.env``` и скопируйте в него содержимое из ```.env.example```
- Через ```docker-compose```
    - В терминале откройте папку проекта и выполните
      команду ```docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs```
      (Данная команда создаст папку _vendor_ с системными файлами Laravel в корне проекта. Данная папка нужна, чтобы ide
      не выводила предупреждения при импорте. Можно работать и без этой папки)
    - Выполните команду ```docker-compose build```
    - Выполните команду ```docker-compose up -d```
- Через ```make```
    - В _wsl_ откройте папку проекта и выполните команду:
        - ```make install-vendor``` для установки проекта с папкой _vendor_
        - ```make install``` для установки проекта без папки _vendor_
- После завершения работы команд проект должен быть доступен по адресу [127.0.0.1:8005](http://127.0.0.1:8005/)

## Команды

- Для ```docker-compose```
    - Запустить проект: ```docker-compose up -d```
    - Остановить проект: ```docker-compose down```
    - Пересобрать проект: ```docker-compose build```
    - Открыть терминал php-сервера: ```docker exec -it backend-dev-php /bin/bash```
    - Полный список команд: [документация docker-compose](https://docs.docker.com/compose/reference/)
- Для ```make```
    - Запустить проект: ```make up-dev```
    - Остановить проект: ```make stop-dev```
    - Пересобрать проект: ```make build-dev```
    - Открыть терминал php-сервера: ```make shell-php```
    - Полный список команд: ```make help```

## БД

- Имя БД: laravel
- Имя пользователя: dev
- Пароль: pass
- Пароль суперпользователя: root
