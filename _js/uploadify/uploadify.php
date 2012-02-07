<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */


$includes = array();
array_push( $includes, dirname(__FILE__) . '/../../core/webservice_client.php' );  
foreach ($includes as $file)
    if( @file_exists( $file ) ) 
        require_once( $file );

if (!empty($_FILES)) {
	$tempFile = $_FILES['images']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['images']['name'];

    $property_id = $_POST['property_id'];
    $testmime = @getimagesize($_FILES['images']['tmp_name']);    
			
    // if (is_array($testmime))
    // {
    //     switch ($testmime['mime'])
    //     {
    //     case 'image/pjpeg': $orig = @imagecreatefromjpeg($_FILES['images']['tmp_name']); break;
    //     case 'image/jpeg':$orig = @imagecreatefromjpeg($_FILES['images']['tmp_name']);  break;
    //     case 'image/jpg': $orig = @imagecreatefromjpeg($_FILES['images']['tmp_name']); break;
    //     case 'image/gif': $orig = @imagecreatefromgif($_FILES['images']['tmp_name']);  break;
    //     case 'image/png': $orig = @imagecreatefrompng($_FILES['images']['tmp_name']); break;
    //     }
    // }

    echo "\n";
    placester_property_image_add(
        $property_id, 
        $_FILES['images']['name'],
        $testmime,
        $_FILES['images']['tmp_name'],
        $_POST['api_key']);
  
        // Delete the temp file
        unlink($tempFile);

        // This would move the temp file to the defined target path
		// move_uploaded_file($tempFile,$targetFile);

        $property_id = $_POST['property_id'];
        $api_key = $_POST['api_key'];

        echo $property_id;
}
?>

