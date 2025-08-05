<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOCKER-WORKSHOP</title>

    <style>
        table,td,th,tr{
            white-space: nowrap !important;
        }
    </style>
</head>
<body>
    <div class="container table-responsive">
        <div class="container">
            <h1>DOCKER-WORKSHOP</h1>
            <?php
                if(Yii::$app->session->getFlash("type") != "logs"){
                        if(Yii::$app->session->hasFlash("statuscode")){
                            if(!(Yii::$app->session->getFlash("statuscode"))){
                                echo "
                                <div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                                    <strong>Success!</strong> operation ".Yii::$app->session->getFlash("type") . " " . Yii::$app->session->getFlash("con_id")." 
                                </div>";
                            } else {
                                echo "
                                <div class='alert alert-danger alert-dismissible'>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                                    <strong>UnSuccessful!</strong> operation ".Yii::$app->session->getFlash("type")."
                                </div>";
                            }
                        }
                }
            ?>
            <p></p>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>CONTAINER ID</th>
                <th>NAME</th>
                <th>STATUS</th>
                <th>IMAGE</th>
                <th>OPERATION</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $item){
                        $exp = explode(",", $item);
                        echo "
                            <tr>
                                <td>$exp[0]</td>
                                <td>$exp[1]</td>
                                <td>$exp[3]</td>
                                <td>$exp[5]</td>
                                <td>
                                    <a href='/docker-workshop/manager?con_id=".$exp[0]."&type=start' class='btn btn-success button'>start</a>
                                    <a href='/docker-workshop/manager?con_id=".$exp[0]."&type=stop' class='btn btn-warning button'>stop</a>
                                    <a href='/docker-workshop/manager?con_id=".$exp[0]."&type=restart' class='btn btn-primary button'>restart</a>
                                    <a href='/docker-workshop/manager?con_id=".$exp[0]."&type=rm' class='btn btn-danger button'>remove</a>
                                    <a href='/docker-workshop/manager?con_id=".$exp[0]."&type=logs' class='btn btn-info'>logs</a>
                                </td>
                            </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>
    
    <?php
    //to prevent showing logs part once there is nothing to show about logs
        if(Yii::$app->session->get("type") != "logs"){
            return;
        }
    ?>
    <div class="container">
        <h3>Docker Logs - <?= Yii::$app->session->getFlash("con_id")?></h3>
        <div class="container">
            <h4>ports</h4>
            <div>
                <?php if(Yii::$app->session->hasFlash("ports")): ?>
                    <?= implode("<br>", Yii::$app->session->getFlash("ports")) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <h4>volumes</h4>
            <div>
                <?php if(Yii::$app->session->hasFlash("volumes")): ?>
                    <?php 
                    $volumes = json_decode(implode("\n", Yii::$app->session->getFlash("volumes")), true);
                    foreach($volumes as $volume): ?>
                        <?= $volume['Source'] ?> → <?= $volume['Destination'] ?><br>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <h4>logs</h4>
            <div>
                <?php if(Yii::$app->session->hasFlash("logs")): ?>
                    <?= implode("<br>", Yii::$app->session->getFlash("logs")) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        let buttons = document.querySelectorAll(".button");
        buttons.forEach(item=>{
            item.addEventListener('click', (e)=>{
                if(!confirm("are you sure ? ")){
                    e.preventDefault();
                }
            })
        })
    </script>
</body>
</html>

