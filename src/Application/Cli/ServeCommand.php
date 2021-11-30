<?php

declare(strict_types=1);

namespace App\Application\Cli;

use React\ChildProcess\Process;
use React\EventLoop\Loop;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ServeCommand extends Command
{
    const NAME = 'serve';

    protected function configure(): void
    {
        $this->setName(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($worker = 1; 8 >= $worker; $worker++) {
            $process = new Process('XDEBUG_MODE=off php public/index.php');

            $process->start();
            $output->writeLn('php public/index.php ' . $worker);

            $process->stdout->on('data', function ($chunk) use ($output) {
                $output->write($chunk);
            });

            $process->on('exit', function ($exitCode, $termSignal) use ($output) {
                $output->writeLn('Process exited with code ' . $exitCode);
            });
        }

        Loop::get()->run();

        return 1;
    }
}
