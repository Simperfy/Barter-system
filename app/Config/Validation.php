<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

use App\Validation\LoginRules;
use App\Validation\EditItemRules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
		LoginRules::class,
		EditItemRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'        => 'CodeIgniter\Validation\Views\list',
		'single' 	  => 'CodeIgniter\Validation\Views\single',
		'user_errors' => 'components/partials/errors/_user_errors'
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
	// All validation rules must be here
	// more on saving rules at https://codeigniter4.github.io/userguide/libraries/validation.html#how-to-save-your-rules
	// available rules at https://codeigniter4.github.io/userguide/libraries/validation.html#available-rules
	public $login = [
		'username' => 'required|min_length[3]|max_length[30]|alpha_numeric',
		'password' => 'required|min_length[8]|max_length[255]|is_correct_password[user,{username}]' // is_correct_password located in app/Validation/LoginRules.php
	];

	public $signup = [
		'first_name' 	  => 'required|min_length[3]|max_length[30]|alpha_space',
		'last_name'  	  => 'required|min_length[3]|max_length[30]|alpha_space',
		'address'	 	  => 'required|max_length[30]|alpha_numeric_punct',
		'contact_details' => 'required|min_length[3]|max_length[30]|numeric',
		'username'   	  => [
			'label' => 'username',
			'rules' => 'required|min_length[3]|max_length[30]|alpha_numeric|is_unique[user.username]',
			'errors' => [
				'is_unique' => 'The {field} {value} is already taken.'
			],
		],
		'password'   	  => 'required|min_length[8]|max_length[255]',
	];

	public $addEditItem = [
		'item_name' 	 => 'required|min_length[3]|max_length[250]',
		'poster_uid'	 => 'is_poster',
		'avail_status' 	 => 'required|alpha_numeric',
		'category_ids' 	 => 'required',
		'category_ids.*' => 'numeric|is_not_unique[category.category_id]',
		'desc_title' 	 => 'required|min_length[3]|max_length[30]',
		'desc_content' 	 => 'required',
	];

	public $editProfile = [
		'first_name' 	  => 'required|min_length[3]|max_length[30]|alpha_space',
		'last_name'  	  => 'required|min_length[3]|max_length[30]|alpha_space',
		'address'	 	  => 'required|max_length[30]|alpha_numeric_punct',
		'contact_details' => 'required|min_length[3]|max_length[30]|numeric',
		'username'   	  => [
			'label' => 'username',
			'rules' => 'required|min_length[3]|max_length[30]|alpha_numeric|is_unique[user.username,username,{username}]',
			'errors' => [
				'is_unique' => 'The {field} {value} is already taken.'
			],
		],
		'password'   	  => 'max_length[255]',
	];

	public $addOffer = [
		'offer_msg_title' => 'required|max_length[255]',
		'offer_msg_content' => 'required|max_length[1000]',
	];

	public $addEditReview = [
		'rating' => 'required|is_natural|less_than_equal_to[5]',
		'content' => 'required|max_length[255]',
	];
}
