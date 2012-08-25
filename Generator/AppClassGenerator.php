<?php
namespace Millwright\RadBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class AppClassGenerator extends Generator
{
    private $skeletonDir;
    protected $target;
    protected $filesystem;

    public function __construct(Filesystem $filesystem, $skeletonDir, $target)
    {
        $this->skeletonDir = $skeletonDir;
        $this->target      = $target;
        $this->filesystem  = $filesystem;
    }

    public function generate($file, $variables = array())
    {
        $this->renderFile($this->skeletonDir, $file, $this->target . '/' . $file, array_merge(array(
            'dir'      => $this->skeletonDir,
        ), $variables));
    }
}
