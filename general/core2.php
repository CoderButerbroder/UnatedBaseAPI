<?php
include($_SERVER['DOCUMENT_ROOT'].'/general/core.php');

class Settings2 extends Settings {

  private $MAIN_entity = 'MAIN_entity';
  private $MAIN_entity_tech_requests = 'MAIN_entity_tech_requests';
  private $MAIN_entity_tech_requests_solutions = 'MAIN_entity_tech_requests_solutions';
  private $MAIN_entity_tech_services = 'MAIN_entity_tech_services';
  private $MAIN_entity_tech_services_comments = 'MAIN_entity_tech_services_comments';
  private $MAIN_entity_tech_services_rating = 'MAIN_entity_tech_services_rating';
  private $MAIN_entity_tech_services_view = 'MAIN_entity_tech_services_view';



  public function insert_company($inn,$data_fns,$data_dadata,$msp,$site,$region,$staff,$district,$street,$house,$type_inf,$additionally,$hash,$date_pickup){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity (inn,data_fns,data_dadata,msp,site,region,staff,district,street,house,type_inf,additionally,hash,date_pickup)
                                          VALUES (:inn,:data_fns,:data_dadata,:msp,:site,:region,:staff,:district,:street,:house,:type_inf,:additionally,:hash,:date_pickup)");
    $request->bindParam(':inn', $inn, PDO::PARAM_INT);
    $request->bindParam(':data_fns', $data_fns, PDO::PARAM_STR);
    $request->bindParam(':data_dadata', $data_dadata, PDO::PARAM_STR);
    $request->bindParam(':msp', $msp, PDO::PARAM_STR);
    $request->bindParam(':site', $site, PDO::PARAM_STR);
    $request->bindParam(':region', $region, PDO::PARAM_STR);
    $request->bindParam(':staff', $staff, PDO::PARAM_STR);
    $request->bindParam(':district', $district, PDO::PARAM_STR);
    $request->bindParam(':street', $street, PDO::PARAM_STR);
    $request->bindParam(':house', $house, PDO::PARAM_STR);
    $request->bindParam(':type_inf', $type_inf, PDO::PARAM_STR);
    $request->bindParam(':additionally', $additionally, PDO::PARAM_STR);
    $request->bindParam(':hash', $hash, PDO::PARAM_STR);
    $request->bindParam(':date_pickup', $date_pickup, PDO::PARAM_STR);
    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }

  public function insert_tech_requests($id_requests_on_referer,$id_entity,$name_request,$description,$demand,$collection_time,$links_to_logos,$type_request,$links_add_files,$request_hash,$status,$date_added,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_requests (id_requests_on_referer,id_entity,name_request,description,demand,collection_time,links_to_logos,type_request,links_add_files,request_hash,status,date_added,id_referer)
                                          VALUES (:id_requests_on_referer,:id_entity,:name_request,:description,:demand,:collection_time,:links_to_logos,:type_request,:links_add_files,:request_hash,:status,:date_added,:id_referer)");

    $request->bindParam(':id_requests_on_referer', $id_requests_on_referer, PDO::PARAM_STR);
    $request->bindParam(':id_entity', $id_entity, PDO::PARAM_STR);
    $request->bindParam(':name_request', $name_request, PDO::PARAM_STR);
    $request->bindParam(':description', $description, PDO::PARAM_STR);
    $request->bindParam(':demand', $demand, PDO::PARAM_STR);
    $request->bindParam(':collection_time', $collection_time, PDO::PARAM_STR);
    $request->bindParam(':links_to_logos', $links_to_logos, PDO::PARAM_STR);
    $request->bindParam(':type_request', $type_request, PDO::PARAM_STR);
    $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
    $request->bindParam(':request_hash', $request_hash, PDO::PARAM_STR);
    $request->bindParam(':status', $status, PDO::PARAM_STR);
    $request->bindParam(':date_added', $date_added, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_STR);

    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }

