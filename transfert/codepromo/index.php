<?php

?>

    <html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

    </head>
    <body>
    <div class="container">

        <h1>Lexel</h1>
        <div class="row">
            <button id="import" class="btn btn-primary">Import Coupon Code</button>

        </div>


        <div id="list">

        </div>
        <script>
            $("#import").click(function(){
                getCustomer();
            });


            function getCustomer(){
                $.ajax({url: "traitement.php",type:"POST",data:{target:"TraitementApp"}, success: function(result){
                    $("#list").html(result);
                }});

                setTimeout(function(){getCustomer()},3000);

            }


        </script>
    </div>
    </body>
    </html>

<?php

?>