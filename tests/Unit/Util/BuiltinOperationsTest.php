<?php

namespace Casbin\Tests\Unit\Util;

use Casbin\Util\BuiltinOperations;
use PHPUnit\Framework\TestCase;

/**
 * RoleManagerTest.
 *
 * @author techlee@qq.com
 */
class BuiltinOperationsTest extends TestCase
{
    private function keyMatchFunc($name1, $name2)
    {
        return (bool)BuiltinOperations::keyMatchFunc($name1, $name2);
    }

    private function keyMatch2Func($name1, $name2)
    {
        return (bool)BuiltinOperations::keyMatch2Func($name1, $name2);
    }

    private function keyMatch3Func($name1, $name2)
    {
        return (bool)BuiltinOperations::keyMatch3Func($name1, $name2);
    }

    private function keyMatch4Func($name1, $name2)
    {
        return (bool)BuiltinOperations::keyMatch4Func($name1, $name2);
    }

    private function keyMatch5Func($name1, $name2)
    {
        return (bool)BuiltinOperations::keyMatch5Func($name1, $name2);
    }

    private function globMatchFunc($name1, $name2)
    {
        return BuiltinOperations::globMatchFunc($name1, $name2);
    }

    private function keyGetFunc($name1, $name2)
    {
        return BuiltinOperations::KeyGetFunc($name1, $name2);
    }

    private function keyGet2Func($name1, $name2, $pathVar)
    {
        return BuiltinOperations::KeyGet2Func($name1, $name2, $pathVar);
    }

    private function regexMatchFunc($name1, $name2)
    {
        return BuiltinOperations::regexMatchFunc($name1, $name2);
    }

    private function ipMatchFunc($ip1, $ip2)
    {
        return BuiltinOperations::ipMatchFunc($ip1, $ip2);
    }

    public function testKeyMatchFunc()
    {
        $this->assertTrue($this->keyMatchFunc('/foo', '/foo'));
        $this->assertTrue($this->keyMatchFunc('/foo', '/foo*'));
        $this->assertFalse($this->keyMatchFunc('/foo', '/foo/*'));
        $this->assertFalse($this->keyMatchFunc('/foo/bar', '/foo'));
        $this->assertTrue($this->keyMatchFunc('/foo/bar', '/foo*'));
        $this->assertTrue($this->keyMatchFunc('/foo/bar', '/foo/*'));
        $this->assertFalse($this->keyMatchFunc('/foobar', '/foo'));
        $this->assertTrue($this->keyMatchFunc('/foobar', '/foo*'));
        $this->assertFalse($this->keyMatchFunc('/foobar', '/foo/*'));
    }

    public function testKeyMatch2Func()
    {
        $this->assertTrue($this->keyMatch2Func('/foo', '/foo'));
        $this->assertTrue($this->keyMatch2Func('/foo', '/foo*'));
        $this->assertFalse($this->keyMatch2Func('/foo', '/foo/*'));
        $this->assertFalse($this->keyMatch2Func('/foo/bar', '/foo'));
        $this->assertFalse($this->keyMatch2Func('/foo/bar', '/foo*'));
        $this->assertTrue($this->keyMatch2Func('/foo/bar', '/foo/*'));
        $this->assertFalse($this->keyMatch2Func('/foobar', '/foo'));
        $this->assertFalse($this->keyMatch2Func('/foobar', '/foo*'));
        $this->assertFalse($this->keyMatch2Func('/foobar', '/foo/*'));

        $this->assertFalse($this->keyMatch2Func('/', '/:resource'));
        $this->assertTrue($this->keyMatch2Func('/resource1', '/:resource'));
        $this->assertFalse($this->keyMatch2Func('/myid', '/:id/using/:resId'));
        $this->assertTrue($this->keyMatch2Func('/myid/using/myresid', '/:id/using/:resId'));

        $this->assertFalse($this->keyMatch2Func('/proxy/myid', '/proxy/:id/*'));
        $this->assertTrue($this->keyMatch2Func('/proxy/myid/', '/proxy/:id/*'));
        $this->assertTrue($this->keyMatch2Func('/proxy/myid/res', '/proxy/:id/*'));
        $this->assertTrue($this->keyMatch2Func('/proxy/myid/res/res2', '/proxy/:id/*'));
        $this->assertTrue($this->keyMatch2Func('/proxy/myid/res/res2/res3', '/proxy/:id/*'));
        $this->assertFalse($this->keyMatch2Func('/proxy/', '/proxy/:id/*'));

        $this->assertTrue($this->keyMatch2Func('/alice', '/:id'));
        $this->assertTrue($this->keyMatch2Func('/alice/all', '/:id/all'));
        $this->assertFalse($this->keyMatch2Func('/alice', '/:id/all'));
        $this->assertFalse($this->keyMatch2Func('/alice/all', '/:id'));

        $this->assertFalse($this->keyMatch2Func('/alice/all', '/:/all'));
    }

