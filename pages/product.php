<?php
$selectProducts = mysqli_query($koneksi, "SELECT categories.category_name, products.* FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY id DESC");
$rowProducts = mysqli_fetch_all($selectProducts, MYSQLI_ASSOC);
if (isset($_GET['idDel'])) {
    $idDel = $_GET['idDel'];
    $foto = mysqli_query($koneksi, "SELECT product_photo FROM products WHERE id='$idDel'");
    $row = mysqli_fetch_assoc($foto);
    unlink("asset/img/" . $row['product_photo']);
    $delete = mysqli_query($koneksi, "DELETE FROM products WHERE id='$idDel'");
    if ($delete) {
        header("location:?page=product");
    }
}
?>
<div class="card table-responsive">
    <div class="card-header">
        <h1>Products</h1>
    </div>
    <div class="card-body">
        <a href="?page=tambah-edit-product" class="btn btn-primary my-2">Add</a>
        <table class="table table-bordered text-center">
            <tr>
                <th>No</th>
                <th>Category Name</th>
                <th>Product Name</th>
                <th>Photo</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php
            $no = 1;
            foreach ($rowProducts as $v) {
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $v['category_name'] ?></td>
                    <td><?php echo $v['product_name'] ?></td>
                    <td><img src="asset/img/<?php echo $v['product_photo']?>" class="rounded" alt="error" width="120"></td>
                    <td>Rp. <?php echo number_format($v['product_price'], 2, ',', '.')?></td>
                    <td><?php echo $v['qty']?></td>
                    <td>
                        <a href="?page=tambah-edit-product&id=<?php echo base64_encode($v['id']) ?>" class="btn btn-success btn-sm">Edit</a>
                        <form action="?page=product&idDel=<?php echo $v['id'] ?>" method="post" onclick="return confirm('Yakin ingin menghapus produk?')" class="d-inline">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>