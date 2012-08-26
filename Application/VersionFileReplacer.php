<?php
namespace Millwright\RadBundle\Application;

class VersionFileReplacer
{
    protected $versionManager;
    protected $fileName;
    protected $currentVersion;
    protected $currentBuild;
    protected $part;

    protected $versionRegex = '#(const\s+VERSION\s*\=\s*\').*?(\'\s*;)#';
    protected $buildRegex   = '#(const\s+BUILD\s*\=\s*\').*?(\'\s*;)#';

    protected $replaceTo = '${1}%s${2}';

    public function __construct(
        VersionManager $versionManager,
        $fileName = null,
        $currentVersion = null,
        $currentBuild = null,
        $part = 2,
        $versionRegex = null,
        $buildRegex = null
    ) {
        $appClass             = '\Application';
        if (!method_exists($appClass, 'getInstance')) {
            throw new \InvalidArgumentException(sprintf('Must be an instance of RadApplication, %s given', get_class($appClass)));
        }

        $this->versionManager = $versionManager;
        $this->fileName       = $fileName ? $fileName : $appClass::getInstance()->getSelfFile();
        $this->currentVersion = $currentVersion ? $currentVersion : $appClass::VERSION;
        $this->currentBuild   = $currentBuild ? $currentBuild : (integer) $appClass::BUILD;
        $this->part           = $part;
        if ($versionRegex) {
            $this->versionRegex = $versionRegex;
        }

        if ($buildRegex) {
            $this->buildRegex = $buildRegex;
        }
    }

    public function incrementVersion($part = null)
    {
        $newVersion = $this->versionManager->getIncrementedVersion($this->currentVersion, null === $part ? $this->part : $part);

        $this->replaceVersion($newVersion);

        return $newVersion;
    }

    public function replaceVersion($version)
    {
        $this->versionManager->explodeVersion($version); //validate
        $this->replaceInFile($this->fileName, $this->versionRegex, sprintf($this->replaceTo, $version));

        return $this;
    }

    public function incrementBuild()
    {
        $build = $this->currentBuild;
        $build++;
        $this->replaceBuild($build);

        return $build;
    }

    public function replaceBuild($build)
    {
        $this->replaceInFile($this->fileName, $this->buildRegex, sprintf($this->replaceTo, $build));

        return $this;
    }

    protected function replaceInFile($fileName, $regex, $replaceTo)
    {
        if (!is_dir(dirname($fileName))) {
            mkdir(dirname($fileName), 0777, true);
        }

        $data    = file_get_contents($fileName);
        $newData = preg_replace($regex, $replaceTo, $data);
        if ($data != $newData) {
            file_put_contents($fileName, $newData);
            return true;
        }

        return false;
    }
}
