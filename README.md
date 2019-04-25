# slim-skeleton

## 运行方式
1. docker中以nginx + php-fpm方式运行

    docker-compose up -d   

2. swoole http server方式运行
   
    php public/SlimServer.php -s start/stop/reload
    配置nginx转发请求

## 代码说明
1. 控制器代码目录  src/Modules/[模块名称]/Controllers/
2. 模型代码的目录  src/Modules/[模块名称]/Models/ or src/Models
3. 视图代码的目录  src/Modules/[模块名称]/Views
4. 命令行任务代码目录(也属于控制器)  src/Commands
5. Swoole Task目录 src/Tasks

## 示例说明
详见 src/Modules/Home/Controllers/Example.php
     src/Modules/Home/Models/Users.php
     src/Modules/Home/Views/Example/View.php