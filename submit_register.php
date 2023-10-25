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

if (($data && isset($_POST['submit']))) {
    require_once("includes/class-autoloader.inc.php");
    $getDatas = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'pwdRepeat' => $_POST['pwdRepeat'],
        'age' => $_POST['age'],
    ];
    /* foreach ($getDatas as $key => $value) {
        echo $getDatas . $key . $value;
    } */
    //$getData = $_POST;

    /* $checkInput = new CheckInput($getData);

    $nom = $checkInput->test_input($_POST['username']);
    $email = $checkInput->test_input($_POST['email']);
    $password = $checkInput->test_input($_POST['password']);
    $pwdRepeat = $checkInput->test_input($_POST['pwdRepeat']);
    $age = $checkInput->test_input($_POST['age']);


    $sqlQuery = 'INSERT INTO users (full_name, email, password, age) VALUES (:full_name, :email, :password, :age);';
            $insertUsers = Mysql::connect()->prepare($sqlQuery);
            $options = [
                'cost' => 12
            ];

            $hashedPwd = password_hash($password, PASSWORD_DEFAULT, $options);

            $insertUsers->execute([
                'full_name' => $nom,
                'email' => $email,
                'password' => $hashedPwd,
                'age' => $age
            ]); */

    //if ($checkInput->checkInputs()) {
    //try {

    /*  $signup = new SignupController(
         $nom,
         $email,
         $password,
         $pwdRepeat,
         $age,
         $getData
     ); */
    $signup = new SignupView(
        $getDatas
    );
    $signup->setUsers();


    header('refresh:10, index.php?error=none');
    //header('refresh:5, '.Functions::getUrl().'?error=none');
}

if (isset($_COOKIE['REGISTERED_USER']) || isset($_SESSION['REGISTERED_USER'])) {
    $registeredUser = [
        'email' => $_COOKIE['REGISTERED_USER'] ?? $_SESSION['REGISTERED_USER'],
    ];
    echo 'cookie okay';
}
// On affiche chaque recette une à une

?>

<!-- Register form for non registered visitor -->
<?php if (!isset($_SESSION['LOGGED_USER']) && !isset($_SESSION['REGISTERED_USER'])): ?>
      <form action="register.php" method="post">
    <?php if (isset($e)):?>
        <div class="alert-error"> 
            <?php echo $e ?>    
        </div>
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
    <?php //endif ?> 

<!-- End of the form for non registered visitor -->

<!--  Display form success message  -->

<?php elseif (isset($_SESSION['REGISTERED_USER'])):?>
    <?php //require_once('signup_success.php')?>
    <?php $signup->displaySignupSuccess($getDatas) ?>
    <?php unset($_SESSION['REGISTERED_USER']) ?>
<?php endif ?>

<!-- 

    
<-- End of display form success message  -->