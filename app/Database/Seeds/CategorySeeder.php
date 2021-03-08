<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CategoryModel;
use Exception;

class CategorySeeder extends Seeder
{
	public function run()
	{
		$categoryModel = new CategoryModel();

		$categories = ['hardware', 'fashion', 'unknown'];

		foreach ($categories as $category) {
			$data = [
				'category_name' => $category,
			];

			// check for validation error
			if ($categoryModel->create($data) === false) {
				throw new Exception('Error while inserting using CategoryModel|'.implode('|', $categoryModel->errors()));
			}
		}
	}
}