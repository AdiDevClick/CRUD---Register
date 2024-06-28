<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
include_once('../includes/functions.inc.php');

/***
 * Grabing URL ID from index page and fetching rows datas
 */
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getDatas = $_GET['id'];
    //$idDatas = new RecipeView($getDatas);
    $checkId = new RecipeView($getDatas);
    //$idDatas->checkId();
    //$getInfos = $idDatas->getRecipeInfoById();
    $averageRating = $checkId->fetchAverageRatingCommentsById($getDatas);
    $getInfos = $checkId->fetchRecipesWithCommentsById($getDatas);

    // Inserting infos into the recipe array
    $recipe = [
    'recipe_id' => $getInfos[0]['recipe_id'],
    'title' => $getInfos[0]['title'],
    'persons' => $getInfos[0]['persons'],
    'oven_time' => $getInfos[0]['oven_time'],
    'oven_time_length' => $getInfos[0]['oven_time_length'],
    'resting_time' => $getInfos[0]['resting_time'],
    'resting_time_length' => $getInfos[0]['oven_time_length'],
    'total_time' => $getInfos[0]['total_time'],
    'total_time_length' => $getInfos[0]['total_time_length'],
    'step_1' => $getInfos[0]['step_1'],
    'step_2' => $getInfos[0]['step_2'],
    'step_3' => $getInfos[0]['step_3'],
    'step_4' => $getInfos[0]['step_4'],
    'step_5' => $getInfos[0]['step_5'],
    'step_6' => $getInfos[0]['step_6'],
    'author' => $getInfos[0]['author'],
    'comments' => [],
    'rating' => $averageRating['rating']
];

    // Append comments array into the recipe array
    foreach($getInfos as $comment) {
        if (!is_null($comment['comment_id'])) {
            $recipe['comments'][] = [
                'comment_id' => $comment['comment_id'],
                'comment' => $comment['comment'],
                'user_id' => $comment['user_id'],
                'created_at' => $comment['comment_date'],
            ];
            //echo $recipe['comments']['user_id'];
        }
    }

    /* $loggedUser = LoginController::checkLoggedStatus();
    print_r  ($loggedUser); */
    /* foreach($getInfos[0] as $recipes => $value) {
        echo($recipes .' => '. $value . '<br>');
    } */

} else {
    header('Location: ../index.php?error=noId');
}



$title = "Site de Recettes - " . htmlspecialchars($recipe['title']);
$script = 'src="../scripts/typeWriter.js" defer';

ob_start()

?>
<header>
    <div class="hero">
        <img src="" alt="">
        <p><span><?php echo($recipe['title']) ?></span></p>
    </div>
</header>
<div class="read_page">
    <article>
        <?php foreach ($recipe as $key => $value) : ?>
            <?php if (str_starts_with($key, 'step_') && !empty($value)) : ?>
                <div>
                    <?php
                        if ($key === 'step_1') echo '<p><span> Première étape </span></p>';
                        if ($key === 'step_2') echo '<p><span> Deuxième étape </span></p>';
                        if ($key === 'step_3') echo '<p><span> Troisième étape </span></p>';
                        if ($key === 'step_4') echo '<p><span> Quatrième étape </span></p>';
                        if ($key === 'step_5') echo '<p><span> Cinquième étape </span></p>';
                        if ($key === 'step_6') echo '<p><span> Sixième étape </span></p>'
                    ?>
                    <p><?php echo($value) ?></p>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </article>
<!-- </div> -->
<!-- <body class="d-flex flex-column min-vh-100"> -->
    <aside class="read__aside">
        <h1><?php echo($recipe['title']) ?></h1>
        <div class="row">
            <div class="col">
                <p>Temps de cuisson :</p><p> <?php echo($recipe['oven_time'] . ' ' . $recipe['oven_time_length']) ?></p>
                <p>Temps de repos :</p><p> <?php echo($recipe['resting_time'] . ' ' . $recipe['resting_time_length']) ?></p>
                <p>Temps total :</p><p> <?php echo($recipe['total_time'] . ' ' . $recipe['total_time_length']) ?></p>
                <p>Nombre de personne<?= $recipe['persons'] > 1 ? "s" : null ?> :</p><p> <?php echo($recipe['persons']); ?></p>
            </div>
            <div class="col">
                <p><i>Contribuée par <?php echo strip_tags(displayAuthor($recipe['author'])) ?></i></p>
                <!-- <p><i>Contribuée par <?php //echo($recipe['author']);?></i></p> -->
                <p><b>Evaluée par la communauté à <?php echo($recipe['rating']); ?> / 5</b></p>
            </div>
        </div>
    </aside>
</div>

        <?php //$checkId->displayComments($recipe, $getInfos)?>
        <?php if(count($recipe['comments']) > 0): ?>
        <hr />
        <h2>Commentaires</h2>
        <div class="row">>
            <?php foreach($recipe['comments'] as $comment): ?>
                <div class="comment">
                    <p><?php echo strip_tags($checkId->display_user($comment['user_id'])) ?></p>
                    <p><?php echo strip_tags($comment['comment']) ?></p>
                    <i>(Le : <?php echo strip_tags($comment['created_at']) ?>)</i>
                </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
        <hr />
        <?php $loggedUser = LoginController::checkLoggedStatus()?>
        <?php if (isset($loggedUser)): ?>
            <?php include_once('../comments/comments.php') ?>
            <?php //$checkId->displayCommentForm($recipe)?>
            <?php //$checkId->displayCommentSuccess()?>
        <?php endif ?>
    <!-- </div> -->
<!-- </body> -->

<?php
$content = ob_get_clean();
require('../templates/layout.php')
?>