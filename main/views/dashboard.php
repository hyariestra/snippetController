
<style>
	.widget-informasi{
		border:1px solid #ccc;
		margin-bottom:5px;
	}
	
	.header-widget{
		text-align:center;
		background:#429489;
		color:#fff;
		font-family:tahoma;
		padding:3px;
		font-size:12px;
	}
	.body-widget{
		padding:5px;
		font-size:12px;
	}
	
	tr > td.data-body{
		padding:3px !important;
		font-size:12px;
		font-family:tahoma;
	}
	.datepicker table{
		font-size:12px;
	}
	.indikator-danger{
		width:10px;
		height:10px;
		background:red;
	}
	
	.indikator-primary{
		width:10px;
		height:10px;
		background:#428bca;
		
	}
	
	.indikator-warning{
		width:10px;
		height:10px;
		background:#f0ad4e;
		#787a93
	}
	
	.indikator-important{
		width:10px;
		height:10px;
		background:#787a93;
	}
	
	.indikator{
		float: left;
		margin-top: 3px;
		margin-right: 3px;
	}
	
	.data-header{
		padding:0px 0px 0px 5px;
		font-size:12px;
		font-family:tahoma;
	}
</style>

<script src="<?php echo base_url("assets/js/highcharts.js")?>"></script>

<!--<h4>Dashboard</h4>-->
<div class="row">
	<div class="col-md-9">
		<div class="widget-container">
			<div id="containers" style="min-width:310;height:400px;margin:0px auto;"></div>
				
			<div class="col-md-12">
				<div class="row">
					<div id="pie2" style="width:310;height:400px;margin:0px auto;"></div>
					<div id="pie3" style="width:310;height:400px;margin:0px auto;"></div>
				</div>
			</div>
		</div>
		
	
	</div>
	<div class="col-md-3">
		<div class="stickys">
			<div class="widget-container" style="padding:10px">
				<div class="widget-informasi">
					<div class="header-widget"> Saldo Awal</div>
					<div class="body-widget">
						<div class="rows">
							<span style="font-size:25px; font-family:tahoma;"><span class="glyphicon "></span> Rp. <?php echo number_format($saldoawal->first_row()->nominal)?>,-</span>
						</div>
						
					</div>
				</div>
				
				<div class="widget-informasi">
					<div class="header-widget"> Pendapatan Bulan Ini</div>
					<div class="body-widget">
						<div class="rows">
							<span style="font-size:25px; font-family:tahoma;"><span class="glyphicon glyphicon-circle-arrow-down"></span> Rp. <?php echo number_format($pemasukan->first_row()->total)?>,-</span>
						</div>
						
					</div>
				</div>
				
				<div class="widget-informasi">
					<div class="header-widget">Pengeluaran Bulan Ini</div>
					<div class="body-widget">
						<div class="rows">
							<span style="font-size:25px; font-family:tahoma;"><span class="glyphicon glyphicon-circle-arrow-up"></span> Rp. <?php echo number_format($pengeluaran->first_row()->total)?>,-</span>
						</div>
					</div>
				</div>
				
				<div class="widget-informasi">
					<div class="header-widget"> Saldo Akhir</div>
					<div class="body-widget">
						<div class="rows">
							<span style="font-size:25px; font-family:tahoma;"><span class="glyphicon "></span> Rp. <?php echo number_format($saldoakhir)?>,-</span>
						</div>
						
					</div>
				</div>
				<?php
				if($_SESSION['IDUnit'] != 1)
				{
					$where = ($_SESSION['IDUnit'] != 1) ? " WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'" : "";
					
					$dataTransaksi = $this->db->query("SELECT * FROM mst_kategori_item".$where);
					foreach($dataTransaksi->result() as $kategori){
				?>
				<div class="widget-informasi">
					<div class="header-widget"><?php echo $kategori->nama_kategori?></div>
					<div class="body-widget">
						<div class="rows">
							<ul>
								<?php 
									$item = $this->db->query("SELECT mst_item.nama_item, mst_item.id_item 
									FROM mst_item 
									WHERE mst_item.id_kategori_item = '".$kategori->id_kategori_item."'");
									
									foreach($item->result() as $row){
									
									$total = $this->db->query("SELECT ((COALESCE((SELECT SUM(trx_penjualan_det.jumlah_item * trx_penjualan_det.harga) as total 
									FROM trx_penjualan_det
									WHERE trx_penjualan_det.id_item = '".$row->id_item."'
									GROUP BY trx_penjualan_det.id_item), 0))
									-
									(COALESCE((SELECT SUM(trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga) as total 
									FROM trx_pembelian_persediaan_det
									WHERE trx_pembelian_persediaan_det.id_item = '".$row->id_item."'
									GROUP BY trx_pembelian_persediaan_det.id_item), 0))) as total
									");
									
									$total = ($total->first_row()->total < 0) ? $total->first_row()->total * -1 : $total->first_row()->total;
								?>
								<li><?php echo $row->nama_item?> : Rp. <?php echo number_format($total)?>.-</li>
								<?php
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				<?php
					}
				}
				else
				{
					$kategori = $this->db->query("SELECT  * FROM mst_akun 
					WHERE (CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '4.1%'
					OR CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '5.1%')
					AND mst_akun.level = 3
					ORDER BY REPLACE(CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun),'.','') ASC");
				?>
				
				<div class="widget-informasi">
				<?php
					foreach($kategori->result() as $row)
					{
				?>
					<div class="header-widget"><?php echo $row->nama_akun?></div>
					<div class="body-widget">
						<div class="rows">
						
						</div>
					</div>
				<?php
					}
				?>
				</div>
				<?php
				}
				?>
				
			</div>
		</div>
	</div>
	

