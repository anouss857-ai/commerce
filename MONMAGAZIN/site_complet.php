<?php
// ============================================
// SITE MONMAGAZIN COMPLET - Un seul fichier
// ============================================

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========== FONCTIONS ==========
function e($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

function getUserPrenom() {
    return isset($_COOKIE['prenom']) ? $_COOKIE['prenom'] : null;
}

function getUserTheme() {
    return isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'clair';
}

// ========== ROUTAGE ==========
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

// Création des dossiers nécessaires
$folders = ['data', 'uploads'];
foreach ($folders as $folder) {
    if (!is_dir($folder)) mkdir($folder, 0777, true);
}

// Création du fichier produits.csv s'il n'existe pas
$csvFile = 'data/produits.csv';
if (!file_exists($csvFile)) {
    $csvContent = "reference,nom,categorie,prix,stock,description\n";
    $csvContent .= "PC001,PC Gamer Pro,Ordinateurs,1299.99,8,\"Processeur Intel Core i7-13700K, RAM 32Go DDR5, SSD NVMe 1Tb, RTX 4070. Idéal pour le gaming et la création de contenu.\"\n";
    $csvContent .= "PC002,Laptop UltraBook,Ordinateurs,899.99,15,\"Ultrabook léger et puissant, idéal pour le nomadisme numérique.\"\n";
    $csvContent .= "CAM001,Caméra 4K Pro,Photographie,649.99,5,\"Caméra professionnelle 4K avec capteur plein format.\"\n";
    $csvContent .= "MON001,Écran 27 144Hz,Moniteurs,349.99,12,\"Écran gaming 27 pouces, 144Hz, temps de réponse 1ms.\"\n";
    $csvContent .= "PER001,Souris Gaming RGB,Périphériques,59.99,2,\"Souris gaming 16000 DPI, RGB personnalisable.\"\n";
    $csvContent .= "PER002,Clavier Mécanique,Périphériques,89.99,3,\"Clavier mécanique RGB, switches bleus.\"\n";
    $csvContent .= "PER003,Webcam Full HD,Périphériques,79.99,1,\"Webcam 1080p avec microphone intégré.\"\n";
    $csvContent .= "AUD001,Casque Audio Pro,Audio,129.99,0,\"Casque audio surround 7.1, micro antibruit.\"\n";
    file_put_contents($csvFile, $csvContent);
}

// ========== HEADER ==========
$prenom = getUserPrenom();
$theme = getUserTheme();
$themeClass = ($theme == 'sombre') ? 'bg-dark text-white' : 'bg-light';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONMAGAZIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-card { transition: transform 0.3s; margin-bottom: 20px; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .ouvert { color: green; font-weight: bold; }
        .ferme { color: red; }
        .today { background-color: #fff3cd; }
        .nav-link.active { font-weight: bold; color: #007bff !important; }
    </style>
</head>
<body class="<?php echo $themeClass; ?>">
    <nav class="navbar navbar-expand-lg <?php echo ($theme == 'sombre') ? 'navbar-dark bg-dark' : 'navbar-light bg-white'; ?> shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="?page=accueil">MONMAGAZIN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'accueil' ? 'active' : ''; ?>" href="?page=accueil">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'menu' ? 'active' : ''; ?>" href="?page=menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'newsletter' ? 'active' : ''; ?>" href="?page=newsletter">Newsletter</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'contact' ? 'active' : ''; ?>" href="?page=contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'gagner' ? 'active' : ''; ?>" href="?page=gagner">Gagner</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'panier' ? 'active' : ''; ?>" href="?page=panier">Panier</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $page == 'profil' ? 'active' : ''; ?>" href="?page=profil">Profil</a></li>
                </ul>
                <?php if($prenom): ?>
                    <span class="ms-3 badge bg-primary">Bonjour, <?php echo e($prenom); ?></span>
                <?php endif; ?>
                <?php if(isset($_SESSION['user_email'])): ?>
                    <a href="?page=login&logout=1" class="btn btn-sm btn-danger ms-3">Déconnexion</a>
                <?php else: ?>
                    <a href="?page=login" class="btn btn-sm btn-outline-primary ms-3">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <main class="container my-4">
        <?php
        // ========== PAGE ACCUEIL ==========
        if ($page == 'accueil'): ?>
            <div class="row">
                <div class="col-12 text-center">
                    <?php if($prenom): ?>
                        <div class="alert alert-info">
                            Bonjour, <?php echo e($prenom); ?> ! Bienvenue sur Mon site.<br>
                            Vos préférences sont mémorisées grâce aux cookies. 
                            <a href="?page=profil" class="alert-link">Gérer mes préférences →</a>
                        </div>
                    <?php endif; ?>
                    <h1>Bienvenue dans mon magasin !</h1>
                    <p class="lead">Nous sommes ravis de vous accueillir dans notre boutique en ligne.</p>
                    
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Mon site</h5>
                                    <p>Un site créé avec PHP & Bootstrap.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Navigation</h5>
                                    <ul class="list-unstyled">
                                        <li><a href="?page=accueil">Accueil</a></li>
                                        <li><a href="?page=menu">Menu</a></li>
                                        <li><a href="?page=contact">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>À propos</h5>
                                    <p>cours de php 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        // ========== PAGE MENU ==========
        elseif ($page == 'menu'): 
            $produits = [];
            if (($handle = fopen($csvFile, 'r')) !== false) {
                $headers = fgetcsv($handle, 1000, ',');
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $produits[] = array_combine($headers, $data);
                }
                fclose($handle);
            }
            ?>
            <h1 class="mb-4">Nos Produits</h1>
            <p class="lead mb-4">Cliquez sur un produit pour voir ses détails, son prix et sa disponibilité.</p>
            <div class="row">
                <?php foreach($produits as $produit): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card product-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($produit['nom']); ?></h5>
                                <p class="card-text text-muted"><?php echo e($produit['categorie']); ?></p>
                                <p class="card-text fw-bold text-primary"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                                <?php 
                                $stock = (int)$produit['stock'];
                                if($stock > 10): ?>
                                    <span class="badge bg-success">En stock (<?php echo $stock; ?> disponibles)</span>
                                <?php elseif($stock > 0 && $stock <= 10): ?>
                                    <span class="badge bg-warning text-dark">Stock limité (<?php echo $stock; ?> restantes)</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rupture de stock</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="?page=produit&ref=<?php echo urlencode($produit['reference']); ?>" class="btn btn-sm btn-outline-primary">Voir les détails →</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php
        // ========== PAGE PRODUIT ==========
        elseif ($page == 'produit'):
            $reference = isset($_GET['ref']) ? $_GET['ref'] : '';
            $produit = null;
            if ($reference && ($handle = fopen($csvFile, 'r')) !== false) {
                $headers = fgetcsv($handle, 1000, ',');
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $p = array_combine($headers, $data);
                    if ($p['reference'] == $reference) { $produit = $p; break; }
                }
                fclose($handle);
            }
            if (!$produit) { header('Location: ?page=menu'); exit; }
            ?>
            <nav aria-label="breadcrumb"><ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=accueil">Accueil</a></li>
                <li class="breadcrumb-item"><a href="?page=menu">Produits</a></li>
                <li class="breadcrumb-item active"><?php echo e($produit['nom']); ?></li>
            </ol></nav>
            <div class="row">
                <div class="col-md-6">
                    <div class="card"><div class="card-body">
                        <h1 class="card-title"><?php echo e($produit['nom']); ?></h1>
                        <p class="text-muted">Référence : <?php echo e($produit['reference']); ?></p>
                        <p class="card-text"><?php echo e($produit['description']); ?></p>
                        <p class="text-muted">Catégorie : <?php echo e($produit['categorie']); ?></p>
                        <a href="?page=menu" class="btn btn-secondary">← Retour au catalogue</a>
                    </div></div>
                </div>
                <div class="col-md-6">
                    <div class="card"><div class="card-body">
                        <h3 class="text-primary"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</h3>
                        <p class="mt-3"><strong>Disponibilité :</strong><br>
                        <?php $stock = (int)$produit['stock'];
                        if($stock > 0): ?>
                            <span class="text-success"><?php echo $stock; ?> unités</span><br>
                            <span class="badge bg-success mt-1">En stock (<?php echo $stock; ?> disponibles)</span>
                        <?php else: ?>
                            <span class="text-danger">Rupture de stock</span>
                        <?php endif; ?></p>
                        <?php if($stock > 0): ?>
                            <form method="POST" action="?page=panier">
                                <input type="hidden" name="produit_ref" value="<?php echo e($produit['reference']); ?>">
                                <input type="hidden" name="produit_nom" value="<?php echo e($produit['nom']); ?>">
                                <input type="hidden" name="produit_prix" value="<?php echo $produit['prix']; ?>">
                                <div class="mb-3"><label class="form-label">Quantité</label>
                                <input type="number" name="quantite" class="form-control" value="1" min="1" max="<?php echo $stock; ?>">
                                <small class="text-muted">Max : <?php echo $stock; ?></small></div>
                                <p><strong>Total estimé :</strong> <span class="fs-4 fw-bold text-primary"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</span></p>
                                <button type="submit" name="ajouter_panier" class="btn btn-success w-100">Ajouter au panier</button>
                            </form>
                        <?php endif; ?>
                    </div></div>
                </div>
            </div>

        <?php
        // ========== PAGE NEWSLETTER ==========
        elseif ($page == 'newsletter'):
            $message = ''; $emailError = ''; $subscribeCount = 0;
            $subscribersFile = 'data/subscribers.txt';
            if (file_exists($subscribersFile)) {
                $subscribers = file($subscribersFile, FILE_IGNORE_NEW_LINES);
                $subscribeCount = count($subscribers);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email'] ?? '');
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailError = 'Le format de l\'email est invalide.';
                } else {
                    file_put_contents($subscribersFile, $email . PHP_EOL, FILE_APPEND);
                    $message = 'Inscription réussie ! Merci.';
                    $subscribeCount++;
                }
            }
            ?>
            <h1>S'inscrire à la newsletter</h1>
            <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci, optio delectus quaerat porro minus beatae modi iusto natus nihil voluptas repudiandae fuga totam ratione ut facilis, odio ab exercitationem sed.</p>
            <?php if($emailError): ?><div class="alert alert-danger"><?php echo e($emailError); ?></div><?php endif; ?>
            <?php if($message): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
            <form method="POST" class="row g-3">
                <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Entrer votre email" required></div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary">S'inscrire</button></div>
            </form>
            <p class="mt-3 text-muted"><?php echo $subscribeCount; ?> personnes inscrites.</p>

        <?php
        // ========== PAGE GAGNER ==========
        elseif ($page == 'gagner'):
            $rewards = [1 => ['reduction' => '-30%', 'code' => 'BEST30'], 2 => ['reduction' => '-25%', 'code' => 'CODE25'], 3 => ['reduction' => '-20%', 'code' => 'WIZI20'], 4 => ['reduction' => '-15%', 'code' => 'TRY15'], 5 => ['reduction' => '-10%', 'code' => 'LAST310']];
            if (!isset($_SESSION['tentatives'])) { $_SESSION['tentatives'] = 0; $_SESSION['nombre_secret'] = rand(1, 10); }
            $message = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proposition'])) {
                $proposition = (int)$_POST['proposition'];
                $_SESSION['tentatives']++;
                $tentatives = $_SESSION['tentatives'];
                if ($proposition == $_SESSION['nombre_secret']) {
                    $reward = ($tentatives <= 5) ? $rewards[$tentatives] : $rewards[5];
                    $message = 'Félicitations ! Vous avez gagné ' . $reward['reduction'] . ' avec le code ' . $reward['code'];
                    setcookie('coupon_code', $reward['code'], time() + 86400 * 30, '/');
                    setcookie('coupon_reduction', $reward['reduction'], time() + 86400 * 30, '/');
                    $_SESSION['tentatives'] = 0; $_SESSION['nombre_secret'] = rand(1, 10);
                } else { $message = 'Mauvaise proposition ! Essayez encore.'; }
            }
            $currentTentatives = $_SESSION['tentatives'];
            $currentReward = $rewards[min($currentTentatives + 1, 5)] ?? $rewards[5];
            ?>
            <h1>Gagnez un coupon de réduction !</h1>
            <p>Évitez le nombre succédant et ne faites pas de tentatives, plus la réduction est grande !</p>
            <div class="card mb-4"><div class="card-body">
                <p><strong>Tentatives :</strong> <?php echo $currentTentatives; ?></p>
                <p><strong>Réduction actuelle :</strong> <?php echo $currentReward['reduction']; ?> (<?php echo $currentReward['code']; ?>)</p>
                <form method="POST"><div class="row">
                    <div class="col-md-4"><label class="form-label">Votre proposition (1 à 10) :</label>
                    <input type="number" name="proposition" class="form-control" min="1" max="10" required></div>
                    <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary">Valider</button></div>
                </div></form>
                <?php if($message): ?><div class="alert alert-info mt-3"><?php echo e($message); ?></div><?php endif; ?>
            </div></div>
            <table class="table table-bordered"><thead><tr><th>Tentatives</th><th>Réduction</th><th>Code coupon</th></tr></thead>
            <tbody><?php foreach($rewards as $tentative => $reward): ?><tr><td><?php echo $tentative; ?> tentative<?php echo $tentative > 1 ? 's' : ''; ?></td><td><?php echo $reward['reduction']; ?></td><td><?php echo $reward['code']; ?></td></tr><?php endforeach; ?>
            <tr><td>5+ tentatives</td><td>-10%</td><td>LAST310</td></tr></tbody></table>

        <?php
        // ========== PAGE CONTACT ==========
        elseif ($page == 'contact'):
            $error = ''; $success = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer'])) {
                $nom = trim($_POST['nom'] ?? ''); $email = trim($_POST['email'] ?? ''); $sujet = trim($_POST['sujet'] ?? ''); $message_contact = trim($_POST['message'] ?? '');
                $errors = [];
                if (empty($nom)) $errors[] = 'Nom obligatoire.';
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format d\'email invalide.';
                if (empty($sujet)) $errors[] = 'Sujet obligatoire.';
                if (empty($message_contact)) $errors[] = 'Le message est obligatoire.';
                if (empty($errors)) {
                    $ligne = date('d/m/Y H:i') . ' | ' . $nom . ' | ' . $email . ' | ' . $sujet . ' | ' . $message_contact . PHP_EOL;
                    file_put_contents('uploads/messages.txt', $ligne, FILE_APPEND);
                    $success = 'Votre message a bien été envoyé. Merci !';
                } else { $error = implode('<br>', $errors); }
            }
            $horaires = ['Lundi'=>['matin'=>'9h00 - 13h00','apres'=>'15h00 - 19h00','ouvert'=>true],'Mardi'=>['matin'=>'9h00 - 13h00','apres'=>'15h00 - 19h00','ouvert'=>true],'Mercredi'=>['matin'=>'9h00 - 13h00','apres'=>'15h00 - 19h00','ouvert'=>true],'Jeudi'=>['matin'=>'9h00 - 13h00','apres'=>'15h00 - 19h00','ouvert'=>true],'Vendredi'=>['matin'=>'9h00 - 13h00','apres'=>'15h00 - 19h00','ouvert'=>true],'Samedi'=>['matin'=>'9h00 - 13h00','apres'=>'Matin seul','ouvert'=>false],'Dimanche'=>['matin'=>'—','apres'=>'—','ouvert'=>false]];
            $joursSemaine = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
            $aujourdhui = $joursSemaine[date('N') - 1];
            $estOuvert = $horaires[$aujourdhui]['ouvert'] && date('H') >= 9 && date('H') < 19;
            ?>
            <div class="row">
                <div class="col-md-6">
                    <h2>Contactez-nous</h2>
                    <p>Nous vous répondrons dans les plus brefs délais.</p>
                    <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                    <?php if($success): ?><div class="alert alert-success">📩 <?php echo e($success); ?></div><?php endif; ?>
                    <form method="POST"><div class="mb-3"><label class="form-label">Nom complet *</label><input type="text" name="nom" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Sujet *</label><select name="sujet" class="form-select" required><option value="Information">Information</option><option value="Réclamation">Réclamation</option><option value="Commande">Commande</option></select></div>
                    <div class="mb-3"><label class="form-label">Votre message...</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
                    <button type="submit" name="envoyer" class="btn btn-primary">Envoyer</button></form>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4"><div class="card-body"><h5>Nos coordonnées</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Rue de la Paix, Paris</p>
                    <p><i class="fas fa-phone"></i> 01 23 45 67 89</p>
                    <p><i class="fas fa-envelope"></i> contact@monsite.fr</p></div></div>
                    <div class="card"><div class="card-body"><h5>Nos horaires</h5>
                    <p class="<?php echo $estOuvert ? 'text-success' : 'text-danger'; ?>"><?php echo $estOuvert ? '✅ Nous sommes ouverts actuellement !' : '❌ Les horaires d\'aujourd\'hui sont terminés. Revenez demain !'; ?></p>
                    <ul class="list-unstyled"><li>9h00 - 13h00</li><li>15h00 - 19h00</li></ul><hr>
                    <p><strong>Jours d'ouverture — 14 prochains jours</strong></p>
                    <table class="table table-sm"><thead><tr><th>Jour</th><th>Date</th><th>Matin</th><th>Après-midi</th><th>Statut</th></tr></thead>
                    <tbody><?php for($i = 0; $i < 14; $i++): $date = new DateTime(); $date->modify("+$i days"); $nomJour = $joursSemaine[(int)$date->format('N') - 1]; $hor = $horaires[$nomJour]; $isToday = ($i == 0); $statut = $hor['ouvert'] ? 'Ouvert' : 'Fermé'; ?>
                    <tr class="<?php echo $isToday ? 'today' : ''; ?>"><td><?php echo $nomJour; ?><?php echo $isToday ? ' <strong>Aujourd\'hui</strong>' : ''; ?></td><td><?php echo $date->format('d/m/Y'); ?></td><td><?php echo $hor['matin']; ?></td><td><?php echo $hor['apres']; ?></td><td class="<?php echo $hor['ouvert'] ? 'ouvert' : 'ferme'; ?>"><?php echo $statut; ?></td></tr>
                    <?php endfor; ?></tbody></table>
                    <p><i class="fas fa-clock"></i> Heure actuelle du serveur : <strong><?php echo date('H:i:s'); ?></strong></p>
                    </div></div>
                </div>
            </div>

        <?php
        // ========== PAGE PANIER ==========
        elseif ($page == 'panier'):
            if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
            if (isset($_POST['ajouter_panier'])) {
                $ref = $_POST['produit_ref']; $nom = $_POST['produit_nom']; $prix = (float)$_POST['produit_prix']; $quantite = (int)$_POST['quantite'];
                if (isset($_SESSION['panier'][$ref])) $_SESSION['panier'][$ref]['quantite'] += $quantite;
                else $_SESSION['panier'][$ref] = ['nom' => $nom, 'prix' => $prix, 'quantite' => $quantite, 'categorie' => 'Produit'];
            }
            if (isset($_GET['supprimer'])) { unset($_SESSION['panier'][$_GET['supprimer']]); }
            $processeurs = ['i5-13600K'=>['nom'=>'Intel Core i5-13600K','prix'=>289.99],'i7-13700K'=>['nom'=>'Intel Core i7-13700K','prix'=>389.99],'r5-7600X'=>['nom'=>'AMD Ryzen 5 7600X','prix'=>249.99],'r7-7700X'=>['nom'=>'AMD Ryzen 7 7700X','prix'=>349.99]];
            $ramStockage = ['ram16'=>['nom'=>'RAM DDR5 16 Go','prix'=>69.99,'type'=>'RAM'],'ram32'=>['nom'=>'RAM DDR5 32 Go','prix'=>129.99,'type'=>'RAM'],'ssd512'=>['nom'=>'SSD NVMe 512 Go','prix'=>59.99,'type'=>'Stockage'],'ssd1to'=>['nom'=>'SSD NVMe 1 To','prix'=>99.99,'type'=>'Stockage']];
            $peripheriques = ['clavier'=>['nom'=>'Clavier mécanique RGB','prix'=>79.99],'souris'=>['nom'=>'Souris gaming 16000 DPI','prix'=>49.99],'webcam'=>['nom'=>'Webcam Full HD','prix'=>59.99]];
            $garanties = ['1an'=>['nom'=>'Garantie 1 an (incluse)','prix'=>0],'2ans'=>['nom'=>'Garantie 2 ans','prix'=>49.99],'3ans'=>['nom'=>'Garantie 3 ans','prix'=>89.99]];
            $selectedProcesseur = $_POST['processeur'] ?? $_SESSION['config_processeur'] ?? null;
            $selectedRamStockage = $_POST['ram_stockage'] ?? $_SESSION['config_ram_stockage'] ?? [];
            $selectedPeripheriques = $_POST['peripheriques'] ?? $_SESSION['config_peripheriques'] ?? [];
            $selectedGarantie = $_POST['garantie'] ?? $_SESSION['config_garantie'] ?? '1an';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider_config'])) {
                $_SESSION['config_processeur'] = $_POST['processeur']; $_SESSION['config_ram_stockage'] = $_POST['ram_stockage'] ?? []; $_SESSION['config_peripheriques'] = $_POST['peripheriques'] ?? []; $_SESSION['config_garantie'] = $_POST['garantie'];
                $_SESSION['panier'] = [];
                if ($_POST['processeur']) { $p = $processeurs[$_POST['processeur']]; $_SESSION['panier']['proc_'.$_POST['processeur']] = ['nom'=>$p['nom'],'prix'=>$p['prix'],'quantite'=>1,'categorie'=>'Processeur']; }
                foreach (($_POST['ram_stockage'] ?? []) as $item) { $p = $ramStockage[$item]; $_SESSION['panier']['rs_'.$item] = ['nom'=>$p['nom'],'prix'=>$p['prix'],'quantite'=>1,'categorie'=>$p['type']]; }
                foreach (($_POST['peripheriques'] ?? []) as $item) { $p = $peripheriques[$item]; $_SESSION['panier']['peri_'.$item] = ['nom'=>$p['nom'],'prix'=>$p['prix'],'quantite'=>1,'categorie'=>'Périphérique']; }
                $g = $garanties[$_POST['garantie']]; $_SESSION['panier']['garantie'] = ['nom'=>$g['nom'],'prix'=>$g['prix'],'quantite'=>1,'categorie'=>'Garantie'];
            }
            $couponCode = isset($_COOKIE['coupon_code']) ? $_COOKIE['coupon_code'] : null;
            $couponReduction = isset($_COOKIE['coupon_reduction']) ? $_COOKIE['coupon_reduction'] : null;
            $reduction = 0;
            if ($couponCode && isset($_POST['appliquer_coupon'])) $reduction = (int)str_replace('%', '', $couponReduction);
            $totalBrut = 0; $panierDetails = [];
            foreach ($_SESSION['panier'] as $item) { $prixTotal = $item['prix'] * $item['quantite']; $totalBrut += $prixTotal; $panierDetails[] = ['categorie'=>$item['categorie']??'Produit','nom'=>$item['nom'],'prix'=>$prixTotal]; }
            $totalAPayer = $totalBrut * (1 - $reduction / 100);
            ?>
            <h1>Mon Panier</h1>
            <p>Configurez votre setup informatique et appliquez votre coupon de réduction. <a href="?page=gagner">Gagnez un coupon →</a></p>
            <div class="row">
                <div class="col-md-6"><div class="card mb-4"><div class="card-body"><h5>Configuration</h5>
                <form method="POST"><div class="mb-3"><label class="form-label fw-bold">Processeurs (un seul choix)</label>
                <?php foreach($processeurs as $key => $proc): ?><div class="form-check"><input class="form-check-input" type="radio" name="processeur" value="<?php echo $key; ?>" <?php echo ($selectedProcesseur == $key) ? 'checked' : ''; ?>><label class="form-check-label"><?php echo $proc['nom']; ?> - <?php echo number_format($proc['prix'], 2); ?> €</label></div><?php endforeach; ?></div>
                <div class="mb-3"><label class="form-label fw-bold">RAM & Stockage (checkbox)</label>
                <?php foreach($ramStockage as $key => $item): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="ram_stockage[]" value="<?php echo $key; ?>" <?php echo (in_array($key, $selectedRamStockage)) ? 'checked' : ''; ?>><label class="form-check-label"><?php echo $item['nom']; ?> - <?php echo number_format($item['prix'], 2); ?> €</label></div><?php endforeach; ?></div>
                <div class="mb-3"><label class="form-label fw-bold">Périphériques (checkbox)</label>
                <?php foreach($peripheriques as $key => $item): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="peripheriques[]" value="<?php echo $key; ?>" <?php echo (in_array($key, $selectedPeripheriques)) ? 'checked' : ''; ?>><label class="form-check-label"><?php echo $item['nom']; ?> - <?php echo number_format($item['prix'], 2); ?> €</label></div><?php endforeach; ?></div>
                <div class="mb-3"><label class="form-label fw-bold">Garantie (liste)</label><select name="garantie" class="form-select">
                <?php foreach($garanties as $key => $item): ?><option value="<?php echo $key; ?>" <?php echo ($selectedGarantie == $key) ? 'selected' : ''; ?>><?php echo $item['nom']; ?> - <?php echo number_format($item['prix'], 2); ?> €</option><?php endforeach; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Coupon</label>
                <?php if($couponCode): ?><div class="alert alert-success">Coupon appliqué : <?php echo $couponCode; ?> (<?php echo $couponReduction; ?>)</div>
                <button type="submit" name="appliquer_coupon" class="btn btn-sm btn-success">Appliquer le coupon</button>
                <?php else: ?><p class="text-muted">Vous n'avez pas de coupon. <a href="?page=gagner">Gagnez-en un !</a></p><?php endif; ?></div>
                <button type="submit" name="valider_config" class="btn btn-primary w-100">Valider mon panier</button></form>
                </div></div></div>
                <div class="col-md-6"><div class="card"><div class="card-body"><h5>Récapitulatif de votre panier</h5>
                <table class="table table-sm"><thead><tr><th>Catégorie</th><th>Produit</th><th>Prix</th></tr></thead>
                <tbody><?php foreach($panierDetails as $item): ?><tr><td><?php echo $item['categorie']; ?></td><td><?php echo $item['nom']; ?></td><td><?php echo number_format($item['prix'], 2); ?> €</td></tr><?php endforeach; ?></tbody>
                <tfoot><tr class="fw-bold"><td colspan="2">Total brut</td><td><?php echo number_format($totalBrut, 2); ?> €</td></tr>
                <?php if($reduction > 0): ?><tr class="text-success"><td colspan="2">Réduction (<?php echo $reduction; ?>%)</td><td>-<?php echo number_format($totalBrut * $reduction / 100, 2); ?> €</td></tr><?php endif; ?>
                <tr class="fw-bold fs-5"><td colspan="2">Total à payer</td><td class="text-primary"><?php echo number_format($totalAPayer, 2); ?> €</td></tr></tfoot></table>
                <p class="text-muted small">Procédure au paiement</p></div></div></div>
            </div>

        <?php
        // ========== PAGE PROFIL ==========
        elseif ($page == 'profil'):
            $message = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['sauvegarder'])) {
                    if (!empty($_POST['prenom'])) setcookie('prenom', $_POST['prenom'], time() + 365 * 86400, '/');
                    setcookie('theme', $_POST['theme'], time() + 365 * 86400, '/');
                    setcookie('langue', $_POST['langue'], time() + 30 * 86400, '/');
                    header('Location: ?page=profil'); exit;
                } elseif (isset($_POST['effacer'])) {
                    setcookie('prenom', '', time() - 3600, '/');
                    setcookie('theme', '', time() - 3600, '/');
                    setcookie('langue', '', time() - 3600, '/');
                    header('Location: ?page=profil'); exit;
                }
            }
            $cookiesList = [
                ['nom'=>'prenom','label'=>'Prénom visiteur','valeur'=>$_COOKIE['prenom']??'—','statut'=>isset($_COOKIE['prenom'])?'Actif':'Non défini'],
                ['nom'=>'theme','label'=>'Thème UI','valeur'=>$_COOKIE['theme']??'—','statut'=>isset($_COOKIE['theme'])?'Actif':'Non défini'],
                ['nom'=>'langue','label'=>'Langue','valeur'=>$_COOKIE['langue']??'—','statut'=>isset($_COOKIE['langue'])?'Actif':'Non défini']
            ];
            ?>
            <h1>Mes Préférences</h1>
            <p>Vos préférences sont sauvegardées dans des <strong>cookies</strong> sur votre navigateur.</p>
            <div class="row">
                <div class="col-md-6"><div class="card"><div class="card-body"><h5>Renseigner mes préférences</h5>
                <form method="POST"><div class="mb-3"><label class="form-label">Votre prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?php echo e($_COOKIE['prenom']??''); ?>" placeholder="Votre prénom">
                <small class="text-muted">Affiche dans la navbar sur toutes les pages. Cookie d'une durée de 1 an.</small></div>
                <div class="mb-3"><label class="form-label">Thème d'affichage</label>
                <div class="form-check"><input class="form-check-input" type="radio" name="theme" value="clair" <?php echo (($_COOKIE['theme']??'clair')=='clair')?'checked':''; ?>><label class="form-check-label">Thème clair - Fond blanc</label></div>
                <div class="form-check"><input class="form-check-input" type="radio" name="theme" value="sombre" <?php echo (($_COOKIE['theme']??'clair')=='sombre')?'checked':''; ?>><label class="form-check-label">Thème sombre - Fond noir</label></div>
                <small class="text-muted">Cookie d'une durée de 1 an.</small></div>
                <div class="mb-3"><label class="form-label">Langue préférée</label>
                <select name="langue" class="form-select"><option value="fr" <?php echo (($_COOKIE['langue']??'fr')=='fr')?'selected':''; ?>>FR Français</option>
                <option value="en" <?php echo (($_COOKIE['langue']??'fr')=='en')?'selected':''; ?>>EN English</option>
                <option value="es" <?php echo (($_COOKIE['langue']??'fr')=='es')?'selected':''; ?>>ES Español</option></select>
                <small class="text-muted">Cookie d'une durée de 30 jours.</small></div>
                <button type="submit" name="sauvegarder" class="btn btn-primary">Sauvegarder mes préférences</button>
                <button type="submit" name="effacer" class="btn btn-danger ms-2">Tout effacer</button></form>
                </div></div></div>
                <div class="col-md-6"><div class="card"><div class="card-body"><h5>Cookies actuellement stockés</h5>
                <table class="table table-sm"><thead><tr><th>Cookie</th><th>Valeur</th><th>Statut</th></tr></thead>
                <tbody><?php foreach($cookiesList as $cookie): ?><tr><td><?php echo $cookie['nom']; ?><br><small class="text-muted"><?php echo $cookie['label']; ?></small></td>
                <td><?php echo e($cookie['valeur']); ?></td><td><?php echo $cookie['statut']; ?></td></tr><?php endforeach; ?></tbody></table>
                <h6 class="mt-3">Tous les cookies reçus</h6><table class="table table-sm"><thead><tr><th>Clé</th><th>Valeur</th></tr></thead>
                <tbody><?php foreach($_COOKIE as $key => $value): if(!in_array($key, ['prenom','theme','langue','coupon_code','coupon_reduction'])): ?>
                <tr><td><?php echo e($key); ?></td><td><?php echo e(substr($value, 0, 30)); ?>...</td></tr><?php endif; endforeach; ?></tbody></table>
                </div></div></div>
            </div>

        <?php
        // ========== PAGE LOGIN ==========
        elseif ($page == 'login'):
            $users = ['user@monsite.fr'=>['password'=>'user123','nom'=>'Utilisateur','role'=>'user'],'admin@monsite.fr'=>['password'=>'admin123','nom'=>'Administrateur','role'=>'admin']];
            if (isset($_GET['logout'])) { session_destroy(); header('Location: ?page=login'); exit; }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
                $email = trim($_POST['email']); $password = trim($_POST['password']);
                if (isset($users[$email]) && $users[$email]['password'] === $password) {
                    $_SESSION['user_email'] = $email; $_SESSION['user_nom'] = $users[$email]['nom']; $_SESSION['user_role'] = $users[$email]['role']; $_SESSION['login_time'] = time();
                    header('Location: ?page=accueil'); exit;
                } else { $error = 'Email ou mot de passe incorrect.'; }
            }
            if (isset($_SESSION['user_email'])): ?>
                <div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
                <h2 class="text-center mb-4">Connexion</h2>
                <div class="alert alert-success"><h5>Bonjour <?php echo e($_SESSION['user_nom']); ?> !</h5>
                <p>Email : <?php echo e($_SESSION['user_email']); ?><br>Rôle : <?php echo e($_SESSION['user_role']); ?></p>
                <p class="small">Connecté depuis <?php echo time() - $_SESSION['login_time']; ?> secondes<br>(Depuis le <?php echo date('d/m/Y à H:i', $_SESSION['login_time']); ?>)</p>
                <a href="?page=login&logout=1" class="btn btn-danger">Se déconnecter</a>
                <a href="?page=accueil" class="btn btn-primary">Retour au site</a></div></div></div></div>
            <?php else: ?>
                <div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
                <h2 class="text-center mb-4">Connexion</h2>
                <?php if(isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST"><div class="mb-3"><label class="form-label">Adresse email</label><input type="email" name="email" class="form-control" placeholder="votre@email.com" required></div>
                <div class="mb-3"><label class="form-label">Mot de passe</label><input type="password" name="password" class="form-control" placeholder="********" required></div>
                <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button></form>
                <hr><div class="alert alert-info"><strong>Comptes de test :</strong><br>
                <strong>Admin :</strong> admin@monsite.fr / admin123<br>
                <strong>Utilisateur :</strong> user@monsite.fr / user123</div></div></div></div>
            <?php endif;

        // ========== PAGE PAR DÉFAUT ==========
        else: ?>
            <div class="text-center py-5"><h1 class="display-1">404</h1><h2 class="mb-4">Page non trouvée</h2>
            <p class="lead">Désolé, la page que vous recherchez n'existe pas.</p><a href="?page=accueil" class="btn btn-primary">Retour à l'accueil</a></div>
        <?php endif; ?>
    </main>
    
    <?php
    // ========== FOOTER ==========
    ?>
    <footer class="bg-light text-muted py-4 mt-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4"><h5>Mon site</h5><p>Un site créé avec PHP & Bootstrap</p></div>
                <div class="col-md-4"><h5>Navigation</h5><ul class="list-unstyled">
                    <li><a href="?page=accueil" class="text-decoration-none">Accueil</a></li>
                    <li><a href="?page=menu" class="text-decoration-none">Menu</a></li>
                    <li><a href="?page=contact" class="text-decoration-none">Contact</a></li></ul></div>
                <div class="col-md-4"><h5>À propos</h5><p>cours de php 2026</p>
                <div><a href="#" class="text-decoration-none me-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-decoration-none me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-decoration-none"><i class="fab fa-linkedin"></i></a></div></div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>