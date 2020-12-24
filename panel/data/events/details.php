<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Детали Мероприятия - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php



?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Мероприятия</a></li>
    <li class="breadcrumb-item active" aria-current="page">Детали Мероприятия {тут событие/наименование <?php echo $_GET["event"]; ?>}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <?php
        //https://demos.creative-tim.com/argon-dashboard-pro/pages/widgets.html
        //сверху 4ре карточки понравились а получилось >_>
        ?>
        <div class="row ">
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body">
                тип события
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body">
                организатор
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body">
                Место
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
              <div class="card-body">
                Статус
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <h5 class="h3 mb-0">Наименование мероприятия</h5>
              </div>
              <div class="card-body">
                <p class="card-text mb-4">Некое описание</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header text-center">
                <h5 class="h3 mb-0">Когда ?</h5>
              </div>
              <div class="card-body">
                <div id="content">
                  <ul class="timeline">
                    <li class="event" data-date="12:30 - 1:00pm">
                      <h3>Registration</h3>
                      <p>Get here on time, it's first come first serve. Be late, get turned away.</p>
                    </li>
                    <li class="event" data-date="2:30 - 4:00pm">
                      <h3>Opening Ceremony</h3>
                      <p>Get ready for an exciting event, this will kick off in amazing fashion with MOP &amp; Busta Rhymes as an opening show.</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
</div>



<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
