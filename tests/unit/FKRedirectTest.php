<?php

    // initial url: http://localhost/phpmyadmin/index.php?route=/table/relation&db=test&table=test

    // Upon Drop: http://localhost/phpmyadmin/index.php?server=1&db=test&table=test

    // Should be staying on that initial url

    use PHPUnit\Framework\TestCase;

    class DropForeignKeyTest extends TestCase
    {
        public function testDropForeignKey()
        {
            // Mock any necessary dependencies or setup for your unit test
            $mockedObject = $this->getMockBuilder('YourClass')
                                ->disableOriginalConstructor()
                                ->getMock();

            // Assuming drop_foreign_key_anchor() is a method of YourClass
            $result = $mockedObject->drop_foreign_key_anchor();

            // Add assertions to check the behavior after calling the function
            $this->assertTrue($result->success);

            // Assuming getCurrentUrl() is a method that gets the current URL or state
            $currentUrl = $mockedObject->getCurrentUrl();

            // Check that the current URL or state is as expected after dropping the foreign key
            $this->assertStringNotContainsString('/index.php?route=/table/relation', $currentUrl);

            // Add more assertions if needed to check other aspects of the behavior
        }
    }
?>
