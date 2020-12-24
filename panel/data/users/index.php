<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Данные - пользователи FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php if (!$data_user_rules->users->rule->view_all_users->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else { ?>


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
              <table class="table" style="width: 100%">
                <thead>
                  <tr>
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
      tab = $('.table').DataTable({
          "language": { "url": "/assets/vendors/datatables.net/Russian.json" },
          "processing": true,
          "serverSide": true,
          "keys": true,
          "ajax": {
                    "url": "/panel/data/users/get_usr",
                    "type": "POST"
                  },
          "columns": [
            { "data": "Number" },
            { "data": "L_Name" },
            { "data": "Name" },
            { "data": "S_Name" },
            { "data": "Email" },
            { "data": "Phone" },
          ]
    });

    tab.on( 'key-focus', function ( e, datatable, cell, originalEvent ) {
            window.open('http://<?php echo $_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil='+(tab.row(cell[0][0]['row']).data()["Number"]));
        } );
  });
</script>

<?php }?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
