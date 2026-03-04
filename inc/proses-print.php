<?php
include '../config/koneksi.php';
if (isset($_POST['pdf'])) {
    require_once '../asset/vendor/autoload.php';
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y-m-d H:i:s");

    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $printPdf = mysqli_query($koneksi, "SELECT * FROM orders WHERE order_date BETWEEN '$start' AND '$end'");
    $rows = mysqli_fetch_all($printPdf, MYSQLI_ASSOC);

    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4',
        'orientation' => 'p'
    ]);
    $html =
        '<h2 style="text-align:center;">Laporan Penjualan</h2>
<hr>
<table border = "1" width="100%" cellpadding="8" cellspacing="0">
<tr>
<th>No</th>
<th>Order Code</th>
<th>Order Date</th>
<th>Order Pay</th>
<th>Order Amount</th>
</tr>';
    $no = 1;
    $total = 0;
    foreach ($rows as $v) {
        $html .= '
    <tr>
    <td>' . $no++ . '</td>
    <td>' . $v['order_code'] . '</td>
    <td>' . $v['order_date'] . '</td>
    <td>Rp. ' . number_format($v['order_pay'], 2, ',', '.') . '</td>
    <td>Rp. ' . number_format($v['order_amount'], 2, ',', '.') . '</td>
    </tr>';
        $total += $v['order_amount'];
    }
    $html .= '
<tr>
<th colspan="4">Total</th>
<td>Rp. ' . number_format($total, 2, ',', '.') . '</td>
</tr>
</table>';
    $mpdf->WriteHTML($html);
    $mpdf->Output('Laporan-penjualan-' . $date . '.pdf', 'I');
}
if (isset($_POST['excel'])) {
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y-m-d H:i:s");
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan-Penjualan-" . $date . ".xls");
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $printExcel = mysqli_query($koneksi, "SELECT * FROM orders WHERE order_date BETWEEN '$start' AND '$end'");
    $rowExcels = mysqli_fetch_all($printExcel, MYSQLI_ASSOC);
?>
    <h2>Laporan Penjualan</h2>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Order Code</th>
            <th>Order Date</th>
            <th>Order Pay</th>
            <th>Order Amount</th>
        </tr>
        <?php
        $no = 1;
        $total = 0;
        foreach ($rowExcels as $v) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $v['order_code'] ?></td>
                <td><?= $v['order_date'] ?></td>
                <td><?= number_format($v['order_pay'], 2, ',', '.') ?></td>
                <td><?= number_format($v['order_amount'], 2, ',', '.') ?></td>
            </tr>
            <?php $total += $v['order_amount'] ?>
        <?php
        }
        ?>
        <tr>
            <th colspan="4">Total</th>
            <td><?= number_format($total, 2, ',', '.') ?></td>
        </tr>
    </table>
<?php
}
