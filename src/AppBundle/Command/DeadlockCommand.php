<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeadlockCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    public function configure()
    {
        $this
            ->setName('deadlock')
            ->addArgument('process', InputArgument::REQUIRED, 'a|b')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initializeDependencies();

        /** @var User $alice */
        $alice = $this->userRepository->findOneBy(['email' => 'alice@example.com']);
        /** @var User $bob */
        $bob = $this->userRepository->findOneBy(['email' => 'bob@example.com']);
        if ($alice === null || $bob === null) {
            throw new \Exception('Some of the users were not found');
        }

        $entityManager = $this->entityManager;

        if ($input->getArgument('process') === 'a') {
            $this->entityManager->transactional(
                function () use ($entityManager, $alice, $bob, $output) {
                    $this->lockUser($alice);
                    $output->writeln('Alice locked');
                    sleep(5);
                    $output->writeln('Trying to lock Bob');
                    $this->lockUser($bob);
                }
            );
        } else {
            $this->entityManager->transactional(
                function () use ($entityManager, $alice, $bob, $output) {
                    $this->lockUser($bob);
                    $output->writeln('Bob locked');
                    $output->writeln('Locking Alice');
                    $this->lockUser($alice);
                    $output->writeln('Alice locked');
                }
            );
        }
    }

    private function lockUser(User $user)
    {
        // todo change to doctrine statements
        $this->entityManager->createNativeQuery(
            '
                select * from user u
                where u.id=?
                for update
            ',
            new ResultSetMapping()
        )->setParameter(1, $user->getId())->getResult();
    }

    private function initializeDependencies()
    {
        $container = $this->getContainer();
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->userRepository = $this->entityManager->getRepository('AppBundle:User');
    }
}

