<?php

namespace App\Core;

class ClassFinder
{
    public static function getClassesByPath(string $path): \Generator
    {
        foreach (self::phpFiles($path) as $phpFile) {
            $tokens = self::fileTokens($phpFile);

            $namespace = '';

            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (self::fileHasNamespace($tokens, $index)) {
                    $index += 2;
                    $namespace = $tokens[$index + 2][1];
                }

                if (self::fileHasClass($tokens, $index)) {
                    yield $namespace . '\\' . $tokens[$index + 2][1];
                    $index += 2;
                    break;
                }
            }
        }
    }

    protected static function phpFiles(string $path): \Iterator
    {
        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        return new \RegexIterator($allFiles, '/\.php$/');
    }

    protected static function fileTokens(\SplFileInfo $phpFile): array
    {
        $content = file_get_contents($phpFile->getRealPath());

        return token_get_all($content);
    }

    protected static function fileHasNamespace(array $tokens, int $index): bool
    {
        return T_OPEN_TAG === $tokens[$index][0]
            && T_WHITESPACE === $tokens[$index + 1][0]
            && T_NAMESPACE === $tokens[$index + 2][0];
    }

    protected static function fileHasClass(array $tokens, int $index): bool
    {
        return T_CLASS === $tokens[$index][0]
            && T_WHITESPACE === $tokens[$index + 1][0]
            && T_STRING === $tokens[$index + 2][0];
    }
}
