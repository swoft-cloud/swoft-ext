<?php declare(strict_types=1);

namespace Swoft\Elasticsearch\Connection;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\CatNamespace;
use Elasticsearch\Namespaces\ClusterNamespace;
use Elasticsearch\Namespaces\IndicesNamespace;
use Elasticsearch\Namespaces\IngestNamespace;
use Elasticsearch\Namespaces\NodesNamespace;
use Elasticsearch\Namespaces\SnapshotNamespace;
use Elasticsearch\Namespaces\TasksNamespace;
use Exception;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Elasticsearch\Exception\ElasticsearchException;

/**
 * Class ConnectionInstance
 *
 * @since   2.8
 *
 * @Bean()
 * @method bool ping(array $params)
 * @method callable|array rankEval(array $params)
 * @method callable|array get(array $params)
 * @method callable|array getSource(array $params)
 * @method bool existsSource(array $params)
 * @method callable|array delete(array $params)
 * @method callable|array deleteByQuery(array $params)
 * @method callable|array deleteByQueryRethrottle(array $params)
 * @method callable|array count(array $params)
 * @method callable|array termvectors(array $params)
 * @method callable|array mtermvectors(array $params)
 * @method bool exists(array $params)
 * @method callable|array mget(array $params)
 * @method callable|array msearch(array $params)
 * @method callable|array msearchTemplate(array $params)
 * @method callable|array create(array $params)
 * @method callable|array bulk(array $params)
 * @method callable|array index(array $params)
 * @method callable|array reindex(array $params)
 * @method callable|array reindexRethrottle(array $params)
 * @method callable|array explain(array $params)
 * @method callable|array search(array $params)
 * @method callable|array searchShards(array $params)
 * @method callable|array searchTemplate(array $params)
 * @method callable|array scroll(array $params)
 * @method callable|array scriptsPainlessExecute(array $params)
 * @method callable|array clearScroll(array $params)
 * @method callable|array update(array $params)
 * @method callable|array updateByQuery(array $params)
 * @method callable|array updateByQueryRethrottle(array $params)
 * @method callable|array getScript(array $params)
 * @method callable|array deleteScript(array $params)
 * @method callable|array putScript(array $params)
 * @method callable|array getTemplate(array $params)
 * @method callable|array deleteTemplate(array $params)
 * @method callable|array fieldCaps(array $params)
 * @method callable|array renderSearchTemplate(array $params)
 * @method IndicesNamespace indices()
 * @method ClusterNamespace cluster()
 * @method NodesNamespace nodes()
 * @method SnapshotNamespace snapshot()
 * @method CatNamespace cat()
 * @method IngestNamespace ingest()
 * @method TasksNamespace tasks()
 * @method null|mixed extractArgument(array $params, string $arg)
 * @method callable|array indicesStats(array $params = [])
 * @method callable|array indicesExists(array $params = [])
 * @method callable|array indicesGet(array $params = [])
 * @method callable|array indicesSegments(array $params = [])
 * @method callable|array indicesDeleteTemplate(array $params = [])
 * @method callable|array indicesDelete(array $params = [])
 * @method callable|array indicesPutSettings(array $params = [])
 * @method callable|array indicesShrink(array $params = [])
 * @method callable|array indicesGetMapping(array $params = [])
 * @method callable|array indicesGetFieldMapping(array $params = [])
 * @method callable|array indicesFlush(array $params = [])
 * @method callable|array indicesFlushSynced(array $params = [])
 * @method callable|array indicesRefresh(array $params = [])
 * @method callable|array indicesRecovery(array $params = [])
 * @method callable|array indicesExistsType(array $params = [])
 * @method callable|array indicesPutAlias(array $params = [])
 * @method callable|array indicesPutTemplate(array $params = [])
 * @method callable|array indicesValidateQuery(array $params = [])
 * @method callable|array indicesGetAliases(array $params = [])
 * @method callable|array indicesGetAlias(array $params = [])
 * @method callable|array indicesPutMapping(array $params = [])
 * @method callable|array indicesGetTemplate(array $params = [])
 * @method callable|array indicesExistsTemplate(array $params = [])
 * @method callable|array indicesCreate(array $params = [])
 * @method callable|array indicesForceMerge(array $params = [])
 * @method callable|array indicesDeleteAlias(array $params = [])
 * @method callable|array indicesOpen(array $params = [])
 * @method callable|array indicesAnalyze(array $params = [])
 * @method callable|array indicesClearCache(array $params = [])
 * @method callable|array indicesUpdateAliases(array $params = [])
 * @method callable|array indicesExistsAlias(array $params = [])
 * @method callable|array indicesGetSettings(array $params = [])
 * @method callable|array indicesClose(array $params = [])
 * @method callable|array indicesUpgrade(array $params = [])
 * @method callable|array indicesGetUpgrade(array $params = [])
 * @method callable|array indicesShardStores(array $params = [])
 * @method callable|array indicesRollover(array $params = [])
 * @method callable|array indicesSplit(array $params = [])
 * @method callable|array clusterHealth(array $params = [])
 * @method callable|array clusterReroute(array $params = [])
 * @method callable|array clusterState(array $params = [])
 * @method callable|array clusterStats(array $params = [])
 * @method callable|array clusterPutSettings(array $params = [])
 * @method callable|array clusterGetSettings(array $params = [])
 * @method callable|array clusterPendingTasks(array $params = [])
 * @method callable|array clusterAllocationExplain(array $params = [])
 * @method callable|array clusterRemoteInfo(array $params = [])
 * @method callable|array nodesStats(array $params = [])
 * @method callable|array nodesUsage(array $params = [])
 * @method callable|array nodesInfo(array $params = [])
 * @method callable|array nodesHotThreads(array $params = [])
 * @method callable|array nodesReloadSecureSettings(array $params = [])
 * @method callable|array snapshotCreate(array $params = [])
 * @method callable|array snapshotCreateRepository(array $params = [])
 * @method callable|array snapshotDelete(array $params = [])
 * @method callable|array snapshotDeleteRepository(array $params = [])
 * @method callable|array snapshotGet(array $params = [])
 * @method callable|array snapshotGetRepository(array $params = [])
 * @method callable|array snapshotRestore(array $params = [])
 * @method callable|array snapshotStatus(array $params = [])
 * @method callable|array snapshotVerifyRepository(array $params = [])
 * @method callable|array catAliases(array $params = [])
 * @method callable|array catAllocation(array $params = [])
 * @method callable|array catCount(array $params = [])
 * @method callable|array catHealth(array $params = [])
 * @method callable|array catHelp(array $params = [])
 * @method callable|array catIndices(array $params = [])
 * @method callable|array catMaster(array $params = [])
 * @method callable|array catNodes(array $params = [])
 * @method callable|array catNodeAttrs(array $params = [])
 * @method callable|array catPendingTasks(array $params = [])
 * @method callable|array catRecovery(array $params = [])
 * @method callable|array catRepositories(array $params = [])
 * @method callable|array catShards(array $params = [])
 * @method callable|array catSnapshots(array $params = [])
 * @method callable|array catThreadPool(array $params = [])
 * @method callable|array catFielddata(array $params = [])
 * @method callable|array catPlugins(array $params = [])
 * @method callable|array catSegments(array $params = [])
 * @method callable|array catTasks(array $params = [])
 * @method callable|array catTemplates(array $params = [])
 * @method callable|array ingestDeletePipeline(array $params = [])
 * @method callable|array ingestGetPipeline(array $params = [])
 * @method callable|array ingestPutPipeline(array $params = [])
 * @method callable|array ingestSimulate(array $params = [])
 * @method callable|array ingestProcessorGrok(array $params = [])
 * @method callable|array tasksGet(array $params = [])
 * @method callable|array tasksTasksList(array $params = [])
 * @method callable|array tasksCancel(array $params = [])
 *
 * @package Swoft\Elasticsearch\Connection
 */
