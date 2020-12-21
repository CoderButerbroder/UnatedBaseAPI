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
  $(document).ready(function(){
    $('.table').DataTable({
          "language": { "url": "/assets/vendors/datatables.net/Russian.json" },
          "processing": true,
          "serverSide": true,
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
  });
</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
