<?php

class AbstractBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * Tests scheme can be set and retrieved from the abstract builder class. Confirms that scheme is
     * validated when being set.
     */
    public function testScheme()
    {
        $stub = $this->getMockForAbstractClass('\Reloaded\Uri\AbstractBuilder');

        $stub->setScheme("http");
        $this->assertEquals("http", $stub->getScheme());

        $stub->setScheme("z39.50r");
        $this->assertEquals("z39.50r", $stub->getScheme());

        try
        {
            $stub->setScheme("1http");
            $this->fail("Expected InvalidArugmentException to be thrown.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

}