<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Канбан - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php

?>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Тех. поддержка</a></li>
    <li class="breadcrumb-item active" aria-current="page">Канбан</li>
  </ol>
</nav>

<div class="row" id="canban_all">

</div>

<script type="text/javascript">
  function update_status(btn,search, status) {
    $('#spiner').removeClass('d-none');
    $(btn).attr('disabled','disabled');
    $.ajax({
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/support/update_status',
      data: {
              "search": search,
              "status": status
      },
      success: function(result) {
        global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/support/elements/canban_all','#canban_all');
        $(btn).removeAttr('disabled');
        $('#spiner').addClass('d-none');
        if (IsJsonString(result)) {
          var arr = JSON.parse(result);
          if (arr["response"]) {
            alerts('success', arr["description"], '');
          } else {
            alerts('warning', 'Ошибка', arr["description"]);
          }
        } else {
          alerts('warning', 'Ошибка', 'Попробуйте позже');
        }
      },
      error: function(jqXHR, textStatus) {
        $(btn).removeAttr('disabled');
        $('#spiner').addClass('d-none');
        alerts('error', 'Ошибка подключения', 'Попробуйте позже');
      }
    });
  }

  $(document).ready(function($) {
      global_load_block('https://<?php echo $_SERVER['SERVER_NAME'];?>/panel/support/elements/canban_all','#canban_all');
  });

</script>




<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
