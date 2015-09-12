<?php namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller {

	public function upload(Request $request) {
		$image = $request->file('image');

		do {
			$uniqueFilename = uniqid() . ".{$image->getClientOriginalExtension()}";
		} while ( file_exists("uploads/{$uniqueFilename}") );

		$image->move('uploads', $uniqueFilename);

		$image = Image::create([
			'id' => $uniqueFilename,
		]);

		return $image;
	}

}