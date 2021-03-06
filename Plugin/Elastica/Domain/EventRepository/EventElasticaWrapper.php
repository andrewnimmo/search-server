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

namespace Apisearch\Plugin\Elastica\Domain\EventRepository;

use Apisearch\Config\ImmutableConfig;
use Apisearch\Exception\ResourceNotAvailableException;
use Apisearch\Plugin\Elastica\Domain\ElasticaWrapper;
use Apisearch\Repository\RepositoryReference;
use Elastica\Type\Mapping;

/**
 * Class EventElasticaWrapper.
 */
class EventElasticaWrapper extends ElasticaWrapper
{
    /**
     * @var string
     *
     * Item type
     */
    const ITEM_TYPE = 'event';

    /**
     * Get item type.
     *
     * @return string
     */
    public function getItemType(): string
    {
        return self::ITEM_TYPE;
    }

    /**
     * Get index name.
     *
     * @param RepositoryReference $repositoryReference
     *
     * @return string
     */
    public function getIndexName(RepositoryReference $repositoryReference): string
    {
        return $this->buildIndexReference(
            $repositoryReference,
            'apisearch_event'
        );
    }

    /**
     * Get index not available exception.
     *
     * @param string $message
     *
     * @return ResourceNotAvailableException
     */
    public function getIndexNotAvailableException(string $message): ResourceNotAvailableException
    {
        return ResourceNotAvailableException::eventsIndexNotAvailable($message);
    }

    /**
     * Get index configuration.
     *
     * @param ImmutableConfig $config
     * @param int             $shards
     * @param int             $replicas
     *
     * @return array
     */
    public function getIndexConfiguration(
        ImmutableConfig $config,
        int $shards,
        int $replicas
    ): array {
        return [
            'number_of_shards' => $shards,
            'number_of_replicas' => $replicas,
        ];
    }

    /**
     * Build index mapping.
     *
     * @param Mapping         $mapping
     * @param ImmutableConfig $config
     */
    public function buildIndexMapping(
        Mapping $mapping,
        ImmutableConfig $config
    ) {
        $mapping->setParam('dynamic_templates', [
            [
                'dynamic_metadata_as_keywords' => [
                    'path_match' => 'indexed_metadata.*',
                    'match_mapping_type' => 'string',
                    'mapping' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ]);

        $mapping->setProperties([
            'uuid' => [
                'type' => 'object',
                'dynamic' => 'strict',
                'properties' => [
                    'id' => [
                        'type' => 'keyword',
                    ],
                    'type' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
            'indexed_metadata' => [
                'type' => 'object',
                'dynamic' => true,
                'properties' => [
                    'occurred_on' => [
                        'type' => 'date',
                        'format' => 'basic_date_time',
                    ],
                ],
            ],
            'payload' => [
                'type' => 'text',
                'index' => false,
            ],
        ]);
    }
}
