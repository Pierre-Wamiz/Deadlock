<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('deadlock:populate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $entityManager = $container->get('doctrine.orm.default_entity_manager');

        $alice = (new User())->setEmail('alice@example.com');
        $entityManager->persist($alice);

        $bob = (new User())->setEmail('bob@example.com');
        $entityManager->persist($bob);

        $entityManager->flush();
    }
}
