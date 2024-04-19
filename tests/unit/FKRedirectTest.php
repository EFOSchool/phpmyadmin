<?php
    use PHPUnit\Framework\TestCase;
    use Facebook\WebDriver\Remote\RemoteWebDriver;
    use Facebook\WebDriver\WebDriverBy;
    use Facebook\WebDriver\WebDriverExpectedCondition;

    // initial url: http://localhost/phpmyadmin/index.php?route=/table/relation&db=test&table=test

    // Upon Drop: http://localhost/phpmyadmin/index.php?server=1&db=test&table=test

    class AjaxEventTest extends TestCase
    {
        protected $webDriver;

        protected function setUp(): void
        {
            // creating driver 
            $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', ['platform' => 'WINDOWS']);
        }

        protected function tearDown(): void
        {
            $this->webDriver->quit();
        }

        public function testDropForeignKey()
        {
            // just using my existing url here, might expand into a dataProvider for many URLs but I would have to make the tables in the app
            $initialUrl = 'http://localhost/phpmyadmin/index.php?route=/table/relation&db=test&table=test';

            // Get info from url
            $urlParts = parse_url($initialUrl);
            parse_str($urlParts['query'], $queryParams);
            $dbName = $queryParams['db'];
            $tableName = $queryParams['table'];

            $this->webDriver->get($initialUrl);

            // Find the "Drop Foreign key" anchor element
            $dropElement = $this->webDriver->findElement(WebDriverBy::cssSelector('a.drop_foreign_key_anchor.ajax'));

            // Drop the FK
            $dropElement->click();

            // Examples say a wait is needed so it can finish up the action
            $this->webDriver->wait(10)->until(
                WebDriverExpectedCondition::urlContains('&server=1&db=' . $dbName . '&table=' . $tableName)
            );

            $currentUrl = $this->webDriver->getCurrentURL();

            // Construct the expected URL based on the database name and table name
            $expectedUrl = 'http://localhost/phpmyadmin/index.php?server=1&db=' . $dbName . '&table=' . $tableName;

            // Assert that the URL remains on the initial URL after the drop action
            $this->assertEquals($expectedUrl, $currentUrl);
        }
    }

?>
