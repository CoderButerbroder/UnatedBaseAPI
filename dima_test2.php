
<?php
if (!isset($_GET["id"])) {
  header('Location: https://'.$_SERVER["SERVER_NAME"]);
  exit;
}

include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Данные по заявке - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php


$data_ticket_str = $settings->get_data_tiket($_GET["id"]);
$data_ticket = json_decode($data_ticket_str);
$data_referer_ticket = json_decode($settings->get_data_referer_id($data_ticket->data->id_referer));
$data_user_request = json_decode($settings->get_user_data_id_boil($data_ticket->data->id_tboil));
$temp_null = 0;
$history_status_ticket = json_decode($settings->get_ticket_status_history($data_ticket->data->id));
?>

<!-- <nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">Тех. поддержка</li>
    <li class="breadcrumb-item"><a href="/panel/support/tikets/">Все запросы</a></li>
    <li class="breadcrumb-item active" aria-current="page">Данные по заявке {тут номер}</li>
  </ol>
</nav> -->



<div class="row chat-wrapper" style="height: calc(100vh - 200px);">
    <div class="col-md-12">


      <div class="card">
            <div class="card-body" >
              <div class="row position-relative">
                <div class="col-lg-8 chat-aside border-lg-right">
                  <div class="aside-content">
                    <div class="aside-header">
                      <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
                        <div class="d-flex align-items-center">
                          <!-- <figure class="mr-2 mb-0">
                            <img src="https://via.placeholder.com/43x43" class="img-sm rounded-circle" alt="profile">
                            <div class="status online"></div>
                          </figure> -->
                          <div>
                                  <h6><?php echo ($data_ticket->data->status == 'open') ? '<span class="badge badge-danger">Открыта</span>' : ''; ?>
                                    <?php echo ($data_ticket->data->status == 'close') ? '<span class="badge badge-success">Закрыта</span>' : ''; ?>
                                    <?php echo ($data_ticket->data->status == 'work') ? '<span class="badge badge-primary">В работе</span>' : ''; ?>
                                   Заявка #<?php echo $data_ticket->data->id; ?> от <?php echo date('d.m.Y', strtotime($data_ticket->data->date_added)); ?>
                                 </h6>
                            <p class="text-muted mt-2 tx-13"><?php echo $data_ticket->data->type_support; ?></p>
                          </div>
                        </div>
                        <!-- <div class="dropdown">
                            <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-lg text-muted pb-3px" data-feather="settings" data-toggle="tooltip" title="Настройки"></i>
                          </button> -->
                          <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View Profile</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit Profile</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="aperture" class="icon-sm mr-2"></i> <span class="">Add status</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="settings" class="icon-sm mr-2"></i> <span class="">Settings</span></a>
                          </div>
                        </div> -->
                        <div class="col text-right">
                          <span class="text-right" data-content="Последнее обновление статуса" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="auto"><i class="link-icon mr-1" style="width: 15px; height: 15px;" data-feather="calendar"></i><?php echo date('H:i d.m.Y', strtotime($data_ticket->data->date_update_status));?></span>
                        </div>
                      </div>
                      <!-- <form class="search-form">
                        <div class="input-group border rounded-sm">
                          <div class="input-group-prepend">
                            <div class="input-group-text border-0 rounded-sm">
                              <i data-feather="search" class="icon-md cursor-pointer"></i>
                            </div>
                          </div>
                          <input type="text" class="form-control  border-0 rounded-sm" id="searchForm" placeholder="Search here...">
                        </div>
                      </form> -->
                    </div>

                    <div class="aside-body">
                      <ul class="nav nav-tabs mt-3" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                              <i data-feather="file-text" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                              <p class="d-none d-sm-block">Описание</p>
                            </div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                              <i data-feather="book-open" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                              <p class="d-none d-sm-block">Статусы</p>
                            </div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                              <i data-feather="file" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                              <p class="d-none d-sm-block">Файлы</p>
                            </div>
                          </a>
                        </li>
                        <!-- <li class="nav-item">
                          <a class="nav-link" id="conclusion-tab" data-toggle="tab" href="#conclusion" role="tab" aria-controls="conclusion" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                              <i data-feather="check-circle" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                              <p class="d-none d-sm-block">Заключение</p>
                            </div>
                          </a>
                        </li> -->
                      </ul>
                      <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                          <div>
                              <div class="row m-0 p-0">
                                <div class="col my-auto">
                                  <h5 class="h5 mb-0">Сведения о запросе</h5>
                                </div>
                                <div class="col my-auto text-right" >
                                  <span style="cursor:pointer;" class="badge badge-success"href="javascript:void(0)" onclick="window.open('<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil=<?php echo $data_ticket->data->id_tboil; ?>')">
                                    <i class="link-icon mr-1" style="width: 15px; height: 15px;" data-feather="user"></i>
                                    <?php echo $data_user_request->data->last_name.' '.$data_user_request->data->name.' '.$data_user_request->data->second_name; ?>
                                  </span>
                                  <?php if($data_user_request->data->id_entity) {
                                    $company = json_decode($settings->get_data_entity($data_user_request->data->id_entity));
                                    if ($company->response) {
                                      $fns_data = json_decode($company->data->data_fns);
                                      $fns_data_company = array_pop($fns_data->items);
                                      $name_company = (isset($fns_data_company->ЮЛ)) ? $fns_data_company->ЮЛ->НаимСокрЮЛ : $fns_data_company->ИП->ФИОПолн;
                                      ?>
                                        <span style="cursor:pointer;" class="badge badge-primary" href="javascript:void(0)" onclick="window.open('<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil=<?php echo $company->data->inn; ?>')">
                                          <i class="link-icon mr-1" style="width: 15px; height: 15px;" data-feather="briefcase"></i><?php echo $name_company; ?>
                                        </span>
                                    <?php
                                    }
                                  }
                                  ?>
                                </div>
                               </div>
                              <hr>
                                <div class="mt-3 col">
                                  <span class="h6 surtitle text-muted">Название запроса</span>
                                  <span class="d-block h6"><?php echo $data_ticket->data->name; ?></span>
                                </div>
                              <?php if($data_ticket->data->short_description) { ?>
                                  <div class="mt-3 col">
                                    <span class="h6 surtitle text-muted">Короткое описание</span>
                                    <span class="d-block h6"><?php echo $data_ticket->data->short_description; ?></span>
                                  </div>
                              <?php } ?>
                              <?php if($data_ticket->data->full_description) { ?>
                                <div class="mt-3 col">
                                  <span class="h6 surtitle text-muted">Полное описание</span>
                                  <span class="d-block h6"><?php echo $data_ticket->data->full_description; ?></span>
                                </div>
                              <?php } ?>
                              <?php if($data_ticket->data->question_desc) { ?>
                                <div class="mt-3 col">
                                  <span class="h6 surtitle text-muted">Описание вопроса</span>
                                  <span class="d-block h6"><?php echo $data_ticket->data->question_desc; ?></span>
                                </div>
                              <?php } ?>

                          </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                          <p class="text-muted mb-1">История статусов</p>
                          <?
                          $array_status = array('work' => 'Заявка <span class="badge badge-primary">в работе</span>',
                                                'close' => 'Заявка <span class="badge badge-success">закрыта</span>',
                                                'open' => 'Заявка <span class="badge badge-danger">открыта</span>'
                                              );
                          if ($history_status_ticket->response) {?>
                              <div class="row mt-2">
                                <?php foreach ($history_status_ticket->data as $key => $value) { ?>
                                    <div class="col-md-auto">
                                        <?php echo $settings->date_time_rus($value->date_update,true) ;?>
                                    </div>
                                    <div class="col-md-10">
                                        <?php echo $array_status[$value->status];?>
                                    </div>
                                <?}?>
                              </div>
                          <? } else { ?>
                              <div class="row">
                                <div class="col-md-12 text-center">
                                    нет истории статусов
                                </div>
                              </div>
                          <? } ?>
                        </div>
                        <!-- <div class="tab-pane fade" id="conclusion" role="tabpanel" aria-labelledby="conclusion-tab">
                          <p class="text-muted mb-1">Заключение по заявке</p>



                        </div> -->
                        <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                          <?php
                           $arr_files = explode(',', $data_ticket->data->links_add_files);
                           if (empty($arr_files)) {
                             echo '<p class="text-muted mb-1">Прикрепленные файлы заявки</p>';
                             foreach ($arr_files as $key => $value) {
                               echo '<p class="mb-1"><strong>Ссылка на документ</strong></p>';
                               echo '<p class="mb-1"><a href="https://'.$data_referer_ticket->data->resourse.$value.'" download></a>Вложение</p>';
                             }
                           } else { ?>
                             <div class="alert alert-info" role="alert">
                                Нет прикрепленных файлов
                             </div>
                          <?php } ?>

                          <!-- <p class="mb-1"><strong>Ссылка на фото</strong></p>
                          <p class="mb-1"><a href="https://lpmtech.ru/">https://lpmtech.ru/</a></p>

                          <p class="mb-1"><strong>Ссылка на документ</strong></p>
                          <p class="mb-1"><a href="https://lpmtech.ru/">https://lpmtech.ru/</a></p> -->

                        </div>
                        <div class="tab-pane fade" id="conclusion" role="tabpanel" aria-labelledby="conclusion-tab">
                          <p class="text-muted mb-1">Заключение по заявке</p>



                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 chat-content" style="position:relative; min-height: 78vh;">
                  <div class="chat-header border-bottom pb-2">
                    <div class="d-flex justify-content-between">
                      <div class="d-flex align-items-center">
                        <!-- <i data-feather="corner-up-left" id="backToChatList" class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i> -->
                        <!-- <figure class="mb-0 mr-2">
                          <img src="https://via.placeholder.com/43x43" class="img-sm rounded-circle" alt="image">
                          <div class="status online"></div>
                          <div class="status online"></div>
                        </figure> -->
                        <div>
                          <p>Сообщения с клиентом</p>
                          <!-- <p class="text-muted tx-13">Front-end Developer</p> -->
                        </div>
                      </div>
                      <!-- <div class="d-flex align-items-center mr-n1">
                        <a href="#">
                          <i data-feather="video" class="icon-lg text-muted mr-3" data-toggle="tooltip" title="Start video call"></i>
                        </a>
                        <a href="#">
                          <i data-feather="phone-call" class="icon-lg text-muted mr-0 mr-sm-3" data-toggle="tooltip" title="Start voice call"></i>
                        </a>
                        <a href="#" class="d-none d-sm-block">
                          <i data-feather="user-plus" class="icon-lg text-muted" data-toggle="tooltip" title="Add to contacts"></i>
                        </a>
                      </div> -->
                    </div>
                  </div>
                  <div class="chat-body" id="div_messages">
                    <ul class="messages" id="messages">
                      <div class="alert alert-light" role="alert">
                        Нет истории переписки
                      </div>
                    </ul>
                  </div>
                  <div class="chat-footer d-flex" style="position:absolute; bottom:0; width: 98%;">
                    <!-- <div>
                      <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="Emoji">
                        <i data-feather="smile" class="text-muted"></i>
                      </button>
                    </div>
                    <div class="d-none d-md-block">
                      <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="Attatch files">
                        <i data-feather="paperclip" class="text-muted"></i>
                      </button>
                    </div>
                    <div class="d-none d-md-block">
                      <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="Record you voice">
                        <i data-feather="mic" class="text-muted"></i>
                      </button>
                    </div> -->
                    <form class="search-form flex-grow mr-2" onsubmit="send_message(this, '<?php echo $_GET["id"]; ?>'); return false;">
                      <div class="input-group">
                        <input type="text" name="msg" class="form-control rounded-pill mr-2" id="chatForm" placeholder="Введите сообщение для пользователя" required>
                        <div class="input-group-append">
                          <button type="submit" name="btn_send" class="btn btn-primary btn-icon rounded-circle">
                            <i data-feather="send"></i>
                          </button>
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>


    </div>
</div>

<script type="text/javascript">
  var ps;

  $( document ).ready(function() {
      $("#messages").load("/panel/support/tikets/history_message?value=<?php echo $_GET["id"];?> > *", function() {
        $('#div_messages').animate({
            scrollTop: $('#div_messages').get(0).scrollHeight
        }, 10);
      });
  });

  function send_message(form, value) {
    $('#spiner').removeClass('d-none');
    btn = form.elements["btn_send"];
    $(btn).attr('disabled','disabled');
    $.ajax({
      type: 'POST',
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/support/tikets/push_message',
      data: {"search": value, "msg" : form.elements['msg'].value},
      success: function(result) {
        $(btn).removeAttr('disabled');
        $('#spiner').addClass('d-none');
        if (IsJsonString(result)) {
          var arr = JSON.parse(result);
          if (arr["response"]) {
            $('#chatForm').val('');
            //alerts('success', arr["description"], '');
            $("#messages").load("/panel/support/tikets/history_message?value=<?php echo $_GET["id"];?> > *", function() {
              $('#div_messages').animate({
                  scrollTop: $('#div_messages').get(0).scrollHeight
              }, 200);
            });
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
  };

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
  };
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
