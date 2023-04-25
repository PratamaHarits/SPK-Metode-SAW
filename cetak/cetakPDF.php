<?php
// koneksi
include '../tools/connection.php';

// header
include '../blade/header.php';
?>

<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">

        <!-- kop surat -->
        <p class="text-center fw-bold m-0">PEMERINTAH KOTA PADANG</p>
        <p class="text-center fw-bold m-0">DINAS PENDIDIKAN</p>
        <p class="text-center fw-bold m-0">SMA NEGERI XYZ KOTA PADANG</p>
        <p class="text-center m-0">Jl. Maju Mundur No. 1 Kota Padang Telepon (0751) 11111</p>
        <p class="text-center m-0">Email : smaxyz@padang.sch.id</p>
        <hr>

        <!-- isi surat -->
        <p class="text-center fw-bold">Laporan Rekomendasi Penerima Beasiswa Pintar SMA NEGERI XYZ KOTA PADANG</p>
        <p class="text-justify">Berdasarkan hasil pengolahan data dengan menggunakan beberapa kriteria yang sudah ditentukan dan dengan mengimplementasikan metode Simple Additive Weighting (SAW), maka menghasilkan tiga rangking teratas sebagai berikut : </p>

        <?php $ranks = array(); ?>

        <!-- <p class="text-center fw-bold">Tabel Matrix Keputusan</p>
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
        </table> -->



        <div class="row mt-3">
            <div class="col-1"></div>
            <div class="col-10">
                <table class="table table-bordered">
                    <thead>
                        <tr">
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
                            //jika hanya menampilkan 3 nilai teratas
                            if ($ranking > 3) {
                                break;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-1"></div>
        </div>

        <p class="text-justify">Demikian surat ini kami sampaikan atas perhatian bapak / ibu / saudara, kami ucapkan terimakasih</p>

        <br><br>

        <p style=" text-align: right;">Padang, <?php echo date("d/m/Y") ?></p><br><br>
        <p style=" text-align: right;">SMA Negeri XYZ Kota Padang</p>

    </div>
    <div class="col-lg-1"></div>
</div>

<script>
    window.print();
</script>