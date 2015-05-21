# laravel-translation-check

Checks If any of the translation files are missing keys from the default language

## example
```php
<?php
include('vendor/autoload.php');

use GedasTheEvil\LaravelTranslationCheck\TranslationCheck;

(new TranslationCheck(__DIR__, 'en'))->runInConsole();

```
