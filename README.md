ShareX Custom Uploader
======================

This is a custom uploader project for [**ShareX Uploader**][1]

Installation
------------

You need [**Composer**][2] for install this project with this command:

```
composer create-project nunopress/sharex-hosting-uploader project_name
```

Configuration
-------------

The configuration file is into `src/AppBundle/Resources/config/services.yml`.

You need to change the `app.secret` and configure [**ShareX**][1] with the same secret key parameter.

You can change the value of `app.upload_dir` if you want the uploads in another directory,
from default this is configured into `%kernel.root_dir%/../uploads` (_root directory + /uploads_).

In [**ShareX**][1] you need to setup the Response Url with this format:

- URL: `http://yoursite.com/view/$json:filename$`
- Thumbnail URL: `http://yoursite.com/thumb/$json:filename$`
- Deletion URL: `http://yoursite.com/delete/$json:filename$`

Remember to setup too the Arguments:

- Request type:     `POST`
- Request URL:      `http://yoursite.com/upload`
- File form name:   `file`
- Arguments:
    - `secret`: Your secret key used into `app.secret`
    - `name`: The filename format saved on the web server

Testing
-------

Rename the file `phpunit.xml.dist` in `phpunit.xml` and run `phpunit` from the root folder.

[1]: https://github.com/ShareX/ShareX
[2]: https://getcomposer.org/