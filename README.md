<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii Чат
</p>


## Install

### Запуск composer
```bash
$ docker exec -it app.chat composer install
```

### Миграция таблицы

~~~
yii migrate

yii migrate --migrationPath=@yii/rbac/migrations
~~~

### Создать администратор

~~~
yii rbac/set-admin
~~~