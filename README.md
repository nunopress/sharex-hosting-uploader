# In testing

# ShareX Custom Uploader for your domain
Annoyed for publish your image/files into other sharing hosting? You wanna share images/files for your business with your domain? Is really simple now with this Symfony Bundle.

# Installation
The installation process is the same for any Symfony bundles:

`composer require nunopress/sf-sharex`

Create new parameter into your `config.yml` with key `nunopress_sharex_secret` and value your `pc-name`.

```yaml
parameters:
  nunopress_sharex_secret: EXAMPLE-PC
```

Add into your `AppKernel` (_for standard edition_) or `MicroKernel` (_for Micro Edition_) the class bundle `NunoPress\ShareXBundle`.

# Support
Send any question's or issue here on Github.
