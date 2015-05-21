<?php

include('src/AutoLoader.php');

use GedasTheEvil\LaravelTranslationCheck\TranslationCheck;

(new TranslationCheck(__DIR__))->runInConsole();
