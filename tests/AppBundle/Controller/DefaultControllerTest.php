<?php

/**
 * This file is part of the Symfony Micro Edition package.
 *
 * (c) NunoPress LLC <hello@nunopress.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * @return string
     */
    protected function getSecretKey() : string
    {
        return 'ThisIsASecretKey';
    }

    /**
     * @return void
     */
    protected function createFixtureCopy()
    {
        $fs = new Filesystem();
        $fs->copy(__DIR__ . '/../Fixtures/image.png', $this->getFixtureCopy());
    }

    /**
     * @return string
     */
    protected function getFixtureCopy() : string
    {
        return __DIR__ . '/../Fixtures/image_copy.png';
    }

    /**
     * @return void
     */
    protected function removeFixtureCopy()
    {
        $fs = new Filesystem();
        $fs->remove($this->getFixtureCopy());
    }

    /**
     * @return void
     */
    protected function createFixtureUploaded()
    {
        $this->createFixtureCopy();

        $fs = new Filesystem();
        $fs->copy($this->getFixtureCopy(), $this->getFixtureUploaded());
    }

    /**
     * @return string
     */
    protected function getFixtureUploaded() : string
    {
        return static::createClient()
            ->getContainer()
            ->getParameter('kernel.root_dir') . '/../uploads/' . $this->getFixtureUploadedName();
    }

    /**
     * @return string
     */
    protected function getFixtureUploadedName() : string
    {
        return 'new_image.png';
    }

    /**
     * @return void
     */
    protected function removeFixtureUploaded()
    {
        $fs = new Filesystem();
        $fs->remove($this->getFixtureUploaded());
    }

    /**
     * @return void
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertJson(json_encode([
            'status_code' => 404,
            'message' => 'Page not found'
        ]), $client->getResponse()->getContent());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
    }

    /**
     * @return void
     */
    public function testViewAction()
    {
        $client = static::createClient();

        $client->request('GET', '/view/file_not_exists.png');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());

        $this->createFixtureCopy();
        $this->createFixtureUploaded();
        $client->request('GET', '/view/' . $this->getFixtureUploadedName());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(Response::class, $client->getResponse());
        $this->removeFixtureCopy();
        $this->removeFixtureUploaded();
    }

    /**
     * @return void
     */
    public function testUploadAction()
    {
        $client = static::createClient();

        // Create a fixture copy for make testing
        $this->createFixtureCopy();

        $file = new UploadedFile(
            $this->getFixtureCopy(),
            'image.png',
            'image/png',
            123
        );

        $client->request('POST', '/upload', [
            'name'      => str_replace('.' . $file->getClientOriginalExtension(), '', $this->getFixtureUploadedName()),
            'secret'    => $this->getSecretKey()
        ], [
            'file'      => $file
        ]);

        $this->assertFileExists($this->getFixtureUploaded());

        // Remove the test file
        $this->removeFixtureUploaded();
    }

    /**
     * @return void
     */
    public function testDeleteAction()
    {
        $client = static::createClient();

        $client->request('GET', '/delete/file_not_found');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());

        $this->createFixtureUploaded();
        $client->request('GET', '/delete/' . $this->getFixtureUploadedName());
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->removeFixtureCopy();
        $this->removeFixtureUploaded();
    }
}