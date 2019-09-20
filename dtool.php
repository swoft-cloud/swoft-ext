<?php
/**
 * Internal develop tool
 */

use SwoftExt\Command\DeleteRemoteTag;
use SwoftExt\Command\GenReadme;
use SwoftExt\Command\GenVersion;
use SwoftExt\Command\GitFindTag;
use SwoftExt\Command\GitAddRemote;
use SwoftExt\Command\GitReleaseTag;
use SwoftExt\Command\GitSubtreePull;
use SwoftExt\Command\GitSubtreePush;
use SwoftExt\Command\GitForcePush;
use SwoftExt\Command\UpdateSwooleVer;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addByConfig($gi = new GitFindTag(), $gi->getHelpConfig());
$cli->addByConfig($drt = new DeleteRemoteTag(), $drt->getHelpConfig());
$cli->addByConfig($grt = new GitReleaseTag(), $grt->getHelpConfig());
$cli->addByConfig($gar = new GitAddRemote(), $gar->getHelpConfig());
$cli->addByConfig($gfp = new GitForcePush(), $gfp->getHelpConfig());
$cli->addByConfig($gsp1 = new GitSubtreePull(), $gsp1->getHelpConfig());
$cli->addByConfig($gsp2 = new GitSubtreePush(), $gsp2->getHelpConfig());

$cli->addCommand('gen:readme', $gr = new GenReadme(), $gr->getHelpConfig());

$cli->addByConfig($cmd = new GenVersion(), $cmd->getHelpConfig());
$cli->addByConfig($cmd = new UpdateSwooleVer(), $cmd->getHelpConfig());

$cli->run();
