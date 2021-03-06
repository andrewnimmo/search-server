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

namespace Apisearch\Server\Tests\Functional\Console;

use Apisearch\Query\Query;

/**
 * Class CreateDeleteIndexCommandTest.
 */
class CreateDeleteIndexCommandTest extends CommandTest
{
    /**
     * Test create index command.
     */
    public function testCreateIndexCommand()
    {
        $this->assertNotExistsIndex();

        static::runCommand([
                'command' => 'apisearch-server:create-index',
                'app-id' => self::$appId,
                'index' => self::$index,
            ]);

        $this->assertExistsIndex();

        static::runCommand([
                'command' => 'apisearch-server:delete-index',
                'app-id' => self::$appId,
                'index' => self::$index,
            ]);

        $this->assertNotExistsIndex();
    }

    /**
     * Test create index with synonyms.
     */
    public function testCreateIndexCommandWithSynonyms()
    {
        $this->assertNotExistsIndex();
        static::runCommand([
                'command' => 'apisearch-server:create-index',
                'app-id' => self::$appId,
                'index' => self::$index,
                '--synonym' => [
                    'alfaguarra, percebeiro, engonga',
                ],
            ]);
        $this->indexTestingItems(self::$appId, self::$index);

        $result = $this->query(Query::create('alfaguar'));
        $this->assertCount(1, $result->getItems());
        $this->assertEquals(1, $result->getFirstItem()->getId());

        $result = $this->query(Query::create('engong'));
        $this->assertCount(1, $result->getItems());
        $this->assertEquals(1, $result->getFirstItem()->getId());

        $result = $this->query(Query::create('perceb'));
        $this->assertCount(1, $result->getItems());
        $this->assertEquals(1, $result->getFirstItem()->getId());

        static::runCommand([
                'command' => 'apisearch-server:delete-index',
                'app-id' => self::$appId,
                'index' => self::$index,
            ]);
        $this->assertNotExistsIndex();
    }
}
