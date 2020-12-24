<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Все юр.лица - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Юр. лица</a></li>
    <li class="breadcrumb-item active" aria-current="page">Все юр. лица</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="" style=" width: 100%;">
              <!-- <div class="table-responsive"> -->
                <table class="table table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="">Наименование</th>
                      <th>ИНН</th>
                      <th>ОГРН</th>
                      <th>ФСИ</th>
                      <th>Резидент Сколково</th>
                      <th>Пользователь ТБойл</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              <!-- </div> -->
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
                    url: "/panel/data/company/get_company",
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
            { "data": "Row", "width": "5%", "searchable": false, visible : false},
            { "data": "Name", "class" : "text-wrap", "width": "30%", "orderable": false },
            { "data": "INN", "width": "10%" },
            { "data": "OGRN", "width": "10%", "orderable": false },
            { "data": "FSI", "width": "2%", "orderable": false , "class":"text-center",
             render: function(data) {
               if (data == true) {
                 return '<span class="badge mr-2 badge-success" style=word-wrap: break-word">Да</span>';
               } else {
                 return '<span class="badge mr-2 badge-danger" style=word-wrap: break-word">Нет</span>';
               }
            }  },
            { "data": "SK", "width": "2%", "orderable": false , "class":"text-center",
             render: function(data) {
               if (data == true) {
                 return '<span class="badge mr-2 badge-success" style=word-wrap: break-word">Да</span>';
               } else {
                 return '<span class="badge mr-2 badge-danger" style=word-wrap: break-word">Нет</span>';
               }
            }  },
            { "data": "Tboil", "width": "2%", "orderable": false , "class":"text-center",
             render: function(data) {
               if (data == false) {
                 return '<span class="badge mr-2 badge-danger" style=word-wrap: break-word">Нет</span>';
               } else {
                 return '<span class="badge mr-2 badge-success" style=word-wrap: break-word" href="javascript:void(0)" onclick="window.open(\'<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil='+data+'\')">'+data+'</span>';
               }
            }  },
          ]
    });

    tab.on( 'key-focus', function ( e, datatable, cell, originalEvent ) {
      if(cell[0][0]["column"] < 6) {
      window.open('http://<?php echo $_SERVER["SERVER_NAME"];?>/panel/data/company/details?inn='+(tab.row(cell[0][0]['row']).data()["INN"]));
      }
    } );
  });
</script>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
