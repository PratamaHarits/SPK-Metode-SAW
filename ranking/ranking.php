<?php
// koneksi
include '../tools/connection.php';

// header
include '../blade/header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="text-center">Decision Support System for The Program Indonesia Pintar Scholarship using Simple Additive Weighting method</h3>
        </div>
        <!-- nav -->
        <?php include '../blade/nav.php' ?>
        <!-- body -->
        <div class="card-body">

            <!-- array ranks untuk menampung hasil perangkingan -->
            <?php $ranks = array(); ?>

            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10 shadow py-3">
                    <!-- judul -->
                    <p class="text-center fw-bold">Hasil Akhir dan Perangkingan</p>
                    <hr>

                    <!-- button trigger cetak PDF -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-1">
                        <button type="button" class="btn btn-outline-primary" onclick="window.open('../cetak/cetakPDF.php', '_blank')">
                            Cetak PDF
                        </button>
                    </div>


                    <!-- tabel matrix -->
                    <div class="row">
                        <!-- <div class="col-1"></div> -->
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Matrix Keputusan</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-info">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($data);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                                    </tr>
                                    <tr class="table-info">

                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $data->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $data->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_nilai = $sql->fetch_assoc()) { ?>
                                                <td><?= $data_nilai['nilai_faktor'] ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-1"></div> -->
                    </div>


                    <!-- tabel normalisasi  -->
                    <div class="row mt-3">
                        <!-- <div class="col-1"></div> -->
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Normalisasi</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-info">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($data);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>

                                    </tr>
                                    <tr class="table-info">
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $data->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $data->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_nilai = $sql->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_nilai['kriteria_kode'];
                                                $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $sqli->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                                        ?>
                                                            <td><?= number_format($hasil = $nilai_Min['min'] / $data_nilai['nilai_faktor'], 2); ?></td>
                                                        <?php } ?>


                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                                        ?>
                                                            <td><?= number_format($hasil = $data_nilai['nilai_faktor'] / $nilai_Max['max'], 2); ?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-1"></div> -->
                    </div>


                    <!-- tabel pemfaktoran  -->
                    <div class="row mt-3">
                        <!-- <div class="col-1"></div> -->
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Hasil Preferensi</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-info">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($data);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                                        <th rowspan="2">Nilai Akhir</th>

                                    </tr>
                                    <tr class="table-info">
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $data->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $data->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php $hasilSum = 0; //variabel hasilSum untuk proses sum nanti
                                            ?>

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_nilai = $sql->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_nilai['kriteria_kode'];
                                                $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $sqli->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                                        ?>
                                                            <?php $hasil = $nilai_Min['min'] / $data_nilai['nilai_faktor']; ?>

                                                            <td><?= number_format($min_dikali_kriteria = $hasil * $kriteria['kriteria_bobot'], 2); ?></td>

                                                            <?php $hasilSum = $hasilSum + $min_dikali_kriteria; ?>

                                                        <?php } ?>


                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                                        ?>
                                                            <?php $hasil = $data_nilai['nilai_faktor'] / $nilai_Max['max']; ?>

                                                            <td><?= number_format($max_dikali_kriteria = $hasil * $kriteria['kriteria_bobot'], 2); ?></td>

                                                            <?php $hasilSum = $hasilSum + $max_dikali_kriteria; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                            <td><?= number_format($hasilSum, 2);  //hasil sum
                                                ?></td>

                                            <?php
                                            //masukan  nilai hasil-sum, nama-alternatif, kode-alternatif ke dalam variabel $ranks(baris 26)
                                            $rank['hasilSum'] = $hasilSum;
                                            $rank['alternatifNama'] = $alternatif['alternatif_nama'];
                                            $rank['alternatifKode'] = $alternatif['alternatif_kode'];
                                            array_push($ranks, $rank);
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-1"></div> -->
                    </div>


                    <!-- tabel ranking -->
                    <div class="row mt-3">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <!-- <p class="text-center fw-bold">Tabel Ranking</p> -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-warning">
                                        <th>Ranking</th>
                                        <th>Kode Alternatif</th>
                                        <th>Nama Alternatif</th>
                                        <th>Nilai SAW</th>
                                        <th>Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ranking = 1;
                                    rsort($ranks);
                                    foreach ($ranks as $r) {
                                    ?>
                                        <tr>
                                            <td><?= $ranking++; ?></td>
                                            <td><?= $r['alternatifKode']; ?></td>
                                            <td><?= $r['alternatifNama']; ?></td>
                                            <td><?= number_format($r['hasilSum'], 2); ?></td>
                                            <td><?= ($ranking <= 4) ? 'Direkomendasikan' : 'Tidak Direkomendasikan'; ?></td>
                                        </tr>
                                    <?php
                                        // //jika hanya menampilkan 3 nilai teratas
                                        // if ($ranking > 3) {
                                        //     break;
                                        // }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-1"></div>
                    </div>

                </div>
                <div class="col-1"></div>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
<?php include '../blade/footer.php' ?>