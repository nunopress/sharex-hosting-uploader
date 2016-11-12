<?php

/**
 * This file is part of the Symfony Micro Edition package.
 *
 * (c) NunoPress LLC <hello@nunopress.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->json([
            'status_code' => 404,
            'message' => 'Page not found'
        ], 404);
    }

    /**
     * @Route("/view/{filename}", name="view")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return Response
     */
    public function viewAction(string $filename) : Response
    {
        try {
            $file = new File($this->getParameter('app.upload_dir') . '/' . $filename);
        } catch (FileNotFoundException $e) {
            return $this->json([
                'status_code' => 404,
                'message' => 'File not found'
            ], 404);
        }

        return $this->file($file, $filename, 'inline');
    }

    /**
     * @Route("/thumb/{filename}", name="thumb")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return Response
     */
    public function thumbAction(string $filename) : Response
    {
        return $this->viewAction($filename);
    }

    /**
     * @Route("/delete/{filename}", name="delete")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return Response
     */
    public function removeAction(string $filename) : Response
    {
        try {
            $file = new File($this->getParameter('app.upload_dir') . '/' . $filename);
        } catch (FileNotFoundException $e) {
            return $this->json([
                'status_code' => 404,
                'message' => 'File not found'
            ], 404);
        }

        (new Filesystem())->remove($file->getRealPath());

        return $this->json([
            'status_code' => 204,
            'message' => 'File deleted'
        ], 204);
    }

    /**
     * @Route("/upload", name="upload")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAction(Request $request) : Response
    {
        if ($this->getParameter('app.secret') != $request->get('secret')) {
            return $this->json([
                'status_code' => 400,
                'message' => 'Security firewall block this request'
            ], 400);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (null === $file) {
            return $this->json([
                'status_code' => 400,
                'message' => 'File not valid'
            ], 400);
        }

        $filename = sprintf('%s.%s', $request->get('name'), $file->getClientOriginalExtension());

        try {
            $file->move($this->getParameter('app.upload_dir'), $filename);
        } catch (FileException $e) {
            return $this->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }

        return $this->json([
            'status_code' => 201,
            'message' => 'File uploaded',
            'filename' => $filename
        ], 201);
    }
}