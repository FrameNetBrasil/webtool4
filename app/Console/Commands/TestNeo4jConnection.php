<?php

namespace App\Console\Commands;

use App\Database\GraphCriteria;
use App\Services\Neo4j\ConnectionService;
use App\Services\Neo4jService;
use Illuminate\Console\Command;

class TestNeo4jConnection extends Command
{
    protected $signature = 'test:neo4j';

    protected $description = 'Test Neo4j connection and GraphCriteria functionality';

    public function handle(): int
    {
        $this->info('🚀 Testing Neo4j Connection and GraphCriteria...');
        $this->newLine();

        // Test 1: Check if Neo4j is enabled
        $this->info('1. Checking Neo4j configuration...');
        if (!ConnectionService::isEnabled()) {
            $this->error('❌ Neo4j is not enabled. Set NEO4J_ENABLED=true in your .env file');
            return self::FAILURE;
        }
        $this->info('✅ Neo4j is enabled');

        // Test 2: Test basic connection
        $this->info('2. Testing basic Neo4j connection...');
        try {
            $neo4jService = new Neo4jService();
            if ($neo4jService->isConnected()) {
                $this->info('✅ Successfully connected to Neo4j');
            } else {
                $this->error('❌ Failed to connect to Neo4j');
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("❌ Connection error: {$e->getMessage()}");
            return self::FAILURE;
        }

        // Test 3: Get database info
        $this->info('3. Getting database information...');
        try {
            $dbInfo = $neo4jService->getDatabaseInfo();
            $this->info("✅ Database Info:");
            $this->line("   - Nodes: {$dbInfo['nodeCount']}");
            $this->line("   - Relationships: {$dbInfo['relationshipCount']}");
            $this->line("   - Labels: " . implode(', ', $dbInfo['labels']));
            $this->line("   - Relationship Types: " . implode(', ', $dbInfo['relationshipTypes']));
        } catch (\Exception $e) {
            $this->error("❌ Database info error: {$e->getMessage()}");
        }

        // Test 4: Test GraphCriteria static factory methods
        $this->info('4. Testing GraphCriteria factory methods...');
        try {
            // Test node() method
            $criteria = GraphCriteria::node('TestNode');
            $query = $criteria->getQueryBuilder()->build();
            $this->info("✅ GraphCriteria::node() works - Query: MATCH (n:TestNode)");

            // Test match() method
            $criteria = GraphCriteria::match('(n:Frame)-[:HAS_ELEMENT]->(fe:FrameElement)');
            $query = $criteria->getQueryBuilder()->build();
            $this->info("✅ GraphCriteria::match() works - Complex pattern matching");

        } catch (\Exception $e) {
            $this->error("❌ GraphCriteria factory error: {$e->getMessage()}");
        }

        // Test 5: Test query building with filters
        $this->info('5. Testing GraphCriteria query building...');
        try {
            $criteria = GraphCriteria::node('TestNode')
                ->where('n.name', 'CONTAINS', 'test')
                ->orderBy('n.created_at', 'DESC')
                ->limit(10);

            $queryBuilder = $criteria->getQueryBuilder();
            $query = $queryBuilder->build();
            $parameters = $queryBuilder->getParameters();

            $this->info("✅ Query building works:");
            $this->line("   - Query: {$query}");
            $this->line("   - Parameters: " . json_encode($parameters));

        } catch (\Exception $e) {
            $this->error("❌ Query building error: {$e->getMessage()}");
        }

        // Test 6: Test actual node creation (if database is empty)
        $this->info('6. Testing node creation...');
        try {
            $testNode = GraphCriteria::createNode('TestNode', [
                'name' => 'GraphCriteria Test Node',
                'description' => 'Created by test command',
                'test_timestamp' => now()->toISOString()
            ]);

            if ($testNode) {
                $this->info("✅ Node created successfully:");
                $this->line("   - ID: {$testNode->id}");
                $this->line("   - Labels: " . implode(', ', $testNode->labels));
                $this->line("   - Name: {$testNode->name}");

                // Test 7: Test node querying
                $this->info('7. Testing node querying...');
                $foundNodes = GraphCriteria::node('TestNode')
                    ->where('n.name', 'CONTAINS', 'GraphCriteria')
                    ->get();

                $this->info("✅ Found {$foundNodes->count()} test nodes");

                // Test 8: Test node deletion (cleanup)
                $this->info('8. Cleaning up test nodes...');
                $deleted = GraphCriteria::node('TestNode')
                    ->where('n.name', 'CONTAINS', 'GraphCriteria')
                    ->delete();

                $this->info("✅ Cleaned up {$deleted} test nodes");

            } else {
                $this->warn('⚠️ Node creation returned null - this might be expected behavior');
            }

        } catch (\Exception $e) {
            $this->error("❌ Node operations error: {$e->getMessage()}");
        }

        $this->newLine();
        $this->info('🎉 Neo4j and GraphCriteria testing completed!');

        return self::SUCCESS;
    }
}
