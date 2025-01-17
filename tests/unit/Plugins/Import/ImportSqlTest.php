<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Plugins\Import;

use PhpMyAdmin\Config;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\File;
use PhpMyAdmin\Import\ImportSettings;
use PhpMyAdmin\Plugins\Import\ImportSql;
use PhpMyAdmin\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(ImportSql::class)]
class ImportSqlTest extends AbstractTestCase
{
    protected ImportSql $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        parent::setUp();

        DatabaseInterface::$instance = $this->createDatabaseInterface();
        $GLOBALS['error'] = null;
        ImportSettings::$timeoutPassed = false;
        ImportSettings::$maximumTime = 0;
        ImportSettings::$charsetConversion = false;
        ImportSettings::$skipQueries = 0;
        ImportSettings::$maxSqlLength = 0;
        $GLOBALS['sql_query'] = '';
        ImportSettings::$executedQueries = 0;
        ImportSettings::$runQuery = false;
        ImportSettings::$goSql = false;

        $this->object = new ImportSql();

        //setting
        ImportSettings::$finished = false;
        ImportSettings::$readLimit = 100000000;
        ImportSettings::$offset = 0;
        Config::getInstance()->selectedServer['DisableIS'] = false;

        ImportSettings::$importFile = 'tests/test_data/pma_bookmark.sql';
        $GLOBALS['import_text'] = 'ImportSql_Test';
        $GLOBALS['compression'] = 'none';
        ImportSettings::$readMultiply = 10;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->object);
    }

    /**
     * Test for doImport
     */
    #[Group('medium')]
    public function testDoImport(): void
    {
        //$sql_query_disabled will show the import SQL detail

        ImportSettings::$sqlQueryDisabled = false;

        //Mock DBI
        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        DatabaseInterface::$instance = $dbi;

        $importHandle = new File(ImportSettings::$importFile);
        $importHandle->open();

        //Test function called
        $this->object->doImport($importHandle);

        //asset that all sql are executed
        self::assertStringContainsString('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', $GLOBALS['sql_query']);
        self::assertStringContainsString('CREATE TABLE IF NOT EXISTS `pma_bookmark`', $GLOBALS['sql_query']);
        self::assertStringContainsString(
            'INSERT INTO `pma_bookmark` (`id`, `dbase`, `user`, `label`, `query`) VALUES',
            $GLOBALS['sql_query'],
        );

        self::assertTrue(ImportSettings::$finished);
    }
}
