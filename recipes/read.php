<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "common.php";
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "variables.inc.php";
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "functions.inc.php";

// Paramètres de la requête SQL pour récupérer la recette
$sessionName = 'GET_RECIPE';
$recipeParams = [
    "fields" => ['*'],
    "join" => [
        'images i' => 'i.recipe_id = r.recipe_id',
    ],
    "where" => [
        "conditions" => [
            'r.is_enabled' => '= 1',
            'r.recipe_id' => '= :recipe_id'
        ],
    ],
    "table" => ["recipes r"],
    "error" => ["Cette recette n'existe pas"]
];

// Paramètres de la requête SQL pour récupérer les commentaires
$sessionCommentName = 'GET_COMMENT';
$commentParams = [
    "fields" => ['comment_id', 'comment', 'user_id', 'c.title', 'review'],
    "date" => ['DATE_FORMAT(c.created_at, "%d/%m/%Y") as comment_date'],
    "join" => [
        'comments c' => 'c.recipe_id = r.recipe_id',
    ],
    "where" => [
        "conditions" => [
            'r.is_enabled' => '= 1',
            'r.recipe_id' => '= :recipe_id'
        ],
    ],
    "table" => ["recipes r"],
    "error" => ["Ce commentaire n'existe pas"],
    "fetchAll" => true
];

/**
 * Permet de filtrer quelles clés de l'array getInfos
 * ne seront pas renvoyées à l'array $recipe
 * @var mixed
 */
$filterKeysToRemove = [
    'comment_id',
    'comment',
    'rating',
    'review',
    'ranking',
    'comment_date'
];
$filterCommentKeys = [
    'comment_id',
    'comment',
    'comment_date',
    'user_id'
];

/**
 * Grabing URL ID from index page and fetching rows datas
 */
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getID = $_GET['id'];
    $id = new RecipeView($getID);
    $averageRating = $id->fetchAverageRatingCommentsById($getID);

    try {
        /**
         * Création d'un array $recipe :
         * Contient toutes les informations de $getInfos
         * et utilise $filterKeysToRemove pour retirer certaines clés
         * Puis modifie la clé 'rating' pour l'arrondir
         */
        // Retrieve all recipe infos
        $getInfos = $id->retrieveFromTable($recipeParams, $sessionName);
        if (isset($_SESSION[$sessionName])) {
            foreach ($getInfos as $key => $value) {
                if (!in_array($key, $filterKeysToRemove)) {
                    $recipe[$key] = $value;
                }
            }
            unset($_SESSION[$sessionName]);
        } else {
            throw new Error("Quelque chose d'anormal s'est produit lors de la récupération de la recette");
        }

        /**
         * Récupère toutes les itérations de commentaires données par $getComments
         * Puis rajoute chaque itérations au tableau $recipe
         */
        // Retrieve all comments related to the recipe
        $getComments = $id->retrieveFromTable($commentParams, $sessionCommentName);

        if (isset($_SESSION[$sessionCommentName])) {
            foreach ($getComments as $comment) {
                if (!is_null($comment['comment_id'])) {
                    $recipe['comments'][] = [
                        'comment_id' => $comment['comment_id'],
                        'comment' => $comment['comment'],
                        'user_id' => $comment['user_id'],
                        'created_at' => $comment['comment_date'],
                        'review' => $comment['review'],
                        'title' => $comment['title'],
                    ];
                }
            }
            unset($_SESSION[$sessionCommentName]);
        } else {
            throw new Error("Impossible d'afficher les commentaires");
        }
    } catch (\Throwable $error) {
        echo $error->getMessage();
    }

    $recipe['rating'] = $averageRating['rating'] ?? "0";

    /* $loggedUser = LoginController::checkLoggedStatus();
    print_r  ($loggedUser); */
    /* foreach($getInfos[0] as $recipes => $value) {
        echo($recipes .' => '. $value . '<br>');
    } */
} else {
    header('Location: ../index.php?error=noId');
}

$title = htmlspecialchars($recipe['title']);
$script = 'src="../scripts/typeWriter.js" type="module" defer';

ob_start()

?>
<header>
    <?php // if ($error) :
    ?>
    <?php // $error
    ?>
    <?php // endif
    ?>
    <div class="read_header">
        <img class="read_header__bg-img" src="<?= '../' . $recipe['img_path'] ?>" alt="">
        <div class="read_header__side-bg"></div>
        <img class="read_header__inner-poster" src="<?= '../' . $recipe['img_path'] ?>" alt="">
        <h1 class="read_header__title"><?= htmlspecialchars($recipe['title']) ?></h1>
        <div class="read_header__description"><?= htmlspecialchars($recipe['description']) ?></div>
        <div class="read_header__preview">
            <div class="read_header__preview__time"><?= $recipe['total_time'] . ' ' . $recipe['total_time_length'] ?></div>
            <div class="dot"></div>
            <div class="read_header__preview__skills">Très facile</div>
            <div class="dot"></div>
            <div class="read_header__preview__price">Bon marché</div>
        </div>
        <div class="read_header__buttons">
            <a type="button" class="btn" href="../index.php">add/j'aime</a>
            <a type="button" class="btn" href="../index.php">partager</a>
        </div>
    </div>
