<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Interface\ModelInterface;

class UserModel extends Model implements ModelInterface
{

    /* Setup of model */

    protected $table            = 'user';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'username',
        'password',
        'first_name',
        'last_name',
        'address',
        'contact_details',
        'photo_url',
        'rating',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /* Validation rules for sign up */
    protected $validationRules  = 'signup';

    protected $beforeInsert     = ['passwordHash', 'checkPhotoUrl'];
    protected $beforeUpdate     = ['passwordHash', 'checkPhotoUrl'];

    protected function checkPhotoUrl(array $data){
        if(empty($data['data']['photo_url'])) {
            $data['data']['photo_url'] = 'images/default/profile.jpg';
        }

        return $data;
    }

    protected function passwordHash(array $data){
        if(isset($data['data']['password']))
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }


    /* Query calls */
    /* Functions to be used on controllers */


    /* Create Methods */


    /**
      * Create a new user.
      *
      * @param array $data Data of the user to be created.
      * @return integer|false `user_id` Of the inserted user, `false` on failure.
      *
      */
      public function create($data){
        return $this->insert($data);
    }


    /* Retrieve Methods */


    /**
     * Returns rows from the `users` table as an array, given
     * certain options, otherwise returns all.
     *
     * @param array $where Values that identify that user.
     *
     * **Must have:**
     * *one key-to-many values* `[count = 1]` OR
     * *many one key-to-one value* `[count = 1 OR N]`
     *
     * Example: `$where = ['username' => 'johndoe', ...]` OR
     *          `$where = ['first_name' => 'john', 'last_name' => 'doe', ...]`
     *
     * @param array $options Query options to be used.
     *
     * Example: `limit` | `offset`
     *          `$options = ['limit' => '1', 'offset' => '1', ...]`
     *
     * @return array `ResultArray` of users.
     *
     */
    public function get($where = [], $options = null){
        $limit = $options['limit'] ?? 0;
        $offset = $options['offset'] ?? 0;
        $sortBy = $options['sortBy'] ?? 'user_id';
        $sortOrder = $options['sortOrder'] ?? 'asc';
        $builder = $this->builder();

        foreach($where as $key=>$arrayValues) {
			$builder->whereIn($key, $arrayValues);
		}

        return $builder->orderBy($sortBy, $sortOrder)
                       ->get($limit, $offset)
                       ->getResultArray();
    }


    /* Update Methods */


    /**
     * Updates the details and credentials of the
     * currently logged user.
     *
     * @param array $data New data to be updated.
     * @param array $where Values that identify that user.
     *
     * **Must have:** `['user_id' => 'session_user']`.
     *
     * @return true|false `true` If successful update otherwise, `false`.
     *
     */
    // @Hezzz this is quite broken
    // public function update($data = null, $where = null) : bool{
    //     return $this->update($data, $where);
    // }

    /* Delete Methods */


    /**
     * Delete a user, usually the current logged in user.
     *
    * @param array $where Values that identify that user.
     *
    * **Must have:** `['user_id' => 'session_user']`.
     *
    */
    public function delete($where = null, bool $purge = false){
        return $this->where($where)
                    ->delete();
    }

}