</div>



<!-- Absensi Guru -->
<div class="modal fade" id="modalAbsensi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Absensi Kehadiran</h5>
      </div>
      <div class="modal-body">
        <div class="row">
	        <div class="col-md-12">
				<div style="background:#787a93;min-height:0px;" class="widget-container padded">
					<div class="widget-container" style="padding:10px;">
						<span class="badge" style="margin-bottom:5px;">
							Tanggal : <?php echo date("d-M-Y")?>
						</span>
						<table class="table table-striped table-bordered">
							<tr>
								<td class="data-header">#</td>
								<td class="data-header">Nama Pengajar</td>
								<td class="data-header">Status</td>
							</tr>
							
							<tr>
								<td class="data-body">1</td>
								<td class="data-body">Ryzvanto Harya Praskasa A.Md</td>
								<td class="data-body">Hadir</td>
							</tr>
							<tr>
								<td class="data-body">2</td>
								<td class="data-body">Radityo S.Kom</td>
								<td class="data-body">Izin</td>
							</tr>
							<tr>
								<td class="data-body">3</td>
								<td class="data-body">Arif Gunawan S.Kom</td>
								<td class="data-body">Hadir</td>
							</tr>
							
						</table>
					</div>
				</div>
			</div>
		</div>
      </div>
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
	$(document).ready(function(){
	
	var gColomPemasukan = <?php echo json_encode($gColomPemasukan); ?>;
	var gColomPengeluaran = <?php echo json_encode($gColomPengeluaran); ?>;
	
	var gPieTotalPemasukan = <?php echo json_encode($gPieTotalPemasukan); ?>;
	var gPieTotalPengeluaran = <?php echo json_encode($gPieTotalPengeluaran); ?>;
	
	var gPiePemasukan = <?php echo json_encode($gPiePemasukan); ?>;
	var gPiePengeluaran = <?php echo json_encode($gPiePengeluaran); ?>;
	
	var bagian = <?php echo json_encode($setbagian); ?>;
	
	
	
	//============= datepicker ========================//
	
	$("#datepicker").datepicker();
	
	
	
	//==================== THEME =====================//
Highcharts.createElement('link', {
   href: 'https://fonts.googleapis.com/css?family=Unica+One',
   rel: 'stylesheet',
   type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

Highcharts.theme = {
   colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066', '#eeaaee',
      '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
   chart: {
      backgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
         stops: [
            [0, '#2a2a2b'],
            [1, '#3e3e40']
         ]
      },
      style: {
         fontFamily: '\'Unica One\', sans-serif'
      },
      plotBorderColor: '#606063'
   },
   title: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase',
         fontSize: '20px'
      }
   },
   subtitle: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase'
      }
   },
   xAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      title: {
         style: {
            color: '#A0A0A3'

         }
      }
   },
   yAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      tickWidth: 1,
      title: {
         style: {
            color: '#A0A0A3'
         }
      }
   },
   tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.85)',
      style: {
         color: '#F0F0F0'
      }
   },
   plotOptions: {
      series: {
         dataLabels: {
            color: '#B0B0B3'
         },
         marker: {
            lineColor: '#333'
         }
      },
      boxplot: {
         fillColor: '#505053'
      },
      candlestick: {
         lineColor: 'white'
      },
      errorbar: {
         color: 'white'
      }
   },
   legend: {
      itemStyle: {
         color: '#E0E0E3'
      },
      itemHoverStyle: {
         color: '#FFF'
      },
      itemHiddenStyle: {
         color: '#606063'
      }
   },
   credits: {
      style: {
         color: '#666'
      }
   },
   labels: {
      style: {
         color: '#707073'
      }
   },

   drilldown: {
      activeAxisLabelStyle: {
         color: '#F0F0F3'
      },
      activeDataLabelStyle: {
         color: '#F0F0F3'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: '#DDDDDD',
         theme: {
            fill: '#505053'
         }
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: '#505053',
         stroke: '#000000',
         style: {
            color: '#CCC'
         },
         states: {
            hover: {
               fill: '#707073',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: '#000003',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            }
         }
      },
      inputBoxBorderColor: '#505053',
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(255,255,255,0.1)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      },
      xAxis: {
         gridLineColor: '#505053'
      }
   },

   scrollbar: {
      barBackgroundColor: '#808083',
      barBorderColor: '#808083',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: '#606063',
      buttonBorderColor: '#606063',
      rifleColor: '#FFF',
      trackBackgroundColor: '#404043',
      trackBorderColor: '#404043'
   },

   // special colors for some of the
   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
   background2: '#505053',
   dataLabelsColor: '#B0B0B3',
   textColor: '#C0C0C0',
   contrastTextColor: '#F0F0F3',
   maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);


