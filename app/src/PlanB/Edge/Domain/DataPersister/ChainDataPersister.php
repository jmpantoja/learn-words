<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\Edge\Domain\DataPersister;


final class ChainDataPersister implements DataPersisterInterface
{

    /**
     * @var iterable<DataPersisterInterface>
     *
     * @internal
     */
    public $persisters;

    /**
     * ChainDataPersister constructor.
     * @param DataPersisterInterface ...$persisters
     */
    public function __construct(DataPersisterInterface ...$persisters)
    {
        $this->persisters = $persisters;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function supports($data): bool
    {
        foreach ($this->persisters as $persister) {
            if ($persister->supports($data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data)
    {
        foreach ($this->persisters as $persister) {
            if ($persister->supports($data)) {
                return $persister->persist($data) ?? $data;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data): void
    {
        foreach ($this->persisters as $persister) {
            if ($persister->supports($data)) {
                $persister->remove($data);
                return;
            }
        }
    }
}
