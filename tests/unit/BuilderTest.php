<?php

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Reloaded\Uri\Builder
     */
    private $stub;

    protected function setUp()
    {
        $this->stub = new \Reloaded\Uri\Builder();
    }

    protected function tearDown()
    {
    }

    /**
     * Tests toString throws an exception if scheme is empty.
     *
     * @throws \Reloaded\Uri\AuthorityParseException
     * @throws \Reloaded\Uri\InvalidSchemeException
     * @expectedException \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithEmptyScheme()
    {
        $this->stub
            ->setScheme("")
            ->setHost("harrisj.net");

        (string) $this->stub;
    }
}