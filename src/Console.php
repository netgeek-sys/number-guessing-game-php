<?php

declare(strict_types=1);

class Console
{
    public static function write(mixed $value): void
    {
        echo $value;
    }

    public static function writeln(mixed $value = ''): void
    {
        echo $value . PHP_EOL;
    }

    public static function readSelection(string $title, array $choices, bool $returnIndex = false): string|int|false
    {
        self::writeln($title);
        self::writeOrderedList($choices);
        self::writeln('');
        $option = self::readIntBetween('Enter your choice:', 1, count($choices));
        return $returnIndex ? $option : (key_exists($option - 1, $choices) ? $choices[$option - 1] : false);
    }

    public static function readText(string $prompt): string|false
    {
        $value = readline($prompt . ' ');

        if (empty($value)) {
            return false;
        }
        return $value;
    }

    public static function readInt(
        string $prompt,
        string $failureMessage = 'Please enter a valid integer value.'
    ): int|false {
        $failed = false;

        do {
            if ($failed) {
                Console::writeln($failureMessage);
                Console::writeln();
            }

            $value = self::readText($prompt);
            $value = filter_var($value, FILTER_VALIDATE_INT);

            if ($value === false) {
                $failed = true;
            }
        } while (!$value);
        return $value;
    }

    public static function readIntBetween(
        string $prompt,
        int $min,
        int $max
    ): int|false {
        $range = ['min_range' => $min, 'max_range' => $max];
        $failed = false;

        do {
            if ($failed) {
                Console::writeln("Enter a valid integer between $min and $max");
                Console::writeln();
            }

            $value = self::readText($prompt);
            $value = filter_var($value, FILTER_VALIDATE_INT, ['options' => $range]);

            if ($value === false) {
                $failed = true;
            }
        } while (!$value);

        return $value;
    }

    public static function confirm(string $question, bool $defaultAnswer = true): bool
    {
        $answer = trim(
            readline(
                $question
                . ' [y / n] ('
                . ($defaultAnswer ? 'y' : 'n')
                . '): '
            )
        );

        if (empty($answer)) {
            return $defaultAnswer;
        }

        return strtolower($answer) === 'y';
    }

    public static function clear(): void
    {
        echo chr(27) . "[H" . chr(27) . "[2J";
    }

    public static function pause($message = 'Press any key to continue...'): void
    {
        readline($message);
    }

    private static function writeOrderedList(array $list): void
    {
        foreach ($list as $key => $value) {
            self::writeln($key + 1 . ' . ' . $value);
        }
    }
}