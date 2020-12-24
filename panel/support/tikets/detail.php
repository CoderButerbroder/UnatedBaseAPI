<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Данные по заявке - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>
<?php

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
            <div class="card-body">
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
                            <h6><span class="badge badge-danger">Открыта</span> Заявка #34563 от 23.12.20</h6>
                            <p class="text-muted mt-2 tx-13">Тип запроса</p>
                          </div>
                        </div>
                        <div class="dropdown">
                          <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-lg text-muted pb-3px" data-feather="settings" data-toggle="tooltip" title="Настройки"></i>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View Profile</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit Profile</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="aperture" class="icon-sm mr-2"></i> <span class="">Add status</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="settings" class="icon-sm mr-2"></i> <span class="">Settings</span></a>
                          </div>
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
                        <li class="nav-item">
                          <a class="nav-link" id="conclusion-tab" data-toggle="tab" href="#conclusion" role="tab" aria-controls="conclusion" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                              <i data-feather="check-circle" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                              <p class="d-none d-sm-block">Заключение</p>
                            </div>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                          <div>
                            <p class="text-muted mb-1">Сведения о запросе</p>

                            <p class="mb-1"><strong>Контакное лицо</strong></p>
                            <p class="mb-1">Контакное лицо (id_tboil)</p>

                            <p class="mb-1"><strong>Название запроса</strong></p>
                            <p class="mb-1">Название запроса</p>

                            <p class="mb-1"><strong>Короткое описание</strong></p>
                            <p class="mb-1">Короткое описание</p>

                            <p class="mb-1"><strong>Полное описание описание</strong></p>
                            <p class="mb-1">Таким образом, рамки и место обучения кадров требует от нас системного анализа существующих финансовых и административных условий? Значимость этих проблем настолько очевидна, что курс на социально-ориентированный национальный проект требует определения и уточнения всесторонне сбалансированных нововведений. Соображения высшего порядка, а также социально-экономическое развитие требует определения и уточнения позиций, занимаемых участниками в отношении поставленных задач!

Соображения высшего порядка, а также постоянный количественный рост и сфера нашей активности позволяет выполнить важнейшие задания по разработке соответствующих условий активизации. Практический опыт показывает, что повышение уровня гражданского сознания требует определения и уточнения форм воздействия! Соображения высшего порядка, а также начало повседневной работы по формированию позиции обеспечивает широкому кругу специалистов участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Таким образом, новая модель организационной деятельности требует от нас анализа существующих финансовых и административных условий.

Таким образом, выбранный нами инновационный путь в значительной степени обуславливает создание дальнейших направлений развития проекта! Разнообразный и богатый опыт постоянное информационно-техническое обеспечение нашей деятельности представляет собой интересный эксперимент проверки существующих финансовых и административных условий. Соображения высшего порядка, а также новая модель организационной деятельности играет важную роль в формировании всесторонне сбалансированных нововведений. Соображения высшего порядка, а также начало повседневной работы по формированию позиции позволяет оценить значение существующих финансовых и административных условий!</p>

                            <p class="mb-1"><strong>Описание вопроса</strong></p>
                            <p class="mb-1">Короткое описание</p>



                          </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                          <p class="text-muted mb-1">История статусов</p>


                        </div>
                        <div class="tab-pane fade" id="conclusion" role="tabpanel" aria-labelledby="conclusion-tab">
                          <p class="text-muted mb-1">Заключение по заявке</p>



                        </div>
                        <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                          <p class="text-muted mb-1">Прикрепленные файлы заявки</p>

                          <p class="mb-1"><strong>Ссылка на фото</strong></p>
                          <p class="mb-1"><a href="https://lpmtech.ru/">https://lpmtech.ru/</a></p>

                          <p class="mb-1"><strong>Ссылка на документ</strong></p>
                          <p class="mb-1"><a href="https://lpmtech.ru/">https://lpmtech.ru/</a></p>

                        </div>
                        <div class="tab-pane fade" id="conclusion" role="tabpanel" aria-labelledby="conclusion-tab">
                          <p class="text-muted mb-1">Заключение по заявке</p>



                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 chat-content" style="position:relative;">
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
                  <div class="chat-body">
                    <ul class="messages">
                      <li class="message-item friend">
                        <img src="https://via.placeholder.com/43x43" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Здравствуйте, хотел бы воспользоваться вашим сервисом</p>
                            </div>
                            <span>23.12.20 в 17:00</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item me">
                        <img src="<?php echo $data_user->data->photo;?>" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                            </div>
                            <span>23.12.20 в 17:02</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item friend">
                        <img src="https://via.placeholder.com/43x43" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Здравствуйте, хотел бы воспользоваться вашим сервисом</p>
                            </div>
                            <span>23.12.20 в 17:00</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item me">
                        <img src="<?php echo $data_user->data->photo;?>" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                            </div>
                            <span>23.12.20 в 17:02</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item friend">
                        <img src="https://via.placeholder.com/43x43" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Здравствуйте, хотел бы воспользоваться вашим сервисом</p>
                            </div>
                            <span>23.12.20 в 17:00</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item me">
                        <img src="<?php echo $data_user->data->photo;?>" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                            </div>
                            <span>23.12.20 в 17:02</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item friend">
                        <img src="https://via.placeholder.com/43x43" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Здравствуйте, хотел бы воспользоваться вашим сервисом</p>
                            </div>
                            <span>23.12.20 в 17:00</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item me">
                        <img src="<?php echo $data_user->data->photo;?>" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                            </div>
                            <span>23.12.20 в 17:02</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item friend">
                        <img src="https://via.placeholder.com/43x43" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Здравствуйте, хотел бы воспользоваться вашим сервисом</p>
                            </div>
                            <span>23.12.20 в 17:00</span>
                          </div>
                        </div>
                      </li>
                      <li class="message-item me">
                        <img src="<?php echo $data_user->data->photo;?>" class="img-xs rounded-circle" alt="avatar">
                        <div class="content">
                          <div class="message">
                            <div class="bubble">
                              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                            </div>
                            <span>23.12.20 в 17:02</span>
                          </div>
                        </div>
                      </li>
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
                    <form class="search-form flex-grow mr-2">
                      <div class="input-group">
                        <input type="text" class="form-control rounded-pill" id="chatForm" placeholder="Введите сообщение для пользователя">
                      </div>
                    </form>
                    <div>
                      <button type="button" class="btn btn-primary btn-icon rounded-circle">
                        <i data-feather="send"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


    </div>
</div>




<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
