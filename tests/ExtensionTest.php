<?php

class ExtensionTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        chdir(__DIR__.'/testapp/');
    }


    public function testThereIsNoServerSetupWithoutExtension()
    {
        $output = $this->runBehat("features/hello-world.feature -c disabled.yml");
        $this->assertOutputContains($output, '1 failed');
    }

    public function testHelloWorldIsCorrectlyServed()
    {
        $output = $this->runBehat("features/hello-world.feature -c enabled.yml");
        $this->assertOutputContains($output, '1 passed');
    }

    private function assertOutputContains($output, $string)
    {
        PHPUnit_Framework_Assert::assertTrue(false !== stripos($output, $string));
    }

    private function runBehat($arguments = '')
    {
        $pathToBehat = __DIR__.'/../vendor/behat/behat/bin/behat';
        ob_start();
        passthru($pathToBehat .' ' . $arguments, $returnVar);
        $contents = ob_get_clean();

        return $contents;
    }
}