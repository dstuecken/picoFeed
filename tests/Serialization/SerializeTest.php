<?php

namespace PicoFeed\Serialization;

use PHPUnit_Framework_TestCase;
use PicoFeed\Parser\Rss20;

class SerializeTest
    extends PHPUnit_Framework_TestCase
{
    public function testSerialization()
    {
        $parser = new Rss20(file_get_contents('tests/fixtures/podbean.xml'));
        $feed   = $parser->execute();

        $this->assertEquals(serialize($feed), file_get_contents('tests/fixtures/podbean.xml.serialized'));
    }

    public function testUnserialization()
    {
        $parser           = new Rss20(file_get_contents('tests/fixtures/podbean.xml'));
        $feed             = $parser->execute();
        $feedUnserialized = unserialize(file_get_contents('tests/fixtures/podbean.xml.serialized'));

        foreach ($feed->items as $index => $item)
        {
            foreach ($item as $key => $value)
            {
                if ($key !== 'xml')
                {
                    $this->assertEquals($value, $feedUnserialized->items[$index]->$key);
                }
                else
                {
                    $this->assertEquals($value, $feedUnserialized->items[$index]->xml);
                }
            }
        }

        $this->assertEquals($feed->date, $feedUnserialized->date);
        $this->assertEquals($feed->description, $feedUnserialized->description);
        $this->assertEquals($feed->feed_url, $feedUnserialized->feed_url);
        $this->assertEquals($feed->icon, $feedUnserialized->icon);
        $this->assertEquals($feed->id, $feedUnserialized->id);
        $this->assertEquals($feed->language, $feedUnserialized->language);
        $this->assertEquals($feed->logo, $feedUnserialized->logo);
        $this->assertEquals($feed->site_url, $feedUnserialized->site_url);
        $this->assertEquals($feed->title, $feedUnserialized->title);
    }
}