class ConnectionInstance
{

    /**
     * 命名空间方法
     *
     * @var array
     */
    protected $namespaceMethods = [
        //IndicesNamespace
        'indicesStats'              => ['indices', 'stats'],
        'indicesExists'             => ['indices', 'exists'],
        'indicesGet'                => ['indices', 'get'],
        'indicesSegments'           => ['indices', 'segments'],
        'indicesDeleteTemplate'     => ['indices', 'deleteTemplate'],
        'indicesDelete'             => ['indices', 'delete'],
        'indicesPutSettings'        => ['indices', 'putSettings'],
        'indicesShrink'             => ['indices', 'shrink'],
        'indicesGetMapping'         => ['indices', 'getMapping'],
        'indicesGetFieldMapping'    => ['indices', 'getFieldMapping'],
        'indicesFlush'              => ['indices', 'flush'],
        'indicesFlushSynced'        => ['indices', 'flushSynced'],
        'indicesRefresh'            => ['indices', 'refresh'],
        'indicesRecovery'           => ['indices', 'recovery'],
        'indicesExistsType'         => ['indices', 'existsType'],
        'indicesPutAlias'           => ['indices', 'putAlias'],
        'indicesPutTemplate'        => ['indices', 'putTemplate'],
        'indicesValidateQuery'      => ['indices', 'validateQuery'],
        'indicesGetAliases'         => ['indices', 'getAliases'],
        'indicesGetAlias'           => ['indices', 'getAlias'],
        'indicesPutMapping'         => ['indices', 'putMapping'],
        'indicesGetTemplate'        => ['indices', 'getTemplate'],
        'indicesExistsTemplate'     => ['indices', 'existsTemplate'],
        'indicesCreate'             => ['indices', 'create'],
        'indicesForceMerge'         => ['indices', 'forceMerge'],
        'indicesDeleteAlias'        => ['indices', 'deleteAlias'],
        'indicesOpen'               => ['indices', 'open'],
        'indicesAnalyze'            => ['indices', 'analyze'],
        'indicesClearCache'         => ['indices', 'clearCache'],
        'indicesUpdateAliases'      => ['indices', 'updateAliases'],
        'indicesExistsAlias'        => ['indices', 'existsAlias'],
        'indicesGetSettings'        => ['indices', 'getSettings'],
        'indicesClose'              => ['indices', 'close'],
        'indicesUpgrade'            => ['indices', 'upgrade'],
        'indicesGetUpgrade'         => ['indices', 'getUpgrade'],
        'indicesShardStores'        => ['indices', 'shardStores'],
        'indicesRollover'           => ['indices', 'rollover'],
        'indicesSplit'              => ['indices', 'split'],
        //ClusterNamespace
        'clusterHealth'             => ['cluster', 'health'],
        'clusterReroute'            => ['cluster', 'reroute'],
        'clusterState'              => ['cluster', 'state'],
        'clusterStats'              => ['cluster', 'stats'],
        'clusterPutSettings'        => ['cluster', 'putSettings'],
        'clusterGetSettings'        => ['cluster', 'getSettings'],
        'clusterPendingTasks'       => ['cluster', 'pendingTasks'],
        'clusterAllocationExplain'  => ['cluster', 'allocationExplain'],
        'clusterRemoteInfo'         => ['cluster', 'remoteInfo'],
        //NodesNamespace
        'nodesStats'                => ['nodes', 'stats'],
        'nodesUsage'                => ['nodes', 'usage'],
        'nodesInfo'                 => ['nodes', 'info'],
        'nodesHotThreads'           => ['nodes', 'hotThreads'],
        'nodesReloadSecureSettings' => ['nodes', 'reloadSecureSettings'],
        //SnapshotNamespace
        'snapshotCreate'            => ['snapshot', 'create'],
        'snapshotCreateRepository'  => ['snapshot', 'createRepository'],
        'snapshotDelete'            => ['snapshot', 'delete'],
        'snapshotDeleteRepository'  => ['snapshot', 'deleteRepository'],
        'snapshotGet'               => ['snapshot', 'get'],
        'snapshotGetRepository'     => ['snapshot', 'getRepository'],
        'snapshotRestore'           => ['snapshot', 'restore'],
        'snapshotStatus'            => ['snapshot', 'status'],
        'snapshotVerifyRepository'  => ['snapshot', 'verifyRepository'],
        //CatNamespace
        'catAliases'                => ['cat', 'aliases'],
        'catAllocation'             => ['cat', 'allocation'],
        'catCount'                  => ['cat', 'count'],
        'catHealth'                 => ['cat', 'health'],
        'catHelp'                   => ['cat', 'help'],
        'catIndices'                => ['cat', 'indices'],
        'catMaster'                 => ['cat', 'master'],
        'catNodes'                  => ['cat', 'nodes'],
        'catNodeAttrs'              => ['cat', 'nodeAttrs'],
        'catPendingTasks'           => ['cat', 'pendingTasks'],
        'catRecovery'               => ['cat', 'recovery'],
        'catRepositories'           => ['cat', 'repositories'],
        'catShards'                 => ['cat', 'shards'],
        'catSnapshots'              => ['cat', 'snapshots'],
        'catThreadPool'             => ['cat', 'threadPool'],
        'catFielddata'              => ['cat', 'fielddata'],
        'catPlugins'                => ['cat', 'plugins'],
        'catSegments'               => ['cat', 'segments'],
        'catTasks'                  => ['cat', 'tasks'],
        'catTemplates'              => ['cat', 'templates'],
        //IngestNamespace
        'ingestDeletePipeline'      => ['ingest', 'deletePipeline'],
        'ingestGetPipeline'         => ['ingest', 'getPipeline'],
        'ingestPutPipeline'         => ['ingest', 'putPipeline'],
        'ingestSimulate'            => ['ingest', 'simulate'],
        'ingestProcessorGrok'       => ['ingest', 'processorGrok'],
        //TasksNamespace
        'tasksGet'                  => ['tasks', 'get'],
        'tasksTasksList'            => ['tasks', 'tasksList'],
        'tasksCancel'               => ['tasks', 'cancel'],
    ];

