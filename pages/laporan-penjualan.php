<?php
$orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
$rows = mysqli_fetch_all($orders, MYSQLI_ASSOC);
$no = 1;
?>
<div class="card">
    <div class="card-header">
        <h1>Laporan Penjualan</h1>
    </div>
    <div class="card-body table-responsive">
        <form action="inc/proses-print.php" method="post">
            <div class="row my-2">
                <div class="col-5">
                    <label for="" class="form-label">Tanggal Awal</label>
                    <input type="datetime-local" class="form-control" name="start_date" required>
                </div>
                <div class="col-5">
                    <label for="" class="form-label">Tanggal Akhir</label>
                    <input type="datetime-local" class="form-control" name="end_date" required>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="submit" name="pdf" class="btn btn-danger">PDF</button>
                    <button type="submit" name="excel" class="btn btn-success ms-1">Excel</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Order Code</th>
                <th>Order Date</th>
                <th>Order Pay</th>
                <th>Order Amount</th>
            </tr>
            <?php
            foreach ($rows as $v) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $v['order_code'] ?></td>
                    <td><?= $v['order_date'] ?></td>
                    <td>Rp. <?= number_format($v['order_pay'], 2, ',', '.') ?></td>
                    <td>Rp. <?= number_format($v['order_amount'], 2, ',', '.') ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>