CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    status VARCHAR(10) DEFAULT 'user'
) ENGINE=InnoDB;

CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    module VARCHAR(100),
    question TEXT,
    answer1 VARCHAR(255),
    answer2 VARCHAR(255),
    answer3 VARCHAR(255),
    answer4 VARCHAR(255),
    correct_answer INT
)ENGINE=InnoDB;

CREATE TABLE attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, 
    score FLOAT,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE answers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    attempt_id INT,
    question_id INT,
    user_answer INT,
    is_correct BOOLEAN,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
)ENGINE=InnoDB;


INSERT INTO users (id, first_name,last_name,email, password, role) VALUES
(1, 'Yohan','Konan','leviyohan123@gmail.com', '$2y$10$CLbMIKPmFfYfF7WugXUdPO.8fukRj7Pdbz5IcxJRUTCthIgdRlCG.','user'),
(2, 'Yohan','Konan','yohan@mail.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(3,'Clauvie','Lordwinds','lordwinds@mail.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G', 'user'),
(4, 'Karounga','Sissoko','karounga@mail.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(5,'Ulrich','Tagho','ulrich@mail.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(6, 'Admin','','admin@mail.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','admin');



INSERT INTO questions (module, question, answer1, answer2, answer3, answer4, correct_answer) VALUES 

('Réseau', "Qu'est-ce que le protocole HTTP ?", "HyperText Transfer Protocol", "High Transfer Tech Protocol", "Hyperlink Transfer Tool", "File Transfer Protocol", 1),
('Réseau', "Quel port par défaut est utilisé par le protocole HTTPS ?", "Port 80", "Port 21", "Port 443", "Port 22", 3),
('Réseau', "Quel protocole est utilisé pour attribuer dynamiquement des adresses IP ?", "DNS", "DHCP", "FTP", "SSH", 2),
('Réseau', "Quelle couche du modèle OSI est responsable du routage des paquets ?", "Couche Physique", "Couche Liaison", "Couche Réseau", "Couche Transport", 3),
('Réseau', "Quel protocole assure une livraison fiable et ordonnée des données ?", "UDP", "IP", "ICMP", "TCP", 4),
('Réseau', "Que signifie l'acronyme DNS ?", "Domain Name System", "Dynamic Network System", "Digital Network Security", "Data Node Server", 1),
('Réseau', "Quel port standard est associé au protocole SSH ?", "Port 21", "Port 22", "Port 23", "Port 25", 2),
('Réseau', "Quelle est la longueur d'une adresse IPv4 ?", "16 bits", "32 bits", "64 bits", "128 bits", 2),
('Réseau', "Quelle est la longueur d'une adresse IPv6 ?", "32 bits", "64 bits", "128 bits", "256 bits", 3),
('Réseau', "Quelle commande permet de tester la connectivité réseau vers une IP ?", "ipconfig", "ifconfig", "ping", "tracert", 3),
('Réseau', "Quel protocole est principalement utilisé pour l'envoi d'e-mails ?", "POP3", "IMAP", "SMTP", "FTP", 3),
('Réseau', "Quelle adresse est un exemple d'adresse IP privée locale ?", "8.8.8.8", "192.168.1.1", "142.250.179.142", "216.58.213.14", 2),
('Réseau', "Dans le modèle OSI, combien de couches existe-t-il ?", "4 couches", "5 couches", "7 couches", "9 couches", 3),
('Réseau', "Qu'est-ce qu'une adresse MAC ?", "Une adresse de routeur", "Un identifiant physique unique", "Une adresse IP dynamique", "Un protocole de chiffrement", 2),
('Réseau', "Quel équipement réseau fonctionne principalement sur la couche 2 du modèle OSI ?", "Le Hub", "Le Switch (Commutateur)", "Le Routeur", "Le Répéteur", 2),
('Réseau', "Quel équipement interconnecte des réseaux différents sur la couche 3 ?", "Le Switch", "Le Hub", "Le Routeur", "Le Pont", 3),
('Réseau', "Quel protocole est non orienté connexion et privilégie la vitesse ?", "TCP", "UDP", "FTP", "HTTP", 2),
('Réseau', "Que signifie LAN ?", "Local Area Network", "Large Access Network", "Link Automatic Network", "Logical Area Node", 1),
('Réseau', "Quel protocole est utilisé pour traduire une adresse IP en adresse MAC ?", "DNS", "ARP", "DHCP", "ICMP", 2),
('Réseau', "Quel masque de sous-réseau correspond par défaut à une classe C ?", "255.0.0.0", "255.255.0.0", "255.255.255.0", "255.255.255.255", 3),
('Réseau', "Quel protocole sécurisé permet de transférer des fichiers à travers SSH ?", "FTP", "TFTP", "SFTP", "HTTP", 3),
('Réseau', "Quel protocole est utilisé par la commande ping ?", "TCP", "UDP", "ICMP", "IGMP", 3),
('Réseau', "Que signifie WAN ?", "Wide Area Network", "Wireless Access Network", "Web Area Network", "World Access Node", 1),
('Réseau', "Quel port est utilisé par défaut pour le protocole HTTP ?", "Port 21", "Port 23", "Port 80", "Port 443", 3),
('Réseau', "Quelle est la fonction principale d'un serveur Proxy ?", "Héberger des bases de données", "Agir comme intermédiaire entre un client et un serveur", "Attribuer des adresses IP", "Router des paquets physiques", 2),


('Développement Web', "Que signifie l'acronyme CSS ?", "Computer Style Sheets", "Creative Style Systems", "Cascading Style Sheets", "Colorful Style Sheets", 3),
('Développement Web', "Quelle méthode HTTP est principalement utilisée pour soumettre un formulaire ?", "GET", "POST", "PUT", "DELETE", 2),
('Développement Web', "En JavaScript, comment déclare-t-on une variable dont la valeur ne change jamais ?", "var", "let", "const", "static", 3),
('Développement Web', "Quelle balise HTML est utilisée pour insérer un saut de ligne ?", "<lb>", "<break>", "<br>", "<next>", 3),
('Développement Web', "Quel attribut HTML permet de spécifier la destination d'un lien ?", "src", "link", "href", "rel", 3),
('Développement Web', "Comment lie-t-on un fichier JavaScript externe en HTML ?", "<script href='app.js'>", "<script src='app.js'>", "<javascript src='app.js'>", "<link rel='js' href='app.js'>", 2),
('Développement Web', "Quelle propriété CSS permet de changer la couleur de fond d'un élément ?", "color", "text-color", "background-color", "bg-style", 3),
('Développement Web', "Que signifie HTML ?", "HyperText Markup Language", "HighText Machine Language", "Hyperlink Transfer Markup Language", "Home Tool Markup Language", 1),
('Développement Web', "Quel sélecteur CSS cible un élément avec l'identifiant 'menu' ?", ".menu", "#menu", "menu", "*menu", 2),
('Développement Web', "Quelle superglobale PHP contient les données envoyées via une URL ?", "$_POST", "$_GET", "$_SERVER", "$_SESSION", 2),
('Développement Web', "Quelle fonction PHP permet de démarrer ou reprendre une session ?", "session_begin()", "start_session()", "session_start()", "init_session()", 3),
('Développement Web', "Quelle fonction JavaScript permet d'écrire un message dans la console de débogage ?", "console.write()", "console.log()", "print()", "alert()", 2),
('Développement Web', "Quel symbole est utilisé pour concaténer des chaînes de caractères en PHP ?", "+", ".", "&&", "concat()", 2),
('Développement Web', "Quelle balise HTML contient les métadonnées visibles uniquement par le navigateur ?", "<body>", "<head>", "<meta>", "<html>", 2),
('Développement Web', "En CSS, quel modèle de disposition permet de créer facilement des grilles complexes ?", "Flexbox", "Grid", "Float", "Inline", 2),
('Développement Web', "Quelle est la version actuelle et standard de HTML ?", "HTML4", "XHTML", "HTML5", "HTML6", 3),
('Développement Web', "En PHP, comment commence le nom de toutes les variables ?", "@", "$", "#", "var", 2),
('Développement Web', "Quelle balise HTML est la plus importante pour le titre principal d'une page ?", "<title>", "<head>", "<h1>", "<header>", 3),
('Développement Web', "Quelle fonction PHP permet de vérifier si une variable est définie ?", "isset()", "empty()", "defined()", "check()", 1),
('Développement Web', "Quel code d'état HTTP indique que la ressource demandée n'existe pas ?", "200 OK", "301 Moved Permanently", "403 Forbidden", "404 Not Found", 4),
('Développement Web', "Quelle propriété CSS permet de modifier la taille de la police de caractères ?", "text-size", "font-size", "font-weight", "style-size", 2),
('Développement Web', "Quel format d'échange de données léger est basé sur la syntaxe des objets JavaScript ?", "XML", "YAML", "JSON", "CSV", 3),
('Développement Web', "Comment insère-t-on un commentaire sur une seule ligne en PHP ?", "<-!- commentaire -!->", "# commentaire uniquement", "// commentaire", "/* commentaire */", 3),
('Développement Web', "Quel attribut HTML permet d'ouvrir un lien dans un nouvel onglet ?", "target='_blank'", "target='_new'", "window='new'", "rel='external'", 1),
('Développement Web', "Quelle fonction PHP permet de supprimer toutes les variables de session ?", "session_destroy()", "session_unset()", "session_stop()", "session_clear()", 2),


('Sécurité', "Quel est l'objectif principal d'une attaque par injection SQL ?", "Saturer la bande passante", "Manipuler ou voler des données en base", "Déchiffrer des mots de passe en local", "Détruire le disque dur du serveur", 2),
('Sécurité', "Quelle fonction PHP moderne est recommandée pour hacher un mot de passe ?", "md5()", "sha1()", "password_hash()", "crypt()", 3),
('Sécurité', "Que signifie l'acronyme XSS ?", "Cross-Site Scripting", "XML System Security", "X-Symmetric Shield", "Extended Security Software", 1),
('Sécurité', "Comment appelle-t-on le sel ajouté à un mot de passe avant son hachage ?", "Un Token", "Un Salt", "Un Cookie", "Un Key", 2),
('Sécurité', "Quel est le principe d'une attaque par force brute ?", "Exploiter une faille logique", "Tester toutes les combinaisons possibles", "Intercepter des sessions réseau", "Modifier le code source", 2),
('Sécurité', "Pour se prémunir des injections SQL en PHP natif, que doit-on utiliser ?", "Les variables globales", "Les requêtes préparées", "La fonction md5", "Le protocole HTTPS", 2),
('Sécurité', "Quelle faille permet à un attaquant d'exécuter du code JavaScript malveillant dans le navigateur d'une victime ?", "Injection SQL", "Faille XSS", "CSRF", "DDoS", 2),
('Sécurité', "Que signifie l'acronyme DDoS ?", "Distributed Denial of Service", "Digital Data Over Security", "Direct Data System", "Database Decentralized Operating", 1),
('Sécurité', "Quel mécanisme permet de protéger un formulaire contre les soumissions automatisées de robots ?", "Un cookie", "Un CAPTCHA", "Un certificat SSL", "Une requête POST", 2),
('Sécurité', "Quelle attaque consiste à intercepter et modifier les communications entre deux parties sans qu'elles le sachent ?", "Brute Force", "Man-in-the-Middle (MITM)", "Phishing", "SQL Injection", 2),
('Sécurité', "Quelle fonction PHP permet de neutraliser les caractères spéciaux pour éviter les failles XSS à l'affichage ?", "md5()", "htmlspecialchars()", "strip_tags()", "addslashes()", 2),
('Sécurité', "Quelle attaque pousse un utilisateur authentifié à exécuter des actions non voulues à son insu sur un site web ?", "XSS", "SQLi", "CSRF", "Phishing", 3),
('Sécurité', "Que signifie l'acronyme CIA (ou DIC en français) en sécurité informatique ?", "Confidentialité, Intégrité, Disponibilité", "Contrôle, Identification, Authentification", "Chiffrement, Isolation, Algorithme", "Cyber, Infrastructure, Attaque", 1),
('Sécurité', "Quel algorithme de chiffrement asymétrique est très largement utilisé sur Internet ?", "AES", "DES", "RSA", "Blowfish", 3),
('Sécurité', "Quel est le rôle principal d'un pare-feu (Firewall) ?", "Détecter les virus sur le disque dur", "Filtrer et contrôler le trafic réseau", "Chiffrer les bases de données", "Gérer les mots de passe des utilisateurs", 2),
('Sécurité', "Qu'est-ce que le Phishing (Hameçonnage) ?", "Une technique d'injection de scripts", "Une escroquerie par e-mail falsifié pour voler des données", "Une surcharge de serveur de base de données", "Une technique de cassage de mot de passe", 2),
('Sécurité', "Pourquoi l'utilisation de l'algorithme MD5 pour stocker les mots de passe est-elle aujourd'hui interdite ?", "Il est trop lent à s'exécuter", "Il génère des collisions et est facilement cassable", "Il ne fonctionne pas en PHP 8", "Il nécessite une base de données NoSQL", 2),
('Sécurité', "Quel protocole sécurise l'accès à distance à un serveur Linux en chiffrant les flux ?", "Telnet", "FTP", "SSH", "HTTP", 3),
('Sécurité', "Qu'est-ce qu'un chiffrement symétrique ?", "Un chiffrement qui utilise la même clé pour chiffrer et déchiffrer", "Un chiffrement qui utilise deux clés différentes", "Un chiffrement impossible à casser", "Un chiffrement réservé au protocole HTTP", 1),
('Sécurité', "Quelle autorité délivre les certificats nécessaires à l'activation du HTTPS ?", "Une autorité de certification (CA)", "Le fournisseur d'accès à Internet", "L'administrateur de la base de données", "Le protocole DNS", 1),
('Sécurité', "Qu'est-ce qu'un rançongiciel (Ransomware) ?", "Un virus qui espionne les touches du clavier", "Un logiciel malveillant qui chiffre les données et réclame de l'argent", "Une attaque qui s'en prend au routeur physique", "Un script JavaScript de minage", 2),
('Sécurité', "Que permet de garantir l'utilisation d'une fonction de hachage comme SHA-256 sur un fichier ?", "La confidentialité du fichier", "L'intégrité du fichier", "La disponibilité du fichier", "La légèreté du fichier", 2),
('Sécurité', "Quelle bonne pratique matérielle permet de se prémunir d'une perte totale de données suite à une cyberattaque ?", "Changer de mot de passe tous les jours", "Mettre en place des sauvegardes régulières et externalisées", "Installer plusieurs pare-feux en série", "Désactiver le JavaScript", 2),
('Sécurité', "Comment appelle-t-on un pirate informatique motivé par des convictions politiques ou idéologiques ?", "Un Script Kiddie", "Un Hacktiviste", "Un White Hat", "Un Insider", 2),
('Sécurité', "Quel attribut de cookie empêche JavaScript d'accéder au cookie, réduisant le risque de vol de session via XSS ?", "Secure", "HttpOnly", "SameSite", "Expires", 2),


('Bases de données', "Que signifie l'acronyme SQL ?", "Structured Query Language", "Simple Queue Linux", "Sequential Query Language", "System Query Logic", 1),
('Bases de données', "Quel mot-clé SQL permet de supprimer des doublons dans les résultats d'une requête ?", "UNIQUE", "DISTINCT", "GROUP BY", "REMOVE", 2),
('Bases de données', "Quelle clause SQL est utilisée pour filtrer les résultats d'une requête ?", "WHERE", "ORDER BY", "GROUP BY", "SELECT", 1),
('Bases de données', "Quelle contrainte garantit qu'une colonne ne peut pas contenir de valeurs vides ?", "UNIQUE", "NOT NULL", "PRIMARY KEY", "DEFAULT", 2),
('Bases de données', "Quelle fonction d'agrégation SQL permet de calculer la moyenne d'une colonne numérique ?", "SUM()", "COUNT()", "AVG()", "MEAN()", 3),
('Bases de données', "Quel type de jointure retourne tous les enregistrements de la table de gauche, même sans correspondance ?", "INNER JOIN", "RIGHT JOIN", "LEFT JOIN", "FULL JOIN", 3),
('Bases de données', "Quel mot-clé est utilisé pour modifier des données existantes dans une table ?", "CHANGE", "MODIFY", "ALTER", "UPDATE", 4),
('Bases de données', "Quelle commande permet de supprimer complètement une table et sa structure de la base ?", "DELETE TABLE", "DROP TABLE", "REMOVE TABLE", "CLEAR TABLE", 2),
('Bases de données', "Que signifie l'acronyme SGBD ?", "Système de Gestion de Base de Données", "Serveur de Stockage Global des Données", "Secrétariat Général des Blocs de Données", "Synchronisation Générale des Bases Digitales", 1),
('Bases de données', "Quelle clause permet de trier les résultats d'une requête SQL ?", "GROUP BY", "SORT BY", "ORDER BY", "HAVING", 3),
('Bases de données', "Quel opérateur SQL permet de rechercher un motif textuel spécifique (ex: commençant par 'A') ?", "IN", "BETWEEN", "LIKE", "EQUAL", 3),
('Bases de données', "Dans une base relationnelle, qu'est-ce qu'une clé étrangère ?", "Une clé générée au hasard", "Une référence à la clé primaire d'une autre table", "Un mot de passe administrateur", "Une colonne temporaire de calcul", 2),
('Bases de données', "Quelle fonction d'agrégation permet de compter le nombre total de lignes renvoyées ?", "COUNT()", "SUM()", "TOTAL()", "ADD()", 1),
('Bases de données', "Quelle clause SQL est utilisée pour filtrer les résultats d'un GROUP BY ?", "WHERE", "HAVING", "ORDER BY", "LIMIT", 2),
('Bases de données', "Quel mot-clé permet d'ajouter une nouvelle ligne dans une table ?", "ADD INTO", "INSERT INTO", "UPDATE", "CREATE LINE", 2),
('Bases de données', "Quel type de données est idéal pour stocker un texte de longueur variable de moins de 255 caractères ?", "INT", "TEXT", "VARCHAR", "CHAR", 3),
('Bases de données', "Que fait la commande 'DELETE FROM table_name;' sans clause WHERE ?", "Elle supprime la table définitivement", "Elle renvoie une erreur de syntaxe", "Elle vide toutes les données de la table sans supprimer la structure", "Elle supprime uniquement la première ligne", 3),
('Bases de données', "Quelle commande permet d'ajouter une colonne à une table existante ?", "ALTER TABLE table_name ADD COLUMN...", "UPDATE TABLE table_name ADD...", "MODIFY TABLE table_name...", "INSERT INTO table_name...", 1),
('Bases de données', "Quelle propriété des transactions garantit qu'une transaction s'exécute entièrement ou pas du tout (Tout ou rien) ?", "Atomicité", "Cohérence", "Isolation", "Durabilité", 1),
('Bases de données', "Que signifie l'acronyme ACID pour une base de données ?", "Atomicité, Cohérence, Isolation, Durabilité", "Accessibilité, Contrôle, Indexation, Distribution", "Algorithme, Compression, Intégrité, Données", "Automatique, Centralisé, Isolé, Direct", 1),
('Bases de données', "Quelle commande SQL permet de limiter le nombre de lignes retournées par une requête ?", "TOP", "MAX", "LIMIT", "ONLY", 3),
('Bases de données', "Quel index particulier accélère les recherches et garantit l'identification unique de chaque ligne ?", "Clé Étrangère", "Clé Primaire", "Index Secondaire", "Vue", 2),
('Bases de données', "Quel symbole sert de joker pour remplacer un ou plusieurs caractères avec l'opérateur LIKE ?", "?", "*", "%", "_", 3),
('Bases de données', "Quel outil standard de l'écosystème Wamp/Xampp offre une interface web pour gérer MySQL ?", "MySQL Workbench", "PhpMyAdmin", "FileZilla", "Putty", 2),
('Bases de données', "Pour trier des résultats du plus grand au plus petit, quel mot-clé ajoute-t-on après ORDER BY ?", "ASC", "DESC", "UP", "DOWN", 2);

