<?php declare(strict_types=1);

namespace Swoft\Elasticsearch;

use Exception;
use Swoft\Elasticsearch\Connection\Connection;
use Swoft\Elasticsearch\Connection\ConnectionInstance;
use Swoft\Elasticsearch\Connection\ConnectionManager;
use Swoft\Elasticsearch\Exception\ElasticsearchException;

/**
 * Class Elasticsearch
 *
 * @since   2.0
 *
 * @method static bool ping(array $params)
 * @method static callable|array rankEval(array $params)
 * @method static callable|array get(array $params)
 * @method static callable|array getSource(array $params)
 * @method static bool existsSource(array $params)
 * @method static callable|array delete(array $params)
 * @method static callable|array deleteByQuery(array $params)
 * @method static callable|array deleteByQueryRethrottle(array $params)
 * @method static callable|array count(array $params)
 * @method static callable|array termvectors(array $params)
 * @method static callable|array mtermvectors(array $params)
 * @method static bool exists(array $params)
 * @method static callable|array mget(array $params)
 * @method static callable|array msearch(array $params)
 * @method static callable|array msearchTemplate(array $params)
 * @method static callable|array create(array $params)
 * @method static callable|array bulk(array $params)
 * @method static callable|array index(array $params)
 * @method static callable|array reindex(array $params)
 * @method static callable|array reindexRethrottle(array $params)
 * @method static callable|array explain(array $params)
 * @method static callable|array search(array $params)
 * @method static callable|array searchShards(array $params)
 * @method static callable|array searchTemplate(array $params)
 * @method static callable|array scroll(array $params)
 * @method static callable|array scriptsPainlessExecute(array $params)
 * @method static callable|array clearScroll(array $params)
 * @method static callable|array update(array $params)
 * @method static callable|array updateByQuery(array $params)
 * @method static callable|array updateByQueryRethrottle(array $params)
 * @method static callable|array getScript(array $params)
 * @method static callable|array deleteScript(array $params)
 * @method static callable|array putScript(array $params)
 * @method static callable|array getTemplate(array $params)
 * @method static callable|array deleteTemplate(array $params)
 * @method static callable|array fieldCaps(array $params)
 * @method static callable|array renderSearchTemplate(array $params)
 * @method static IndicesNamespace indices()
 * @method static ClusterNamespace cluster()
 * @method static NodesNamespace nodes()
 * @method static SnapshotNamespace snapshot()
 * @method static CatNamespace cat()
 * @method static IngestNamespace ingest()
 * @method static TasksNamespace tasks()
 * @method static null|mixed extractArgument(array $params, string $arg)
 * @method static callable|array indicesStats(array $params = [])
 * @method static callable|array indicesExists(array $params = [])
 * @method static callable|array indicesGet(array $params = [])
 * @method static callable|array indicesSegments(array $params = [])
 * @method static callable|array indicesDeleteTemplate(array $params = [])
 * @method static callable|array indicesDelete(array $params = [])
 * @method static callable|array indicesPutSettings(array $params = [])
 * @method static callable|array indicesShrink(array $params = [])
 * @method static callable|array indicesGetMapping(array $params = [])
 * @method static callable|array indicesGetFieldMapping(array $params = [])
 * @method static callable|array indicesFlush(array $params = [])
 * @method static callable|array indicesFlushSynced(array $params = [])
 * @method static callable|array indicesRefresh(array $params = [])
 * @method static callable|array indicesRecovery(array $params = [])
 * @method static callable|array indicesExistsType(array $params = [])
 * @method static callable|array indicesPutAlias(array $params = [])
 * @method static callable|array indicesPutTemplate(array $params = [])
 * @method static callable|array indicesValidateQuery(array $params = [])
 * @method static callable|array indicesGetAliases(array $params = [])
 * @method static callable|array indicesGetAlias(array $params = [])
 * @method static callable|array indicesPutMapping(array $params = [])
 * @method static callable|array indicesGetTemplate(array $params = [])
 * @method static callable|array indicesExistsTemplate(array $params = [])
 * @method static callable|array indicesCreate(array $params = [])
 * @method static callable|array indicesForceMerge(array $params = [])
 * @method static callable|array indicesDeleteAlias(array $params = [])
 * @method static callable|array indicesOpen(array $params = [])
 * @method static callable|array indicesAnalyze(array $params = [])
 * @method static callable|array indicesClearCache(array $params = [])
 * @method static callable|array indicesUpdateAliases(array $params = [])
 * @method static callable|array indicesExistsAlias(array $params = [])
 * @method static callable|array indicesGetSettings(array $params = [])
 * @method static callable|array indicesClose(array $params = [])
 * @method static callable|array indicesUpgrade(array $params = [])
 * @method static callable|array indicesGetUpgrade(array $params = [])
 * @method static callable|array indicesShardStores(array $params = [])
 * @method static callable|array indicesRollover(array $params = [])
 * @method static callable|array indicesSplit(array $params = [])
 * @method static callable|array clusterHealth(array $params = [])
 * @method static callable|array clusterReroute(array $params = [])
 * @method static callable|array clusterState(array $params = [])
 * @method static callable|array clusterStats(array $params = [])
 * @method static callable|array clusterPutSettings(array $params = [])
 * @method static callable|array clusterGetSettings(array $params = [])
 * @method static callable|array clusterPendingTasks(array $params = [])
 * @method static callable|array clusterAllocationExplain(array $params = [])
 * @method static callable|array clusterRemoteInfo(array $params = [])
 * @method static callable|array nodesStats(array $params = [])
 * @method static callable|array nodesUsage(array $params = [])
 * @method static callable|array nodesInfo(array $params = [])
 * @method static callable|array nodesHotThreads(array $params = [])
 * @method static callable|array nodesReloadSecureSettings(array $params = [])
 * @method static callable|array snapshotCreate(array $params = [])
 * @method static callable|array snapshotCreateRepository(array $params = [])
 * @method static callable|array snapshotDelete(array $params = [])
 * @method static callable|array snapshotDeleteRepository(array $params = [])
 * @method static callable|array snapshotGet(array $params = [])
 * @method static callable|array snapshotGetRepository(array $params = [])
 * @method static callable|array snapshotRestore(array $params = [])
 * @method static callable|array snapshotStatus(array $params = [])
 * @method static callable|array snapshotVerifyRepository(array $params = [])
 * @method static callable|array catAliases(array $params = [])
 * @method static callable|array catAllocation(array $params = [])
 * @method static callable|array catCount(array $params = [])
 * @method static callable|array catHealth(array $params = [])
 * @method static callable|array catHelp(array $params = [])
 * @method static callable|array catIndices(array $params = [])
 * @method static callable|array catMaster(array $params = [])
 * @method static callable|array catNodes(array $params = [])
 * @method static callable|array catNodeAttrs(array $params = [])
 * @method static callable|array catPendingTasks(array $params = [])
 * @method static callable|array catRecovery(array $params = [])
 * @method static callable|array catRepositories(array $params = [])
 * @method static callable|array catShards(array $params = [])
 * @method static callable|array catSnapshots(array $params = [])
 * @method static callable|array catThreadPool(array $params = [])
 * @method static callable|array catFielddata(array $params = [])
 * @method static callable|array catPlugins(array $params = [])
 * @method static callable|array catSegments(array $params = [])
 * @method static callable|array catTasks(array $params = [])
 * @method static callable|array catTemplates(array $params = [])
 * @method static callable|array ingestDeletePipeline(array $params = [])
 * @method static callable|array ingestGetPipeline(array $params = [])
 * @method static callable|array ingestPutPipeline(array $params = [])
 * @method static callable|array ingestSimulate(array $params = [])
 * @method static callable|array ingestProcessorGrok(array $params = [])
 * @method static callable|array tasksGet(array $params = [])
 * @method static callable|array tasksTasksList(array $params = [])
 * @method static callable|array tasksCancel(array $params = [])
 *
 * @package Swoft\Elasticsearch
 */
class Elasticsearch
{
    /**
     * connection
     *
     * @param string $pool
     *
     * @return ConnectionInstance
     * @throws ElasticsearchException
     */
    public static function connection(string $pool = Pool::DEFAULT_POOL): ConnectionInstance
    {
        try {
            /** @var ConnectionManager $manager */
            $manager = bean(ConnectionManager::class);
            /** @var Pool $elasticsearchPool */
            $elasticsearchPool = bean($pool);
            /** @var Connection $connection */
            $connection = $elasticsearchPool->getConnection();
            $connection->setRelease(true);
            $manager->setConnection($connection);
        } catch (Exception $e) {
            throw new ElasticsearchException(sprintf('Pool error is %s file=%s line=%d', $e->getMessage(),
                    $e->getFile(), $e->getLine()));
        }
        return $connection->getInstance();
    }

    /**
     * __callStatic
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws ElasticsearchException
     */
    public static function __callStatic($name, $arguments)
    {
        /** @var ConnectionInstance $instance */
        $instance = self::connection();
        return $instance->$name(...$arguments);
    }
}
