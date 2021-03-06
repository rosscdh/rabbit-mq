<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\RabbitMq;

use ArrayObject;
use Generated\Shared\Transfer\QueueConnectionTransfer;
use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use PhpAmqpLib\Message\AMQPMessage;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\RabbitMq\RabbitMqEnv;

class RabbitMqConfig extends AbstractBundleConfig
{
    /**
     * @return \Generated\Shared\Transfer\QueueConnectionTransfer[]
     */
    public function getQueueConnections()
    {
        $queueConnectionConfigs = $this->getQueueConnectionConfigs();

        $connectionTransferCollection = [];
        foreach ($queueConnectionConfigs as $queueConnectionConfig) {
            $connectionTransfer = (new QueueConnectionTransfer())
                ->fromArray($queueConnectionConfig, true)
                ->setQueueOptionCollection($this->getQueueOptions());

            $connectionTransferCollection[] = $connectionTransfer;
        }

        return $connectionTransferCollection;
    }

    /**
     * @return \ArrayObject
     */
    protected function getQueueOptions()
    {
        $queueOptionCollection = new ArrayObject();
        $queueOptionCollection->append(new RabbitMqOptionTransfer());

        return $queueOptionCollection;
    }

    /**
     * @return array
     */
    protected function getQueueConnectionConfigs()
    {
        $connections = [];

        foreach ($this->get(RabbitMqEnv::RABBITMQ_CONNECTIONS) as $connection) {
            $isDefaultConnection = isset($connection[RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION]) ?
                (bool)$connection[RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION] :
                false;

            $connections[] = [
                'name' => $connection[RabbitMqEnv::RABBITMQ_CONNECTION_NAME],
                'host' => $connection[RabbitMqEnv::RABBITMQ_HOST],
                'port' => $connection[RabbitMqEnv::RABBITMQ_PORT],
                'username' => $connection[RabbitMqEnv::RABBITMQ_USERNAME],
                'password' => $connection[RabbitMqEnv::RABBITMQ_PASSWORD],
                'virtualHost' => $connection[RabbitMqEnv::RABBITMQ_VIRTUAL_HOST],
                'storeNames' => $connection[RabbitMqEnv::RABBITMQ_STORE_NAMES],
                'isDefaultConnection' => $isDefaultConnection,
            ];
        }

        return $connections;
    }

    /**
     * @return array
     */
    public function getMessageConfig()
    {
        return [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ];
    }
}
