<?php

// Include the Google Cloud SDK library
require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Replace with your Firebase service account file path
$serviceAccountPath = 'sascode-aa3b7-firebase-adminsdk-8s9ya-698f45e07d.json';

// Replace with your Firebase Storage bucket name
$bucketName = 'sascode-aa3b7.appspot.com';

// Replace with the path to your image file
$imagePath = 'path/to/your/image.jpg';

// Create a Firebase Storage client
$storage = new StorageClient([
    'keyFilePath' => $serviceAccountPath
]);

// Get the bucket
$bucket = $storage->bucket($bucketName);

// Upload the image to Firebase Storage
$file = fopen($imagePath, 'r');
$object = $bucket->upload($file, [
    'name' => 'motif/image.jpg' // Specify the file name and path in your bucket
]);

// Get the public download URL
$imageUrl = $object->info()['mediaLink'];

echo "Image uploaded successfully! URL: " . $imageUrl;

?>
