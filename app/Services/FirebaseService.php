<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Config;

class FirebaseService
{
    protected $auth;
    protected $storage;

    public function __construct()
    {
        $serviceAccount = Config::get('firebase.projects.app.credentials');

        if (!$serviceAccount) {
            throw new \Exception('Firebase credentials file is missing or not configured properly.');
        }

        $firebase = (new Factory)->withServiceAccount($serviceAccount);
        $this->auth = $firebase->createAuth();
        $this->storage = $firebase->createStorage();
    }

    public function createUser($email, $password)
    {
        return $this->auth->createUser([
            'email' => $email,
            'password' => $password,
        ]);
    }


    public function listAllFiles()
    {
        $bucketName = Config::get('firebase.projects.app.storage.default_bucket');
        if (!$bucketName) {
            throw new \Exception('Firebase Storage bucket is not configured.');
        }

        $bucket = $this->storage->getBucket($bucketName);
        $objects = $bucket->objects();

        $fileList = [];
        foreach ($objects as $object) {
            $fileList[] = $object->name();
        }

        return $fileList;
    }
    public function listAllBuckets()
    {
        $storageClient = $this->storage->getStorageClient();
        $buckets = $storageClient->buckets();

        $bucketList = [];
        foreach ($buckets as $bucket) {
            $bucketList[] = $bucket->name();
        }

        return $bucketList;
    }

    public function getFileUrl($filePath)
    {
        $bucketName = Config::get('firebase.projects.app.storage.default_bucket');
        if (!$bucketName) {
            throw new \Exception('Firebase Storage bucket is not configured.');
        }

        $bucket = $this->storage->getBucket($bucketName);
        $object = $bucket->object($filePath);

        if (!$object->exists()) {
            throw new \Exception("File '$filePath' does not exist.");
        }

        return $object->signedUrl(new \DateTime('tomorrow')); // URL valid for 24 hours
    }

    public function fileExists($filePath)
    {
        try {
            $bucket = $this->storage->getBucket();
            $object = $bucket->object($filePath);

            return $object->exists();
        } catch (\Exception $e) {

            return false;
        }
    }

    public function deleteFile($filePath)
    {
        $bucketName = Config::get('firebase.projects.app.storage.default_bucket');
        if (!$bucketName) {
            throw new \Exception('Firebase Storage bucket is not configured.');
        }

        $bucket = $this->storage->getBucket($bucketName);
        $object = $bucket->object($filePath);

        if (!$object->exists()) {
            throw new \Exception("File '$filePath' does not exist.");
        }

        $object->delete();

        return "File '$filePath' deleted successfully.";
    }
}
