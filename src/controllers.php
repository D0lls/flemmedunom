<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})
->bind('homepage')
;

$app->get('/connexion', function () use ($app) {
    return $app['twig']->render('connexion.html.twig');
})
->bind('connexion')
;
$app->get('/newsmanager', function () use ($app) {
    $messageModel = new messageModels();
    $message = $messageModel->getMessages($app);
    return $app['twig']->render('newsmanager.html.twig', array('listemessage'=> $message));
})
->bind('newsmanager')
;
$app->post('/checklogin', function (Request $request) use ($app) {
    $messageModel = new messageModels();
    if($request->get('auth')=="ldap"){
        $isValide = $messageModel->checkIfExistLdap($app,$request->get('name'),$request->get('password'));
    }else{
        $isValide = $messageModel->checkIfExist($app,$request->get('name'),$request->get('password'));
    }
    if($isValide){
        if($request->get('auth')=="ldap"){
            $app['session']->set('user', $messageModel->getIdLdap($app,$request->get('name'),$request->get('password'),$messageModel->getRoleLdap($app,$request->get('name'),$request->get('password'))));
            $app['session']->set('role', $messageModel->getRoleLdap($app,$request->get('name'),$request->get('password')));
        }else{
            //$app['session']->set('user', $messageModel->getIdLdap($app,$request->get('name'),$request->get('password')));
            $app['session']->set('user', $messageModel->getId($app,$request->get('name'),$request->get('password')));
            $app['session']->set('role', $messageModel->getRole($app,$request->get('name'),$request->get('password')));
        }

        $subRequest = Request::create('/newsmanager');
        return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    }
    $subRequest = Request::create('/connexion');
    return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
        }
    );

$app->post('/ajoutmessage', function(Request $request) use ($app) {
    $message = $request->get('message');
    $messageModel = new messageModels();
    $message = $messageModel->insertMessage($app,$message,$app['session']->get("user"));
    return $app->json(array($message));
})
->bind('ajoutmessage')
;
$app->post('/supprimermessage', function(Request $request) use ($app) {
    $id = $request->get('id');
    $messageModel = new messageModels();
    $message = $messageModel->removeMessage($app,$id);
    return $app->json(array($message));
})
->bind('supprimermessage')
;
$app->post('/modifiermessage', function(Request $request) use ($app) {
    $id = $request->get('id');
    $contenu = $request->get('message');
    $messageModel = new messageModels();
    $messageModel->updateMessage($app,$id,$contenu);
    return $app->json(array(true));
})
->bind('modifiermessage')
;

$app->get('/news', function () use ($app) {
    $messageModel = new messageModels();
    $message = $messageModel->getMessages($app);
    return $app['twig']->render('news.html.twig', array('listemessage'=> $message));
})->bind('affichage')
;


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );
    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
