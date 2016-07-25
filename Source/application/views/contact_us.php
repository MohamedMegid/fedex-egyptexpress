<?php
// Make the page validate
ini_set('session.use_trans_sid', '0');

// Create a random string, leaving out 'o' to avoid confusion with '0'
$char = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 4));

// Concatenate the random string onto the random numbers
// The font 'Anorexia' doesn't have a character for '8', so the numbers will only go up to 7
// '0' is left out to avoid confusion with 'O'
$str = rand(1, 7) . rand(1, 7) . $char;

// Begin the session
session_start();

// Set the session contents
$_SESSION['captcha_id'] = $str;
?>

<?php
if($_GET['w2l'] && $_GET['w2l'] == 'true')
{
   ?>
   <div class="container-fluid">
       <div class="container">
           <div class="alert alert-success" role="alert"><?php echo lang("Thank you, Your message has been sent successfully & an agent will get back to you asap."); ?></div>
       </div>
   </div>
<?php } ?>

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Our Contacts"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Contacts"); ?></li>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row margin-bottom-30">
        <div class="col-md-9 mb-margin-bottom-30">
            <?php
            if($branches)
            {
               $title = lang_db("title");
               $text = lang_db("text");
               ?>
               <div class="headline"><h2><?php echo lang("Contact Branches"); ?></h2></div>
               <div class="table-search-v2 margin-bottom-50">
                   <div class="table-responsive">
                       <table class="table table-bordered table-striped">
                           <thead>
                               <tr>
                                   <th><?php echo lang("Branch"); ?></th>
                                   <th><?php echo lang("Contacts"); ?></th>
                               </tr>
                           </thead>
                           <tbody>
                               <?php
                               foreach($branches as $branche)
                               {
                                  ?>
                                  <tr>
                                      <td><h3><?php echo $branche->$title; ?></h3></td>
                                      <td><h3><?php echo $branche->$text ?></h3></td>
                                  </tr>
                               <?php } ?>
                           </tbody>
                       </table>
                   </div>
               </div>
            <?php } ?>
            <div class="headline"><h2><?php echo lang("Contact Form"); ?></h2></div>
            <div class="ourform">
                <?php echo lang("contact_us_page"); ?>
                <?php
                $attributes = array(
                               'id'    => "sky-form",
                               'class' => "sky-form sky-changes-3"
                );
                echo form_open('home/contact_us', $attributes)
                ?>
                <fieldset>
                    <section>
                        <label class="label"><?php echo lang("Department"); ?></label>
                        <label class="select">
                            <i class="icon-append fa fa-tag"></i>
                            <select name="department" id="department" required>
                                <?php
                                foreach($departments as $dept)
                                {
                                   ?>
                                   <option value="<?php echo $dept->id; ?>"><?php echo $dept->department; ?></option>

                                <?php } ?>
                            </select>
                        </label>
                    </section>
                    <section>
                        <label class="label"><?php echo lang("Name"); ?></label>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" name="name" id="name">
                        </label>
                    </section>
                    <section>
                        <label class="label"><?php echo lang("E-mail"); ?></label>
                        <label class="input">
                            <i class="icon-append fa fa-envelope-o"></i>
                            <input type="email" name="email" id="email">
                        </label>
                    </section>
                    <section>
                        <label class="label"><?php echo lang("Telephone / Mobile"); ?></label>
                        <label class="input">
                            <i class="icon-append fa fa-phone"></i>
                            <input type="number" step="any"  minlength="8" maxlength="11" name="phone" id="phonee" >
                        </label>
                    </section>



                    <section>
                        <label class="label"><?php echo lang("Subject"); ?></label>
                        <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="subject" id="subject" required>
                        </label>
                    </section>

                    <section>
                        <label class="label"><?php echo lang("Message"); ?></label>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea rows="4" name="message" id="message" required></textarea>
                        </label>
                    </section>

                    <section>
                        <label class="label"><?php echo lang("Enter characters below:"); ?></label>
                        <label class="input input-captcha">
                            <img src="<?php echo site_url("../webroot/plugins/sky-forms/version-2.0.1/captcha/image.php?" . time()); ?>" width="100" height="32" alt="Captcha image" />
                            <input type="text" maxlength="6" name="captcha" id="captcha" required <?php
                            if($this->lang->lang() == "ar")
                            {
                               ?>style="text-align: left;"<?php } ?>>
                        </label>
                    </section>

                </fieldset>


                <footer>
                    <button type="submit" class="btn-u"><?php echo lang("Send message"); ?></button>
                </footer>
                <?php echo form_close(); ?>
            </div>
            <div class="web2lead" style="display: none;">
                <fieldset>
                    <form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST" class="sky-form sky-changes-3">
                        <input type=hidden name="oid" value="00D24000000aIN6">
                        <input type=hidden name="retURL" value="<?php echo site_url('home/contact_us?w2l=true'); ?>">

                        <section>
                            <label class="label" for="first_name"><?php echo lang('First Name'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  id="first_name" maxlength="40" name="first_name" size="20" type="text" required/><br>
                            </label>
                        </section>

                        <section>
                            <label for="last_name" class="label"><?php echo lang('Last Name'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  id="last_name" maxlength="80" name="last_name" size="20" type="text" required/><br>
                            </label>
                        </section>



                        <section>
                            <label for="email" class="label"><?php echo lang('Email'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  id="email" maxlength="80" name="email" size="20" type="email" required/><br>
                            </label>
                        </section>

                        <section>
                            <label for="phone" class="label"><?php echo lang('Phone'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  type="number" step="any"  minlength="8" maxlength="11"  name="phone" /><br>
                            </label>
                        </section>


                        <section>

                            <label class="label"><?php echo lang("Country:"); ?></label>
                            <label class="select">
                                <i class="icon-append fa fa-tag"></i>

                                <select  id="country_code" name="country_code"><option value="">--None--</option><option value="">--None--</option>
                                    <option value="AX">Aland Islands</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia, Plurinational State of</option>
                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="BN">Brunei Darussalam</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="TW">Chinese Taipei</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo</option>
                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Cote d&#39;Ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Cura</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="VA">Holy See (Vatican City State)</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran, Islamic Republic of</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KP">Korea, Democratic People&#39;s Republic of</option>
                                    <option value="KR">Korea, Republic of</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Lao People&#39;s Democratic Republic</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libyan Arab Jamahiriya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao</option>
                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="MD">Moldova, Republic of</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PS">Palestinian Territory, Occupied</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Reunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russian Federation</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="BL">Saint Barthmy</option>
                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin (French part)</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="SS">South Sudan</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syrian Arab Republic</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania, United Republic of</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                    <option value="VN">Viet Nam</option>
                                    <option value="VG">Virgin Islands, British</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
                                </select>
                            </label>

                        </section>


                        <section>
                            <label for="city" class="label"><?php echo lang('City'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  id="city" maxlength="40" name="city" size="20" type="text" required/><br>
                            </label>
                        </section>


                        <section>
                            <label for="street"><?php echo lang('Address'); ?></label>
                            <label class="textarea">
                                <textarea name="street"></textarea><br>
                            </label>
                        </section>


                        <section>
                            <label for="zip" class="label"><?php echo lang('ZIP'); ?></label>
                            <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input  id="zip" maxlength="20" name="zip" size="20" type="text" /><br>
                            </label>
                        </section>

                        <section>
                            <label for="description"><?php echo lang('Description'); ?></label>
                            <label class="textarea">
                                <textarea name="description"></textarea><br>
                            </label>
                        </section>


                        <input type="hidden" name="lead_source" value="Company Website">


                        <footer>
                            <button type="submit" class="btn-u"><?php echo lang("Send message"); ?></button>
                        </footer>

                    </form>
                </fieldset>
            </div>
        </div><!--/col-md-9-->






        <?php $this->load->view("short_contact"); ?>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->

<!-- Google Map -->
<div id="map" class="map">
</div>
<?php
if($branches)
{
   $title = lang_db("title");
   $text = lang_db("text");
   ?>
   <script type="text/javascript">
      jQuery(function ($) {
          // Asynchronously Load the map API
          var script = document.createElement('script');
          script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
          document.body.appendChild(script);
      });

      function initialize() {
          var map;
          var bounds = new google.maps.LatLngBounds();
          var mapOptions = {
              mapTypeId: 'roadmap'
          };

          // Display a map on the page
          map = new google.maps.Map(document.getElementById("map"), mapOptions);
          map.setTilt(45);

          // Multiple Markers
          var markers = [
   <?php
   foreach($branches as $branche)
   {
      ?>
                 ["<?php echo $branche->$title; ?>", <?php echo $branche->lat; ?>, <?php echo $branche->lng; ?>],
   <?php } ?>
          ];

          // Info Window Content
          var infoWindowContent = [
   <?php
   foreach($branches as $branche)
   {
      ?>
                 ["<div class='info_content'>" +
                   "<h3><?php echo $branche->$title; ?></h3>" +
                   "<div><?php echo $branche->$text; ?></div>" + "</div>"],
   <?php } ?>
          ];

          // Display multiple markers on a map
          var infoWindow = new google.maps.InfoWindow(), marker, i;

          // Loop through our array of markers & place each one on the map
          for (i = 0; i < markers.length; i++) {
              var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
              bounds.extend(position);
              marker = new google.maps.Marker({
                  position: position,
                  map: map,
                  title: markers[i][0]
              });

              // Allow each marker to have an info window
              google.maps.event.addListener(marker, 'click', (function (marker, i) {
                  return function () {
                      infoWindow.setContent(infoWindowContent[i][0]);
                      infoWindow.open(map, marker);
                  }
              })(marker, i));

              // Automatically center the map fitting all markers on the screen
              map.fitBounds(bounds);
          }

          // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
          var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
              this.setZoom(14);
              google.maps.event.removeListener(boundsListener);
          });

      }

   </script>
<?php }
?>

<script type="text/javascript">
   $(document).ready(function () {
       $("#phonee").keydown(function (e) {
           // Allow: backspace, delete, tab, escape, enter and .
           if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
               (e.keyCode == 65 && e.ctrlKey === true) ||
               // Allow: Ctrl+C
                 (e.keyCode == 67 && e.ctrlKey === true) ||
                 // Allow: Ctrl+X
                   (e.keyCode == 88 && e.ctrlKey === true) ||
                   // Allow: home, end, left, right
                     (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
                 }
                 // Ensure that it is a number and stop the keypress
                 if (((e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                     e.preventDefault();
                 }
             });
       });
</script>

