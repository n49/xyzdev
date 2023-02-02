<?php

  function email_html($data){
    $html = '<!doctype html>
    <html>
    <head>
    <title>Booking Confirmed - Thanks for storing with us</title>
    <meta charset="utf-8">

    <style type="text/css">

        /* FONTS */
        @media screen {
            /* latin-ext */
            @font-face {
              font-family: \'Poppins\';
              font-style: normal;
              font-weight: 400;
              src: url(https://fonts.gstatic.com/s/poppins/v8/pxiEyp8kv8JHgFVrJJfecnFHGPc.woff2) format(\'woff2\');
            }

            @font-face {
              font-family: \'Poppins\';
              font-style: normal;
              font-weight: 700;
              src: url(https://fonts.gstatic.com/s/poppins/v8/pxiByp8kv8JHgFVrLCz7Z1xlFd2JQEk.woff2) format(\'woff2\');
            }
        }

        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        /* RESET STYLES */
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px){
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }

            .heading {
                font-size: 40px !important;
                line-height: 56px !important;
            }

            .subheading {
                font-size: 24px !important;
                line-height: 24px !important;
            }
        }
        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] { margin: 0 !important; }

        /* RESPONSIVE TABLES */
        @media screen and (max-width: 600px) {
        	.responsive-table {
        		display: block;
        		width: 96% !important;
            }

            .responsive-two-table {
            	display: block;
                width: 96% !important;
            }

            .responsive-spacer {
            	display: block;
                width: 96% !important;
            }
        }

        @media screen and (min-width: 601px) {
        	.responsive-table {
        		width: 31% !important;
            }

            .responsive-two-table {
            	width: 45% !important;
            }

            .responsive-spacer {
                width: 6% !important;
            }
        }
    </style>
    </head>
    <body style="margin: 0 !important; padding: 0 !important;">
    <!-- PREHEADER TEXT FOR MESSAGE PREVIEW -->
    <div style="display:none;font-size:1px;color:#54565b;line-height:1px;font-family:\'Poppins\', sans-serif;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;">
    	Thanks for storing with XYZ Storage '.$data['location'].'. Please find your booking details below.
    </div>
    <!-- MAIN CONTENT TABLE -->
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <!-- HEADER -->
        <tr>
            <td bgcolor="#54565b" align="center" style="padding:32px 16px 32px 16px; background: linear-gradient(120deg, rgba(85,86,91,1) 70%, rgba(107,109,114,1) 70%);">
                <table cellspacing="0" cellpadding="0" border="0" width="96%" style="max-width: 600px;">
                    <tr>
                        <td>
                            <a href="https://xyzstorage.com" target="_blank">
                            <img alt="XYZ Storage" src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/logo.png" width="100" style="display: block; width: 200px; max-width: 200px; min-width: 200px; font-family: \'Poppins\', sans-serif; color: #ffffff; font-size: 16px;" border="0"/>
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- HERO -->
        <tr>
            <td bgcolor="#f5d93e" align="center" style="padding:0 8px 0 8px;">
                <table cellspacing="0" cellpadding="0" border="0" width="96%" style="max-width: 600px;">
                    <tr>
                    	<td style="height: 64px"></td>
                    </tr>
                    <tr>
                        <td style="background-repeat: no-repeat; background-position: center center; background-size: contain; color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 700; padding: 0px 0px 32px 0px; text-align: center;" valign="top">
                            <h1 class="heading" style="font-size: 72px; line-height: 84px; margin: 0px 0px 0px 0px;">thank you</h1>
                            <h2 class="subheading" style="font-size: 36px; margin: 0px 0px 0px 0px;">for storing with us</h2>
                        </td>
                    </tr>
                    <tr>
                    	<td style="height: 32px"></td>
                    </tr>
                    <tr>
                    <!-- First line of Intro Message -->
                    <tr>
                    	<td>
                    		<table cellspacing="0" cellpadding="0" border="0" width="100%" >
                                <tr>
                                	<td style="background: #ffffff; border-radius: 4px 4px 0px 0px; color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 400; padding: 16px 16px 0px 16px;">
                                		<p style="margin: 0px 0px 0px 0px;">Dear '.$data['first_name'].',</p>
                                	</td>
                                </tr>
                    		</table>
                    	</td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- LIGHT GREY BACKGROUND -->
        <!-- RESERVATION INFO -->
        <tr>
        	<td bgcolor="#f1f1f1" align="center" style="padding: 0px 8px 0px 8px;">
        		<table cellspacing="0" cellpadding="0" border="0" width="96%" style="max-width: 600px;">
                    <!-- INTRO MESSAGE -->
        			<tr>
        				<td style="background: #ffffff; border-radius: 0px 0px 4px 4px; color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 400; padding: 16px 16px 16px 16px;">
        					<p style="margin: 0px 0px 16px 0px;">Thanks for storing with XYZ Storage '.$data['location'].'.</p>
                            <p style="margin: 0px 0px 0px 0px;">Please find your booking details below.</p>
							<br />
							  <p>
								<strong>You have signed your lease agreement</strong>
							  </p>
							  <p>
								Please remember to bring your ID when you come to the facility for the first time. 
							  </p>
								<div style="font-family: \'Poppins\', sans-serif; padding: 16px 0px 16px 0px; text-align: center;">
								  <a href="'.$data['lease'].'" style="border-radius: 4px 4px 4px 4px; color: #54565b; font-weight: 700; padding: 16px; text-align: center; text-decoration: none; background-color: rgb(245, 217, 62);margin-right:16px;">
									view lease agreement
								  </a>
								  <a href="https://www.xyzstorage.com/customer-portal/" style="border-radius: 4px 4px 4px 4px; color: #54565b; font-weight: 700; padding: 16px 20px; text-align: center; text-decoration: none; background-color: rgb(245, 217, 62)">
									view customer portal
								  </a>
								</div>
        				</td>
        			</tr>
                    <!-- SPACER -->
                    <tr>
                    	<td style="color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 700; padding: 16px 0px 16px 0px; text-align: center;">
                    	</td>
                    </tr>
                    <!-- UNIT SIZE & MOVE IN DATE -->
                    <tr>
                    	<td style="background: #ffffff; border-radius: 4px 4px 4px 4px; color: #54565b; font-family: \'Poppins\', sans-serif; padding: 16px 16px 16px 16px;">
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <!-- UNIT SIZE -->
                                    <td style="padding: 0px 16px 0px 0px;">
                                        <img src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/unit_icon.png" alt="unit size" width="40" style="display: block; width: 40px; max-width: 40px; min-width: 40px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0"/>
                                    </td>
                                    <td valign="top">
                                        <h3 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">unit size</h3>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['size'].'</h2>
                                    </td>
                                    <!-- TABLE SPACER -->
                                    <td width="30%" style="min-width: 16px; max-width: 100px;">
                                    </td>
                                    <!-- MOVE IN DATE -->
                                    <td style="padding: 0px 16px 0px 0px;">
                                        <img src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/date_icon.png" alt="unit size" width="40" style="display: block; width: 40px; max-width: 40px; min-width: 40px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0"/>
                                    </td>
                                    <td valign="top">
                                        <h3 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">move in date</h3>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['date'].'</h2>
                                    </td>
                                </tr>
                            </table>
                    	</td>
                    </tr>
                    <!-- SPACER -->
                    <tr>
                    	<td style="padding: 16px 0px 16px 0px;">
                    	</td>
                    </tr>
                    <!-- LOCATION & MAP -->
                    <tr>
                    	<td style="background: #ffffff; border-radius: 4px 4px 4px 4px;">
                            <table cellspacing="0" cellpadding="0" border="0">
                            	<tr>
                                    <!-- MAP CONTAINER -->
                            		<td width="40%">
                            			<a href="https://goo.gl/maps/'.$data['location_map_code'].'">
                                            <img src="'.$data['location_map'].'" alt="map" width="100%" style="border-radius: 4px 0px 0px 4px; display: block; font-family: \'Poppins\', sans-serif; font-size: 16px; max-height: 220px" border="0"/>
                                        </a>
                            		</td>
                                    <!-- TABLE SPACER -->
                                    <td width="16px" style="min-width: 16px; max-width: 16px;">
                                    </td>
                                    <!-- LOCATION DETAILS -->
                                    <td>
                                    	<h3 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">XYZ STORAGE</h3>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['location'].'</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['address_1'].'</p>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['address_2'].'</p>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 10px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;"><a href="tel: '.$data['location_phone'].'">'.$data['location_phone'].'</a></p>
                                        <a href="https://goo.gl/maps/'.$data['location_map_code'].'" style="color: #5ac4ba; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;"><p>open in Google Maps</p></a>
                                    </td>
                            	</tr>
                            </table>

                    	</td>
                    </tr>
                    <!-- SPACER -->
                    <tr>
                    	<td style="padding: 16px 0px 16px 0px;">
                    	</td>
                    </tr>
                    <!-- RESERVATION DETAILS -->
                    <!-- RESERVATION DETAILS HEADER -->
                    <tr>
                        <td style="background: #6b6d72; border-radius: 4px 4px 0px 0px; padding: 8px 0px 8px 16px">
                            <table>
                                <tr>
                                    <td style="color: #ffffff; padding: 0px 8px 0px 0px;">
                                    	<img src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/lock-icon-greymatte.png" alt="unit size" width="16px" style="color: #ffffff; display: block; width: 16px; max-width: 16px; min-width: 16px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0">
                                    </td>
                                    <td>
                                        <h3 style="color: #ffffff; display: block; font-family: \'Poppins\', sans-serif; font-weight: 400; margin: 0px 0px 0px 0px; ">booking details</h3>
                                    </td>
                                </tr>
                            </table>
                        </td>

                    </tr>
                    <!-- RESERVATION DETAILS INFO -->
                    <tr>
                    	<td style="background: #ffffff; border-radius: 0px 0px 4px 4px; padding: 16px 16px 16px 16px">
                            <!-- RESERVATION DETAILS INFO TABLE -->
                    		<table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <!-- RESERVATION DETAILS LEFT COLUMN -->
                                	<td valign="top">
                                	    <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">name</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['full_name'].'</p>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">phone</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['phone'].'</p>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">email</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['email'].'</p>
                                	</td>
                                    <!-- TABLE SPACER -->
                                    <td width="30%" style="min-width: 16px; max-width: 100px;">
                                    </td>
                                    <!-- RESERVATION DETAILS RIGHT COLUMN-->
                                    <td valign="top">
                                         <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">unit size</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['size'].'</p>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">move in date</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['date'].'</p>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">location</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['address_1'].'</p>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 0px 0px; white-space: nowrap;">'.$data['address_2'].'</p>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;"><a href="tel: '.$data['location_phone'].'">'.$data['location_phone'].'</a></p>
                                        <h2 style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 700; margin: 0px 0px 0px 0px; white-space: nowrap;">monthly rent</h2>
                                        <p style="color: #54565b; font-family: \'Poppins\', sans-serif; font-size: 12px; font-weight: 400; margin: 0px 0px 16px 0px; white-space: nowrap;">'.$data['rent'].'</p>
                                    </td>
                                </tr>

                    		</table>
                    	</td>
                    </tr>
                    <!-- SPACER -->
                    <tr>
                    	<td style="padding: 16px 0px 16px 0px;">
                    	</td>
                    </tr>
                </table> <!-- END LIGHT GREY BACKGROUND -->

        <!-- WHITE BACKGROUND -->
        <!-- WHAT\'S NEXT -->
        <tr>
        	<td bgcolor="#ffffff" align="center" style="padding: 0px 8px 0px 8px;">
        		<table cellspacing="0" cellpadding="0" border="0" width="96%" style="max-width: 600px;">
                    <!-- WHAT\'S NEXT INTRO -->
                    <tr>
                    	<td style="color: #54565b; font-family: \'Poppins\', sans-serif; padding: 32px 0px 32px 0px;">
                    		<h2 style="font-weight: 700; margin: 0px 0px 16px 0px;">what\'s next?</h2>
                            <p style="font-weight: 400; margin: 0px 0px 0px 0px;">Here\'s what to expect between now and your move-in date:</p>
                    	</td>
                    </tr>
                    <!-- 1-2-3 STEPS -->
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <!-- MAKE FIRST PAYMENT -->
                                    <td class="responsive-table" valign="top" align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 400; padding: 16px 16px 16px 16px;">
                                                    <img src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/icon-payment-green.png" alt="make your first month\'s payment" width="80" style="display: block; width: 80px; max-width: 80px; min-width: 80px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">you\'ve made your </p>
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">first month\'s payment</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 16px 16px 16px;">
                                                    <p style="color: #54565b; font-weight: 400; margin: 0px 0px 0px 0px;">you’ve already made your payment to rent your storage unit</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <!-- SIGN YOUR LEASE AGREEMENT -->
                                    <td class="responsive-table" valign="top" align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 400; padding: 16px 16px 16px 16px;">
                                                    <img src="https://xyzstorage.com/wp-content/uploads/2020/11/icon@2x.png" alt="sign your lease agreement" width="80" style="display: block; width: 80px; max-width: 80px; min-width: 80px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">you\'ve signed your</p>
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">lease agreement</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 16px 16px 16px;">
                                                    <p style="color: #54565b; font-weight: 400; margin: 0px 0px 0px 0px;">click here to <a href="'.$data['lease'].'">view your lease agreement</a></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <!-- PACK AND MOVE -->
                                    <td class="responsive-table" valign="top" align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="color: #54565b; font-family: \'Poppins\', sans-serif; font-weight: 400; padding: 16px 16px 16px 16px;">
                                                    <img src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/pack_and_move_icon.png" alt="pack and move your belongings" width="80" style="display: block; width: 80px; max-width: 80px; min-width: 80px; font-family: \'Poppins\', sans-serif; font-size: 16px;" border="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">pack and move</p>
                                                    <p style="color: #54565b; font-weight: 700; margin: 0px 0px 0px 0px;">your belongings</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-family: \'Poppins\', sans-serif; padding: 0px 16px 16px 16px;">
                                                    <p style="color: #54565b; font-weight: 400; margin: 0px 0px 0px 0px;">do you need a moving truck or packing supplies? we got you covered.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SPACER -->
                    <tr>
                    	<td style="padding: 16px 0px 16px 0px;">
                    	</td>
                    </tr>
                </table>
        	</td>
        </tr>
        <!-- LIGHT GREY BACKGROUND -->
        <tr>
            <td bgcolor="#f1f1f1" align="center" style="padding:32px 16px 32px 16px;">
                <table cellspacing="0" cellpadding="0" border="0" width="89%" style="max-width: 600px;">
                    <!-- 1-2 MARKETING CARDS -->
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <!-- PACKING SUPPLIES -->
                                    <td class="responsive-two-table" valign="top" align="center" style="background: #ffffff; border-radius: 8px 8px 8px 8px; color: #54565b; font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                            	<td valign="top" align="center">
                                                    <a href="https://www.boxestoronto.ca">
                                            		<img src="https://www.xyzstorage.com/wp-content/uploads/2020/05/img-packing-supplies.png" alt="packing supplies" style="width: 100%;"/>
                                                    </a>
                                            	</td>
                                            </tr>
                                            <tr>
                                            	<td style="font-family: \'Poppins\', sans-serif; padding: 16px 16px 16px 16px;">
                                            		<h3 style="color: #54565b; margin: 0px 0px 16px 0px; text-align: center;">packing supplies</h3>
                                                    <p style="color: #54565b; margin: 0px 0px 0px 0px;">we deliver packing supplies right to your door to ensure your move is smooth and all of your items are properly stored</p>
                                            	</td>
                                            </tr>
                                            <tr>
                                            	<td style="font-family: \'Poppins\', sans-serif; padding: 16px 0px 16px 0px; text-align: center;">
                                            		<a href="https://www.boxestoronto.ca" style="border: 2px solid #54565b; border-radius: 4px 4px 4px 4px; color: #54565b; font-weight: 700; padding: 4px 16px 4px 16px; text-align: center; text-decoration: none">
                                            			shop now
                                            		</a>
                                            	</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <!-- SPACER -->
                                    <td class="responsive-spacer" style="height: 16px;">

                                    </td>
                                    <!-- TRUCK RENTAL -->
                                    <td class="responsive-two-table" valign="top" align="center" style="background: #ffffff; border-radius: 8px 8px 8px 8px; color: #54565b; font-family: \'Poppins\', sans-serif; padding: 0px 0px 16px 0px;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                            	<td valign="top" align="center">
                                                    <a href="https://www.xyzstorage.com/services/truck-rental/">
                                                        <img src="https://www.xyzstorage.com/wp-content/uploads/2020/05/img-truck-rental.png" alt="truck rental" style="width: 100%;"/>
                                                    </a>

                                            	</td>
                                            </tr>
                                            <tr>
                                            	<td style="font-family: \'Poppins\', sans-serif; padding: 16px 16px 16px 16px;">
                                            		<h3 style="color: #54565b; margin: 0px 0px 16px 0px; text-align: center;">truck rental</h3>
                                                    <p style="color: #54565b; margin: 0px 0px 0px 0px;">our fleet of trucks are conveniently located at every facility for hassle-free move in. book yours today!</p>
                                            	</td>
                                            </tr>
                                            <tr>
                                            	<td style="font-family: \'Poppins\', sans-serif; padding: 16px 0px 16px 0px; text-align: center;">
                                            		<a href="https://www.xyzstorage.com/services/truck-rental/" style="border: 2px solid #54565b; border-radius: 4px 4px 4px 4px; color: #54565b; font-weight: 700; padding: 4px 16px 4px 16px; text-align: center; text-decoration: none">
                                            			book now
                                            		</a>
                                            	</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- OUTRO MESSAGE -->
                    <tr>
                    	<td style="color: #54565b; font-family: \'Poppins\', sans-serif; padding: 16px 0px 16px 0px; text-align: center">
                    		<h3 style="margin: 0px 0px 16px 0px">have a question?</h3>
                            <p style="margin: 0px 0px 0px 0px"><a href="https://www.xyzstorage.com/help-center/" target="_blank" style="color: #5ac4ba">check our help center</a> for frequently asked questions or speak to a customer service representative by calling <a href="tel:+18663101999" target="_blank" style="color: #5ac4ba">1-866-310-1999</a></p>
                    	</td>
                    </tr>
                </table>


            </td>
        </tr>
        <!-- FOOTER -->
        <tr>
            <td bgcolor="#54565b" align="center" style="padding:32px 16px 32px 16px;">
                <table cellspacing="0" cellpadding="0" border="0" width="89%" style="max-width: 600px;">
                    <tr>
                        <td>
                            <a href="https://xyzstorage.com" target="_blank">
                            <img alt="XYZ Storage" src="https://www.xyzstorage.com/wp-content/themes/custom-theme/img/email/logo.png" width="120" style="display: block; width: 120px; max-width: 120px; min-width: 120px; font-family: \'Poppins\', sans-serif; color: #ffffff; font-size: 16px;" border="0"/>
                            </a>
                        </td>
                        <td>
                            <p style="color: #ffffff; font-family: \'Poppins\', sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; text-align: right">© '.date("Y").' XYZ Storage</p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>


    </body>
    </html>
';
    return $html;
  }
?>
