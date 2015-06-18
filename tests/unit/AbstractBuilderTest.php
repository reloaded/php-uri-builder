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
        /*
         * Test host
         */

        // Registered name
        $this->stub->setAuthority("harrisj.net");
        $this->assertEquals("harrisj.net", $this->stub->getAuthority());

        // IPv6 literals
        $this->stub->setAuthority("[::1]");
        $this->assertEquals("[::1]", $this->stub->getAuthority());

        // Any '0' components of dotted notation can be omitted
        $this->stub->setAuthority("127.0.1");
        $this->assertEquals("127.0.1", $this->stub->getAuthority());

        $this->stub->setAuthority("127.1");
        $this->assertEquals("127.1", $this->stub->getAuthority());

        // Decimal
        $this->stub->setAuthority("2130706433");
        $this->assertEquals("2130706433", $this->stub->getAuthority());

        // Octal
        $this->stub->setAuthority("017700000001");
        $this->assertEquals("017700000001", $this->stub->getAuthority());


        /*
         * Test user information
         */
        $this->stub->setAuthority("reloaded@harrisj.net");
        $this->assertEquals("reloaded@harrisj.net", $this->stub->getAuthority());

        // Specifying password in clear text is deprecated so don't support bad security habits.
        try
        {
            $this->stub->setAuthority("reloaded:mypassword%24%23%5E%25%24@harrisj.net");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\UserInformationException', $e);
        }

        /*
         * Test port
         */
        $this->stub->setAuthority("harrisj.net:8000");
        $this->assertEquals("harrisj.net:8000", $this->stub->getAuthority());

        $this->stub->setAuthority("127.1:8000");
        $this->assertEquals("127.1:8000", $this->stub->getAuthority());

        $this->stub->setAuthority("[::1]:8000");
        $this->assertEquals("[::1]:8000", $this->stub->getAuthority());

        try
        {
            $this->stub->setAuthority("harrisj.net:75000");
            $this->assertEquals("harrisj.net:75000", $this->stub->getAuthority());
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\LengthException', $e);
        }
    }

}