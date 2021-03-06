<?php

/*
 * This file is part of the Apisearch Server
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\Server\Domain\Event;

use Apisearch\Repository\RepositoryReference;

/**
 * Class DomainEventWithRepositoryReference.
 */
class DomainEventWithRepositoryReference
{
    /**
     * @var RepositoryReference
     *
     * Repository reference
     */
    private $repositoryReference;

    /**
     * @var DomainEvent
     *
     * Domain event
     */
    private $domainEvent;

    /**
     * EventWithRepositoryReference constructor.
     *
     * @param RepositoryReference $repositoryReference
     * @param DomainEvent         $domainEvent
     */
    public function __construct(
        RepositoryReference $repositoryReference,
        DomainEvent $domainEvent
    ) {
        $this->repositoryReference = $repositoryReference;
        $this->domainEvent = $domainEvent;
    }

    /**
     * Get RepositoryReference.
     *
     * @return RepositoryReference
     */
    public function getRepositoryReference(): RepositoryReference
    {
        return $this->repositoryReference;
    }

    /**
     * Get DomainEvent.
     *
     * @return DomainEvent
     */
    public function getDomainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }
}
