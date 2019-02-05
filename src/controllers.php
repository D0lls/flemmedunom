<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})
->bind('homepage')
;
$app->get('/affichage', function () use ($app) {
    $messageModel = new messageModels();
    $message = $messageModel->getMessages($app);
    return $app['twig']->render('blog.html.twig', array('listemessage'=> $message));
})
->bind('affichage')
;
$app->post('/ajoutmessage', function(Request $request) use ($app) {
    $message = $request->get('message');
    $messageModel = new messageModels();
    $message = $messageModel->insertMessage($app,$message,'test');
    return $app['twig']->render('index.html.twig');
})
->bind('formulairemessage')
;
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
