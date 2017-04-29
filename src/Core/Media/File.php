<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

use Serps\Core\Media\MediaInterface;

class File extends AbstractMedia
{

    protected $file;
    protected $useCache;
    protected $cache;

    /**
     * @param string $file path to file
     */
    public function __construct($file, $useCache = true)
    {
        $this->file = $file;
        $this->useCache = $useCache;
    }

    private function getFileContent()
    {
        return @file_get_contents($this->file);
    }

    public function asString()
    {
        if ($this->useCache) {
            if (!$this->cache) {
                $this->cache = $this->getFileContent();
            }
            return $this->cache;
        } else {
            return $this->getFileContent();
        }
    }
}
