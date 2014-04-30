<?php

namespace Tmt\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'text', array(
                'label'  => 'Projektname'
            ))
            ->add('template', 'text', array(
                'label'  => 'Drucktemplate'
            ));
    }

    public function getName() {
        return 'project';
    }
}