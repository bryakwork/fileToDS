<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

/*$app->get('/', App\Action\HomePageAction::class, 'home');*/

if ($container->has('home-service')) {
    $app->route('/', 'home-service', ['GET'], 'home-page');
}
if ($container->has('crud-service')) {
    $app->route('/crud', 'crud-service', ['GET'], 'crud-page');
}
if ($container->has('api-datastore')) {
    $app->route('/api/datastore[/{resourceName}[/{id}]]', 'api-datastore', ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], 'api-datastore');
}
if ($container->has('webhookActionRender')) {
    $app->route('/webhook[/{resourceName}]', 'webhookActionRender', ['GET', 'POST'], 'webhook');
}
if ($container->has("file2DS")) {
    $app->route('/file2ds[/{resourceName}]', "file2DS", ['POST', 'OPTIONS'], 'fileToDS');
}

