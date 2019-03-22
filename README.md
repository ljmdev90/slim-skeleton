# slim-skeleton

## 运行方式
1. nginx + php-fpm  

    docker-compose up -d   

2. nginx + swoole
   
    php public/SlimServer.php -s start/stop/reload
    配置nginx转发请求

## 代码说明
1. 控制器代码目录  src/Modules/[模块名称]/Controllers/
2. 模型代码的目录  src/Modules/[模块名称]/Models/ or src/Models
3. 视图代码的目录  src/Modules/[模块名称]/Views
4. 命令行任务代码目录(也属于控制器)  src/Commands