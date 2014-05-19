<?php

namespace Tmt\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GeneratePdfIconsCommand extends ContainerAwareCommand {
    protected function configure() {
        $this->setDefinition(array())
        ->setDescription('Generate class to use icomoon.ttf in PDF.')
        ->setName('tmt:generate:pdficons');
    }

    protected function execute(OutputInterface $output) {
        $output->write('Start ... ');
        $root_path = $this->getContainer()->get('kernel')->getRootDir();
        $icons_css = $root_path . '/../src/Tmt/CoreBundle/Resources/public/css/icons.css';
        $pdficons_class = $root_path . '/../src/Tmt/CoreBundle/Component/PDF/Icon.php';

        if (file_exists($icons_css) && is_readable($icons_css)) {
            preg_match_all("/\.([^:]+):before[^\"]+\"\\\([^\"]+)\"/", file_get_contents($icons_css), $matches);

            if (isset($matches[1]) && isset($matches[2]) && !empty($matches[1])) {
                $dataString = "// data#start\n";
                for ($i=0; $i < count($matches[1]); $i++)
                    $dataString .= "'" . $matches[1][$i] . "' => '" . $matches[2][$i] . "',\n";
                $dataString .= "//data#end";


                if (file_exists($pdficons_class) && is_readable($pdficons_class)) {
                    file_put_contents(
                        $pdficons_class,
                        preg_replace(
                            "/(\/\/ data#start.*\/\/ data#end)/s",
                            $dataString,
                            file_get_contents($pdficons_class)
                        )
                    );
                } else $output->writeln('ERROR-READ:' . $pdficons_class);
            } else $output->writeln('ERROR: No matches.');
        } else $output->writeln('ERROR-READ:' . $icons_css);

        $output->writeln('done.');
    }
}