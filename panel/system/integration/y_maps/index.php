<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php'); ?>


<?php if (!$data_user_rules->sistem->rule->settings->value) {?>

    <div class="container-fluid text-center">
        <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
    </div>

<?php } else {
$key_y_maps = $settings->get_global_settings('api_yandex_map_key');
?>
<script src="https://api-maps.yandex.ru/2.1/?apikey=<?php echo $key_y_maps;?>&lang=ru_RU" type="text/javascript">
</script>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/panel/system/integration/">Настройки Интеграций</a></li>
    <li class="breadcrumb-item active" aria-current="page">Интеграция с Яндекс Картами</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5>API-ключ <small><a href="https://developer.tech.yandex.ru/services/">Где получить?</a></small></h5>
            <div class="row mt-2">
              <form method="post"  onsubmit="upd_key(this); return false;" class="form-inline" style="width: 100%">
                <div class="col-md-12">
                    <div class="row">
                      <div class="form-inline col-md-8">
                        <input type="password" name="secret_key" class="form-control" style="width:100%" id="key_api" autocomplete="current-password" value="<?php echo $key_y_maps;?>" required placeholder="Обязательное поле">
                        <i class="icon_pass far fa-eye" onclick="change_view_pass(this);" style="top: 35px; right: 25px;"></i>
                      </div>
                      <div class="col-md-4">
                          <button type="submit" name="submit" class="btn btn-success btn-block">Сохранить</button>
                      </div>
                    </div>

                    <?php
                    if ($nevalid_key) { ?>
                      <small id="emailHelp" class="form-text text-danger">Ошибка: Неверный ключ</small>
                    <?}?>
                    <div class="col-md-12" style="padding-top: 15px; padding-left: 0px;">
                      <small id="emailHelp" class="form-text text-danger">После установки API-ключ обязательно проверьте его ниже, в поле ввода, а так же на карте</small>
                    </div>
                    <div class="col-md-12" style="padding-top: 15px; padding-left: 0px;">
                      <p>Ознакомится с <a href="https://yandex.ru/dev/maps/jsapi/doc/2.1/quick-start/index.html/">руководством разрабочика</a></p>
                    </div>
                </div>

             </form>
           </div>
          </div>
        </div>
    </div>
    <!-- <div class="col-md-6" >
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-10 my-auto">
              Количественные показатели методов
            </div>
            <button type="button" onclick="upd_tbl()" class="col-2 btn btn-outline-primary">Обновить</button>
          </div>
        </div>

        <div class="card-body">
          <div id="spinner_table" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="" id="div_count_fns_table">
          </div>
        </div>
      </div>
    </div> -->
    <div class="col-md-12" style="margin-top: 20px;">
      <div class="card">
        <div class="card-body">
          <h5>Проверка установки API-ключа</h5>
          <p class="text-danger">При возникновении проблем с API-ключем, данные об адресе загружаться не будут</p>
              <select style=""  class=" " aria-describedby="" name="check_street" id="check_street" onchange="">
                <!-- <option disabled value="">Укажите, Проспект/Улицу, Дом/Строение</option> -->
              </select>
          <p class="text-danger" style="margin-top: 10px;">При возникновении проблем с API-ключем, данная карта не будет отображаться!</p>
          <div id="map" style="width: 100%; height: 400px"></div>
        </div>
      </div>
    </div>


</div>

<?php }  ?>

<script type="text/javascript">
var myMap, clusterer;

ymaps.ready(init);
function init(){
    myMap = new ymaps.Map("map", {
        center: [59.938951, 30.315635],
        zoom: 10
    });
    clusterer = new ymaps.Clusterer({ clusterDisableClickZoom: true });
};

$(document).ready(function() {
  // $('#check_street').select2();
  select_check = $('#check_street').select2({
        // theme: 'bootstrap4',
        language: "ru",
        minimumInputLength: 2,
        placeholder : 'укажите улицу и дом',
        ajax: {
         url: "https://<?php echo $_SERVER["SERVER_NAME"];?>/panel/system/integration/y_maps/action/get_street.php", // адрес бэкэн-обработчика (url)
         delay: 250,
         type: "post",
         dataType: "json",
         cache: true,
         // что будем отправлять на сервер в запросе
         data: function (obj) {
             return {'query' : obj.term};
         },
          /* обрабатываем то, что пришло с сервера
           * (напр. просто берём подмассив) */
         processResults: function (data, params) {
           return {
               results: data.results
           };
         }
        }
    });

    select_check
                .on('select2:open', function(e){
                  $('.select2-search__field').val("");
                  $('.select2-search__field').val(($("#check_street option:selected").text()).trim() + ' ');
                  $('.select2-search__field').trigger('change');
                });

    $("#check_street").on('change', function(){

                data = $("#check_street option:selected").val();
                if(IsJsonString(data)){
                  json_data = JSON.parse(data);
                  pieces = json_data["pos"].split(" ");
                  if(pieces.length == 2){
                    myGeoObjects = [];
                    myGeoObjects[0] = new ymaps.GeoObject({
                     geometry: { type: "Point", coordinates: [pieces[1], pieces[0]] },
                     properties: {
                         clusterCaption: 'Геокодер',
                         balloonContentBody: 'Проверка работоспособности геокодера yandex'
                       }
                    });
                    clusterer.removeAll();
                    clusterer.add(myGeoObjects);
                    myMap.geoObjects.add(clusterer);
                  }
               }
             });

});


  function upd_key(form) {
    $.ajax({
      async: true,
      cache: false,
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/system/integration/y_maps/action/upd_key',
      data: $(form).serialize(),
      success: function(result, status, xhr) {
        if (IsJsonString(result)) {
          ar_data = JSON.parse(result);
          if (ar_data["response"]) {
            alerts('success', ar_data["description"], '');
          } else {
            alerts('warning', 'Ошибка', ar_data["description"]);
          }
        } else {
          alerts('warning', 'Ошибка', result);
        }
      },
      error: function(jqXHR, textStatus) {
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  }

  function change_view_pass(el) {
      if ($(el.previousElementSibling).attr('type') == 'password'){
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye-slash");
        $(el.previousElementSibling).attr('type', 'text');
      } else {
        $(el).removeClass();
        $(el).addClass("icon_pass far fa-eye");
        $(el.previousElementSibling).attr('type', 'password');
      }
    return false;
  };


</script>


<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
