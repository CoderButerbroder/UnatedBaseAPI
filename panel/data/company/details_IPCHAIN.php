<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Детали юр.лица - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

<?php
$count_list_fns = 0;
$data_local_ipchain = $settings->ipchain_get_data_entity('inn', $_GET["inn"]);
$arr_data_local_ipchain = json_decode($data_local_ipchain);

if (!$arr_data_local_ipchain->response) {
  //надо подумать что делать в таких случаях >_<
  echo "<h1>error</h1>";
  exit();
}

echo $data_local_ipchain;


?>



<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Данные</a></li>
    <li class="breadcrumb-item"><a href="#">Юр. лица IPchain</a></li>
    <li class="breadcrumb-item active" aria-current="page">Детали юр. лица IPchain <?php echo $arr_data_local_ipchain->data->Name; ?></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="container-fluid" style="border-radius: 3px;">
            <?php // 1 строка с основной информацией и показателей ФНС ?>
            <div class="row border-bottom">
                <?php // блок основной информации ?>
                <div class="col-lg-6 border-right p-4">
                  <?php // Заголовок и кнопка редактирвоания  ?>
                  <div class="row p-1">
                    <div class="col-sm-9">
                      <h5>Основная информация</h5>
                    </div>
                    <div class="col-sm-3 text-right" style="padding-top: .05rem">

                    </div>
                  </div>
                  <?php // Основная информация о компании  ?>
                  <div class="row pl-1 pt-3">
                    <div class="col-sm-3">
                      <p>ИНН:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Inn; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>ОГРН:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Ogrn; ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Код ОКВЭД:</p>
                    </div>
                    <div class="col-sm-9">
                      <p>
                        <?php echo $arr_data_local_ipchain->data->Okved; ?>
                      </p>
                    </div>
                  </div>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                    <div class="row pl-1 pt-1">
                      <div class="col-sm-3">
                        <p>Сайт:</p>
                      </div>
                      <div class="col-sm-9">
                        <a href="<? echo (trim($arr_data_local_ipchain->data->Website) != '' && $arr_data_local_ipchain->data->Website != NULL) ? $arr_data_local_ipchain->data->Website : $false_data ?>">
                          <? echo (trim($arr_data_local_ipchain->data->Website) != '' && $arr_data_local_ipchain->data->Website != NULL) ? $arr_data_local_ipchain->data->Website : $false_data ?></a>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Адрес:</p>
                    </div>
                    <div class="col-sm-9">
                      <?php echo $arr_data_local_ipchain->data->LawAddress; ?>
                    </div>
                  </div>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                    <div class="row pl-1 pt-1">
                      <div class="col-sm-3">
                        <p>Отрасль:</p>
                      </div>
                      <div class="col-sm-9">
                        <div id="technology_branch"></div>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if(isset($arr_data_local_ipchain->data->Website) && trim($arr_data_local_ipchain->data->Website) != ''){ ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-3">
                      <p>Технологии:</p>
                    </div>
                    <div class="col-sm-9">
                      <div id="technology_TECH"></div>
                    </div>
                  </div>
                  <?php } ?>
                </div>

                <?php // блок ключевые показатели ФНС ?>
                <div class="col-lg-6 p-4">
                  <?php // Заголовок и кнопка редактирвоания  ?>
                  <div class="row p-1 ">
                    <div class="col-sm-9">
                      <h5>Ключевые показатели</h5>
                    </div>
                    <div class="col-sm-3 text-right" style="padding-top: .05rem">
                      <h5><span class="badge" style="background: #727cf5; color: #fff">ФНС</span></h5>
                    </div>
                  </div>
                  <?php // Основная информация о компании  ?>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Выручка за год:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                          if ($data_fns->ЮЛ->ОткрСведения->СумДоход) {echo $data_fns->ЮЛ->ОткрСведения->СумДоход.' руб.';}
                          else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Количество сотрудников:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                         if ($data_fns->ЮЛ->ОткрСведения->КолРаб) {echo $data_fns->ЮЛ->ОткрСведения->КолРаб;}
                         else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Специальные налоговые режимы:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->СведСНР) {echo $data_fns->ЮЛ->ОткрСведения->СведСНР;}
                        else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-3">
                    <div class="col-md-12">
                      <h5>Налоги или сборы (последний год)</h5>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Сумма уплаченных налогов или сборов:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $nalog = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $nalog = $nalog + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->СумУплНал;
                          }
                          echo $nalog.' руб.';
                        }
                        else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                  <div class="row pl-1 pt-1">
                    <div class="col-sm-8">
                      <p>Общая сумма недоимки по налогу, пени и штрафу:</p>
                    </div>
                    <div class="col-sm-4">
                      <p>
                        <? /*
                        if ($data_fns->ЮЛ->ОткрСведения->Налоги) {
                          $peni = 0;
                          for ($i=0; $i < count($data_fns->ЮЛ->ОткрСведения->Налоги); $i++) {
                              $peni = $peni + $data_fns->ЮЛ->ОткрСведения->Налоги[$i]->ОбщСумНедоим;
                          }
                          echo $peni.' руб.';
                        }
                        else {echo $false_data;} */
                        ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <?php /* конец блока с общ информацией */ ?>

              <?php // 2 строка с пользователем и нти ?>
              <div class="row border-bottom">
                  <?php // блок основной информации ?>
                  <div class="col-lg-6 border-right p-4">
                      <?php // Заголовок и кнопка редактирвоания  ?>
                      <div class="row p-1">
                          <div class="col-sm-9">
                            <h5>Контактное лицо <?php //echo ($data_user->response) ? "" : "Не зарегистрированно в системе ЕБД" ;?></h5>
                          </div>
                          <div class="col-sm-3 text-right" style="padding-top: .05rem">

                          </div>
                      </div>
                      <?php // Основная информация о пользователе  ?>
                      <div class="row pl-1 pt-3">
                          <div class="col-sm-auto">
                            <img style="width: 180px; height: auto;" src="/assets/images/male-user-profile-picture.png" />
                          </div>
                          <div class="col-sm-auto"><?php /*
                              <p><? echo ($data_user->data->name) ? $data_user->data->last_name." ".$data_user->data->name." ".$data_user->data->second_name : 'ФИО не указано'; ?></p>
                              <p><? echo ($data_user->data->position) ? $data_user->data->position : 'Должность не указан'; ?></p>
                              <p><? echo ($data_user->data->phone) ? $data_user->data->phone : 'Телефон не указан'; ?></p>
                              <p><? echo ($data_user->data->email) ? $data_user->data->email : 'Email не указан'; ?></p>
                              <p>Tboil ID: <a href="https://tboil.spb.ru/"><? echo ($data_user->data->id_tboil) ? $data_user->data->id_tboil : 'Не зарегистрирован'; ?></a></p>
                              <?php if($data_user->data->id_tboil) { ?><p><button href="javascript:void(0)" class="btn btn btn-outline-info" onclick="window.open('<?php echo 'https://'.$_SERVER["SERVER_NAME"];?>/panel/data/users/details?tboil=<?php echo $data_user->data->id_tboil; ?>')">Посмотреть профиль</button></p> <?php } ?>
                              */ ?>
                          </div>
                      </div>
                  </div>

                  <?php // блок ключевые показатели ФНС ?>
                  <div class="col-lg-6 p-4">
                          <?php // Заголовок и кнопка редактирвоания  ?>
                          <div class="row p-1">
                              <div class="col-sm-9">
                                <h5>Национальная технологическая инициатива</h5>
                              </div>
                              <div class="col-sm-3 text-right" style="padding-top: .05rem">

                              </div>
                          </div>
                          <?php // Основная информация о компании  ?>
                          <div class="row pl-1 pt-3">
                              <div class="col-sm-auto ">
                                <p>Рынки НТИ:</p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NTI"></div>
                              </div>
                          </div>
                          <div class="row pl-1 pt-1">
                              <div class="col-sm-auto">
                                <p>Национальные и федеральные проекты:</p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_NFP"></div>
                              </div>
                          </div>
                          <div class="row pl-1 pt-1">
                              <div class="col-sm-auto">
                                <p>Цифровые сквозные технологии:</p>
                              </div>
                              <div class="col-sm-auto">
                                  <div id="technology_CST"></div>
                              </div>
                          </div>
                  </div>
                </div>

                <?php // 3 строка с информацией по сколково ?>
                <div class="row border-bottom">
                  <?php // блок основной информации ?>
                  <div class="col-lg-6 border-right p-4">
                    <?php // Заголовок и кнопка редактирвоания  ?>
                    <div class="row p-1">
                      <div class="col-sm-12">
                        <h5>Фонд Сколково</h5>
                      </div>
                    </div>
                    <?php // Основная информация о компании  ?>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Резидент Сколково:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <? //echo ($status_skolkovo) ? 'Резидент' : 'Не резидент'; ?>
                        </p>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Сумма полученных грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php /*
                            // 200000001 = соклково
                            // 200000002 = фси
                            // пустые = ФСИ
                            $data_grant = json_decode($out_stateSupport)->data;
                            $scolkovo_grant_sum = 0;
                            for ($i=0; $i < count($data_grant); $i++) {
                                if ($data_grant[$i]->typeId == '200000001') {
                                  $scolkovo_grant_sum = $scolkovo_grant_sum + $data_grant[$i]->Sum;
                                }
                            }
                            echo $scolkovo_grant_sum.' руб.'; */
                            ?>
                        </p>
                      </div>
                    </div>
                  </div>

                  <?php // блок ключевые показатели ФНС ?>
                  <div class="col-lg-6 p-4">
                    <?php // Заголовок и кнопка редактирвоания  ?>
                    <div class="row p-1 ">
                      <div class="col-sm-12">
                        <h5>Фонд содействия инновациям</h5>
                      </div>
                    </div>
                    <?php // Основная информация о компании  ?>
                    <div class="row pl-1 pt-3">
                      <div class="col-sm-6">
                        <p><b>Программы:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <?php /*
                          // 200000001 = соклково
                          // 200000002 = фси
                          // пустые = ФСИ
                          // var_dump(json_decode($out_stateSupport)->data);
                          $data_grant = json_decode($out_stateSupport)->data;
                          //
                          $fsi_grant= '';
                          for ($i=0; $i < count($data_grant); $i++) {
                              if ($array_document_fsi[stristr($data_grant[$i]->id_Support, '-', true)]) {
                                $fsi_grant .= '<p class="badge mr-2 h5" style="background: #2079cf; color: #fff; word-wrap: break-word">'.$array_document_fsi[stristr($data_grant[$i]->id_Support, '-', true)].'</p>';
                                // $fsi_grant_sum = $fsi_grant_sum + intval($data_grant[$i]->Sum);
                              }
                          }
                          echo $fsi_grant; */
                        ?>
                      </div>
                    </div>
                    <div class="row pl-1">
                      <div class="col-sm-6">
                        <p><b>Общая сумма средств грантов:</b></p>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <?php /*
                          // // 200000001 = соклково
                          // // 200000002 = фси
                          // // пустые = ФСИ
                          // // var_dump(json_decode($out_stateSupport)->data);
                          $data_grant = json_decode($out_stateSupport)->data;
                          $fsi_grant_sum = 0;
                          for ($i=0; $i < count($data_grant); $i++) {
                              if ($data_grant[$i]->typeId == '200000002' || trim($data_grant[$i]->typeId) == '') {
                                $fsi_grant_sum = $fsi_grant_sum + intval($data_grant[$i]->Sum);
                              }
                          }
                          echo $fsi_grant_sum.' руб.'; */
                          ?>
                        </p>
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
