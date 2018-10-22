<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

          <script src="https://res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/3.0.0/vconsole.min.js"></script>
  <script>
    var vConsole = new VConsole();
  </script>
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
   
    </head>
    <body>
        <button class ="send-btn">ยืนยัน</button></center>
                <div class="title m-b-md">
                   <div id="chartdiv"></div>
                </div>    
                
     <script>
           $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

                $(function () {
                      $('.send-btn').on('click', function(){
                                  // alert('user_id_line');
                // document.getElementById('us_id').innerHTML = user_id_line;
                                    var _token = $('input[name="_token"]').val(); 
                                    $.ajax({
                                        url:"{{route('warning')}}",
                                        method:"POST",
                                         data:{doctor_id:'1234', user_id_line:'U2dc636d2cd052e82c29f5284e00f69b9',text:'55555',_token:_token},
                                         
                                 })
                            });
                    
                });

    

    </script>
 
    </body>
    
</html>
