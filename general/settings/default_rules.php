<?php
header('Content-type:application/json;charset=utf-8');

$json = json_decode('
{
"rules":
    {
      "view":
          {
            "all":false
          },
     "add":
          {
            "all":false
          },
     "edit":
          {
            "all":false
          },

     "delete":
          {
            "all":false
          }
    }
}
');
echo json_encode($json);

?>
