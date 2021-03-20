<?php

namespace App\Controllers;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use Config\Services;
use Exception;
use phpDocumentor\Reflection\PseudoTypes\True_;

class Item extends BaseController
{
	protected $itemModel;
	protected $userModel;
	protected $categoryModel;

	public function __construct()
	{
		$this->userModel = new UserModel();
		$this->itemModel = new ItemModel();
		helper(['form']);
		$this->validation = Services::validation();
		$this->categoryModel = new CategoryModel();
	}

	/**
	 * METHOD: GET
	*/
	public function index(int $item_id)
	{
		$item = $this->itemModel->find($item_id);
		$user = $this->userModel->find($item['poster_uid']);
		$msgURL = base_url(route_to('message')."?user_id=$user[user_id]&username=$user[username]");

		$data = [
			'item' => $item,
			'user' => $user,
			'msgURL' => $msgURL,
		];

		if($user['user_id'] == $item['poster_uid']){
			return view('pages/itemProfile', $data);
		}
		
		return view('pages/itemProfile', $data);
	}

	/**
	 * METHOD: GET/POST
	*/
	public function create() {
		$categories = $this->categoryModel->findAll();
		if ($this->request->getMethod() === 'get') return view('pages/auth/itemCreate', ['categories' => $categories]);
		if ($this->request->getMethod() === 'post') {
			$rules = $this->validation->getRuleGroup('addEditItem');
			if (!$this->validate($rules)) return view('pages/auth/itemCreate', ['validation' => $this->validator, 'categories' => $categories]);

			$_POST['poster_uid'] = (int)session()->get('user')['user_id'];
			$fileService = Services::file_service();
			$_POST['photo_url'] = $fileService->saveFile($this->request, 'item_photo');

			if ($this->itemModel->create($_POST, $_POST['category_ids']) === false)
				throw new Exception('Error while inserting to database');

			return redirect()->route('itemCreate')->with('msg', 'Item creation successful, you may see it in your profile');
		}
	}

	/**
	 * METHOD: GET/POST
	*/
	public function edit(int $item_id) {
		$item = $this->itemModel->get(['item_id' => [$item_id]])[0];
		$categories = $this->categoryModel->findAll();

		// If current logged user is not the poster, redirect to index page (Does not work yet..)
		$current_user = session()->get('user')['user_id'];
		if ($current_user !== $item['poster_uid'])
			return redirect()->route('item', [$item['item_id']])->with('msg', 'No permission to edit this item.');

		$data = [
			'item' => $item, 
			'categories' => $categories
		];

		if ($this->request->getMethod() === 'get') return view('pages/auth/itemProfileEdit', $data);
		if ($this->request->getMethod() === 'post') {
			$rules = $this->validation->getRuleGroup('addEditItem');
			if (!$this->validate($rules)) return view('pages/auth/itemProfileEdit', $data, ['validation' => $this->validator]);

			$_POST['poster_uid'] = (int)session()->get('user')['user_id'];
			$fileService = Services::file_service();
			$image_url = $fileService->saveFile($this->request, 'item_photo');
			$_POST['photo_url'] = empty($image_url) ? $item['photo_url'] : $image_url;
			
			if ($this->itemModel->update($item['item_id'], $_POST) === false)
				throw new Exception('Error while updating an item.');
			return redirect()->route('item', [$item['item_id']])->with('msg', 'Item update successful!');
		}
	}

	/**
	 * METHOD: GET
	*/
	public function delete(int $item_id)
	{
		$item = $this->itemModel->find($item_id);

		if ($item['poster_uid'] !== session()->get('user')['user_id']) {
			throw new Exception("You can't delete what's not yours :P");
		}

		$this->itemModel->delete(['item_id' => $item_id]);

		return redirect()
			   ->route('userProfile', [session()->get('user')['user_id']])
			   ->with('msg', "Item $item[item_name] has been deleted.");
	}
}
