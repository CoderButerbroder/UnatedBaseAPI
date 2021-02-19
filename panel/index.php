  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
  <?php /*тут метатеги*/?>
	<title>Панель - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');

  //
  //
  //

  //
  // $arr_data_users = $settings->get_count_all_users();
  // $arr_result_usersusers = (object) array();
  // $arr_result_usersusers->name = array_keys($arr_data_users);
  // $arr_result_usersusers->data = (array) $arr_result_usersusers->data;
  // foreach($arr_data_users as $key => $value){
  //   array_push($arr_result_usersusers->data, $value);
  // }
  //
  //
  //
  // $data_count_company_period = $settings->get_count_entity_groupby_time_reg('month');
  // $data_count_company_SK_period = $settings->get_count_entity_skolkovo_groupby_time_reg('month');
  // $data_count_company_FSI_period = $settings->get_count_entity_fci_groupby_time_reg('month');
  // $data_count_company_EXPORT_period = $settings->get_count_entity_export_groupby_time_reg('month');
  //
  // function period($a, $b) {
  //   if(isset($a->dayd)){
  //     $date1 = $a->dayd.'.'.$a->monthd.'.'.$a->yeard;
  //     $date2 = $a->dayd.'.'.$b->monthd.'.'.$b->yeard;
  //   } else {
  //     $date1 = '00.'.$a->monthd.'.'.$a->yeard;
  //     $date2 = '00.'.$b->monthd.'.'.$b->yeard;
  //   }
  //     if (( strtotime( $date1 ) == strtotime( $date2 ) )) {
  //         return 0;
  //     }
  //     return ( strtotime( $date1 ) < strtotime( $date2 ) ) ? -1 : 1;
  // }
  //
  // $arr_select_month = array('1' => (object) array('name' => 'Январь', ),
  //                           '2' => (object) array('name' => 'Февраль', ),
  //                           '3' => (object) array('name' => 'Март', ),
  //                           '4' => (object) array('name' => 'Апрель', ),
  //                           '5' => (object) array('name' => 'Май', ),
  //                           '6' => (object) array('name' => 'Июнь', ),
  //                           '7' => (object) array('name' => 'Июль', ),
  //                           '8' => (object) array('name' => 'Август', ),
  //                           '9' => (object) array('name' => 'Сентябрь', ),
  //                           '10' => (object) array('name' => 'Октябрь', ),
  //                           '11' => (object) array('name' => 'Ноябрь', ),
  //                           '12' => (object) array('name' => 'Декабрь' ) );
  //
  // $arr_merge_count_company = array_merge($data_count_company_period, $data_count_company_SK_period, $data_count_company_FSI_period, $data_count_company_EXPORT_period);
  // usort($arr_merge_count_company, 'period');
  //
  // $temp_data_foreach_count = 0;
  // $temp_data_arr_foerch = (object) array();
  //
  // foreach ($arr_merge_count_company as $key => $value) {
  //   $temp_name_to_obj = $value->monthd.$value->yeard;
  //   if(!isset($temp_data_arr_foerch->$temp_name_to_obj)) {
  //     $temp_data_arr_foerch->$temp_name_to_obj =$arr_select_month[$value->monthd]->name.' '.$value->yeard;
  //     $temp_data_foreach_count++;
  //   }
  // }
  //
  // $arr_result_company_count = (object) [];
  // $arr_result_company_count->data = [];
  // $arr_result_company_count->time = [];
  //
  // function search_value($value_arr, $value_search){
  //   foreach ($value_arr as $key2 => $value2) {
  //     if ($value_search == $value2->monthd.$value2->yeard) {
  //       return (object) array ( "response" => true , "sum" => $value2->sum);
  //     }
  //   }
  //   return array ( "response" => false );
  // }
  //
  // $temp_data = (object)  array();
  // $temp_data->name = 'Компании';
  // $temp_data->data = [];
  // $temp_data->time = [];
  //
  // foreach ($temp_data_arr_foerch as $key => $value) {
  //   $temp_search = search_value($data_count_company_period,$key);
  //   if($temp_search->response){
  //      array_push( $temp_data->data, $temp_search->sum );
  //   } else {
  //      array_push( $temp_data->data, "0" );
  //   }
  // }
  // array_push( $arr_result_company_count->data, $temp_data );
  //
  // $temp_data = (object)  array();
  // $temp_data->name = 'Компании Сколково';
  // $temp_data->data = [];
  //
  // foreach ($temp_data_arr_foerch as $key => $value) {
  //   $temp_search = search_value($data_count_company_SK_period,$key);
  //   if($temp_search->response){
  //      array_push( $temp_data->data, $temp_search->sum );
  //   } else {
  //      array_push( $temp_data->data, "0" );
  //   }
  // }
  // array_push( $arr_result_company_count->data, $temp_data );
  //
  // $temp_data = (object)  array();
  // $temp_data->name = 'Компании ФСИ';
  // $temp_data->data = [];
  //
  // foreach ($temp_data_arr_foerch as $key => $value) {
  //   $temp_search = search_value($data_count_company_FSI_period,$key);
  //   if($temp_search->response){
  //      array_push( $temp_data->data, $temp_search->sum );
  //   } else {
  //      array_push( $temp_data->data, "0" );
  //   }
  // }
  //
  // array_push( $arr_result_company_count->data, $temp_data );
  //
  //
  // $temp_data = (object)  array();
  // $temp_data->name = 'Компании Экспорт';
  // $temp_data->data = [];
  //
  // foreach ($temp_data_arr_foerch as $key => $value) {
  //   $temp_search = search_value($data_count_company_EXPORT_period,$key);
  //   if($temp_search->response){
  //      array_push( $temp_data->data, $temp_search->sum );
  //   } else {
  //      array_push( $temp_data->data, "0" );
  //   }
  // }
  //
  // array_push( $arr_result_company_count->data, $temp_data );
  //
  //
  // $temp_data = [];
  // foreach ($temp_data_arr_foerch as $key => $value) {
  //   array_push( $arr_result_company_count->time, $value);
  // }



  ?>

  <?php if (!$data_user_rules->dashboard->rule->view_dashboard->value) {?>

      <div class="container-fluid text-center">
          <div class="alert alert-danger" role="alert"><i class="mb-3" style="width: 40px; height: 40px;" data-feather="alert-triangle"></i><h4>Доступ запрещен</h4><p>Доступ к данному разделу запрещен для вашей роли, запросите доступ у администратора</p> <a class="btn btn-primary m-4" href="/panel">Вернуться на главную</a></div>
      </div>

  <?php } else { ?>


    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-header">
               <h5 class="card-title my-auto">Юр. лица по отрослям</h5>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_branch" >
                  <div id="spinner_chart_line_branch" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-header">
                 <h5 class="card-title my-auto">Количественные показатели Юр. лиц</h5>
              </div>
              <div class="card-body" style="min-height: 420px; max-height: 420px;">
                <div class="" id="div_chart_line_company" >
                  <div id="spinner_chart_line_company" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-12 stretch-card">
          <div class="card">
            <div class="card-header">
               <h5 class="card-title my-auto">Физ лица</h5>
            </div>
            <div class="card-body" style="min-height: 420; /*max-height: 420;*/">
              <div class="" id="div_chart_line_user" >
                <div id="spinner_chart_line_user" class="spinner-border text-primary" style="position: absolute; margin: -25px 0 0 -25px; top: 50%; left: 50%;  width: 3rem; height: 3rem; z-index:99999;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>

  <?php include(__DIR__.'/actions/js_chart.php');?>

  <?php } ?>


  <?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
