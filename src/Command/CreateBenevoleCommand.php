<?php

namespace App\Command;

use App\Entity\Benevole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-benevole',
    description: 'Crée ou met à jour le bénévole de test.',
)]
class CreateBenevoleCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $benevole = $this->entityManager->getRepository(Benevole::class)->findOneBy(['email' => 'joleron@art2rue.fr']);

        if (!$benevole) {
            $benevole = new Benevole();
            $benevole->setEmail('joleron@art2rue.fr');
            $benevole->setRoles(['ROLE_USER']);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $benevole,
            'Elite.22'
        );
        $benevole->setPassword($hashedPassword);

        $this->entityManager->persist($benevole);
        $this->entityManager->flush();

        $output->writeln('<info>Bénévole joleron@art2rue.fr configuré avec succès !</info>');

        return Command::SUCCESS;
    }
}
