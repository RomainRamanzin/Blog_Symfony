<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create a new user with a specific role',
    aliases: ['app:create-user'],
)]
class UserCreateCommand extends Command
{

     // deprecated
     // protected static $defaultName = 'app:user:create';
     // protected static $defaultDescription = 'Create a new user with a specific role';
     
     private EntityManagerInterface $em;
     private UserPasswordHasherInterface $passwordHasher;

     public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
     {
          parent::__construct();
          $this->em = $em;
          $this->passwordHasher = $passwordHasher;
     }

     protected function configure()
     {
          $this->setHelp('This command allows you to create a user with a specific role');
          $this->addArgument('email', null, 'The email of the user.');
          $this->addOption("admin", 'a', null, 'If set, the user is created as an admin');
     }

     protected function execute(InputInterface $input, OutputInterface $output)
     {
          $helper = $this->getHelper('question');

          $io = new SymfonyStyle($input, $output);
          
          $email = $input->getArgument('email');
          $admin = $input->getOption('admin');

          if($email === null) {
               $email = $io->ask('Please enter the email: ');

               if(!$email) {
                    $output->writeln('The email is required');
                    return Command::INVALID;
               }
          }
          
          $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

          if($user) {
               $output->writeln('The user already exists');
               return Command::FAILURE;
          }

          $password = $io->askHidden('Please enter the password: ');

          // $parswordAsk = new Question('Please enter the password: ');
          // $parswordAsk->setHidden(true);
          // $parswordAsk->setHiddenFallback(false);
          // $password = $helper->ask($input, $output, $parswordAsk);

          if(!$password) {
               $output->writeln('The password is required');
               return Command::FAILURE;
          }


          // $userNameAsk = new Question('Please enter the username: ');
          // $userName = $helper->ask($input, $output, $userNameAsk);

          $userName = $io->ask('Please enter the username: ');

          if(!$userName) {
               $output->writeln('The username is required');
               return Command::FAILURE;
          }

          $user = new User();
          $user->setEmail($email);
          $user->setPassword($this->passwordHasher->hashPassword($user, $password));
          $user->setUsername($userName);
          $user->setRoles($admin ? ['ROLE_ADMIN'] : ['ROLE_USER']);

          $this->em->persist($user);
          $this->em->flush();


          $io->success('You have created a new user');

          // $output->writeln(["Email: $email", "Admin: $admin"]);

         return Command::SUCCESS;
     }
}