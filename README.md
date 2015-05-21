# laravel-translation-check

Checks If any of the translation files are missing keys from the default language

## example
```php
<?php

include('src/AutoLoader.php');

use GedasTheEvil\LaravelTranslationCheck\TranslationCheck;

(new TranslationCheck(__DIR__))->runInConsole();

```

**Note:** You dont need to include the AutoLoader if you are using composer to get this package