  public function insert_tech_requests_solutions($id_requests_on_referer,$id_entity,$name_request,$description,$demand,$collection_time,$links_to_logos,$type_request,$links_add_files,$request_hash,$status,$date_added,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_requests_solutions (id_request_on_referer,id_solution_on_referer,id_tboil,name_project,description,result_project,readiness,period,forms_of_support,protection,links_add_files,solutions_hash,status,date_receiving,id_referer)
                                          VALUES (:id_request_on_referer,:id_solution_on_referer,:id_tboil,:name_project,:description,:result_project,:readiness,:period,:forms_of_support,:protection,:links_add_files,:solutions_hash,:status,:date_receiving,:id_referer)");

    $request->bindParam(':id_request_on_referer', $id_request_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_solution_on_referer', $id_solution_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
    $request->bindParam(':name_project', $name_project, PDO::PARAM_STR);
    $request->bindParam(':description', $description, PDO::PARAM_STR);
    $request->bindParam(':result_project', $result_project, PDO::PARAM_STR);
    $request->bindParam(':readiness', $readiness, PDO::PARAM_STR);
    $request->bindParam(':period', $period, PDO::PARAM_STR);
    $request->bindParam(':forms_of_support', $forms_of_support, PDO::PARAM_STR);
    $request->bindParam(':protection', $protection, PDO::PARAM_STR);
    $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
    $request->bindParam(':solutions_hash', $solutions_hash, PDO::PARAM_STR);
    $request->bindParam(':status', $status, PDO::PARAM_STR);
    $request->bindParam(':date_receiving', $date_receiving, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }

  public function insert_tech_services($id_service_on_referer,$id_entity,$name,$category,$object_type,$description,$district,$street,$link_preview,$links_add_files,$status,$additionally,$data_added,$service_hash,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services (id_service_on_referer,id_entity,name,category,object_type,description,district,street,link_preview,links_add_files,status,additionally,data_added,service_hash,id_referer)
                                          VALUES (:id_service_on_referer,:id_entity,:name,:category,:object_type,:description,:district,:street,:link_preview,:links_add_files,:status,:additionally,:data_added,:service_hash,:id_referer)");


    $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_STR);
    $request->bindParam(':id_entity', $id_entity, PDO::PARAM_STR);
    $request->bindParam(':name', $name, PDO::PARAM_STR);
    $request->bindParam(':category', $category, PDO::PARAM_STR);
    $request->bindParam(':object_type', $object_type, PDO::PARAM_STR);
    $request->bindParam(':description', $description, PDO::PARAM_STR);
    $request->bindParam(':district', $district, PDO::PARAM_STR);
    $request->bindParam(':street', $street, PDO::PARAM_STR);
    $request->bindParam(':link_preview', $link_preview, PDO::PARAM_STR);
    $request->bindParam(':links_add_files', $links_add_files, PDO::PARAM_STR);
    $request->bindParam(':status', $status, PDO::PARAM_STR);
    $request->bindParam(':additionally', $additionally, PDO::PARAM_STR);
    $request->bindParam(':data_added', $data_added, PDO::PARAM_STR);
    $request->bindParam(':service_hash', $service_hash, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_STR);


    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }


  public function insert_tech_services_comments($id_services_comments_on_referer,$id_service_on_referer,$id_tboil,$comment,$status,$date_update,$comments_hash,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_comments (id_services_comments_on_referer,id_service_on_referer,id_tboil,comment,status,date_update,comments_hash,id_referer)
                                          VALUES (:id_services_comments_on_referer,:id_service_on_referer,:id_tboil,:comment,:status,:date_update,:comments_hash,:id_referer)");

    $request->bindParam(':id_services_comments_on_referer', $id_services_comments_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
    $request->bindParam(':comment', $comment, PDO::PARAM_STR);
    $request->bindParam(':status', $status, PDO::PARAM_STR);
    $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
    $request->bindParam(':comments_hash', $comments_hash, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }


  public function insert_tech_services_rating($id_services_rating_on_referer,$id_service_on_referer,$id_comment,$id_tboil,$rating,$date_update,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_rating (id_services_rating_on_referer,id_service_on_referer,id_comment,id_tboil,rating,date_update,id_referer)
                                          VALUES (:id_services_rating_on_referer,:id_service_on_referer,:id_comment,:id_tboil,:rating,:date_update,:id_referer)");

    $request->bindParam(':id_services_rating_on_referer', $id_services_rating_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_comment', $id_comment, PDO::PARAM_INT);
    $request->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
    $request->bindParam(':rating', $rating, PDO::PARAM_INT);
    $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }


  public function insert_tech_services_view($id_services_rating_on_referer,$id_service_on_referer,$id_comment,$id_tboil,$rating,$date_update,$id_referer){
    global $database;

    $request = $database->prepare("INSERT INTO $this->MAIN_entity_tech_services_view (id_services_rating_on_referer,id_service_on_referer,id_comment,id_tboil,rating,date_update,id_referer)
                                          VALUES (:id_services_rating_on_referer,:id_service_on_referer,:id_comment,:id_tboil,:rating,:date_update,:id_referer)");

    $request->bindParam(':id_services_rating_on_referer', $id_services_rating_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_service_on_referer', $id_service_on_referer, PDO::PARAM_INT);
    $request->bindParam(':id_comment', $id_comment, PDO::PARAM_INT);
    $request->bindParam(':id_tboil', $id_tboil, PDO::PARAM_INT);
    $request->bindParam(':rating', $rating, PDO::PARAM_INT);
    $request->bindParam(':date_update', $date_update, PDO::PARAM_STR);
    $request->bindParam(':id_referer', $id_referer, PDO::PARAM_INT);

    $check_request = $request->execute();
    $count_request = $request->rowCount();
    if($count_request > 0) {
      return true;
      exit;
    } else {
      return false;
      exit;
    }
  }





}


?>
