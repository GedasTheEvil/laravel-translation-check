<?php

namespace GedasTheEvil\LaravelTranslationCheck;

use GedasTheEvil\LaravelTranslationCheck\Exceptions\TranslationNotFound;
use GedasTheEvil\LaravelTranslationCheck\Service\Assertion;

class TranslationCheck
{
    const PATH = '/resources/lang/';

    private $root = '/';
    private $dir_tree = [];
    private $translation_tree = [];
    private $default_language = 'en';

    public function __construct($root = __DIR__, $default_language = 'en')
    {
        $this->root = rtrim($root, '/');
        $this->default_language = $default_language;
    }

    public function assertTranslationsExist()
    {
        $this->loadDirTree();
        $this->loadTranslations();

        foreach ($this->translation_tree as $language => $translations) {
            if ($language === $this->default_language) {
                continue;
            }

            Assertion::arrayHasFieldKeys(
                $translations,
                $this->translation_tree[$this->default_language],
                "Translation for '{$language}' :: "
            );
        }
    }

    protected function loadDirTree()
    {
        $this->dir_tree = $this->getDirList($this->getDir(), true);

        if (!in_array($this->default_language, $this->dir_tree)) {
            throw new TranslationNotFound("Could not find the '{$this->default_language}'" .
                " language that was supposed to be the default!");
        }
    }

    protected function getDir($dir = '')
    {
        return $this->root . self::PATH . trim($dir, '/');
    }

    protected function getDirList($dir, $only_dir = false)
    {
        $result = [];
        $raw = scandir($dir);

        if (!(is_array($raw) && count($raw) > 0)) {
            return $result;
        }

        foreach ($raw as $item) {
            if (!in_array($item, ['.', '..']) &&
                (!$only_dir || ($only_dir && is_dir("{$dir}/{$item}")))
            ) {
                $result[] = $item;
            }
        }

        return $result;
    }

    protected function loadTranslations()
    {
        $this->translation_tree = [];

        foreach ($this->dir_tree as $dir) {
            $this->translation_tree[$dir] = $this->loadTranslationsFromDir($this->getDir($dir));
        }
    }

    protected function loadTranslationsFromDir($dir)
    {
        $result = [];
        $files = $this->getDirList($dir, false);

        foreach ($files as $file) {
            if (is_dir($file)) {
                $result[$file] = $this->loadTranslationsFromDir("{$dir}/{$file}");
            } else {
                $result[$file] = $this->getTranslationContent("{$dir}/{$file}");
            }
        }

        return $result;
    }

    /**
     * @param string $file
     * @return array
     */
    protected function getTranslationContent($file)
    {
        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            return include($file);
        } else {
            echo "\n\nFile '{$file}' Does not exist? \n\n";
        }

        return [];
    }

    public function runInConsole()
    {
        echo "Checking Translations ... \n\n";

        try {
            $this->assertTranslationsExist();
            ConsoleColor::green("All translations exist");
        } catch (Exception $e) {
            ConsoleColor::red(get_class($e));
            ConsoleColor::blue($e->getMessage());
        }
    }
}
