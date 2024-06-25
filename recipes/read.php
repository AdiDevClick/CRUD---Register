<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
//include_once('../includes/functions.inc.php');

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
    'recipe' => $getInfos[0]['recipe'],
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

ob_start()

?>

<!-- <body class="d-flex flex-column min-vh-100"> -->
    <div class="card">

        <h1><?php echo($recipe['title']); ?></h1>
        <div class="row">
            <article class="col">
                <?php echo($recipe['recipe']); ?>
            </article>
            <aside class="col">
                <p><i>Contribuée par <?php echo($recipe['author']); ?></i></p>
                <p><b>Evaluée par la communauté à <?php echo($recipe['rating']); ?> / 5</b></p>
            </aside>
        </div>
    </div>
        <?php //$checkId->displayComments($recipe, $getInfos)?>
        <?php if(count($recipe['comments']) > 0): ?>
        <hr />
        <h2>Commentaires</h2>
        <div class="row">>
            <?php foreach($recipe['comments'] as $comment): ?>
                <div class="comment">
                    <p><?php echo strip_tags($comment['created_at']) ?></p>
                    <p><?php echo strip_tags($comment['comment']) ?></p>
                    <i>(<?php echo strip_tags($checkId->display_user($comment['user_id'])) ?>)</i>
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