<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("includes/class-autoloader.inc.php");
include_once("config/mysql.php");
//include_once("config/user.php");
//display_erreurLogin();
include_once('includes/variables.inc.php');
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$data = $_SERVER['REQUEST_METHOD'] == 'POST';
$submit = $_POST['submit'];
$password = $_POST['password'];
$username = $_POST['username'];
$errorUsername = "Nom d'utilisateur";
$errorPassword = 'Mot de passe';


//$errorMessage = "";
$value1 = $value2 = '';

if (isset($data)) {
    $value1 = test_input($username);
    $value2 = test_input($password);
    if (empty($value1) && empty($value2)) {
        echo"Vous n'avez pas saisit de Nom d'utilisateur ni de Mot de passe";
        //return $status = false;
    }
    if (!isset($value1) || empty($value1)) {
        echo"Vous n'avez pas saisit de Nom d'utilisateur" ;
        //return $status = false;
    }
    if (!isset($value2) || empty($value2)) {
        echo"Vous n'avez pas saisit de Mot de passe";
        //return $status = false;
    }
    if ($value1 === '' && $value2 === '') {
        foreach ($users as $user) {

            if ($user['email'] === $value1 &&
                $user['password'] === $value2) {
                ;
                $loggedUser = [
                    'email' => $user['email'],
                    'username' => $user['full_name'],
                ];


                /****
                 * Création d'un cookie qui expire dans 1 an
                 */
                setcookie(
                    'LOGGED_USER',
                    $user['full_name'],
                    [
                        'expires' => time() + 365 * 24 * 3600,
                        'secure' => true,
                        'httponly' => true,
                    ]
                );
                $_SESSION['LOGGED_USER'] = $user['full_name'];

            } else {
                $errorMessage = sprintf(
                    'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                    $value1,
                    $value2
                );
            }

        }
    }
}

if (isset($_COOKIE['LOGGED_USER']) || isset($_SESSION['LOGGED_USER'])) {
    $loggedUser = [
        'email' => $_COOKIE['LOGGED_USER'] ?? $_SESSION['LOGGED_USER'],
    ];
}

?>

<?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
    <form action="testinput.php" method="post">
        <!-- Si il y a erreur on affiche le message -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert-error">
                <?php echo $errorMessage; ?>    
            </div>
        <?php endif; ?>
            <label for="username">Votre identifiant :</label>
            <input type="text" id="username" name="username" placeholder="exemple@exemple.com"/>
    
            <label for="password"> Votre mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="****">
    
            <button type="submit" class="bTn" id="btn" value="submit" name="submit"> S'identifier</button>
    </form>
    <!-- 
        Si l'utilisateur est bien loggé, on affiche le message de succès
    -->
    <?php else: ?>
        <div class="alert-success">
            Bonjour <?php echo $loggedUser['email']; ?> et bienvenue sur le site !
        </div>
    <?php endif ?>