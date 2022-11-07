<?php
$authDb = new App\AuthDb();
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TOO_SHORT = 'Ce champ est trop court';
const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe doit faire au moins 6 caractères';
const ERROR_PASSWORD_MISSMATCH = 'Les mot de passe ne sont pas identiques';
const ERROR_EMAIL_INVALID = 'L\'email n\'est pas valide';

$errors = [
    'firstname' => '',
    'lastname' => '',
    'email' => '',
    'password' => '',
    'confirmpassword' => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        "firstname" => FILTER_SANITIZE_SPECIAL_CHARS,
        "lastname" => FILTER_SANITIZE_SPECIAL_CHARS,
        "email" => FILTER_SANITIZE_EMAIL
    ]);

    $firstname = $input['firstname'] ?? "";
    $lastname = $input['lastname'] ?? "";
    $email = $input['email'] ?? "";
    $password = $_POST['password'] ?? "";
    $confirmpassword = $_POST['confirmpassword'] ?? "";

    if(!$firstname) {
        $errors['firstname'] = ERROR_REQUIRED;
    } else if(mb_strlen($firstname) < 2) {
        $errors['firstname'] = ERROR_TOO_SHORT;
    }
    
    if(!$lastname) {
        $errors['lastname'] = ERROR_REQUIRED;
    } else if(mb_strlen($lastname) < 2) {
        $errors['lastname'] = ERROR_TOO_SHORT;
    }

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

    if(!$confirmpassword) {
        $errors['confirmpassword'] = ERROR_REQUIRED;
    } else if( $confirmpassword !== $password) {
        $errors['confirmpassword'] = ERROR_PASSWORD_MISSMATCH;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $authDb->register([ 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'password' => $password]);
        header('Location: /login');
    }
}

require '../views/register.view.php';