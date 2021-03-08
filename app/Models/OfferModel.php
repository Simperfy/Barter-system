<?php

namespace App\Models;

use Config\Database;
use App\Models\Interface\ModelInterface;

class OfferModel implements ModelInterface
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->builder = $this->db->table('offers');

    }

    
    /* Query calls */
    /* Functions to be used on controllers */


    /* Create Methods */


    /**
     * Create a new offer to an item.
     * 
     * @param array $data Data of the offer.
     * 
     * **Must have:**
     * `[
     *      'item_id' => 'an_item_id', 
     *      'poster_uid' => 'that_user_id', 
     *      'customer_uid' => 'session_user',
     * ]`
     * 
     * @return mixed|false `false` On failure.
     * 
     */
    public function create($data){
        return $this->builder->insert($data);
    }


    /* Retrieve Methods */


    /**
     * Gets the offers for the current item, 
     * given its `item_id` and options.
     * 
     * @param mixed $item_id `item_id` Of the currently viewed item
     * @param array $options Query options to be used.
     * 
     * Example: `limit` | `offset` | `sortBy` | `sortOrder`
     *          `$options = ['limit' => '1', 'offset' => '1', ...]`
     * 
     * Recommended values for `sortBy`: `'customer_uid'` | `'created_at'` 
     * 
     * @return array `ResultArray` of offers.
     * 
     */
    public function get($item_id, $options){
        $limit = $options['limit'] ?? 0;
        $offset = $options['offset'] ?? 0;
        $sortBy = $options['sortBy'] ?? 'created_at';
        $sortOrder = $options['sortOrder'] ?? 'desc';

        return $this->builder->where('item_id', $item_id)
                        ->orderBy($sortBy, $sortOrder)
                        ->get($limit, $offset)
                        ->getResultArray();
    }


    /* Update Methods */


    /**
     * Updates the current user's offer to a selected item.
     * 
     * @param array $data Data to be updated.
     * @param array $where Values that identify that offer,
     * values to be used in the `where()` query.
     * 
     * **Must have:**
     * `[
     *      'item_id' => 'an_item_id', 
     *      'poster_uid' => 'that_user_id', 
     *      'customer_uid' => 'session_user',
     * ]`
     * 
     * @return true|false `true` If successful update otherwise, `false`.
     * 
     */
    public function update($data, $where){
        return $this->builder
                    ->where($where)
                    ->set($data)
                    ->update();
    }


    /* Delete Methods */


    /**
     * Delete an offer.
     * 
     * @param array $where Values that identify that offer,
     * values to be used in the `where()` query.
     * 
     * **Must have:**
     * `[
     *      'item_id' => 'an_item_id', 
     *      'poster_uid' => 'that_user_id', 
     *      'customer_uid' => 'session_user',
     * ]`
     * 
     * @param bool $purge ignore param, must be set to `false`.
     * 
     */
    public function delete($where, $purge = false){
        return $this->builder->where($where)
                             ->delete();
    }

}