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
            $this->fail("Expected an exception.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidSchemeException', $e);
        }
    }

    /**
     * Tests authority setting/validation and getting.
     *
     * @link http://regexr.com/3b7up
     */
    public function testAuthority()
    {
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
            $this->assertInstanceOf('\Reloaded\Uri\InvalidUserInfoException', $e);
        }

        /*
         * Registered name host
         */
        $this->stub->setAuthority("reloaded@harrisj.net:8000");
        $this->assertEquals("reloaded@harrisj.net:8000", $this->stub->getAuthority());
        $this->assertEquals("harrisj.net", $this->stub->getHost());
        $this->assertEquals("8000", $this->stub->getPort());
        $this->assertEquals("reloaded", $this->stub->getUserInfo());

        $this->stub->setAuthority("portfolio.harrisj.co.uk");
        $this->assertNull($this->stub->getUserInfo());
        $this->assertEquals("portfolio.harrisj.co.uk", $this->stub->getHost());
        $this->assertEquals(0, $this->stub->getPort());

        /*
         * IPv4 host
         */
        $this->stub->setAuthority("reloaded@127.0.0.1:8000");
        $this->assertEquals("8000", $this->stub->getPort());
        $this->assertEquals("127.0.0.1", $this->stub->getHost());


        /*
         * IPv6 host
         */

        $this->stub->setAuthority("reloaded@[::1]:8000");
        $this->assertEquals("8000", $this->stub->getPort());
        $this->assertEquals("[::1]", $this->stub->getHost());

        $this->stub->setAuthority("[2001:0db8:0000:0000:0000:ff00:0042:8329]:9000");
        $this->assertEquals("9000", $this->stub->getPort());
        $this->assertEquals("[2001:0db8:0000:0000:0000:ff00:0042:8329]", $this->stub->getHost());

        $this->stub->setAuthority("[2001:db8::ff00:42:8329]");
        $this->assertEquals("[2001:db8::ff00:42:8329]", $this->stub->getHost());
    }

    /**
     * @throws \Reloaded\Uri\InvalidPortException
     * @expectedException \Reloaded\Uri\InvalidPortException
     */
    public function testPort()
    {
        $this->stub->setPort("8080");
        $this->assertEquals(8080, $this->stub->getPort());

        $this->stub->setPort("75000");
    }

    /**
     * @throws \Reloaded\Uri\InvalidPortException
     */
    public function testHasPort()
    {
        $this->assertFalse($this->stub->hasPort());

        $this->stub->setPort(80);

        $this->assertTrue($this->stub->hasPort());
    }

    /**
     * Check to see if set host accepts valid registered names.
     */
    public function testRegisteredNameHost()
    {
        $this->stub->setHost("harrisj.net");
        $this->assertEquals("harrisj.net", $this->stub->getHost());

        try
        {
            $this->stub->setHost("2015harrisj.net");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidHostException', $e);
        }
    }

    /**
     * Check to see if set host accepts IPv4 as long as it has four octets.
     *
     * @link http://blogs.msdn.com/b/ieinternals/archive/2014/03/06/browser-arcana-ipv4-ipv6-literal-urls-dotted-va-dotless.aspx
     */
    public function testIpv4Host()
    {
        $this->stub->setHost("127.0.0.1");
        $this->assertEquals("127.0.0.1", $this->stub->getHost());

        // Don't allow any '0' components to be omitted
        try
        {
            $this->stub->setHost("127.0.1");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidHostException', $e);
        }

        // Don't allow decimal
        try
        {
            $this->stub->setHost("2130706433");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidHostException', $e);
        }

        // Don't allow octal
        try
        {
            $this->stub->setHost("017700000001");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidHostException', $e);
        }
    }

    /**
     * Check to see if set host accepts IPv6.
     *
     * @link http://blogs.msdn.com/b/ieinternals/archive/2014/03/06/browser-arcana-ipv4-ipv6-literal-urls-dotted-va-dotless.aspx
     */
    public function testIpv6Host()
    {
        $this->stub->setHost("[::1]");
        $this->assertEquals("[::1]", $this->stub->getHost());

        $this->stub->setHost("[2001:0db8:0000:0000:0000:ff00:0042:8329]");
        $this->assertEquals("[2001:0db8:0000:0000:0000:ff00:0042:8329]", $this->stub->getHost());
    }

    /**
     * @throws \Reloaded\Uri\InvalidHostException
     */
    public function testHasHost()
    {
        $this->assertFalse($this->stub->hasHost());

        $this->stub->setHost("localhost");

        $this->assertTrue($this->stub->hasHost());
    }
}