<?php

namespace Tmt\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GeneratePdfIconsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setDefinition(array())
        ->setDescription('Generate class to use icomoon.ttf in PDF.')
        ->setName('tmt:generate:pdficons');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Start ... ');
        $rootPath = $this->getContainer()->get('kernel')->getRootDir();
        $iconsCss = $rootPath . '/../src/Tmt/CoreBundle/Resources/public/css/icons.css';
        $pdficonsClass = $rootPath . '/../src/Tmt/CoreBundle/Component/PDF/Icon.php';


        if (!file_exists($iconsCss) || !is_readable($iconsCss)) {
            $output->writeln('ERROR-READ:' . $iconsCss);
            return false;
        }

        preg_match_all("/\.([^:]+):before[^\"]+\"\\\([^\"]+)\"/", file_get_contents($iconsCss), $matches);

        if (!isset($matches[1]) || !isset($matches[2]) || empty($matches[1])) {
            $output->writeln('ERROR: No matches.');
            return false;
        }

        $dataString = "// data#start\n";
        for ($i=0; $i < count($matches[1]); $i++) {
            $dataString .= "'" . $matches[1][$i] . "' => '" . $matches[2][$i] . "',\n";
        }
        $dataString .= "//data#end";


        if (!file_exists($pdficonsClass) || !is_readable($pdficonsClass)) {
            $output->writeln('ERROR-READ:' . $pdficonsClass);
            return false;
        }

        file_put_contents(
            $pdficonsClass,
            preg_replace(
                "/(\/\/ data#start.*\/\/ data#end)/s",
                $dataString,
                file_get_contents($pdficonsClass)
            )
        );
        $output->writeln('done.');
    }
}
