<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Все заявки - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Тех. поддержка</a></li>
    <li class="breadcrumb-item active" aria-current="page">Все запросы</li>
  </ol>
</nav>


<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="" style=" width: 100%;">
                <table class="table table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>id</th>
                      <th>Тип</th>
                      <th>Наименование</th>
                      <th>Дата</th>
                      <th>Статус</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var tab;
  $(document).ready(function(){
    var tab = $('.table').DataTable({
          "language": { "url": "/assets/vendors/datatables.net/Russian.json" },
          "responsive": true,
          "processing": true,
          "serverSide": true,
          "keys": true,
          "cache": false,
          "ajax": {
                    url: "/panel/data/support/tikets/get_tikets.php",
                    type: "POST",
                    "dataSrc": function ( json ) {
                      return json.data;
                        // if(json.response){
                        //   return json.data.data;
                        // } else {
                        //   alerts('warning', json.description, '');
                        //   return false;
                        // }
                    },
                    error: function(jqXHR, exception) {
                        alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
                        return false;
                    },
                  },
          "columns": [
            { "data": "Row", "width": "5%", "searchable": false},
            { "data": "Id", "class" : "text-wrap", "width": "30%", "orderable": false },
            { "data": "Type", "width": "10%" },
            { "data": "Name", "width": "10%", "orderable": false },
            { "data": "Data", "width": "2%", "orderable": false },
            { "data": "Status", "width": "2%", "orderable": false },
          ]
    });

    tab.on( 'key-focus', function ( e, datatable, cell, originalEvent ) {
            window.open('http://<?php echo $_SERVER["SERVER_NAME"];?>/panel/data/company/details?inn='+(tab.row(cell[0][0]['row']).data()["INN"]));
        } );
  });
</script>




<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
