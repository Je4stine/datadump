<?php
$referral_link  = URL::to("stud/new?referred_by=$user->id");
?>
<div class="col-md-8 col-xs-6">
    <p><span style="font-size: xx-large;"><strong>Earn {{ env('REFERRAL_COMMISSION') }}%</strong></span> commission through our affiliate program.
        <br/>
        <div class="col-md-12">
        Affiliate Link: <p style="color:green;padding:5px;border:solid;border-width:0.2px;border-color:#00dd00;" name="link">{{ $referral_link }}</p>
    </div>
    <div class="row"></div>
    <h4>How to invite your friends and family and make profits!.</h4>
    <ul>
        <li>
            Share your affiliate program link to your friends and family via social media such as facebook, twitter, whatsapp, Instagram, and Google plus. You will earn a commission once your friend places an order and pays for it.
            <p> <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ $referral_link }}"><i class="fa fa-facebook fa-2x"></i> </a>&nbsp;
        <a target="_blank" href="https://twitter.com/home?status={{ $referral_link }}"><i class="fa fa-twitter fa-2x"></i> </a>&nbsp;
        <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $referral_link }}&title=Get%20Academi%20Help&summary=&source="><i class="fa fa-linkedin fa-2x"></i> </a>&nbsp;
        <a target="_blank" href="https://plus.google.com/share?url={{ $referral_link }}"><i class="fa fa-google-plus fa-2x"></i> </a>&nbsp;
        <a target="_blank" href="mailto:?&subject=Get Online Academic Assistance&body=Hey,%20Get%20High%20quality%20academic%20assignment%20and%20research%20help%20%0A%3Ca%20href=%22{{ $referral_link }}%22%3E{{ $referral_link }}%3C/a%3E"><i class="fa fa-envelope fa-2x"></i> </a>&nbsp;
    </p>
        </li>
        <li>
            Invite your friends via email using our email invitation invitation tool.<a href="{{ url('stud/affiliate/gmail') }}">Email Invites</a>

        </li>
        <li>
            Refer your friend using word of mouth. Once your friend places an order and pay’s for it, inform us via live chat or email admin@<?php echo $_SERVER['HTTP_HOST'] ?> for us to check and credit your account with the $40 commission. 

        </li>
    </ul>
    
    </p>
</div>
