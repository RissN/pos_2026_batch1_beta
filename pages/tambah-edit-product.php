<?php
$selectCategories = mysqli_query($koneksi, "SELECT * FROM categories");
$rowCategories = mysqli_fetch_all($selectCategories, MYSQLI_ASSOC);

if (isset($_POST['add'])) {
    $category_id = $_POST['category_id'];
    if (empty($category_id)) {
        echo "<script>alert('Silahkan pilih kategori terlebih dahulu!'); window.history.back();</script>";
        exit;
    }
    $product_name = $_POST['product_name'];
    $product_photo = $_FILES['product_photo'];
    $product_price = $_POST['product_price'];
    $qty = $_POST['qty'];
    $product_description = $_POST['product_description'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    if ($product_photo['error'] == 0) {
        $fileName = uniqid() . "_" . basename($product_photo['name']);
        $filePath = "asset/img/" . $fileName;
        move_uploaded_file($product_photo['tmp_name'], $filePath);

        $insertProduct = mysqli_query($koneksi, "INSERT INTO products (category_id, product_name, product_price, qty, product_description, is_active, product_photo) 
        VALUES ('$category_id', '$product_name', '$product_price', '$qty', '$product_description', '$is_active', '$fileName')");
        if ($insertProduct) {
            header("location:?page=product");
        }
    }
}
if (isset($_GET['id'])) {
    $idEdt = base64_decode($_GET['id']);
    $selectProduct = mysqli_query($koneksi, "SELECT * FROM products WHERE id='$idEdt'");
    $rowProduct = mysqli_fetch_assoc($selectProduct);
    if (isset($_POST['edit'])) {
        $category_id = $_POST['category_id'];
        $product_name = $_POST['product_name'];
        $product_photo = $_FILES['product_photo'];
        $product_price = $_POST['product_price'];
        $qty = $_POST['qty'];
        $product_description = $_POST['product_description'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $uploadFoto = "";
        if ($product_photo['error'] == 0) {
            $fileName = uniqid() . "_" . basename($product_photo['name']);
            $filePath = "asset/img/" . $fileName;
            if (move_uploaded_file($product_photo['tmp_name'], $filePath)) {
                if (file_exists("asset/img/" . $rowProduct['product_photo'])) {
                    unlink("asset/img/" . $rowProduct['product_photo']);
                }
                $uploadFoto = "product_photo='$fileName',";
            } else {
                echo "Gagal mengupload foto!";
            }
        }
        $update = mysqli_query($koneksi, "UPDATE products SET category_id='$category_id',product_name='$product_name', $uploadFoto product_price='$product_price',qty='$qty',product_description='$product_description',is_active='$is_active' WHERE id='$idEdt'");
        if ($update) {
            header("location:?page=product");
            exit;
        }
    }
}
?>
<div class="card">
    <div class="card-header">
        <h1>Products</h1>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="" class="form-label">Category Name</label>
            <select name="category_id" class="form-select" id="" required>
                <option value="">--Choose Category--</option>
                <?php
                foreach ($rowCategories as $v) {
                    if ($v['category_name'] !== 'All Menus') {
                ?>
                        <option value="<?php echo $v['id']; ?>" <?php echo isset($_GET['id']) && $v['id'] == $rowProduct['category_id'] ? 'selected' : ''; ?>><?php echo $v['category_name']; ?></option>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </select>
            <label for="" class="form-label">Product Name</label>
            <input type="text" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['product_name'] : '' ?>" name="product_name">
            <label for="" class="form-label">Photo</label>
            <div class="my-2">
                <?php
                if (isset($_GET['id'])) {
                ?>
                    <img src="asset/img/<?php echo $rowProduct['product_photo'] ?>" class="rounded" width="120" alt="">
                <?php
                }
                ?>
            </div>
            <input type="file" class="form-control" name="product_photo">
            <label for="" class="form-label">Price</label>
            <input type="number" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['product_price'] : '' ?>" name="product_price">
            <label for="" class="form-label">Stock</label>
            <input type="number" class="form-control" value="<?php echo isset($_GET['id']) ? $rowProduct['qty'] : '' ?>" name="qty">
            <label for="" class="form-label">Product Description</label>
            <textarea name="product_description" class="form-control" cols="30" rows="5"></textarea>
            <label for="" class="form-label">Status Product</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" <?php echo (isset($_GET['id']) && $rowProduct['is_active'] == 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="switchCheckChecked">Default switch checkbox input</label>
            </div>
            <button type="submit" class="btn btn-primary my-2" name="<?php echo isset($_GET['id']) ? 'edit' : 'add' ?>"><?php echo isset($_GET['id']) ? 'Edit' : 'Add' ?></button>
        </form>
    </div>
</div>