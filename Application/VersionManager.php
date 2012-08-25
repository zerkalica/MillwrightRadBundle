<?php
namespace Millwright\RadBundle\Application;

class VersionManager
{
    protected $versionRegex    = '#(\d+)\.(\d+)\.(\d+)#';
    protected $limits          = array(null, 99, 99);
    protected $versionTemplate = '%s.%s.%s';

    public function __construct($versionRegex = null, array $limits = array(), $versionTemplate = null)
    {
        if ($limits) {
            $this->limits = $limits;
        }

        if ($versionRegex) {
            $this->versionRegex = $versionRegex;
        }

        if ($versionTemplate) {
            $this->versionTemplate = $versionTemplate;
        }
    }

    public function explodeVersion($version)
    {
        if (!preg_match($this->versionRegex, $version, $matches)) {
            throw new \InvalidArgumentException(sprintf('version %s does not match %s regexp', $version, $this->versionRegex));
        }
        array_shift($matches);

        return $this->partsToInt($matches);
    }

    protected function partsToInt(array $parts)
    {
        foreach ($parts as &$part) {
            $part = $part == '0' ? 0 : (integer) $part;
        }

        return $parts;
    }

    protected function partsToString(array $parts)
    {
        foreach ($parts as &$part) {
            $part = !$part ? '0' : (string) $part;
        }

        return $parts;
    }

    protected function incrementPart(array &$parts, $part)
    {
        $parts[$part]++;
        if (null !== $this->limits[$part] && $parts[$part] > $this->limits[$part]) {
            $this->incrementPart($parts, $part - 1);
            $parts[$part] = 0;
        }
    }

    protected function getIncrementedParts($version, $part)
    {
        $parts = $this->explodeVersion($version);
        $this->incrementPart($parts, $part);

        return $this->partsToString($parts);
    }

    public function getIncrementedVersion($version, $part = 2)
    {
        return vsprintf($this->versionTemplate, $this->getIncrementedParts($version, $part));
    }
}
