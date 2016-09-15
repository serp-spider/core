<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Media;

use Serps\Core\Media\Base64;
use Serps\Core\Media\Binary;
use Serps\Core\Media\File;
use Serps\Core\Media\MediaFactory;
use Serps\Core\Media\MediaInterface;
use Serps\Core\Media\Stream;

/**
 * @covers Serps\Core\Media\Base64
 * @covers Serps\Core\Media\Binary
 * @covers Serps\Core\Media\File
 * @covers Serps\Core\Media\MediaFactory
 * @covers Serps\Core\Media\MediaInterface
 * @covers Serps\Core\Media\Stream
 */
class MediaTest extends \PHPUnit_Framework_TestCase
{

    public function mediaProvider()
    {
        $imAMedia = 'I\'m a media';
        
        return [
            [$imAMedia, new Binary($imAMedia)],
            [$imAMedia, new Base64(base64_encode($imAMedia))],
            [$imAMedia, new Stream(fopen('data://text/plain,' . $imAMedia, 'r'))],
            [$imAMedia, new File(__DIR__ . '/i-m-a-media.txt')],
            [$imAMedia, new File(__DIR__ . '/i-m-a-media.txt', true)],
            [$imAMedia, MediaFactory::createMediaFromSrc('data:text/plain;base64,' . base64_encode($imAMedia))],
        ];
    }

    /**
     * @dataProvider mediaProvider
     */
    public function testMedia($baseData, MediaInterface $media)
    {
        $this->assertEquals($baseData, $media->asString());
        $this->assertEquals($baseData, $media->__toString());
        $this->assertEquals($baseData, base64_decode($media->asBase64()));
        $this->assertInternalType('resource', $media->asStream());
        $this->assertEquals($baseData, stream_get_contents($media->asStream()));

        $tempFile = tempnam(sys_get_temp_dir(), 'serps-test.');
        $media->saveFile($tempFile);
        $this->assertEquals($baseData, file_get_contents($tempFile));
        unlink($tempFile);
    }
}
