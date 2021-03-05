<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Interface\ModelInterface;

class ItemModel extends Model implements ModelInterface
{
    protected $table      = 'item';
    protected $primaryKey = 'item_id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'poster_uid', 
        'item_name',
        'photo_url',
        'avail_status',
        'desc_title',
        'desc_content',
        'rating',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // protected $validationRules    = [];

    /* Query calls */
    /* Functions to be used on controllers */

    
    /* Create Methods */


     /**
     * Creates new item into the database.
     * 
     *  @param array $data data of the item to be inserted.
     *  @return bool `true` if successfully inserted, otherwise returns `false`.
     * 
     */
    public function create($data){
        return $this->insert($data);
    }


    /* Retrieve Methods */

    /**
     * Returns rows from the `items` table as an array, given
     * certain search conditions and limit, otherwise returns all.
     * 
     * @param array $search_values values needed to query
     * @param int $limit the number of rows to find
     * @param int $offset the number of rows to skip during the search
     * 
     * Example: `$search_values = ['item_id' => [001, 002, 003,...]]`
     *          OR `$search_values = ['poster_uid' => '000', ...]`
     * 
     */
    public function get($search_values = null, $limit = 0, $offset = 0){
        if(count($search_values) == 1){
            $col = array_key_first($search_values);
            $value = array_values($search_values);
            
            return $this->whereIn($col, $value)
                        ->findAll($limit, $offset);
        }
        elseif(count($search_values) > 1){
            return $this->where($search_values)
                        ->findAll($limit, $offset);
        }
        else{
            return $this->findAll($limit, $offset);
        }
    }


    /* Update Methods */


    /**
     * Updates the details of the current selected item.
     * 
     * @param mixed $id `item_id` of the item to be updated.
     * @param array $data updated details of the item.
     * @return bool `true` if successful update, otherwise `false`.
     *
     */
    public function update($id = null, $data = null) : bool{
        return parent::update($id, $data);
    }

}