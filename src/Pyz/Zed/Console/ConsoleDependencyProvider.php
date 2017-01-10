<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Console;

use Pyz\Zed\Importer\Communication\Console\DemoDataImportConsole;
use Pyz\Zed\Updater\Communication\Console\UpdaterConsole;
use Spryker\Shared\Library\Environment;
use Spryker\Zed\Application\Communication\Console\ApplicationIntegrationCheckConsole;
use Spryker\Zed\Application\Communication\Console\BuildNavigationConsole;
use Spryker\Zed\Cache\Communication\Console\DeleteAllCachesConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleClientCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleSharedCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleYvesCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleZedCodeGeneratorConsole;
use Spryker\Zed\Collector\Communication\Console\CollectorSearchExportConsole;
use Spryker\Zed\Collector\Communication\Console\CollectorStorageExportConsole;
use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\Development\Communication\Console\CodePhpMessDetectorConsole;
use Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole;
use Spryker\Zed\Development\Communication\Console\CodeTestConsole;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NewRelic\Communication\Console\RecordDeploymentConsole;
use Spryker\Zed\Oms\Communication\Console\CheckConditionConsole as OmsCheckConditionConsole;
use Spryker\Zed\Oms\Communication\Console\CheckTimeoutConsole as OmsCheckTimeoutConsole;
use Spryker\Zed\Oms\Communication\Console\ClearLocksConsole as OmsClearLocksConsole;
use Spryker\Zed\Product\Communication\Console\ProductTouchConsole;
use Spryker\Zed\Search\Communication\Console\SearchConsole;
use Spryker\Zed\StateMachine\Communication\Console\CheckConditionConsole as StateMachineCheckConditionConsole;
use Spryker\Zed\StateMachine\Communication\Console\CheckTimeoutConsole as StateMachineCheckTimeoutConsole;
use Spryker\Zed\StateMachine\Communication\Console\ClearLocksConsole as StateMachineClearLocksConsole;
use Spryker\Zed\Touch\Communication\Console\TouchCleanUpConsole;
use Spryker\Zed\Transfer\Communication\Console\GeneratorConsole;
use Spryker\Zed\Transfer\Communication\Console\ValidatorConsole;

class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands(Container $container)
    {
        $commands = [
            new ApplicationIntegrationCheckConsole(),
            new BuildNavigationConsole(),
            new CollectorStorageExportConsole(),
            new CollectorSearchExportConsole(),
            new TouchCleanUpConsole(),
            new DeleteAllCachesConsole(),
            new DemoDataImportConsole(),
            new UpdaterConsole(),
            new GeneratorConsole(),
            new InitializeDatabaseConsole(),
            new RecordDeploymentConsole(),
            new SearchConsole(),
            new OmsCheckConditionConsole(),
            new OmsCheckTimeoutConsole(),
            new OmsClearLocksConsole(),
            new StateMachineCheckTimeoutConsole(),
            new StateMachineCheckConditionConsole(),
            new StateMachineClearLocksConsole()
        ];

        $propelCommands = $container->getLocator()->propel()->facade()->getConsoleCommands();
        $commands = array_merge($commands, $propelCommands);

        $setupCommands = $container->getLocator()->setup()->facade()->getConsoleCommands();
        $commands = array_merge($commands, $setupCommands);

        if (Environment::isDevelopment()) {
            $commands[] = new CodeTestConsole();
            $commands[] = new CodeStyleSnifferConsole();
            $commands[] = new CodePhpMessDetectorConsole();
            $commands[] = new ProductTouchConsole();
            $commands[] = new ValidatorConsole();
            $commands[] = new BundleCodeGeneratorConsole();
            $commands[] = new BundleYvesCodeGeneratorConsole();
            $commands[] = new BundleZedCodeGeneratorConsole();
            $commands[] = new BundleSharedCodeGeneratorConsole();
            $commands[] = new BundleClientCodeGeneratorConsole();
        }

        return $commands;
    }

}