//========================= chart ========================//
	Highcharts.chart('containers', {
    title: {
        text: 'Pemasukan dan Pengeluaran Tahun <?php echo date("Y")?>'
    },
    xAxis: {
        categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    },
    labels: {
        items: [{
            html: 'Total Pengeluaran Pemasukan',
            style: {
                left: '50px',
                top: '18px',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
            }
        }]
    },
    series: [{
        type: 'column',
        name: 'Pemasukan',
        data: gColomPemasukan
    }, {
        type: 'column',
        name: 'Pengeluaran',
        data: gColomPengeluaran
    }, {
        type: 'spline',
        name: 'Rata - rata',
        data: [3, 2, 1, 3, 4,3, 2, 1, 3, 4,5,5],
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
        }
    }, {
        type: 'pie',
        name: 'Total consumption',
        data: [{
            name: 'Pemasukan',
            y: gPieTotalPemasukan,
            color: Highcharts.getOptions().colors[0] // Jane's color
        }, {
            name: 'Pengeluaran',
            y: gPieTotalPengeluaran,
            color: Highcharts.getOptions().colors[1] // John's color
        }],
        center: [100, 80],
        size: 100,
        showInLegend: false,
        dataLabels: {
            enabled: false
        }
    }]
});
	
	

//================== PIE CHART ===================//

	Highcharts.chart('pie2', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Pemasukan Berdasarkan '+bagian+' Pada Tahun <?php echo date("Y")?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Total',
            colorByPoint: true,
            data: gPiePemasukan
        }]
    });
	
	Highcharts.chart('pie3', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Pengeluaran Berdasarkan '+bagian+' Pada Tahun <?php echo date("Y")?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Total',
            colorByPoint: true,
            data: gPiePengeluaran
        }]
    });
	
	
});

	function openJadwal()
	{
		$("#modalJadwal").modal("show");
	}
	
	function absensiGuru()
	{
		$("#modalAbsensi").modal("show");
	}
	
</script>