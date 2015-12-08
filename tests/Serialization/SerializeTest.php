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

        $parser2           = new Rss20(file_get_contents('tests/fixtures/podbean.xml'));
        $feed2             = $parser2->execute();
        $feedUnserialized = unserialize(serialize($feed2));

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
                    $this->assertEquals(
                        trim(html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", str_replace('<?xml version="1.0"?>', '', $value->asXML())), ENT_NOQUOTES, 'UTF-8')),
                        trim(html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", str_replace('<?xml version="1.0"?>', '', $feedUnserialized->items[$index]->xml->asXML())), ENT_NOQUOTES, 'UTF-8'))
                    );
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