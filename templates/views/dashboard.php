<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            App-Store <small>home</small>
        </h1>
        <?php
            echo getMessage();
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <h3 style="text-align:center"><?php echo $header; ?></h3>

        <canvas id="grafAtendIndiv"></canvas>


        <script type="text/javascript">
            let grafAtendIndiv = document.getElementById('grafAtendIndiv').getContext('2d');

            let chart = new Chart(grafAtendIndiv, {
                type: 'bar',

                data: {
                    //labels: ['2000', '2001', '2002', '2003', '2004', '2005'],
                    labels: [<?php echo $labels; ?>],
                    datasets: [
                        {
                            label: 'Atendimentos por Tipo',
                            //data: [173448346, 175885229, 178276128, 180619108, 182911487, 185150806],
                            data: [<?php echo $dados; ?>],
                            backgroundColor: "rgba(0, 255, 0, 0.3)",
                        }
                    ],
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                suggestedMax: 25
                            }
                        }]
                    }
                },
            });

        </script>
    </div>
    <div class="col-lg-6">
        <h3 style="text-align:center"><?php echo $header; ?></h3>

        <canvas id="grafAtendIndiv2"></canvas>


        <script type="text/javascript">
            let grafAtendIndiv2 = document.getElementById('grafAtendIndiv2').getContext('2d');

            let chart2 = new Chart(grafAtendIndiv2, {
                type: 'bar',

                data: {
                    //labels: ['2000', '2001', '2002', '2003', '2004', '2005'],
                    labels: [<?php echo $labels2; ?>],
                    datasets: [
                        {
                            label: 'Atendimentos por Assistência',
                            //data: [173448346, 175885229, 178276128, 180619108, 182911487, 185150806],
                            data: [<?php echo $dados2; ?>],
                            backgroundColor: "rgba(0, 255, 0, 0.3)",
                        }
                    ],
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                suggestedMax: 25
                            }
                        }]
                    }
                },
            });

        </script>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <h3 style="text-align:center"><?php echo $header; ?></h3>

        <canvas id="grafAtendIndiv3"></canvas>


        <script type="text/javascript">
            let grafAtendIndiv3 = document.getElementById('grafAtendIndiv3').getContext('2d');

            let chart3 = new Chart(grafAtendIndiv3, {
                type: 'bar',

                data: {
                    //labels: ['2000', '2001', '2002', '2003', '2004', '2005'],
                    labels: [<?php echo $labels3; ?>],
                    datasets: [
                        {
                            label: 'Atendimentos por Valor do Produto (R$)',
                            //data: [173448346, 175885229, 178276128, 180619108, 182911487, 185150806],
                            data: [<?php echo $dados3; ?>],
                            backgroundColor: "rgba(0, 255, 0, 0.3)",
                        }
                    ],
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                suggestedMax: 25
                            }
                        }]
                    }
                },
            });

        </script>
    </div>
    <div class="col-lg-6">
        <h3 style="text-align:center"><?php echo $header; ?></h3>

        <canvas id="grafAtendIndiv4"></canvas>


        <script type="text/javascript">
            let grafAtendIndiv4 = document.getElementById('grafAtendIndiv4').getContext('2d');

            let chart4 = new Chart(grafAtendIndiv4, {
                type: 'bar',

                data: {
                    //labels: ['2000', '2001', '2002', '2003', '2004', '2005'],
                    labels: [<?php echo $labels4; ?>],
                    datasets: [
                        {
                            label: 'Atendimentos por Modelo do Produto',
                            //data: [173448346, 175885229, 178276128, 180619108, 182911487, 185150806],
                            data: [<?php echo $dados4; ?>],
                            backgroundColor: "rgba(0, 255, 0, 0.3)",
                        }
                    ],
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                suggestedMax: 25
                            }
                        }]
                    }
                },
            });

        </script>
    </div>
</div>
<!-- /.row -->
