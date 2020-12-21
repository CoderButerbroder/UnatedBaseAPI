<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Данные - пользователи FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>


<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#"></a>Физ. лица</li>
    <li class="breadcrumb-item active" aria-current="page">Все физ. лица</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>id Tboil</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Email</th>
                    <th>Phone</th>
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
          "processing": true,
          "serverSide": true,
          "keys": true,
          cache: false,
          "ajax": {
                    url: "/panel/data/users/get_usr",
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
            { "data": "Row", visible:false },
            { "data": "Number" },
            { "data": "L_Name" },
            { "data": "Name" },
            { "data": "S_Name" },
            { "data": "Email" },
            { "data": "Phone" },
          ]
    });

    console.log(tab);
    tab.on( 'key-focus', function ( e, datatable, cell, originalEvent ) {
            window.open('http://<?php echo $_SERVER["SERVER_NAME"];?>/panel/data/users/details?id_tboil='+(tab.row(cell[0][0]['row']).data()["Number"]));
        } );


  });
</script>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
