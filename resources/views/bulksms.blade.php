<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bulk SMS with Twilio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        #ajax-loading{
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1100;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center mt-5">Send SMS</h3>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form action="/bulksms" method="post" id="smsForm">
                    @csrf
                    <input type="hidden" name="type" id="msg_type" value="" />
                    <div class="form-group row">
                        <div class="col-md-6">                            
                            <label for="start_number">Start Number</label>
                            <input type="text" name="start_number" class="form-control" placeholder="+60174651418" />
                            @error('start_number')
                                <div class="invalid-feedback text-left d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">                            
                            <label for="end_number">End Number</label>
                            <input type="text" name="end_number" class="form-control" placeholder="+60174651418" />
                            @error('end_number')
                                <div class="invalid-feedback text-left d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Message"></textarea>
                        @error('message')
                            <div class="invalid-feedback text-left d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <button type="button" id="btn_sms" class="btn btn-primary mr-3">Send SMS</button>
                    {{-- <button type="button" id="btn_whatsapp" class="btn btn-primary mr-3">Send WhatsApp Msg</button> --}}
                    <a href="/export" class="btn btn-info">Export Activity</a>
                </form>
            </div>
        </div>
    </div>    
    <div id="ajax-loading" class="text-center">
        <img class="mx-auto" src="{{asset('images/loader.gif')}}" width="70" alt="" style="margin:45vh auto;">
    </div> 
    <script>
        $(document).ready(function(){
            $("#btn_sms").click(function (e) {
                e.preventDefault();
                $("#msg_type").val('sms');
                $("#ajax-loading").fadeIn();
                $.ajax({
                    url: '/bulksms',
                    data: $('#smsForm').serialize(),
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        $("#ajax-loading").fadeOut();
                        alert(data.count + ' messages sent!');
                    },
                    error: function(data) {
                        $("#ajax-loading").fadeOut();
                        alert('Something went wrong');
                    }
                });
            });
            $("#btn_whatsapp").click(function (e) {
                e.preventDefault();
                $("#msg_type").val('whatsapp');
                $("#ajax-loading").fadeIn();
                $.ajax({
                    url: '/bulksms',
                    data: $('#smsForm').serialize(),
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {  
                        $("#ajax-loading").fadeOut();                      
                        alert(data.count + ' messages sent!');
                    },
                    error: function(data) {
                        $("#ajax-loading").fadeOut();
                        alert('Something went wrong');
                    }
                });
            });
        });
    </script>
</body>
</html>