    public function testKeyMatch3Func()
    {
        $this->assertTrue($this->keyMatch3Func('/foo', '/foo'));
        $this->assertTrue($this->keyMatch3Func('/foo', '/foo*'));
        $this->assertFalse($this->keyMatch3Func('/foo', '/foo/*'));
        $this->assertFalse($this->keyMatch3Func('/foo/bar', '/foo'));
        $this->assertFalse($this->keyMatch3Func('/foo/bar', '/foo*'));
        $this->assertTrue($this->keyMatch3Func('/foo/bar', '/foo/*'));
        $this->assertFalse($this->keyMatch3Func('/foobar', '/foo'));
        $this->assertFalse($this->keyMatch3Func('/foobar', '/foo*'));
        $this->assertFalse($this->keyMatch3Func('/foobar', '/foo/*'));

        $this->assertFalse($this->keyMatch3Func('/', '/{resource}'));
        $this->assertTrue($this->keyMatch3Func('/resource1', '/{resource}'));
        $this->assertFalse($this->keyMatch3Func('/myid', '/{id}/using/{resId}'));
        $this->assertTrue($this->keyMatch3Func('/myid/using/myresid', '/{id}/using/{resId}'));

        $this->assertFalse($this->keyMatch3Func('/proxy/myid', '/proxy/{id}/*'));
        $this->assertTrue($this->keyMatch3Func('/proxy/myid/', '/proxy/{id}/*'));
        $this->assertTrue($this->keyMatch3Func('/proxy/myid/res', '/proxy/{id}/*'));
        $this->assertTrue($this->keyMatch3Func('/proxy/myid/res/res2', '/proxy/{id}/*'));
        $this->assertTrue($this->keyMatch3Func('/proxy/myid/res/res2/res3', '/proxy/{id}/*'));
        $this->assertFalse($this->keyMatch3Func('/proxy/', '/proxy/{id}/*'));

        $this->assertFalse($this->keyMatch3Func('/myid/using/myresid', '/{id/using/{resId}'));
    }

    public function testKeyMatch4Func()
    {
        $this->assertTrue($this->keyMatch4Func('/parent/123/child/123', '/parent/{id}/child/{id}'));
        $this->assertFalse($this->keyMatch4Func('/parent/123/child/456', '/parent/{id}/child/{id}'));

        $this->assertTrue($this->keyMatch4Func('/parent/123/child/123', '/parent/{id}/child/{another_id}'));
        $this->assertTrue($this->keyMatch4Func('/parent/123/child/456', '/parent/{id}/child/{another_id}'));

        $this->assertTrue($this->keyMatch4Func('/parent/123/child/123/book/123', '/parent/{id}/child/{id}/book/{id}'));
        $this->assertFalse($this->keyMatch4Func('/parent/123/child/123/book/456', '/parent/{id}/child/{id}/book/{id}'));
        $this->assertFalse($this->keyMatch4Func('/parent/123/child/456/book/123', '/parent/{id}/child/{id}/book/{id}'));
        $this->assertFalse($this->keyMatch4Func('/parent/123/child/456/book/', '/parent/{id}/child/{id}/book/{id}'));
        $this->assertFalse($this->keyMatch4Func('/parent/123/child/456', '/parent/{id}/child/{id}/book/{id}'));

        $this->assertFalse($this->keyMatch4Func('/parent/123/child/123', '/parent/{i/d}/child/{i/d}'));
    }

    public function testKeyMatch5Func()
    {
        $this->assertTrue($this->keyMatch5Func('/parent/child', '/parent/child'));
        $this->assertTrue($this->keyMatch5Func('/parent/child?status=1&type=2', '/parent/child'));
        $this->assertFalse($this->keyMatch5Func('/parent?status=1&type=2', '/parent/child'));

        $this->assertTrue($this->keyMatch5Func('/parent/child/?status=1&type=2', '/parent/child/'));
        $this->assertFalse($this->keyMatch5Func('/parent/child/?status=1&type=2', '/parent/child'));
        $this->assertFalse($this->keyMatch5Func('/parent/child?status=1&type=2', '/parent/child/'));
    }

    public function testGlobMatchFunc()
    {
        $this->assertTrue($this->globMatchFunc('/foo', '/foo'));
        $this->assertTrue($this->globMatchFunc('/foo', '/foo*'));
        $this->assertFalse($this->globMatchFunc('/foo', '/foo/*'));

        $this->assertFalse($this->globMatchFunc('/prefix/foo', '*/foo'));

        $this->assertTrue($this->globMatchFunc('/foo/bar', '/foo/*'));
    }

    public function testKeyGetFunc()
    {
        $this->assertEquals('', $this->keyGetFunc('/foo', '/foo'));
        $this->assertEquals('', $this->keyGetFunc('/foo', '/foo*'));
        $this->assertEquals('', $this->keyGetFunc('/foo', '/foo/*'));
        $this->assertEquals('', $this->keyGetFunc('/foo/bar', '/foo'));
        $this->assertEquals('/bar', $this->keyGetFunc('/foo/bar', '/foo*'));
        $this->assertEquals('bar', $this->keyGetFunc('/foo/bar', '/foo/*'));
        $this->assertEquals('', $this->keyGetFunc('/foobar', '/foo'));
        $this->assertEquals('bar', $this->keyGetFunc('/foobar', '/foo*'));
        $this->assertEquals('', $this->keyGetFunc('/foobar', '/foo/*'));
    }

