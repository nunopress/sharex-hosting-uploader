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

Testing
-------

Rename the file `phpunit.xml.dist` in `phpunit.xml` and run `phpunit` from the root folder.

[1]: https://github.com/ShareX/ShareX
[2]: https://getcomposer.org/