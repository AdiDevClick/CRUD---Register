<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* include_once("../includes/class-autoloader.inc.php");
//include_once('../includes/functions.inc.php');

**
 * Grabing URL ID from index page and fetching rows datas
 *
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $getDatas = $_GET['id'];

    //$idDatas = new RecipeView($getDatas);
    $checkId = new RecipeView($getDatas);
    //$idDatas->checkId();
    //$getInfos = $idDatas->getRecipeInfoById();
    $averageRating = $checkId->getAverageRatingCommentsById($getDatas);
    $getInfos = $checkId->getRecipesWithCommentsById($getDatas);

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
} */

/* $postData = $_POST;

if (
    !isset($postData['comment']) &&
    !isset($postData['recipe_id']) &&
    !is_numeric($postData['recipe_id'])
    )
{
    echo('Le commentaire est invalide.');
    return;
}
$loggedUser = LoginController::checkLoggedStatus();
if (!isset($loggedUser)) {
    echo('Vous devez être authentifié pour soumettre un commentaire');
    return;
}

$comment = $postData['comment'];
$recipeId = $postData['recipe_id']; */

?>

<form action="<?php echo($rootUrl . '../comments/post_create.php'); ?>" method="POST">
<!--<form action="<?php //echo($rootUrl . 'recettes/recipes/read.php');?>" method="POST"> -->
    <div class="mb-3 hidden">
    <!-- <div class="mb-3 visually-hidden"> -->
        <input class="form-control" type="text" name="recipe_id" value="<?php echo($getDatas); ?>" />
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Postez un commentaire</label>
        <textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>