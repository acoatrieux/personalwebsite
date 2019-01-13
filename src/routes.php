<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.html.twig', $args);
});
$app->get('/presentation', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'presentation.html.twig', $args);
});
$app->get('/contact', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'contact.html.twig', $args);
})->setName('contact.form');

$app->post('/contact', function (Request $request, Response $response, array $args) {
    $getParam = $request->getParams();

    $rawInputs = [
        'email' => FILTER_VALIDATE_EMAIL,
        'phone' => FILTER_SANITIZE_ENCODED,
        'firstname' => FILTER_SANITIZE_STRING,
        'lastname' => FILTER_SANITIZE_STRING,
        'message' => FILTER_SANITIZE_STRING,
        'g-recaptcha-response' => FILTER_UNSAFE_RAW
    ];
    
    $inputs = filter_input_array(INPUT_POST, $rawInputs);
    foreach ($inputs as $key => $value) {
        if (empty($value)) {
            $this->get('flash')->addMessage($key, "{$key} invalide");
        }
    }
    if (!$inputs['g-recaptcha-response']) {
        $this->get('flash')->addMessage('captcha', "Please check the the captcha form");
    }
    $secretKey = "6LfNEn4UAAAAAFLAMjAm46hSZxtvom5QqkAOePry";
    $ip = $_SERVER['REMOTE_ADDR'];
    $reCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $inputs['g-recaptcha-response'] . "&remoteip=" . $ip);
    $responseKeys = json_decode($reCaptcha, true);
    if ((int)$responseKeys['success'] !== 1) {
        $this->get('flash')->addMessage($key, "Votre mail à bien été envoyé");
    } else {
        $this->get('flash')->addMessage($key, "Merci de ne pas spammer.");
    }

    // Test send mail

// Create the Transport
    $transport = (new Swift_SmtpTransport('smtp1.2isr.local', 25));
//        ->setUsername('your username')
//        ->setPassword('your password');

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

// Create a message
    $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom([$inputs['email'] => 'Mailer'])
        ->setTo(['anton49110@gmail.com' => 'contact'])
        ->setBody('<h1 style="color:#003a77;align-items: center">ISIMed</h1><h2 style="color:#94c115">avez reçu un message provenant du site ISIMED,</h2> de la part de :'.$inputs['lastname']." ".$inputs['firstname']." ;".
            $inputs['message'],'text/html');

// Send the message
    $result = $mailer->send($message);
    return $response->withRedirect($this->router->pathFor('contact.form'));
});

