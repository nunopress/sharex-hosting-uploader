<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

# Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

# New application
$app = new Application([
    'upload_dir' => __DIR__ . '/../uploads',
    'upload_secret' => 'NUNOPRESS-PC'
]);

/**
 * Index
 */
$app->get('/', function (Application $app) {
    return $app->json([
        'status_code' => 404,
        'message' => 'Page not found'
    ], 404);
});

/**
 * Upload File
 */
$app->post('/upload', function (Request $request, Application $app) {
    if ($app['upload_secret'] != $request->get('secret')) {
        return $app->json([
            'status_code' => 400,
            'message' => 'Cannot upload file here'
        ], 400);
    }

    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
    $file = $request->files->get('file');

    if (null === $file) {
        return $app->json([
            'status_code' => 400,
            'message' => 'Error on the uploading'
        ], 400);
    }

    $name = $request->get('name') . '.' . $file->getClientOriginalExtension();

    $file->move($app['upload_dir'], $name);

    return $app->json([
        'status_code' => 201,
        'message' => 'File uploaded successfully',
        'data' => [
            'url' => $name
        ]
    ], 201);
});

/**
 * View File
 */
$app->get('/view/{name}', function (Application $app, $name) {
    try {
        $file = new File($app['upload_dir'] . '/' . $name);
    } catch(FileNotFoundException $e) {
        return $app->json([
            'status_code' => 404,
            'message' => 'File not found'
        ], 404);
    }

    return new BinaryFileResponse($file, 200);
});

/**
 * Thumb File
 *
 * todo: not implemented yet
 */
$app->get('/thumb/{name}', function (Application $app, $name) {
    try {
        $file = new File($app['upload_dir'] . '/' . $name);
    } catch(FileNotFoundException $e) {
        return $app->json([
            'status_code' => 404,
            'message' => 'File not found'
        ], 404);
    }

    return new BinaryFileResponse($file, 200);
});

/**
 * Delete file
 */
$app->get('/delete/{name}', function (Application $app, $name) {
    try {
        $file = new File($app['upload_dir'] . '/' . $name);
    } catch(FileNotFoundException $e) {
        return $app->json([
            'status_code' => 404,
            'message' => 'File not found'
        ], 404);
    }

    unlink($file->getRealPath());

    return $app->json([
        'status_code' => 200,
        'message' => 'File deleted successfully'
    ], 200);
});

return $app;