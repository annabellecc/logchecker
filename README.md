# logchecker
log,debug

1. `annabellecc/logchecker/config/logchecker.php` 复制到 app`config/`文件夹下，并进行对应的修改
2. 路由配置:logchecker 保证和配置`logchecker.php`中的 url的值保持一致
```
Route::get('logchecker', function(Annabelle\LogChecker\LogChecker $logChecker){
    $logChecker->index();
});
```
