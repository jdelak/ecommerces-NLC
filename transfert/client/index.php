<?php

?>

<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">

    <h1>Lexel ecommerce</h1>
    <div class="row">
        <button id="import" class="btn btn-primary">Import Customer</button>

    </div>
    <div class="row">
        <button id="import2" class="btn btn-primary">Import Group</button>

    </div>
    <hr>
    <div class="row">
        <button id="update_via_gesco" class="btn btn-primary">update customer via GESCO </button>

    </div>

    <div id="list">

    </div>
    <script>
        $("#import").click(function(){
            getCustomer();
        });
        $("#import2").click(function(){
            getCustomerGroup();
        });
        $("#update_via_gesco").click(function(){
            updateCustomerViaGesco();
        });

        function getCustomer(){
            $.ajax({url: "traitement.php",type:"POST",data:{target:"TraitementApp"}, success: function(result){
                $("#list").html(result);
            }});

            //setTimeout(function(){getCustomer()},3000);

        }

        function getCustomerGroup(){
            $.ajax({url: "traitement.php",type:"POST",data:{target:"CusGroupApp"}, success: function(result){
                $("#list").html(result);
            }});

            //setTimeout(function(){getCustomerGroup()},3000);

        }

        function updateCustomerViaGesco(){
            $.ajax({url: "traitement.php",type:"POST",data:{target:"updateCustomerViaGesco"}, success: function(result){
                $("#list").html(result);
            }});

            //setTimeout(function(){getCustomerGroup()},3000);

        }
    </script>
</div>
</body>
</html>

<?php

?>