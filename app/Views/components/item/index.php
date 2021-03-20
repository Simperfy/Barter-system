<div class="wrapper">
    <div class="all-container">
        <div class="validation-box">
            <?php if (session()->getFlashdata('msg') !== null) : ?>
                <div>
                    <p style='color: green'><?= session()->getFlashdata('msg') ?></p>
                </div>
            <?php endif; ?>
            <?= isset($validation) ? $validation->listErrors('user_errors') : '' ?>
        </div>
        <div class="main-container">
            <!-- username of poster -->
            <div class="left-container itempic">
                <img src="<?= base_url($item['photo_url']) ?>" alt="" class="itempicture">
            </div>
            <div class="right-container">
                <?php if ((session()->get('user')['user_id'] ?? null) === $item['poster_uid']) : ?>
                    <div class="actions">
                        <a class="edit-btn" href="<?= base_url(route_to('itemEdit', $item['item_id'])) ?>">edit</a>
                        <p class="delete-btn" onclick="confirm('Delete the item?') ? window.location = '<?= base_url(route_to('itemDelete', $item['item_id'])) ?>' : null">delete</p>
                    </div>
                <?php endif; ?>

                <div class="container poster">
                    <img src="<?= base_url($user['photo_url']) ?>" alt="" class="dp posterpic">
                    <p class="postername"><?= $user['username'] ?></p>
                    <div class="stars">
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                        <span><?= $item['rating'] ?></span>
                    </div>
                </div>
                <div class="container itemname">
                    <p class="itemname"><?= $item['item_name'] ?></p>
                </div>
                <div class="container rate">
                    <p class="rating"></p>
                </div>
                <div class="container status">
                    <p class="availstatus"><?= $item['avail_status'] ?></p>
                </div>
                <div class="container desc">
                    <p class="details">Details</p>
                    <p class="description">
                        <?= $item['desc_content'] ?>
                    </p>
                </div>
                <div class="lister">
                    <p class="listed">Listed by <?= $user['username'] ?></p>
                    <img src="<?= base_url($user['photo_url']) ?>" alt="" class="dp listerpic">
                </div>
                <p class="gotoprofile"><a href="<?= base_url(route_to('userProfile', $user['user_id'])) ?>">Check user profile</a></p>
                <div class="offerbutton">
                    <?php if (session()->get('user') !== null) : ?>
                        <?php if (session()->get('user')['user_id'] !== $user['user_id']) : ?>
                            <button class="message" onclick="window.location='<?= $msgURL ?>'">Message</button>
                            <button class="offer" onclick="window.location='<?= route_to('placeOffer', $item['item_id']); ?>'">Offer</button>
                        <?php else : ?>
                            <button class="message disabled" title="You cannot message yourself">Message</button>
                            <button class="offer disabled" title="You cannot place offer to your own item">Offer</button>
                        <?php endif; ?>
                    <?php else : ?>
                        <button class="message" onclick="window.location='<?= $msgURL ?>'">Message</button>
                        <button class="offer" onclick="window.location='<?= route_to('placeOffer', $item['item_id']); ?>'">Offer</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ((session()->get('user')['user_id'] ?? null) === $item['poster_uid']) : ?>
        <div class="offerlist-container">
            <div class="list-container">
                <?php for ($x = 0; $x <= 10; $x++) : ?>
                    <div class="offer-container">
                        <div class="text-content">
                            <h3 class="subject-title">Subject</h3>
                            <h4 class="message-view">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel, laudantium quasi. Sed illo doloribus eum reiciendis ipsam ea, omnis alias? Dolores nesciunt consequuntur necessitatibus voluptas et quam fugiat cum veritatis?</h4>
                        </div>
                        <div class="accept">
                            <button class="accept_button" type="submit" form="placeOffer-form" value="submit">Accept Offer</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>