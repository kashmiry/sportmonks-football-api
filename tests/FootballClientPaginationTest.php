<?php

namespace Sportmonks\Test;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sportmonks\Football\FootballClient;

class FootballClientPaginationTest extends TestCase
{
    private function queryFor(FootballClient $client): array
    {
        $reflection = new ReflectionClass($client);
        $property = $reflection->getProperty('query');
        $property->setAccessible(true);

        return $property->getValue($client);
    }

    /**
     * @test
     */
    public function testSetPageRemovesCursor()
    {
        $_ENV['SPORTMONKS_API_TOKEN'] = 'TOKEN';
        $client = new FootballClient();

        $client->setCursor('abc')->setPage(2);
        $query = $this->queryFor($client);

        $this->assertSame(2, $query['page']);
        $this->assertArrayNotHasKey('cursor', $query);
    }

    /**
     * @test
     */
    public function testSetCursorRemovesPage()
    {
        $_ENV['SPORTMONKS_API_TOKEN'] = 'TOKEN';
        $client = new FootballClient();

        $client->setPage(2)->setCursor('abc');
        $query = $this->queryFor($client);

        $this->assertSame('abc', $query['cursor']);
        $this->assertArrayNotHasKey('page', $query);
    }

    /**
     * @test
     */
    public function testSetCursorNullRemovesCursorAndPage()
    {
        $_ENV['SPORTMONKS_API_TOKEN'] = 'TOKEN';
        $client = new FootballClient();

        $client->setPage(2)->setCursor(null);
        $query = $this->queryFor($client);

        $this->assertArrayNotHasKey('cursor', $query);
        $this->assertArrayNotHasKey('page', $query);
    }
}
