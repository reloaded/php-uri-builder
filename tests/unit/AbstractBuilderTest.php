<?php

class AbstractBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Reloaded\Uri\AbstractBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stub;

    protected function setUp()
    {
        $this->stub = $this->getMockForAbstractClass('\Reloaded\Uri\AbstractBuilder');
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
        $this->stub->setScheme("http");
        $this->assertEquals("http", $this->stub->getScheme());

        $this->stub->setScheme("HTTP-2.0");
        $this->assertEquals("http-2.0", $this->stub->getScheme());

        $this->stub->setScheme("z39.50r");
        $this->assertEquals("z39.50r", $this->stub->getScheme());

        try
        {
            $this->stub->setScheme("1http");
            $this->fail("Expected InvalidArugmentException to be thrown.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

    /**
     * Tests authority setting/validation and getting.
     */
    public function testAuthority()
    {
        $this->stub->setAuthority("harrisj.net");
        $this->assertEquals("harrisj.net", $this->stub->getAuthority());

        $this->stub->setAuthority("harrisj.net:8000");
        $this->assertEquals("harrisj.net:8000", $this->stub->getAuthority());

        $this->stub->setAuthority("reloaded@harrisj.net");
        $this->assertEquals("reloaded@harrisj.net", $this->stub->getAuthority());

    }

}