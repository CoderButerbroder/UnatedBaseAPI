<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/gen_header.php');?>
<?php /*тут метатеги*/?>
<title>Профиль - FULLDATA ЛЕНПОЛИГРАФМАШ</title>

<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/header_panel.php');?>

  <div class="profile-page tx-13">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="profile-header">
          <div class="cover">
            <div class="gray-shade"></div>
            <figure>
              <img src="https://via.placeholder.com/1148x272" class="img-fluid" alt="profile cover">
            </figure>
            <div class="cover-body d-flex justify-content-between align-items-center">
              <div>
                <img class="profile-pic" onclick="$('#mod-success').modal('show');" style="cursor:pointer;" src="<?php echo ($data_user->data->photo) ? 'https://'.$_SERVER["SERVER-NAME"].$data_user->data->photo : 'https://via.placeholder.com/100x100'; ?>" alt="profile">
                <span class="profile-name"><?php echo $data_user->data->name.' '.$data_user->data->lastname; ?></span>
              </div>
              <div class="d-none d-md-block">
                <button class="btn btn-primary btn-icon-text btn-edit-profile">
                  <i data-feather="edit" class="btn-icon-prepend"></i> Редактировать
                </button>
              </div>
            </div>
          </div>
          <div class="header-links">
            <!-- <ul class="links d-flex align-items-center mt-3 mt-md-0">
              <li class="header-link-item d-flex align-items-center active">
                <i class="mr-1 icon-md" data-feather="columns"></i>
                <a class="pt-1px d-none d-md-block" href="#">Timeline</a>
              </li>
              <li class="header-link-item ml-3 pl-3 border-left d-flex align-items-center">
                <i class="mr-1 icon-md" data-feather="user"></i>
                <a class="pt-1px d-none d-md-block" href="#">About</a>
              </li>
              <li class="header-link-item ml-3 pl-3 border-left d-flex align-items-center">
                <i class="mr-1 icon-md" data-feather="users"></i>
                <a class="pt-1px d-none d-md-block" href="#">Friends <span class="text-muted tx-12">3,765</span></a>
              </li>
              <li class="header-link-item ml-3 pl-3 border-left d-flex align-items-center">
                <i class="mr-1 icon-md" data-feather="image"></i>
                <a class="pt-1px d-none d-md-block" href="#">Photos</a>
              </li>
              <li class="header-link-item ml-3 pl-3 border-left d-flex align-items-center">
                <i class="mr-1 icon-md" data-feather="video"></i>
                <a class="pt-1px d-none d-md-block" href="#">Videos</a>
              </li>
            </ul> -->
          </div>
        </div>
      </div>
    </div>
    <div class="row profile-body">
      <!-- left wrapper start -->
      <div class="d-none d-md-block col-md-4 col-xl-3 left-wrapper">
        <div class="card rounded">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <h6 class="card-title mb-0">About</h6>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="git-branch" class="icon-sm mr-2"></i> <span class="">Update</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View all</span></a>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <label class="tx-11 font-weight-bold mb-0 text-uppercase">Роль:</label>
              <?php
                    $data_role = json_decode($settings->get_role_data($data_user->data->role));
                    if($data_role->response) {
                      echo '<p class="text-muted">'.$data_role->data->alias.'</p>';
                    } else {
                      echo '<p class="text-muted">Ошибка</p>';
                    }
              ?>
            </div>
            <div class="mt-3">
              <label class="tx-11 font-weight-bold mb-0 text-uppercase">Email:</label>
              <p class="text-muted"><?php echo $data_user->data->email;?></p>
            </div>
            <div class="mt-3">
              <label class="tx-11 font-weight-bold mb-0 text-uppercase">Phone:</label>
              <p class="text-muted"><?php echo ($data_user->data->phone) ? $data_user->data->phone : '-'; ?></p>
            </div>
            <div class="mt-3">
              <label class="tx-11 font-weight-bold mb-0 text-uppercase">Соц. Сети:</label>
                <div class="mt-3 d-flex social-links">
                  <a href="javascript:;" class="btn d-flex align-items-center justify-content-center border mr-2 btn-icon github">
                    <i data-feather="github" data-toggle="tooltip" title="github.com/nobleui"></i>
                  </a>
                  <a href="javascript:;" class="btn d-flex align-items-center justify-content-center border mr-2 btn-icon twitter">
                    <i data-feather="twitter" data-toggle="tooltip" title="twitter.com/nobleui"></i>
                  </a>
                  <a href="javascript:;" class="btn d-flex align-items-center justify-content-center border mr-2 btn-icon instagram">
                    <i data-feather="instagram" data-toggle="tooltip" title="instagram.com/nobleui"></i>
                  </a>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!-- left wrapper end -->
      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-6 middle-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card rounded">
              <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">1 min ago</p>
                    </div>
                  </div>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="meh" class="icon-sm mr-2"></i> <span class="">Unfollow</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="corner-right-up" class="icon-sm mr-2"></i> <span class="">Go to post</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="share-2" class="icon-sm mr-2"></i> <span class="">Share</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="copy" class="icon-sm mr-2"></i> <span class="">Copy link</span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <p class="mb-3 tx-14">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus minima delectus nemo unde quae recusandae assumenda.</p>
                <img class="img-fluid" src="https://via.placeholder.com/513x342" alt="">
              </div>
              <div class="card-footer">
                <div class="d-flex post-actions">
                  <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                    <i class="icon-md" data-feather="heart"></i>
                    <p class="d-none d-md-block ml-2">Like</p>
                  </a>
                  <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                    <i class="icon-md" data-feather="message-square"></i>
                    <p class="d-none d-md-block ml-2">Comment</p>
                  </a>
                  <a href="javascript:;" class="d-flex align-items-center text-muted">
                    <i class="icon-md" data-feather="share"></i>
                    <p class="d-none d-md-block ml-2">Share</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card rounded">
              <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">5 min ago</p>
                    </div>
                  </div>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="meh" class="icon-sm mr-2"></i> <span class="">Unfollow</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="corner-right-up" class="icon-sm mr-2"></i> <span class="">Go to post</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="share-2" class="icon-sm mr-2"></i> <span class="">Share</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="copy" class="icon-sm mr-2"></i> <span class="">Copy link</span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <p class="mb-3 tx-14">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <img class="img-fluid" src="https://via.placeholder.com/513x342" alt="">
              </div>
              <div class="card-footer">
                <div class="d-flex post-actions">
                  <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                    <i class="icon-md" data-feather="heart"></i>
                    <p class="d-none d-md-block ml-2">Like</p>
                  </a>
                  <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                    <i class="icon-md" data-feather="message-square"></i>
                    <p class="d-none d-md-block ml-2">Comment</p>
                  </a>
                  <a href="javascript:;" class="d-flex align-items-center text-muted">
                    <i class="icon-md" data-feather="share"></i>
                    <p class="d-none d-md-block ml-2">Share</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- middle wrapper end -->
      <!-- right wrapper start -->
      <div class="d-none d-xl-block col-xl-3 right-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card rounded">
              <div class="card-body">
                <h6 class="card-title">latest photos</h6>
                <div class="latest-photos">
                  <div class="row">
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure>
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure class="mb-0">
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure class="mb-0">
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                    <div class="col-md-4">
                      <figure class="mb-0">
                        <img class="img-fluid" src="https://via.placeholder.com/67x67" alt="">
                      </figure>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 grid-margin">
            <div class="card rounded">
              <div class="card-body">
                <h6 class="card-title">suggestions for you</h6>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>
                <div class="d-flex justify-content-between">
                  <div class="d-flex align-items-center hover-pointer">
                    <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">
                    <div class="ml-2">
                      <p>Mike Popescu</p>
                      <p class="tx-11 text-muted">12 Mutual Friends</p>
                    </div>
                  </div>
                  <button class="btn btn-icon"><i data-feather="user-plus" data-toggle="tooltip" title="Connect"></i></button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- right wrapper end -->
    </div>
  </div>


  <style media="screen">
    .div_content_file {
      width: 100%;
      height: 200px;
      position: relative;
      border: 2px dashed #c0beff;
      border-radius: 5px;
      transition: border 400ms ease;
    }
    .content_file {
      position: absolute;
      top: 60%;
      left: 50%;
      margin-right: -50%;
      transform: translate(-50%, -50%);

    }
    .content_file input[type=file] {
      outline: 0;
      opacity: 0;
      pointer-events: none;
      user-select: none;
    }
    .content_file .label span{
      display: block;
      font-size: 32px;
      color:gray;
    }

    .div_content_file:hover{
    border: 2px solid #c0beff;
    }

    .cropper-crop-box, .cropper-view-box {
    border-radius: 50%;
    }

    .cropper-view-box {
    box-shadow: 0 0 0 1px #39f;
    outline: 0;
    }


  </style>

  <div id="mod-success" tabindex="-1" role="dialog" style="" class="modal fade">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="form-group" style="text-align: left;">
              <label for=""></label>
              <div id="div_select_preview" class="row" onclick="" style="position: relative; height: 200px; min-width:100%; /*margin-top:20px; padding-top:20px;*/">
                <div  id="action_div_file_preview" class="col-md-12 my-auto animate__animated" style="position:absolute; padding-top:65px;">
                  <div class="row" >
                    <div  class="col-md-12 text-center animate__animated" style="margin-top:-70px;">
                      <form id="form_preview" >
                        <div class="div_content_file text-center">
                          <div class="content_file">
                            <label class="label">
                              <span class="icon mdi mdi-file-plus"></span>
                            </br>
                              <span class="title">Добавить файл</span><br>
                              <input type="file" id="file_preview" accept=".jpg, .jpeg, .png">
                            </label>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="col-md-12" >
                      <button type="button" class="btn gen_button btn-block" name="" style="display: none; margin-top:30px;" name="img_min" id="download_btn_preview">Загрузить</button>
                    </div>
                  </div>
                </div>

                <div id="action_div_file_preview_crop" class="col-md-12 animate__animated" style=" position:absolute; display:none;">
                  <div class="row">
                  <div class="col-md-1" style=" padding-right:0;">
                    <button type="button" class="btn btn-default btn-block" style="height:100%;" onclick="return_select_file();"><span class="icon mdi mdi-chevron-left"></span></button>
                  </div>
                  <div class="col-md-11" id="div_preview" style="display:block; max-width:90%; max-height:400px; padding:0; margin-left: 0;" >
                    <img id ="image_preview">
                  </div>
                </div>
              </div>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer"><button class="btn btn-space btn-success btn-block" id="btn_update_send">Загузить</button></div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
    const image = document.getElementById('image_preview');

    const cropper = new Cropper(image, {
      aspectRatio: 1 / 1,
      viewMode:2,
    });


    $(document).ready(function() {
      $('#file_preview').on('change', function(){

        if(!$('#file_preview').val()){
          return false;
        }
        user_file = (($('#file_preview').val().split('\\'))[2]);
        arr_user_file = user_file.split('.');
        len_arr = arr_user_file.length;
        ext = arr_user_file[len_arr-1].toUpperCase();
        arr_accept_ext = ['JPG','JPEG','JFIF','PNG','TIF','TIFF','GIF','BMP','WEBM'];

        if(arr_accept_ext.indexOf(ext) == -1){
          alerts('warning', 'Ошибка', 'Для выбора миниатюры сервиса выбирите файл JPG,JPEG,JFIF,PNG,TIF,TIFF,GIF,BMP,WEBM');
          return false;
        }

        altitude_change('div_select_preview', 400);
        altitude_change('bos_div', ($('#bos_div').height()+200));
        $('#action_div_file_preview').addClass('animate__fadeOutLeft');
        $('#action_div_file_preview_crop').addClass('animate__fadeInRight');
        $('#action_div_file_preview').removeClass('animate__fadeInLeft');
        $('#action_div_file_preview_crop').removeClass('animate__fadeOutRight');
        $('#action_div_file_preview_crop').show();
        cropper.reset();
        cropper.replace(URL.createObjectURL(event.target.files[0]));
        $($('#div_preview').prev()).css('height', 400);
      });

    });


    function return_select_file(){
      //altitude_change($('#div_form_1').height());
      $('#file_preview')[0].value = '';
      altitude_change('div_select_preview', 200);
      altitude_change('bos_div', ($('#bos_div').height()-200));
      $('#action_div_file_preview').addClass('animate__fadeInLeft');
      $('#action_div_file_preview_crop').addClass('animate__fadeOutRight');
      $('#action_div_file_preview').removeClass('animate__fadeOutLeft');
      $('#action_div_file_preview_crop').removeClass('animate__fadeInRight');
      $('#action_div_file_preview_crop').show();
    };

    function altitude_change(id_name, height_result){
      var initial_value = $('#'+id_name).height();

       if (height_result > initial_value) {
         let timerId = setTimeout(function tick() {
            //alert('tick');
            if (initial_value < height_result) {
              initial_value+=5;
              $('#'+id_name).height(initial_value);
            }
            if(initial_value >= height_result) {
              clearTimeout(timerId);
            }
            timerId = setTimeout(tick, 10);
          }, 50);
       } else {
         let timerId = setTimeout(function tick() {
            //alert('tick');
            if (initial_value > height_result) {
              initial_value-=5;
              $('#'+id_name).height(initial_value);
            }
            if(initial_value >= height_result) {
              clearTimeout(timerId);
            }
            timerId = setTimeout(tick, 10);
          }, 50);
       }
    };

    $('#btn_update_send').on('click', function(){

    canvas_img = cropper.getCroppedCanvas({ width: 600,
                              height: 600,
                              maxWidth: 2096,
                              maxHeight: 2096,
                              fillColor: '#fff' });
    if(!canvas_img){
        alerts('warning','','Необходимо добавить изображение');

        return false;
      }
    var temp_img = canvas_img.toDataURL("image/png");
    img_min = '';
    $.ajax({
      type: 'POST',
      async : false,
      url: 'https://<?php echo $_SERVER["SERVER_NAME"]; ?>/panel/profile/update_avatar.php',
      data: "action=send_preview&file_name="+($('#file_preview').val().split('\\'))[2].split('.')[0]+"&data_img="+btoa(temp_img),
      success: function(result) {
        if (result == '') {
          alerts('error', '', 'Ошибка загрузки фотграфии на сервер');

          return false;
        } else {
          arr_result = JSON.parse(result);
          if (arr_result["response"]) {
              window.location.href = "https://<? echo $_SERVER['SERVER_NAME']?>/panel/profile/";
          } else {
            alerts('error', 'Ошибка', arr_result["description"]);
          }

        }
      },
      error: function(jqXHR, textStatus) {
        alerts('error', '', 'Ошибка подключения');

        return false;
      }
    });

  });

  </script>




<?php include($_SERVER['DOCUMENT_ROOT'].'/assets/template/footer_panel.php');?>
