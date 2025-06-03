<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250603220000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add test data for demo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO user (email, password, roles, username) VALUES 
            ('user01@email.com', '\$2y\$13\$1Zklw2v93eVIoJ7dpJ7rSOlRFj2XftD4iMBjCPlzOPMjP1mxM3dBW', '[\"ROLE_USER\"]', 'user01'),
            ('user02@email.com', '\$2y\$13\$UnzM14FPZOKQOyLLW1QcHed7FGRSdu6IQnpNTDLwDNPTlXQFKzIp6', '[\"ROLE_USER\"]', 'user02'),
            ('user03@email.com', '\$2y\$13\$8ZLzgb5RafqDIECYFfQ7suyjl21Tj.QCziFWep.vMe/uJOXJzgriy', '[\"ROLE_USER\"]', 'user03'),
            ('user04@email.com', '\$2y\$13\$IwbLvNknRlUlGK3lJtmN8uw.LAwG4LkTcrPl8ZVTxNFQl4x0ub0Q.', '[\"ROLE_USER\"]', 'user04'),
            ('user05@email.com', '\$2y\$13\$FrLWfLJehaLZn4.BTnJ/GesI2JymT7AHU9WaoL01uIjglvWyik7MW', '[\"ROLE_USER\"]', 'user05'),
            ('admin@email.com', '\$2y\$13\$WWqVkByXGhVnjB6Bz2j.SeXif3zlT.iHakBA03NbqyjaIH7L.1S2C', '[\"ROLE_ADMIN\"]', 'admin'),
            ('anonymous@email.com', '\$2y\$13\$AH0uREOmhSGEysA7NoV5P.EfhXP9/YzY.7vvmuK42W5KwYZPbVajm', '[\"ROLE_USER\"]', 'anonymous')
        ");

        $this->addSql("INSERT INTO task (title, content, is_done, author_id, created_at) VALUES 
            ('Task User 01', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 1, 1, NOW()),
            ('Task User 02', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 1, 2, NOW()),
            ('Task User 03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 1, 3, NOW()),
            ('Task User 04', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 1, 4, NOW())
        ");

        $this->addSql("INSERT INTO task (title, content, is_done, author_id, created_at) VALUES 
            ('Task Anonymous 01', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW()),
            ('Task Anonymous 02', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW()),
            ('Task Anonymous 03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW()),
            ('Task Anonymous 04', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW()),
            ('Task Anonymous 05', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW()),
            ('Task Anonymous 06', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', 0, NULL, NOW())
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM task WHERE title LIKE 'Task User %' OR title LIKE 'Task Anonymous %'");

        $this->addSql("DELETE FROM user WHERE email IN (
            'user01@email.com', 'user02@email.com', 'user03@email.com', 
            'user04@email.com', 'user05@email.com', 'admin@email.com', 'anonymous@email.com'
        )");
    }
}