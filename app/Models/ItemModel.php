<?php

namespace App\Models;

use CodeIgniter\Model;
// use App\Models\Interface\ModelInterface;

class ItemModel extends Model
{
    protected $table            = 'item';
    protected $primaryKey       = 'item_id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'poster_uid',
        'item_name',
        'photo_url',
        'avail_status',
        'desc_title',
        'desc_content',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $beforeInsert     = ['checkPhotoUrl'];

    protected function checkPhotoUrl(array $data){
        if(empty($data['data']['photo_url'])) {
            $data['data']['photo_url'] = 'images/default/product.jpg';
        }
        return $data;
        
    }

    // protected $validationRules    = [];

    /* Create Methods */

     /**
     * Create a new item with its categories.
     *
     * @param array $data Data of the item to be inserted.
     * @param array $category_ids `id`s Of categories associated with the item.
     * @return integer|false `item_id` Of the inserted item, `false` on failure.
     *
     * Example:
     * $data = [
	 *	'poster_uid'   => 1,
	 *	'item_name'    => "item$i",
	 *	'photo_url'    => 'https://via.placeholder.com/300',
	 *	'avail_status' => 'available',
	 *	'desc_title'   => $faker->word,
	 *	'desc_content' => $faker->text,
	 *	];
     * $itemModel->create($data, [1, 2])
     *
     */
    public function create($data, $category_ids){
        if (empty($category_ids)) return false;

        $item_id = $this->insert($data);

        $this->addToItemListing($item_id, $category_ids);

        return $item_id;
    }


    /* Retrieve Methods */


    /**
     * Returns an array of item with its associated categories,
     * given certain options, otherwise returns all.
     * Binds each item row with its corresponding categories.
     *
     * @param array $where Values that identify that item.
     * @param array $options Query options to be used.
     *
     * Example:
	 * $where = ['item_name' => ['item0'] ];
	 * $options = ['limit' => 1, 'offset' => 1, 'sortBy' => 'item_name', 'sortOrder' => 'desc'];
	 * $itemModel->get($where, $options);
     *
     * @return array items with categories.
     *
     */
    public function get($where = [], $options = null){
        $limit = $options['limit'] ?? 0;
        $offset = $options['offset'] ?? 0;
        $sortBy = $options['sortBy'] ?? 'created_at';
        $sortOrder = $options['sortOrder'] ?? 'desc';
        $builder = $this->builder();

        foreach($where as $key=>$arrayValues) {
			$builder->whereIn($key, $arrayValues);
		}

        $itemList = $builder->orderBy($sortBy, $sortOrder)
                            ->get($limit, $offset)
                            ->getResultArray();

        return $this->getItemWithCategories($itemList);
    }

    /**
     * Updates the details of the currently selected item.
     * Deletes associated old categories.
     *
     * @param mixed $item_id `item_id` Of the item to be updated.
     * @param array $data Updated details of the item.
     * @return bool `true` If successful update otherwise, `false`.
     * Example:
     * $data = [
     *     'item_name' => 'new item',
     *     'category_id' => 2,
     *     'new_category_id' => 3,
     * ];
     * $itemModel->update(1, $data);
     */
    public function update($item_id = null, $data = null) : bool{
        // Delete binded categories first in item_listing.
        $this->deleteItemListing($item_id);
        if (isset($data['category_ids'])) {
            $builder = $this->builder('item_listing');
            $batchData = [];
            foreach($data['category_ids'] as $category_id){
                array_push($batchData, ['item_id' => $item_id, 'category_id' => $category_id]);
            }
            $builder->insertBatch($batchData);
        }
        return parent::update($item_id, $data);
    }

    /**
     * Delete an item.
     *
     * @param array $where Values that identify that item.
     * Delete also cascades to the `item_listing` table.
     *
     * **Must have:** `['item_id' => 'item_id', 'poster_uid' => 'session_user']`.
     *
     */
    public function delete($where = [], bool $purge = false){
        $items = $this->builder()
                      ->where($where)
                      ->get()
                      ->getResultArray();

        foreach($items as $item) {
            $this->deleteItemListing($item['item_id']);
        }

        $this->builder()->where($where)->delete();
    }


    /* Helper Methods */

    /**
     * Adds a set of category to the identified item as a batch.
     *
     * @param mixed $item_id `item_id` Of the item to be categorized.
     * @param array $category_ids list Of all categories.
     *
     */
    protected function addToItemListing($item_id, $category_ids){
        $data = [];

        foreach($category_ids as $category_id){
            array_push(
                $data,
                [
                    'item_id'     => $item_id,
                    'category_id' => $category_id,
                ],
            );
        }

        $builder = $this->builder('item_listing');

        return $builder->ignore()->insertBatch($data);
    }


    /**
     * Returns an array of item with its corresponding categories,
     * given a list of items to be binded.
     *
     * @param array $itemList Rows of items from a query.
     * @return array items with categories.
     *
     */
    protected function getItemWithCategories($itemList) {
        return array_map(function ($item) {
            $builder = $this->builder('item_listing');
            $itemListings = $builder->where('item_id', $item['item_id'])->get()->getResultArray();

            $item['categories'] = array_map(function ($itemListing) {
                $categoryId = $itemListing['category_id'];

                $category = $this->builder('category')->where('category_id', $categoryId)->get()->getResultArray()[0];

                return $category;
            }, $itemListings);

            return $item;
        }, $itemList);
    }


    /**
     * Deletes corresponding categories from an item.
     * Usually done when updating an item. Deletes them at the
     * `item_listing` table.
     *
     * @param mixed $item_id `item_id` of the item selected.
     *
     */
    protected function deleteItemListing($item_id) {
        $builder = $this->builder('item_listing');

        return $builder->where('item_id', $item_id)
                       ->delete();
    }

}