</header>
<div class="read_page">
    <article>
        <?php foreach ($recipe as $key => $value) : ?>
            <?php if (str_starts_with($key, 'step_') && !empty($value)) : ?>
                <div>
                    <?php
                    if ($key === 'step_1') {
                        echo '<p><span> Première étape </span></p>';
                    }
                    if ($key === 'step_2') {
                        echo '<p><span> Deuxième étape </span></p>';
                    }
                    if ($key === 'step_3') {
                        echo '<p><span> Troisième étape </span></p>';
                    }
                    if ($key === 'step_4') {
                        echo '<p><span> Quatrième étape </span></p>';
                    }
                    if ($key === 'step_5') {
                        echo '<p><span> Cinquième étape </span></p>';
                    }
                    if ($key === 'step_6') {
                        echo '<p><span> Sixième étape </span></p>';
                    }
                    ?>
                    <p><?= htmlspecialchars($value) ?></p>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </article>

    <!-- </div> -->
    <!-- <body class="d-flex flex-column min-vh-100"> -->
    <aside class="read__aside">
        <h2><?= htmlspecialchars($recipe['title']) ?></h2>
        <div>
            <div class="read__preparations">
                <div class="read__aside__item">
                    <span class="read__aside__head">Temps de cuisson :</span>
                    <p class="read__aside__name"> <?= $recipe['oven_time'] . ' ' . $recipe['oven_time_length'] ?></p>
                </div>
                <div class="read__aside__item">
                    <span class="read__aside__head">Temps de repos :</span>
                    <p class="read__aside__name"> <?= $recipe['resting_time'] . ' ' . $recipe['resting_time_length'] ?></p>
                </div>
                <div class="read__aside__item">
                    <span class="read__aside__head">Temps total :</span>
                    <p class="read__aside__name"> <?= $recipe['total_time'] . ' ' . $recipe['total_time_length'] ?></p>
                </div>
                <div class="read__aside__item">
                    <span class="read__aside__head">Nombre de personne<?= $recipe['persons'] > 1 ? "s" : null ?> :</span>
                    <p class="read__aside__name"> <?= $recipe['persons']; ?></p>
                </div>
            </div>
            <div class="read__credits">
                <p><i>Contribuée par <span class="read__aside__head"><?= strip_tags(displayAuthor($recipe['author'])) ?></span></i></p>
                <!-- <p><i>Contribuée par <?php //echo($recipe['author']);
                                            ?></i></p> -->
                <p><b>Evaluée par la communauté à <?= $recipe['rating'] ?><span class="read__aside__head"> / 5</span></b></p>
            </div>
        </div>
    </aside>
</div>
<div class="preps">
    <article>
        <?php foreach ($recipe as $key => $value) : ?>
            <?php if (str_starts_with($key, 'ingredient_') && !empty($value)) : ?>
                <div>
                    <?php
                    if ($key === 'ingredient__1') {
                        echo '<p><span> Première étape </span></p>';
                    }
                    if ($key === 'ingredient__2') {
                        echo '<p><span> Deuxième étape </span></p>';
                    }
                    if ($key === 'ingredient__3') {
                        echo '<p><span> Troisième étape </span></p>';
                    }
                    if ($key === 'ingredient__4') {
                        echo '<p><span> Quatrième étape </span></p>';
                    }
                    if ($key === 'ingredient__5') {
                        echo '<p><span> Cinquième étape </span></p>';
                    }
                    if ($key === 'ingredient__6') {
                        echo '<p><span> Sixième étape </span></p>';
                    }
                    ?>
                    <p><?php echo htmlspecialchars($value) ?></p>
                </div>
            <?php endif ?>
            <?php if ($key === 'custom_ingredients') : ?>
                <p><?php $str = explode(',', $value) ?></p>
                <?php foreach ($str as $key) : ?>
                    <p><?php echo htmlspecialchars($key) ?></p>
                <?php endforeach ?>
            <?php endif ?>
        <?php endforeach ?>
    </article>
</div>
<?php //$checkId->displayComments($recipe, $getInfos)
?>

<hr />

<div class="comment-grid">
    <div class="note_container">
        <h3>Note des utilisateurs</h3>
        <div class="note__stars">
            <?php
            echo display_5_stars($recipe['rating'], $recipe['recipe_id']);
            ?>
        </div>
        <p>Filtrer par notes</p>
    </div>
    <?php if (isset($recipe['comments']) && count($recipe['comments']) > 0): ?>
        <div class="comment_container">
            <h2>Vos commentaires</h2>
            <?php foreach ($recipe['comments'] as $comment): ?>
                <div class="comment">
                    <div class="comment__header">
                        <?php include '../templates/profile_picture.html' ?>
                        <i>Par <?php echo strip_tags($id->display_user($comment['comment_id'])) ?>, le <?php echo strip_tags($comment['created_at']) ?></i>
                    </div>
                    <div class="comment__title">
                        <?php
                        echo display_5_stars((string)$comment['review'], $comment['comment_id']);
                        ?>
                        <p class="comment__title-text"><?= strip_tags($comment['title']) ?></p>
                    </div>
                    <p class="comment__body"><?php echo strip_tags($comment['comment']) ?></p>
                </div>
            <?php endforeach ?>
        </div>
    <?php else : ?>
        <p>Soyez le premier à poster un commentaire !</p>
    <?php endif ?>
</div>

<hr />

<?php $loggedUser = LoginController::checkLoggedStatus() ?>

<?php if (isset($loggedUser)): ?>
    <?php //if ($errorComment) :
    ?>
    <?php // $errorComment
    ?>
    <?php // endif
    ?>
    <div class="comment-form_container">
        <h4>Postez un commentaire</h4>
        <?php include_once '../comments/comments.php' ?>
    </div>

    <?php //$checkId->displayCommentForm($recipe)
    ?>
    <?php //$checkId->displayCommentSuccess()
    ?>
<?php endif ?>

<!-- </div> -->
<!-- </body> -->

<?php
$content = ob_get_clean();
require('../templates/layout.php')
?>