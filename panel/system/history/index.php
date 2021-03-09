<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/ ?>
<title>История - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>

<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>

  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Система</a></li>
      <li class="breadcrumb-item active" aria-current="page">История</li>
    </ol>
  </nav>

  <div class="row">
      <div class="col-md-12 stretch-card">

      </div>
  </div>


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
                        <th class="">id_user</th>
                        <th>ФИО</th>
                        <th>Действие</th>
                        <th>Тип</th>
                        <th>Содержание</th>
                        <th>Дата</th>
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
                      url: "/panel/system/history/actions/get_data",
                      type: "POST",
                      "dataSrc": function ( json ) {
                        return json.data;
                      },
                      error: function(jqXHR, exception) {
                          alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
                          return false;
                      },
                    },
            "columns": [
              { "data": "id", "width": "5%", "searchable": true, visible : false},
              { "data": "id_user", "class" : "text-wrap", "width": "5%", "orderable": true },
              { "data": "FIO", "width": "20%", "orderable": false },
              { "data": "action", "width": "10%", "orderable": true },
              { "data": "type", "width": "10%", "orderable": true , "class":"text-center" },
              { "data": "content", "width": "10%", "orderable": true , "orderable": false , "class":"text-center" },
              { "data": "d_time", "width": "10%", "orderable": false , "class":"text-center" },
            ]
      });
    });
  </script>



<?php }  ?>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