    /**
     * @var Client
     */
    protected $elasticsearch;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return Client
     */
    public function getElasticsearch(): Client
    {
        return $this->elasticsearch;
    }

    /**
     * @param Client $elasticsearch
     */
    public function setElasticsearch(Client $elasticsearch): void
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * command
     *
     * @param array $methods
     *
     * @return mixed
     * @throws ElasticsearchException
     */
    public function command(array $methods)
    {
        try {
            $result = null;
            foreach ($methods as $method => $arguments) {
                if ($result) {
                    $result = $result->{$method}(...$arguments);
                } else {
                    $result = $this->elasticsearch->{$method}(...$arguments);
                }
            }
            $this->connection->release();
        } catch (Exception $e) {
            $this->connection->release();

            $message   = $e->getMessage();
            $exception = new ElasticsearchException(sprintf('ElasticSearch command error(%s)', $message));

            $error = json_decode($message, true);

            if (isset($error['error']['reason'])) {
                $exception->setResponse($error);
            }

            throw $exception;
        }

        return $result;
    }

    /**
     * Magic method __call
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws ElasticsearchException
     */
    public function __call(string $method, array $arguments)
    {
        if (isset($this->namespaceMethods[$method])) {
            $methods = [
                $this->namespaceMethods[$method][0] => [],
                $this->namespaceMethods[$method][1] => $arguments,
            ];

            $result = $this->command($methods);
        } else {
            $result = $this->command([$method => $arguments]);
        }

        return $result;
    }
}
