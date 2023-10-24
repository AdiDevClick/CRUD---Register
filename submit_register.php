<?php //declare(strict_types=1)?>

<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once("includes/class-autoloader.inc.php");
//include_once('config/mysql.php');
//include_once("config/user.php");
//include_once("includes/variables.inc.php");

$data = $_SERVER['REQUEST_METHOD'] == 'POST';
$getData = $_POST;
$password = '';
$nom = '';
$email = '';
$age = '';
echo "test 1 <br>";
//try {

if (isset($_POST["username"])) {
    $nom = $_POST["username"];
} else {
    $nom = "un";
}
if (isset($_POST["password"])) {
    $password = $_POST["password"];
} else {
    $password = 'deux';
}
if (isset($_POST["email"])) {
    $email = $_POST["email"];
} else {
    $email = 'trois';
}
if (isset($_POST["pwdRepeat"])) {
    $pwdRepeat = $_POST["pwdRepeat"];
} else {
    $pwdRepeat = "quatre";
}
if (isset($_POST["age"])) {
    $age = $_POST["age"];
} else {
    $age = '10';
}
echo $nom . $age . $email . $password . $pwdRepeat;
if (($data && isset($_POST['submit']))) {
    echo "test 2 <br>";


    $getData = $_POST;


    require_once("includes/class-autoloader.inc.php");


    /* ;

    $checkInput = new CheckInput($getData);

    $nom = $checkInput->test_input($_POST['nom']);
    $email = $checkInput->test_input($_POST['email']);
    $password = $checkInput->test_input($_POST['password']);
    $pwdRepeat = $checkInput->test_input($_POST['pwdRepeat']);
    $age = $checkInput->test_input($_POST['age']);
 */



    //if ($checkInput->checkInputs()) {
    //try {

    $signup = new SignupView(
        $nom,
        $email,
        $password,
        $pwdRepeat,
        $age,
        $getData
    );
    $signup->setUsers();
}
//include_once('config/mysql.php');
//require_once("includes/variables.inc.php");
/* $db = new Mysql();
$sqlQuery = 'INSERT INTO users(full_name, email, password, age)
            VALUES (:full_name, :email, :password, :age)';
$insertUsers = $db->connect()->prepare($sqlQuery);
$insertUsers->execute([
    'full_name' => $nom,
    'email' => $email,
    'password' => $password,
    'age' => $age
]);  */

//$db = null;
//$signup = null;
//header('Location: index.php');
//die();

/* else {
       throw new Error('Les inputs ne sont pas sets...');
   }

} catch (Error $e) {
       die('Erreur : ' . $e->getMessage() . ' Quelque chose ne va pas dans la connexion DB...');
} */
/* try {
    if ($check = $checkInput->checkInputs())
    {
        $db = new PDO('mysql:host=localhost;dbname=my_recipes;charset=utf8', 'root', '');
    }
} catch (TypeError $e) {
    die ('Error ! : '. $e->getMessage() .'Something went wrong...');
} */
// On affiche chaque recette une à une


/* if (
    !isset($_POST['title'])
    || !isset($_POST['recipe'])
    )
{
    echo 'Il faut un titre et une recette pour soumettre le formulaire.';
    return;
} */

?>   

<!-- Register form for non registered visitor -->
<?php if (!isset($_SESSION['LOGGED_USER'])): ?>
    <form action="register.php" method="post">
    <!-- <?php //if (isset($e)):?>
        <div class="alert-error">
            <?php //echo $e;?>    
        </div> -->
        <?php endif ?>
        <label for="username" class="label">Votre prénom et nom :</label>
        <input name="username" class="input" type="text" id="username" placeholder="Votre nom et prénom..."/>

        <label for="email" class="label">Votre email :</label>
        <input name="email" class="input" type="email" id="email" placeholder="Votre email..."/>

        <label for="password" class="label">Votre mot de passe :</label>
        <input name="password" class="input" type="password" id="password" placeholder="*****" />

        <label for="pwdRepeat" class="label">Confirmez votre mot de passe :</label>
        <input name="pwdRepeat" class="input" type="password" id="pwdRepeat" placeholder="*****"/>

        <label for="age" class="label">Votre âge :</label>
        <input name="age" type="number" class="input" id="age" placeholder="Votre âge..."/>

        <button type="submit" name="submit" class="btn">S'enregistrer</button>
    </form>

<!-- End of the form for non registered visitor -->

<!--  submit form success message  -->
<?php //else:?>
    <div class="alert-success">
    <section class="container">
        <div class="form-flex">
            <h1>Votre profil à bien été enregistré !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Votre nom</b> : <?php //echo strip_tags($nom)?></p>
                    <p><b>Votre email</b> : <?php //echo strip_tags($email)?></p>
                    <p><b>Votre âge</b> : <?php //echo strip_tags($age)?></p>
                    <p><b>Votre password </b> : <?php //echo strip_tags($password)?></p>
                </div>
            </div>  
        </div>
    </section>
<?php //endif?> 