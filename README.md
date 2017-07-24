# csdnVisit
CSDN博客访问量工具
## 一、 Introduction【介绍】
> 
>CSDN Blog's 刷访问量脚本 (CSDN通过cookie来统计访问量)
>
> 首先遍历获取文章列表，提取每篇博客的地址id
> 
> 再通过file_get_contents函数访问这些地址
> 
> 将博客中所有文章访问一遍，从而达到刷访问量的目的


----------

## 二、 特点
- 使用方便
- 暂支持多种皮肤的正则匹配，扩展方便
- 使用只需修改

```php
/* * * * * * 需要修改的地方* * * * * *  */
$username="ityangs";//文章列表页的用户标识
$page_count=5;//文章的总页数
$visit_count=10; //每篇文章访问次数
/* * * * * * 需要修改的地方* * * * *  */
```
- 访问自动随机更换来访IP和添加浏览器标识


## 三、 使用方式
- Linux【cli模式下执行】
   - 方法一：cli模式下执行
   
```php
    php /var/www/my/csdn/csdnVisit.php
```

   - 方法二：定时任务
 
```php
    crontab -e
    //这条语句就可以在每2小时的0分钟，通过linux内部php环境执行csdnVisit.php
    00 */2 * * * php /var/www/my/csdn/csdnVisit.php
```


-Windows【cli模式下执行】

   - 方法一：cli模式下执行
   
```php
    php d:/www/csdnVisit.php
```

   - 方法二：定时任务也可以
 

​