    public function testKeyGet2Func()
    {
        $this->assertEquals('', $this->keyGet2Func("/foo", "/foo", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foo", "/foo*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foo", "/foo/*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foo/bar", "/foo", "id"));
        // different with KeyMatch.
        $this->assertEquals('', $this->keyGet2Func("/foo/bar", "/foo*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foo/bar", "/foo/*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foobar", "/foo", "id"));
        // different with KeyMatch.
        $this->assertEquals('', $this->keyGet2Func("/foobar", "/foo*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/foobar", "/foo/*", "id"));
        $this->assertEquals('', $this->keyGet2Func("/", "/:resource", "resource"));
        $this->assertEquals('resource1', $this->keyGet2Func("/resource1", "/:resource", "resource"));
        $this->assertEquals('', $this->keyGet2Func("/myid", "/:id/using/:resId", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/myid/using/myresid", "/:id/using/:resId", "id"));
        $this->assertEquals('myresid', $this->keyGet2Func("/myid/using/myresid", "/:id/using/:resId", "resId"));

        $this->assertEquals('', $this->keyGet2Func("/proxy/myid", "/proxy/:id/*", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/proxy/myid/", "/proxy/:id/*", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/proxy/myid/res", "/proxy/:id/*", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/proxy/myid/res/res2", "/proxy/:id/*", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/proxy/myid/res/res2/res3", "/proxy/:id/*", "id"));
        $this->assertEquals('myid', $this->keyGet2Func("/proxy/myid/res/res2/res3", "/proxy/:id/res/*", "id"));
        $this->assertEquals('', $this->keyGet2Func('/proxy/', "/proxy/:id/*", "id"));
        $this->assertEquals('alice', $this->keyGet2Func("/alice", "/:id", "id"));
        $this->assertEquals('alice', $this->keyGet2Func("/alice/all", "/:id/all", "id"));
        $this->assertEquals('', $this->keyGet2Func("/alice", "/:id/all", "id"));
        $this->assertEquals('', $this->keyGet2Func("/alice/all", "/:id", "id"));

        $this->assertEquals('', $this->keyGet2Func("/alice/all", "/:/all", ""));
    }

    public function testRegexMatchFunc()
    {
        $this->assertTrue($this->regexMatchFunc("/topic/create", "/topic/create"));
        $this->assertTrue($this->regexMatchFunc("/topic/create/123", "/topic/create"));
        $this->assertFalse($this->regexMatchFunc("/topic/delete", "/topic/create"));
        $this->assertFalse($this->regexMatchFunc("/topic/edit", "/topic/edit/[0-9]+"));
        $this->assertTrue($this->regexMatchFunc("/topic/edit/123", "/topic/edit/[0-9]+"));
        $this->assertFalse($this->regexMatchFunc("/topic/edit/abc", "/topic/edit/[0-9]+"));
        $this->assertFalse($this->regexMatchFunc("/foo/delete/123", "/topic/delete/[0-9]+"));
        $this->assertTrue($this->regexMatchFunc("/topic/delete/0", "/topic/delete/[0-9]+"));
        $this->assertFalse($this->regexMatchFunc("/topic/edit/123s", "/topic/delete/[0-9]+"));
    }

    public function testIPMatchFunc()
    {
        // ipv4
        $this->assertTrue($this->ipMatchFunc("192.168.2.123", "192.168.2.0/24"));
        $this->assertFalse($this->ipMatchFunc("192.168.2.123", "192.168.3.0/24"));
        $this->assertTrue($this->ipMatchFunc("192.168.2.123", "192.168.2.0/16"));
        $this->assertTrue($this->ipMatchFunc("192.168.2.123", "192.168.2.123"));
        $this->assertTrue($this->ipMatchFunc("192.168.2.123", "192.168.2.123/32"));
        $this->assertTrue($this->ipMatchFunc("10.0.0.11", "10.0.0.0/8"));
        $this->assertFalse($this->ipMatchFunc("11.0.0.123", "10.0.0.0/8"));
        // ipv6
        $this->assertTrue($this->ipMatchFunc('2001:db8::ffff', '2001:db8::/64'));
        $this->assertTrue($this->ipMatchFunc('2a00:1450:400c:c04::6a', '2a00:1450::/32'));
        $this->assertFalse($this->ipMatchFunc('2001:db8:ffff::', '2001:db8::/64'));
        $this->assertTrue($this->ipMatchFunc('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::/64'));
        $this->assertTrue($this->ipMatchFunc('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3:1319:8a2e:0370:7347'));
        $this->assertFalse($this->ipMatchFunc('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::'));
    }
}
