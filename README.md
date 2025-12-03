# site-web
Participantes: BEHIFETY SYLVIA SOAMINAH, TCHAPLEU ANGE SARAH et INES OZTURK.







 C:\wamp64\www\quizzeo\
├── index.php                 ← SEUL LIEN D'ENTRÉE
├── quiz_direct.php           ← Lien direct ?code=Q123
├── contact.php               ← Formulaire contact
└── src/
    ├── db.php                ← Connexion BDD
    ├── auth.php              ← Login système
    ├── dashboard.php         ← Dashboard selon rôle
    ├── login.php
    ├── register.php
    ├── logout.php
    ├── quiz_take.php         ← Passer quiz connecté
    ├── quiz_create.php       ← Créer quiz
    ├── quiz_list.php         ← Mes quiz
    ├── admin_users.php       ← Admin users
    └── admin_contact.php     ← Admin messages





 BASE DE DONNÉES QUIZZEO - STRUCTURE COMPLÈTE
code pour notre base 

-- 1. TABLE UTILISATEURS
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `role` enum('admin','ecole','entreprise','utilisateur') NOT NULL DEFAULT 'utilisateur',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 2. TABLE QUIZ
CREATE TABLE `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `id_createur` int(11) NOT NULL,
  `statut` enum('brouillon','en_cours','lancé','terminé') DEFAULT 'brouillon',
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_createur` (`id_createur`),
  CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 3. TABLE QUESTIONS
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_quiz` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse_1` varchar(255) NOT NULL,
  `reponse_2` varchar(255) NOT NULL,
  `reponse_3` varchar(255) NOT NULL,
  `reponse_4` varchar(255) NOT NULL,
  `bonne_reponse` int(1) NOT NULL COMMENT '0,1,2 ou 3',
  PRIMARY KEY (`id`),
  KEY `id_quiz` (`id_quiz`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 4. TABLE CONTACT (messages)
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 5. TABLE REPONSES (historique - optionnel)
CREATE TABLE `reponses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_quiz` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `reponse_user` varchar(50) NOT NULL,
  `score` int(1) DEFAULT 0,
  `date_reponse` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_quiz` (`id_quiz`),
  KEY `id_question` (`id_question`),
  CONSTRAINT `reponses_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id`),
  CONSTRAINT `reponses_ibfk_2` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

