<?php

namespace uuf6429\WFS\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use uuf6429\WFS\DSNCheck;
use uuf6429\WFS\Exception\ConflictingHandlersException;
use uuf6429\WFS\Exception\TimeoutReachedException;
use uuf6429\WFS\Exception\UnsupportedDSNException;
use uuf6429\WFS\Handler\Handler;
use uuf6429\WFS\HandlerManager;

class CheckCommand extends Command
{
    /**
     * Delay in microseconds.
     * @var int
     */
    private $delay;

    /**
     * Timestamp in seconds.
     * @var int
     */
    private $timeout;

    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Waits until all the provided DSNs are up (or timeout is reached)')
            ->addArgument(
                'dsn',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Which DSNs to check for?'
            )
            ->addOption(
                'timeout',
                't',
                InputOption::VALUE_REQUIRED,
                'Change how much time until giving up (any value allowed by strtotime)',
                '2 minutes'
            )
            ->addOption(
                'delay',
                'd',
                InputOption::VALUE_REQUIRED,
                'Change delay between checks (in seconds, fractions allowed)',
                1.0
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $this->timeout = strtotime($input->getOption('timeout'));
        $this->delay   = round($input->getOption('delay') * 1000000);

        if ($this->timeout < time()) {
            throw new InvalidOptionException('Timeout is wrong or in the past.');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DSNCheck[] $dsnChecks */
        $dsnChecks = array_map(
            function ($dsn) {
                /** @var Handler[] $supported */
                $supported = array_filter(
                    HandlerManager::getInstance()->getHandlers(),
                    function (Handler $handler) use ($dsn) {
                        return $handler->supports($dsn);
                    }
                );

                switch(count($supported)){
                    case 0:
                        throw new UnsupportedDSNException($dsn);
                    case 1:
                        return new DSNCheck($dsn, $supported[0]);
                    default:
                        throw new ConflictingHandlersException($supported, $dsn);
                }
            },
            $input->getArgument('dsn')
        );

        $output->write(sprintf('Waiting for <info>%d</info> services...', count($dsnChecks)));

        while ($dsnChecks) {
            foreach ($dsnChecks as $i => $dsnCheck) {
                if ($dsnCheck->checkNow()->isSuccessful()) {
                    unset($dsnChecks[$i]);

                    $output->writeln('');
                    $output->writeln('<info>ONLINE</info> '.$dsnCheck->getDSN());
                }

                $output->write('.');
            }

            if ($dsnChecks) {
                usleep($this->delay);
            }

            if ($this->timeout < time()) {
                throw new TimeoutReachedException($dsnChecks);
            }
        }

        $output->writeln('<info>DONE</info>');

        return 0;
    }
}
