<div class="modal fade bs-modal-sm" id="register_login" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <br>
            <div class="bs-example bs-example-tabs">
                <ul id="myTab" class="nav nav-tabs">
                    @if ($errors->has('login_email'))
                        <?php
                            $login_active = "active";
                            $register_active = "";
                            ?>
                        <li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
                        <li class=""><a href="#signup" data-toggle="tab">Register</a></li>
                        <li class=""><a href="#why" data-toggle="tab">Why?</a></li>
                        @else
                        <?php
                        $login_active = "";
                        $register_active = "active";
                        ?>
                        <li class=""><a href="#signin" data-toggle="tab">Sign In</a></li>
                        <li class="active"><a href="#signup" data-toggle="tab">Register</a></li>
                        <li class=""><a href="#why" data-toggle="tab">Why?</a></li>
                        @endif
                </ul>
            </div>
            <div class="modal-body">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in" id="why">
                        <p>We need this information so that you can be able to manage your academic papers well and also for us to communicate. Rest assured your information will not be sold, traded, or given to anyone.</p>
                        <p></p><br></p>
                    </div>
                    <div class="tab-pane fade {{ $login_active }} in" id="signin">
                        <form id="reg_form" class="form-horizontal" method="post">
                            <fieldset>
                            {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group{{ $errors->has('login_email') ? ' has-error' : '' }}">
                                    <label class="control-label" for="userid">Email</label>
                                    <div class="controls">
                                        <input required name="email" type="email" class="form-control" placeholder="" class="input-medium" required="">
                                        @if ($errors->has('login_email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('login_email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Password input-->
                                <div class="form-group">
                                    <label class="control-label" for="passwordinput">Password:</label>
                                    <div class="controls">
                                        <input required="" id="password" name="password" class="form-control" type="password" placeholder="********" class="input-medium">
                                    </div>
                                </div>
                                <!-- Button -->
                                <div class="control-group">
                                    <label class="control-label" for="signin"></label>
                                    <div class="controls">
                                        <button id="signin" name="signin" class="btn btn-success">Sign In</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="tab-pane {{ $register_active }} fade in" id="signup">
                        <form class="form-horizontal" method="post">
                            <fieldset>
                                {{ csrf_field() }}

                                <!-- Text input-->
                                <div class="control-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label" for="userid">Full Name:</label>
                                    <div class="controls">
                                        <input name="name" value="" class="form-control" type="text" class="input-large" required="">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                                @endif
                                    </span>
                                    </div>
                                </div>

                                <div style="display: none;" class="control-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                    <label class="control-label" for="userid">Country:</label>
                                    <div class="controls">
                                        <input disabled name="country" value="{{ $country }}" class="form-control" type="text" class="input-large" required="">
                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                                @endif
                                    </span>
                                    </div>
                                </div>

                                <div id="phone_group" class="control-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label class="control-label" for="userid">Phone Number:</label>
                                    <div class="controls">
                                        <input name="phone" value="" class="form-control" type="text" class="input-large" required="">
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                                @endif
                                    </span>
                                    </div>
                                </div>
                                <div class="control-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label" for="Email">Email:</label>
                                    <div class="controls">
                                        <input name="email" value="" class="form-control" type="email" class="input-large" required="">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                        @endif
                                    </div>
                                </div>
                                <!-- Password input-->
                                <div class="control-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label" for="password">Password:</label>
                                    <div class="controls">
                                        <input name="password" class="form-control" type="password" class="input-large" required="">
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                                @endif
                                    </span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="password">Confirm Password:</label>
                                    <div class="controls">
                                        <input name="password_confirmation" class="form-control" type="password" class="input-large" required="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="confirmsignup"></label>
                                    <div class="controls">
                                        <button id="confirmsignup" name="confirmsignup" class="btn btn-success">Sign Up</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var telInput = $("input[name='phone']");
    telInput.intlTelInput("loadUtils", "lib/libphonenumber/build/utils.js");
    telInput.intlTelInput();
    telInput.change(function(){
        checkValid();
    });
    function setCountry(){
        var countryData = $("input[name='phone']").intlTelInput("getSelectedCountryData");
        var country = countryData.name+"(+"+countryData.dialCode+")";
        $("input[name='country']").val(country);
    }
    $("#reg_form").submit(function(){
        checkValid();
    });
    function checkValid(){
        setCountry();
//       var phone = $("input[name='phone']").val();
//        if(isNaN(phone) && phone.length<15 && phone.length>6){
//            isValid = true;
//        }else{
//            isValid = false;
//        }
//
//        if(isValid){
//            console.log('valid');
//            $("#phone_group").removeClass('has-error');
//        }else{
//            $("#phone_group").addClass('has-error');
//            console.log('invalid');
//        }
//        return isValid;
    }
</script>

<style type="text/css">
    .prettyline {
        height: 5px;
        border-top: 0;
        background: #c4e17f;
        border-radius: 5px;
        background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    }

</style>