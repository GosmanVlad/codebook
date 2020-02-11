<?php 
session_start();
require_once('includes/functions.php');
require_once('includes/mysql.php');
/***************************************/
echo"<title>Pagina negasita- ".WEBTITLE."</title>";
/***************************************/
Website_Header();
echo' <body><div id="all">';
include('modules/menu.php');
/***********************************************************/
echo'<div id="content">
        <div class="container" style="min-height:50%">
          <div id="error-page" class="col-md-8 mx-auto text-center">
            <div class="box">
              <p class="text-center"><a href="'.URL.'"><img src="'.URL.'img/minilogo2.png" alt="'.WEBNAME.'"></a></p>
              <h3>We are sorry - this page is not here anymore</h3>
              <h4 class="text-muted">Error 404 - Page not found</h4>
              <p class="buttons"><a href="'.URL.'" class="btn btn-template-outlined"><i class="fa fa-home"></i> Go to Homepage</a></p>
            </div>
          </div>
        </div>
      </div>';
/****************************************************************/
Footer();
echo'</div>';
AnotherScripts();
echo'</body>';
?>