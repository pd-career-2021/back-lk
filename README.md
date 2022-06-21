## Коллекция запросов к API в Postman

- Импортируйте коллекцию в Postman: Workspace -> Import -> Link.
```https://www.getpostman.com/collections/852d9f9c4b95b6a77eb6```
- Для выполнения запросов требуется авторизация/регистрация. 
    - Первый пользователь по умолчанию становится администратором. 
    - Выполнить регистрацию можно отправив POST-запрос Register из папки Auth. 
    - Важно: Первая регистрация создает 4 роли - Admin, Student, Employer, User. При изменении стандартных id ролей потребуется внести правки в AuthController.php (38; 107), так как возможности ролей привязываются к вручную указанным id.
    Последующие регистрации будут присваивать роль User, не имеющую доступа к ресурсам ролей Admin, User, Student. Администратор должен будет
    изменить роль пользователя.
- После регистрации/авторизации в HTTP-ответе будет находиться токен авторизации. Скопируйте его.
- Нажмите на корневую папку коллекции и зайдите во вкладку "Variables". Введите URL-адрес хоста и полученный токен.
- Во вкладке Authorization выберите Type -> Bearer Token и введите имя переменной с токеном. 