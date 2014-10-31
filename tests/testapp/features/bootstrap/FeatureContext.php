<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $baseUrl;

    private $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($params)
    {
        $this->baseUrl = $params['base_url'];
    }

    /**
     * @When I open :url
     */
    public function iOpen($url)
    {
        $this->output = file_get_contents($this->baseUrl . $url);
    }

    /**
     * @Then I should see :needle
     */
    public function iShouldSee($needle)
    {
        \PHPUnit_Framework_Assert::assertTrue(false !== stripos($this->output, $needle));
    }

}
