<?php
$authDb = new App\AuthDb();
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe doit faire au moins 6 caractères';
const ERROR_PASSWORD_MISSMATCH = 'Les mot de passe n\'est pas valide';
const ERROR_EMAIL_INVALID = 'L\'email n\'est pas valide';
const ERROR_EMAIL_UNKNOW = 'L\'email n\'est pas enregistré';

$errors = [
    'email' => '',
    'password' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [ "email" => FILTER_SANITIZE_EMAIL] );

    $email = $input['email'] ?? "";
    $password = $_POST['password'] ?? "";


    if(!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL_INVALID;
    }
    
    if(!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } else if(mb_strlen($password) < 6) {
        $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $user = $authDb->getUserFromEmail($email);
        if(!$user) {
            $errors['email'] = ERROR_EMAIL_UNKNOW;
        } else {
            if( !password_verify($password, $user['password']) ) {
                $errors['password'] = ERROR_PASSWORD_MISSMATCH;
            } else {
                $authDb->login($user['id']);
                header("Location: /");
            }    
        }
    }
}

require '../views/login.view.php';
