<?php declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/class-autoloader.inc.php");
include_once('../logs/customErrorHandlers.php');
include_once('../includes/variables.inc.php');
include_once('../includes/functions.inc.php');

/**
 * Permet de filtrer quelles clés de l'array getInfos
 * ne seront pas renvoyées à l'array $recipe
 * @var mixed
 */
$filterKeys = [
    'comment_id', 'comment', 'rating', 'user_id',
    'review', 'ranking', 'comment_date'
];
$array;

/**
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
    //     $recipe = [
    //     'recipe_id' => $getInfos[0]['recipe_id'],
    //     'title' => $getInfos[0]['title'],
    //     'persons' => $getInfos[0]['persons'],
    //     'oven_time' => $getInfos[0]['oven_time'],
    //     'oven_time_length' => $getInfos[0]['oven_time_length'],
    //     'resting_time' => $getInfos[0]['resting_time'],
    //     'resting_time_length' => $getInfos[0]['oven_time_length'],
    //     'total_time' => $getInfos[0]['total_time'],
    //     'total_time_length' => $getInfos[0]['total_time_length'],
    //     'step_1' => $getInfos[0]['step_1'],
    //     'step_2' => $getInfos[0]['step_2'],
    //     'step_3' => $getInfos[0]['step_3'],
    //     'step_4' => $getInfos[0]['step_4'],
    //     'step_5' => $getInfos[0]['step_5'],
    //     'step_6' => $getInfos[0]['step_6'],
    //     'author' => $getInfos[0]['author'],
    //     'comments' => [],
    //     'rating' => $averageRating['rating']
    // ];
    // echo($getInfos[0]['recipe_id']);
    // print_r($getInfos[0]['recipe_id']);
    // print_r($averageRating['rating']);
    // echo "<pre>";
    // print_r($getInfos);
    // echo "</pre>";
    // print_r($filterKeys);
    // echo "</pre>";
    // foreach($filterKey as $keys) {
    //     foreach ($getInfos[0] as $key => $value) {
    //         if ($key === $keys) {
    //             $recipe[$key] = $value;
    //         }
    //     }
    // }
    // foreach($filterKeys as $keys) {
    foreach ($getInfos[0] as $key => $value) {
        if (!in_array($key, $filterKeys)) {
            $recipe[$key] = $value;
        }
        // if ($keys !== $key) {
        //     echo '<pre>';
        //     echo $keys . ' => ' . $key;
        //     echo '</pre>';
        //     // $recipe[$key] = $value;
        // }

    }
    // }
    $recipe['rating'] = $averageRating['rating'];
    // $recipe['rating'] ?? $recipe['rating'] = $averageRating['rating'];

    // $array = array_diff_key($filterKey, $getInfos[0]);

    // Append comments array into the recipe array
    foreach($getInfos as $comment => $data) {
        // print_r($getInfos[0]) . '<br>';
        // print_r($getInfos)  . '<br>';
        // print_r($getInfos) ;
        // print_r($comment) ;
        // print_r($data);
        // if ($comment === in_array($comment, $filterKey)) {
        //     echo 'hello';
        // }
        // $recipe = array_filter($filterKey, fn ($key, $value) => $value);
        // $recipe[$comment] ?? $recipe = array_filter($filterKey, fn ($key, $value) => $key == $comment || $value = $data);
        // $recipe = array_filter($filterKey, function ($key, $value) {
        //     $key == '4' || $value == '2';
        // });


        // if (empty($array)) return;
        // print_r($key) ;
        // $recipe[$comment] = $data;
        // $recipe['rating'] ?? $recipe['rating'] = $averageRating['rating'];
        // $recipe['comments'][0] ?? $recipe['comments'][0];

        // print_r($data['comment_id']) ;
        // if (!is_null(['comment_id'])) {
        // // // if ($comment === 'comment_id' && !is_null($data)) {
        //     // echo $comment;
        //     $recipe['comments'][0] ?? $recipe['comments'][] = [
        //         'comment_id' => ['comment_id'][0][$data],
        //         'comment' => ['comment'][0][$data],
        //         'user_id' => ['user_id'][0][$data],
        //         'created_at' => ['comment_date'][0][$data],
        //     ];
        // }
    }

    /**
     * Récupère toutes les itérations de commentaires données par $getInfos
     * Puis rajoute chaque itérations au tableau $recipe
     */
    foreach($getInfos as $comment) {
        // echo "<pre>";
        // print_r($comment);
        // echo "</pre>";
        if (!is_null($comment['comment_id'])) {
            $recipe['comments'][] = [
                'comment_id' => $comment['comment_id'],
                'comment' => $comment['comment'],
                'user_id' => $comment['user_id'],
                'created_at' => $comment['comment_date'],
            ];
            //echo $recipe['comments']['user_id'];
        }
        // $recipe['rating'] = $averageRating['rating'];
    }
    // echo "<pre>";
    // print_r($recipe);
    // echo "</pre>";
    // print_r($array);

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
        <img src="../img/img1.jpeg" alt="">
        <p><span><?php echo($recipe['title']) ?></span></p>
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
                    <p><?php echo($value) ?></p>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </article>
<!-- </div> -->
<!-- <body class="d-flex flex-column min-vh-100"> -->
    <aside class="read__aside">
        <h1><?php echo($recipe['title']) ?></h1>
        <div>
            <div class="read__preparations">
                <p>Temps de cuisson :</p><p> <?php echo($recipe['oven_time'] . ' ' . $recipe['oven_time_length']) ?></p>
                <p>Temps de repos :</p><p> <?php echo($recipe['resting_time'] . ' ' . $recipe['resting_time_length']) ?></p>
                <p>Temps total :</p><p> <?php echo($recipe['total_time'] . ' ' . $recipe['total_time_length']) ?></p>
                <p>Nombre de personne<?= $recipe['persons'] > 1 ? "s" : null ?> :</p><p> <?php echo($recipe['persons']); ?></p>
            </div>
            <div class="read__credits">
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