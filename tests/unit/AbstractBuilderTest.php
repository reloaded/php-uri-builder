<?php

class AbstractBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Reloaded\Uri\AbstractBuilder
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

        try
        {
            $this->stub->setScheme([]);
            $this->fail("Expected an exception.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidSchemeException', $e);
        }
    }

    public function testHasScheme()
    {
        $this->assertFalse($this->stub->hasScheme());

        $this->stub->setScheme("http");

        $this->assertTrue($this->stub->hasScheme());
    }

    /**
     * Tests authority setting/validation and getting.
     *
     * @link http://regexr.com/3b7up
     */
    public function testAuthority()
    {
        /*
         * Test invalid authority parameter
         */
        try
        {
            $this->stub->setAuthority(false);
            $this->fail("Expected an exception.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\AuthorityParseException', $e);
        }

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
        $this->assertEquals("", $this->stub->getUserInfo());
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
            $this->fail("Expected an exception.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('\Reloaded\Uri\InvalidHostException', $e);
        }

        try
        {
            $this->stub->setHost(false);
            $this->fail("Expected an exception.");
        }
        catch(\Exception $e)
        {
            $this->assertInstanceOf('Reloaded\Uri\InvalidHostException', $e);
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

    /**
     * Check to see if URI user information can be set.
     *
     * @throws \Reloaded\Uri\InvalidUserInfoException
     */
    public function testSetUserInfo()
    {
        $this->stub->setUserInfo("reloaded");

        $this->assertEquals("reloaded", $this->stub->getUserInfo());
    }

    /**
     * @throws \Reloaded\Uri\InvalidUserInfoException
     */
    public function testHasUserInfo()
    {
        $this->assertFalse($this->stub->hasUserInfo());

        $this->stub->setUserInfo("reloaded");

        $this->assertTrue($this->stub->hasUserInfo());
    }

    /**
     * Check to see if set path accepts an array of path elements and encodes reserved characters.
     */
    public function testSetPath()
    {
        $this->stub->setPath([
            "web%development",
            "php",
            "angular-js",
            "c#.net",
            "jasmine@js",
            "test:unit",
            "sql+server"
        ]);

        $this->assertEquals(
            [
                "web%25development",
                "php",
                "angular-js",
                "c%23.net",
                "jasmine@js",
                "test:unit",
                "sql%2Bserver"
            ],
            $this->stub->getPath()
        );
    }

    public function testHasPath()
    {
        $this->assertFalse($this->stub->hasPath());

        $this->stub->setPath([
            "web%development"
        ]);

        $this->assertTrue($this->stub->hasPath());
    }

    /**
     * Check to see if elements can be appended to the path array without affecting existing elements.
     */
    public function testAppendPath()
    {
        $this->stub->setPath([
            "web%development",
            "php"
        ]);

        $this->stub->appendPath("angular-js");

        $this->assertEquals(["web%25development", "php", "angular-js"], $this->stub->getPath());
    }

    /**
     * Check to see if elements can be removed from the path array.
     */
    public function testRemovePath()
    {
        $this->stub->setPath([
            "web%development",
            "php",
            "angular-js",
            "c#.net",
            "jharris@harrisj.net"
        ]);

        $this->stub
            ->removePath("angular-js")
            ->removePath("c#.net");

        $this->assertEquals(["web%25development", "php", "jharris@harrisj.net"], $this->stub->getPath());

        $this->stub->removePath("jharris@harrisj.net");

        $this->assertEquals(["web%25development", "php"], $this->stub->getPath());
    }

    /**
     * Check to see if individual URI path elements can be checked for presence.
     */
    public function testPathExists()
    {
        $this->stub->setPath([
            "web%development",
            "php",
            "angular-js",
            "c#.net"
        ]);

        $this->assertTrue($this->stub->pathExists("php"));

        $this->assertTrue($this->stub->pathExists("angular-js"));

        $this->assertTrue($this->stub->pathExists("c#.net"));
    }

    /**
     * Check to see if URI query can be set.
     */
    public function testSetQuery()
    {
        $this->stub->setQuery([
            "key1" => "val1",
            "key2" => "val/val2",
            "key3" => "val%2Fval2",
            "key4" => "val?ue",
            "key5" => "",
            "key6" => "c#.net",
            "key/7" => "val"
        ]);

        $this->assertEquals(
            [
                "key1" => "val1",
                "key2" => "val/val2",
                "key3" => "val%252Fval2",
                "key4" => "val?ue",
                "key5" => "",
                "key6" => "c%23.net",
                "key/7" => "val"
            ],
            $this->stub->getQuery()
        );
    }

    public function testHasQuery()
    {
        $this->assertFalse($this->stub->hasQuery());

        $this->stub->setQuery([
            "key1" => "val1"
        ]);

        $this->assertTrue($this->stub->hasQuery());
    }

    /**
     * Check to see if elements can be appended to the URI query.
     */
    public function testAppendQuery()
    {
        $this->stub->setQuery([
            "key1" => "val1"
        ]);

        $this->assertEquals(["key1" => "val1"], $this->stub->getQuery());

        $this->stub->appendQuery("key2", "val/val2");

        $this->assertEquals(
            ["key1" => "val1", "key2" => "val/val2"],
            $this->stub->getQuery()
        );
    }

    /**
     * Check to see if URI query parameters can be removed.
     */
    public function testRemoveQuery()
    {
        $this->stub->setQuery([
            "key1" => "val1",
            "key2" => "val/val2",
            "key3" => ""
        ]);

        $this->stub->removeQuery("key1");

        $this->assertEquals(
            ["key2" => "val/val2", "key3" => ""],
            $this->stub->getQuery()
        );
    }

    /**
     * Check to see if URI query elements can be checked for presence.
     */
    public function testQueryExists()
    {
        $this->assertFalse($this->stub->queryExists("key2"));

        $this->stub->setQuery([
            "key1" => "val1",
            "key2" => "val/val2",
            "key/3" => "val"
        ]);

        $this->assertTrue($this->stub->queryExists("key2"));

        $this->assertTrue($this->stub->queryExists("key/3"));
    }

    public function testSetFragment()
    {
        $this->stub->setFragment("category/promotions");

        $this->assertEquals("category/promotions", $this->stub->getFragment());

        $this->stub->setFragment("label/Two Birds Nutrition");

        $this->assertEquals("label/Two%20Birds%20Nutrition", $this->stub->getFragment());
    }

    public function testHasFragment()
    {
        $this->assertFalse($this->stub->hasFragment());

        $this->stub->setFragment("category/promotions");
        $this->assertTrue($this->stub->hasFragment());

        $this->stub->setFragment("");
        $this->assertFalse($this->stub->hasFragment());
    }
}