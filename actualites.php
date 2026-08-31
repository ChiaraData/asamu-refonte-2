<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$publishedPosts = array_values(array_filter($newsPosts, static fn (array $post): bool => !empty($post['is_published'])));
usort($publishedPosts, static fn (array $left, array $right): int => strcmp((string) ($right['published_at'] ?? ''), (string) ($left['published_at'] ?? '')));
$newsInitialCount = 3;
$hasMoreNews = count($publishedPosts) > $newsInitialCount;
$requestedSlug = trim((string) ($_GET['article'] ?? ''));
$article = null;
foreach ($publishedPosts as $post) {
    if (($post['slug'] ?? '') === $requestedSlug) {
        $article = $post;
        break;
    }
}

$pageTitle = $article ? (string) ($article['title'] ?? 'Actualité') : 'Actualités';
$pageDescription = $article ? (string) ($article['excerpt'] ?? 'Actualité de l’AS amU.') : 'Les dernières informations, résultats et rendez-vous de l’AS amU.';
require_once __DIR__ . '/includes/header.php';

/** @return string */
function news_date_label(string $date): string
{
    $timestamp = strtotime($date);
    return $timestamp ? date('d/m/Y', $timestamp) : $date;
}
?>
<?php if ($article): ?>
    <section class="page-hero compact news-article-hero">
        <div class="container">
            <p class="eyebrow"><?= e((string) ($article['category'] ?? 'Actualité')) ?> · <?= e(news_date_label((string) ($article['published_at'] ?? ''))) ?></p>
            <h1><?= e((string) ($article['title'] ?? 'Actualité')) ?></h1>
            <?php if (!empty($article['excerpt'])): ?><p class="lead"><?= e((string) $article['excerpt']) ?></p><?php endif; ?>
        </div>
    </section>
    <article class="section container news-article-content">
        <?php if (!empty($article['image'])): ?><img src="<?= e((string) $article['image']) ?>" alt="<?= e((string) ($article['image_alt'] ?? $article['title'] ?? '')) ?>"><?php endif; ?>
        <div class="news-rich-text"><?= rich_text_render((string) ($article['body'] ?? '')) ?></div>
        <a class="btn btn-secondary" href="actualites">← Toutes les actualités</a>
    </article>
<?php else: ?>
    <section class="page-hero compact">
        <div class="container">
            <p class="eyebrow">À la une</p>
            <h1>Les actualités.</h1>
            <p class="lead">Résultats, événements et informations de l’AS amU, au fil de la saison.</p>
        </div>
    </section>
    <section class="section container">
        <?php if ($publishedPosts): ?>
            <div id="news-list" class="news-grid" data-news-list>
                <?php foreach ($publishedPosts as $post): ?>
                    <article class="news-card" data-news-card>
                        <?php if (!empty($post['image'])): ?><img src="<?= e((string) $post['image']) ?>" alt="<?= e((string) ($post['image_alt'] ?? $post['title'] ?? '')) ?>" loading="lazy" decoding="async"><?php endif; ?>
                        <div>
                            <p><?= e((string) ($post['category'] ?? 'Actualité')) ?> · <?= e(news_date_label((string) ($post['published_at'] ?? ''))) ?></p>
                            <h2><?= e((string) ($post['title'] ?? 'Actualité AS amU')) ?></h2>
                            <?php if (!empty($post['excerpt'])): ?><p class="news-card-excerpt"><?= e((string) $post['excerpt']) ?></p><?php endif; ?>
                            <a class="text-link" href="actualites?article=<?= urlencode((string) ($post['slug'] ?? '')) ?>">Lire l’actualité →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if ($hasMoreNews): ?>
                <div class="news-load-more">
                    <button class="btn btn-primary" type="button" data-news-load-more data-news-target="news-list" data-news-step="3" hidden>Voir plus d’actualités</button>
                    <p class="sr-only" data-news-status aria-live="polite"></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="photo-gallery-empty"><span class="photo-gallery-empty-icon" aria-hidden="true">✦</span><div><h2>Les actualités arrivent bientôt.</h2><p>Les prochaines informations de l’AS amU seront publiées ici.</p></div></div>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
