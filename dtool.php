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
use SwoftExt\Command\GitSubtreePush;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addByConfig($gi = new GitFindTag(), $gi->getHelpConfig());
$cli->addByConfig($drt = new DeleteRemoteTag(), $drt->getHelpConfig());
$cli->addByConfig($grt = new GitReleaseTag(), $grt->getHelpConfig());
$cli->addByConfig($gar = new GitAddRemote(), $gar->getHelpConfig());
$cli->addByConfig($gsp = new GitSubtreePush(), $gsp->getHelpConfig());

$cli->addCommand('gen:readme', $gr = new GenReadme(), $gr->getHelpConfig());
$cli->addCommand('gen:version', $gv = new GenVersion(), $gv->getHelpConfig());

$cli->run();
