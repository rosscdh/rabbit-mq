<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\RabbitMq\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\RabbitMq\Business\RabbitMqFacadeInterface getFacade()
 */
class DeleteAllExchangesConsole extends Console
{
    public const COMMAND_NAME = 'queue:exchanges:delete-all';
    public const DESCRIPTION = 'This command will delete all exchanges.';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Delete all exchanges');

        if ($this->getFacade()->deleteAllExchanges($this->getMessenger())) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
