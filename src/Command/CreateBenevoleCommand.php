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
    description: 'Crée un bénévole de test pour la connexion.',
)]
class CreateBenevoleCommand extends Command
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $benevole = new Benevole();
        $benevole->setEmail('joleron@art2rue.fr');

        $hashedPassword = $this->passwordHasher->hashPassword(
            $benevole,
            'Elite.22'
        );
        $benevole->setPassword($hashedPassword);

        if (method_exists($benevole, 'setRoles')) {
            $benevole->setRoles(['ROLE_USER']);
        }

        $this->entityManager->persist($benevole);
        $this->entityManager->flush();

        $output->writeln('<info>Bénévole créé avec succès !</info>');

        return Command::SUCCESS;
    }
}
