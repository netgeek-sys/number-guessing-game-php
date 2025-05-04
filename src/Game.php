<?php

declare(strict_types=1);

class Game
{
    private const MIN = 1;
    private const MAX = 100;
    private int $chances = 0;
    private int $attempts = 0;
    private int $randomNumber = 0;

    public function __construct()
    {
    }

    public function start(): void
    {
        $this->displayWelcomeMessage();
        $this->chances = $this->selectDifficulty();
        $this->displayReadyMessage();
        $this->play();
    }

    private function displayWelcomeMessage(): void
    {
        Console::clear();
        Console::writeln('Welcome to the Number Guessing Game!');
        Console::writeln("I'm thinking of a number between " . self::MIN . " and " . self::MAX . "!");
        Console::writeln();
    }

    public function displayReadyMessage(): void
    {
        Console::writeln(PHP_EOL . "You have $this->chances chances to guess the correct number");
        Console::pause("Let's start the game! Press any key whenever you are ready...");
    }

    private function selectDifficulty(): int
    {
        $difficultyLevel = Console::readSelection(
            'Please Select the difficulty level:',
            [
                'Easy (10 chances)',
                'Medium (5 chances)',
                'Hard (3 chances)'
            ],
            true
        );

        return match ($difficultyLevel) {
            1 => 10,
            2 => 5,
            3 => 3,
            default => 0
        };
    }

    private function guess(): bool
    {
        do {
            Console::writeln();
            $guessedNumber = Console::readInt('Enter your guess:');

            --$this->chances;
            ++$this->attempts;

            if ($guessedNumber === $this->randomNumber) {
                return true;
            } else {
                Console::write('Incorrect! The number is ');

                if ($this->randomNumber < $guessedNumber) {
                    Console::write('less than ');
                }

                if ($this->randomNumber > $guessedNumber) {
                    Console::write('greater than ');
                }

                Console::writeln($guessedNumber . '.');
                Console::writeln($this->chances . ' attempt' . ($this->chances > 1 ? 's' : '') . ' left.');
            }
        } while ($this->chances > 0);
        return false;
    }

    private function displayResult(bool $isCorrect): void
    {
        if ($isCorrect) {
            Console::writeln(
                "Congratulations! You guessed the correct number in $this->attempts attempt"
                . ($this->attempts > 1 ? 's' : '') . "."
            );
        } else {
            Console::writeln(PHP_EOL . "Game over! The number is $this->randomNumber. Better luck next time.");
        }

        Console::pause("Press any key to quit the game...");
    }

    private function play(): void
    {
        $this->randomNumber = mt_rand(self::MIN, self::MAX);
        $isCorrect = $this->guess();
        $this->displayResult($isCorrect);
    }
}