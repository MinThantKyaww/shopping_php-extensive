<?php include'header.php' ?>

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                        <h1>Welcome <?php echo $_SESSION['user_name']; ?></h1>
                        <a href="logout.php" >
                            <button style="border: 1px solid white" type="submit" value="submit" class="primary-btn">
                                Log Out
                            </button>
                        </a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area" style="padding-top : 1px !important;padding-bottom: 1px !important;">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <?php if(!empty($_SESSION['cart'])) :?>
                        <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            require'config/config.php';
                                $total=0;
                                foreach ($_SESSION['cart'] as $key => $qty) :
                                    // print_r($_SESSION['cart']);
                                    $id = str_replace('id','',$key);
                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total += $result['price']* $qty;

                                    ?>
                                    <tr>
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="admin/images/<?php echo $result['image'] ?>" alt="">
                                        </div>
                                        <div class="media-body">
                                            <p><?php echo $result['name'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo $result['price'] ?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="text" name="qty" id="sst" maxlength="12" 
                                        value="<?php echo $qty ?>" title="Quantity:"
                                            class="input-text qty">
                                    </div>
                                </td>
                                
                                <td>
                                    <h5><?php echo $result['price']*$qty ?></h5>
                                </td>
                                <td>
                                    <a style="line-height: 30px;border-radius: 10px;"
                                    class="primary-btn" href="cart_item_clear.php?id=<?php echo $result['id']?>">Clear</a>
                                </td>
                            </tr>
                                
                            <?php endforeach ?>
                            
                            
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo $total ?></h5>
                                </td>
                            </tr>
                        
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clearall.php">Clear</a>
                                        <a class="primary-btn" href="confirmation.php">Proceed to checkout</a>
                                        <a class="gray_btn" href="index.php">Continue Shopping</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                    <?php endif ?>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

<?php require 'footer.php'; ?>
