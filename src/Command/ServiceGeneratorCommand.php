<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ServiceGeneratorCommand
 */
class ServiceGeneratorCommand extends Command
{

    protected $placeholders = [
        '__VendorName__' => 'BridgemanShare',
        '__BundleName__' => 'ServiceAlphaBeta',
        '__bundle_name__' => 'service_alpha_beta',
    ];

    protected function configure()
    {
        $this
            ->setName('service-bundle')
            ->setDescription('Generates service bundle')
            ->addOption('destination', 'dest', InputOption::VALUE_REQUIRED, 'Where to generate bundle')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the bundle')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();

        $source = __DIR__ . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'Resources'
            . DIRECTORY_SEPARATOR . 'ServiceBundleTemplate'
            . DIRECTORY_SEPARATOR;
        $sourcePathLength = strlen($source);

        $destination = $input->getOption('destination');

        $dirIterator = new \RecursiveDirectoryIterator($source);

        foreach (new \RecursiveIteratorIterator($dirIterator) as $file) {
            if ($file->isFile()) {

                $subPath = substr($file->getPathname(), $sourcePathLength);
                $destPath = $destination . $this->replacePlaceholders($subPath);
                $output->writeln("Creating $destPath");

                $content = file_get_contents($file->getPathname());
                $content = $this->replacePlaceholders($content);
                $filesystem->dumpFile($destPath, $content);

//                var_dump($file->getBasename());
//                var_dump($file->getFilename());
//                var_dump($file->getPath());
//                var_dump($file->getPathname());

            }
        }


        $output->writeln('Hello World');
    }


    protected function replacePlaceholders($string)
    {
        return str_replace(array_keys($this->placeholders), $this->placeholders, $string);
    }
}