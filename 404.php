<?php
declare(strict_types=1);

http_response_code(404);

$pageTitle = 'Page introuvable';
$pageDescription = 'La page demandée n’existe pas ou a été déplacée.';

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Erreur 404</p>
        <h1>Cette page est introuvable.</h1>
        <p class="lead">Elle a peut-être été déplacée lors de la refonte du site.</p>
        <p><a class="btn btn-primary" href="./">Retour à l’accueil</a></p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
