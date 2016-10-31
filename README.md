# ShareX Custom Uploader for your domain
Annoyed for publish your image/files into other sharing hosting? You wanna share images/files for your business with your domain? Is really simple now with this Silex application.

# Installation
The installation process is the same for any composer project:

`composer create-project nunopress/silex-sharex-hosting-uploader`

You need to configure the application into `/bootstrap.php` and change this 2 lines:

```
upload_dir: 'uploads',
upload_secret: 'secret-key'
```

That's all for the configuration process.

> __Note:__ inside the webroot `web` you found 3 files (_index.php, app.php and app_dev.php_), `app.php` and `index.php` are the same (_useful for who have configured Nginx with front controller index.php_) and `app_dev.php` with `$app['debug] = true` for debugging.

> __Note:__ the `.htaccess` is production ready, if you need to develop you need to change `index.php` front controller to `app_dev.php` controller or add manually `$app['debug] = true` to the application;

# ShareX Configuration
For configure ShareX you need to add the custom uploader and configure it, in the first place you need to add it:

### Select destination
![screenshot-1](https://screenshots.nunopress.com/view/31-10-2016-1477919829.png)

### Select destination settings
![screenshot-2](https://screenshots.nunopress.com/view/31-10-2016-1477920010.png)

### Configure custom uploader
![screenshot-3](https://screenshots.nunopress.com/view/31-10-2016-1477920477.png)

Select your custom uploader in the left bottom section.

Change the __secret__ value based on you Silex application `upload_secret` key.

You can change here the image name format, I like this `dd-mm-YYYY-{unixtime}.ext` if you like too leave it same i write.

