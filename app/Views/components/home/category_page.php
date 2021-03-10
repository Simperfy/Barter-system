<div class="wrapper">
    <div class="feature">
        <img src="<?= base_url('assets/home/feature-sample.jpg') ?>"  alt="feature" class="feature-img">
    </div>

    <div class="explore">
        <div class="explore-header">
            <p>Explore Items</p>
        </div>
        <div class="category-container">
        <?php for($i = 0; $i<count($categories); $i++): ?>
                <?= view_cell('\App\Libraries\Category::getCategory', ['category' => $categories[$i]]) ?>
            <?php endfor; ?>
        </div>
    </div>

    <div class="fresh-finds">
        <div class="fresh-finds-header">
            <p>Category: <?= $category['category_name'] ?></p>
        </div>
        <div class="product-list">
            <?php for($i = 0; $i<count($category['items']); $i++): ?>
                <?php 
                    $poster_id = $category['items'][$i]['poster_uid'];
                    $poster_info = $class->getPosterInfo($poster_id);
                    echo view_cell('\App\Libraries\Product::getItem', ['item' => $category['items'][$i], 'poster'=>$poster_info[0]]);
                ?>
               
                
            <?php endfor; ?>
        </div>
        <div class="bottom-end">
            <button>View more</button>
        </div>
    </div>
</div>
