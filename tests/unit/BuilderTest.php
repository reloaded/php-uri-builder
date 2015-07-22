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

    /**
     * Tests toString builds URI with user information.
     *
     * @throws \Reloaded\Uri\AuthorityParseException
     * @throws \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithUserInfo()
    {
        $this->stub
            ->setScheme("http")
            ->setAuthority("reloaded@harrisj.net");

        $this->assertEquals("http://reloaded@harrisj.net", (string) $this->stub);
    }

    /**
     * Tests toString builds URI with port if a port was specified.
     *
     * @throws \Reloaded\Uri\AuthorityParseException
     * @throws \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithPort()
    {
        $this->stub
            ->setScheme("https")
            ->setAuthority("harrisj.net:443");

        $this->assertEquals("https://harrisj.net:443", (string) $this->stub);
    }

    /**
     * Tests toString builds URI with path if a path was specified.
     *
     * @throws \Reloaded\Uri\InvalidHostException
     * @throws \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithPath()
    {
        $this->stub
            ->setScheme("http")
            ->setHost("harrisj.net")
            ->appendPath("programming")
            ->appendPath("c#.net");

        $this->assertEquals("http://harrisj.net/programming/c%23.net", (string) $this->stub);
    }

    /**
     * Tests toString builds URI with query string.
     *
     * @throws \Reloaded\Uri\AuthorityParseException
     * @throws \Reloaded\Uri\InvalidQueryException
     * @throws \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithQuery()
    {
        $this->stub
            ->setScheme("http")
            ->setAuthority("harrisj.net")
            ->appendPath("programming")
            ->appendQuery("php", "5.5")
            ->appendQuery("css", "2/3");

        $this->assertEquals("http://harrisj.net/programming?php=5.5&css=2/3", (string) $this->stub);
    }

    /**
     * Tests toString builds URI with fragment.
     *
     * @throws \Reloaded\Uri\AuthorityParseException
     * @throws \Reloaded\Uri\InvalidFragmentException
     * @throws \Reloaded\Uri\InvalidSchemeException
     */
    public function testToStringWithFragment()
    {
        $this->stub
            ->setScheme("http")
            ->setAuthority("harrisj.net")
            ->setFragment("course/php&completion=68/100");

        $this->assertEquals("http://harrisj.net#course/php%26completion%3D68/100", (string) $this->stub);
    }
}