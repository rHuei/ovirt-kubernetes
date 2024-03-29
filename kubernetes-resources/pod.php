<?php
  require_once('function.php');
  $namespace = htmlspecialchars($_GET['namespace'], ENT_QUOTES, 'UTF-8');
  $pod = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
  $ch = curl_init();
  curl_setopt ( $ch, CURLOPT_URL, $apiEntryPoint."/api/v1/namespaces/".$namespace."/pods/".$pod );
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
  $output = curl_exec($ch);
  $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
  curl_close( $ch );
  if ( ( $http_code != "200" ) ) {
    http_response_code(404);
    die();
  } else {
    $obj = json_decode($output,true);
    $name = $obj['metadata']['name'];
    $namespace = $obj['metadata']['namespace'];
    $labels = $obj['metadata']['labels'];
    $createTime = $obj['metadata']['creationTimestamp'];
    $status = $obj['status']['phase'];
    $node = $obj['spec']['nodeName'];
    $ip = $obj['status']['podIP'];
    $containers = $obj['spec']['containers'];
    $conditions = $obj['status']['conditions'];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/patternfly/css/patternfly.min.css">
    <link rel="stylesheet" type="text/css" href="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/patternfly/css/patternfly-additions.min.css">
    <link rel="stylesheet" type="text/css" href="/ovirt-engine/webadmin/theme/00-ovirt.brand/common.css">
    <link rel="stylesheet" type="text/css" href="/ovirt-engine/webadmin/theme/00-ovirt.brand/webadmin.css">
    <script type="text/javascript" src="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./scripts/datatables.min.js"></script>
    <script type="text/javascript" src="/ovirt-engine/webadmin/theme/00-ovirt.brand/bundled/patternfly/js/patternfly.min.js"></script>
    <style>
      .GKGFBNLBERB {
        font-weight: bold;
        margin-bottom: 5px;
      }
      .GKGFBNLBCNB {
        overflow-x: auto;
        margin-top: -1px;
      }
      .GKGFBNLBEWB {
        position: inherit;
      }
      .GKGFBNLBGVB {
        padding-top: 10px;
      }
      .GKGFBNLBFOB {
        color: black;
      }
      .GKGFBNLBGOB {
        color:#777;
        word-wrap:break-word;
      }
      .block {
        display: block;
      }
      .content-view-pf-pagination .btn-pagination {
        display: -ms-flexbox;
        display: flex;
        margin: 0 0 0 10px;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 detailMainHeaderContainer">
          <div class="detailMainBreadcrumbs">
            <ol class="breadcrumb">
              <li class="active">Kubernetes</li>
              <li class="active"><a href="pods.html">Pods</a></li>
              <li class="active"><a href="javascript:;" style="font-size: 28px;" title="<?php echo $name; ?>"><?php echo $name; ?></a></li>
            </ol>
          </div>
          <div class="detailMainActionPanelContainer">
            <div class="toolbar-pf">
              <div class="toolbar-pf-actions">
                <div class="form-group toolbar-pf-filter" aria-hidden="true" style="display: none;"></div>
                <div class="form-group">
                  <button type="button" class="btn btn-default" id="ActionPanelView_Edit">Edit</button> 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 GKGFBNLBEWB">
          <ul class="nav nav-tabs nav-tabs-pf">
            <li class="active"><a data-toggle="tab" href="#general">General</a></li>
            <li><a data-toggle="tab" href="#container">Container</a></li>
            <li><a data-toggle="tab" href="#conditions">Conditions</a></li>
          </ul>
          <div class="container-fluid GKGFBNLBGVB">
            <div class="obrand_detail_tab tab-content">
              <div class="tab-pane fade in active" id="general">
                <div class="row">
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Name:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $name; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Namespace:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $namespace; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Label:</div>
                      </div>
                      <div class="col-xs-6">
                        <?php
                          foreach ($labels as $key=>$value){
                            echo "<span class=\"label label-primary\" style=\"display:inline-block;\">$key: $value</span>\n"; 
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Create Time:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $createTime; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Status:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $status; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">Node:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $node; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-4">
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="GKGFBNLBFOB">IP:</div>
                      </div>
                      <div class="col-xs-6">
                        <span class="GKGFBNLBGOB"><?php  echo $ip; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="container">
              <?php 
                foreach ($containers as $container) {
                  $c_name = $container['name'];
                  $c_image = $container['image'];
                  $c_env = $container['env'];
                  $c_command = $container['command'];
                  $c_args = $container['args'];
              ?>
                <div class="col-xs-6">
                  <div class="card-pf card-pf-accented">
                    <h2 class="card-pf-title"><?php echo $c_name; ?></h2>
                    <div class="card-pf-body">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="GKGFBNLBFOB">Image:</div>
                        </div>
                        <div class="col-xs-8">
                          <span class="GKGFBNLBGOB"><?php  echo $c_image; ?></span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="GKGFBNLBFOB">Environmental variables:</div>
                        </div>
                        <div class="col-xs-8">
                        <?php foreach ($c_env as $env) { ?>
                          <span class="GKGFBNLBGOB block"><?php  echo $env['name'].": <code>".$env['value']."</code>"; ?></span>
                        <?php } ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="GKGFBNLBFOB">Command:</div>
                        </div>
                        <div class="col-xs-8">
                        <?php foreach ($c_command as $command) { ?>
                          <span class="GKGFBNLBGOB block"><?php  echo $command; ?></span>
                        <?php } ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="GKGFBNLBFOB">Argument:</div>
                        </div>
                        <div class="col-xs-8">
                        <?php foreach ($c_args as $arg) { ?>
                          <span class="GKGFBNLBGOB block"><?php  echo $arg; ?></span>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              </div>
              <div class="tab-pane fade" id="conditions">
                <div class="table-responsive">          
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Last probe time</th>
                        <th>Last transition time</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($conditions as $condition) { ?>
                      <tr>
                        <td><?php echo $condition['type']; ?></td>
                        <td><?php echo $condition['status']; ?></td>
                        <td><?php echo $condition['lastProbeTime']; ?></td>
                        <td><?php echo $condition['lastTransitionTime']; ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="./scripts/k8s.js"></script>
  <script>
    $(document).ready(function(){
    });
  </script>
</html>
