<html>
    <head>
        <meta http-equiv="Content-Type" content="charset=utf-8"/>
        <style>
            
            table
            {                   
                font-size: 12px;
                font-family: Helvetica;
            }
            
            .lineTable
            {                
                border: 1px solid black;
                border-collapse: collapse;
                height: 50px;
                margin-left: 38px;                
                padding: 1px;
                width: 92%;
                /*margin-top: 30px; */
                text-align: left;                                  
            }
            
            td
            {
                font-size: 12px;
                font-family: Helvetica;
                border:none;
            }
            
            th
            {
                
                font-family: "Helvetica", monospace;
                border: solid 1px black;
                font-size: 12px;
                font-family: Helvetica;
                text-align: center;
            }
            
            tr
            {
                border-bottom: 1px solid #000;
                font-size: 12px;
                font-family: Helvetica;
            }
            
            body
            {
                width: 100%;                
                margin-top: 270px;                
                text-align: justify;
                font-size: 12px;
                font-family: Helvetica;
            }
            
            .logo
            {
                float: left;
            }
            
            .sideBar
            {                                               
                float: left;
                height: 845px;
                margin-top: -330px;
                width: 22px;
            }
            
            .hrClass
            {
                float: right;
                margin-right: -70px;
                margin-top: 175px;
                width: 250px;
            }
            
            .signature
            {
                float: right;
                margin-right: -138px;
                margin-top:0px;
                width: 250px;
            }
           
            .lineTable { border: none; }            
            .lineTable tr {border: none;}
            .lineTable td { border-left: 1px solid #000; }            
            .lineTable tr:last-child{border-bottom: 1px solid #000;}            
            .lineTable th:last-child {border-left: 1px solid #000;}
            .lineTable td:last-child {border-right: 1px solid #000;}
            /*#content {padding-bottom: 180px; margin-top:0px}*/
            @page { margin: 0px; }
            #headerpreview
            {                
                height: 150px;
                left: 40px;
                padding: 8px;
                position: fixed;
                right: 60px;
                text-align: center;
                /*top: 20px;*/
            }
            #footerpreview {position: fixed; left: 35px; bottom: 45px; right: 0px; height: 100px; padding: 10px; width:100% }
            #footerpreview .page:after { content: counter(page, upper-roman); }
            .page-number:before { content: counter(page);}
            #signaturetr
            {                
               
                margin-left: 40px;
                padding: 8px;
                text-align: center;
                /*top: 20px;*/
            }
            .detailTable
            {
                margin: 0px 90px 25px 0;
            }
            
            .pageBottom td
            { 
                padding: 5px;
            }
            
            .bottomLine
            {
                margin-right: 30px;
                margin-left: 8px;
            }
            .headStyle{
            	padding-top: 10px;
            }
		    @media print {
		    	@font-face {
				  font-family: 'Roboto';
				  src: local('Roboto'), local('Roboto-Regular'), url(https://fonts.gstatic.com/s/roboto/v15/ek4gzZ-GeXAPcSbHtCeQI_esZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
				  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
				}
			}
        </style>

    </head>
    <?php
    	$paymentid=$feepaymentid;
    	$institution_details=$this->financemodel->fetchInstitutionDetails();
        $student_details=$this->financemodel->fetchStudentDetailfeelist($paymentid);
    ?>
    <body>
        <div>
            <div id="headerpreview" >
                <img class="logo headStyle" width="100px" src="https://images.g2crowd.com/uploads/product/image/detail/detail_1483704915/rubycampus.png">
                <h3 style="font-family: Roboto;color: #000000;margin-left: 40px; margin-top: 10px;text-align: justify-all;" class="headStyle allfontStyle">
                <b><?php echo $institution_details[0]['INSTITUTION_NAME'];?></b><br>
                <span style="font-size:10px;text-align:left;"><?php echo $institution_details[0]['ADDRESS'];?>,<?php echo $institution_details[0]['STATE'];?> <br><?php echo $institution_details[0]['PHONE_NO'];?></span>
                </h3>
                <!-- <h5>No: 28, Bajanai madam street, Pondicherry <br>0413-654 6542 | school@edu.com</h5> -->
                <table style="width: 100%;margin-top: 20px;">
                    <tr>
                        <td align="left" width="9%"><b>Student Name</b></td>
                        <td align="left" width="60.5%" style="text-align: justify">: <?php echo $student_details[0]['STUDENT_NAME'];?></td>
                        <td align="left" width="30%"><b>Admission Number</b></td>
                        <td align="right" width="20.5%" style="text-align: justify">: <?php echo $student_details[0]['ADMISSION_NO'];?></td>
                    </tr>
                    <tr>
                        <td align="left" width="20%"><b>Course</b></td>
                        <td align="left" width="30.5%" style="text-align: justify">: <?php echo $student_details[0]['COURSE_NAME'];?></td>
                        <td align="left" width="11%"><b>Batch</b></td>
                        <td align="right" width="20.5%" style="text-align: justify">: <?php echo $student_details[0]['BATCH_NAME'];?></td>
                    </tr>
                    <!-- <tr>
                        <td align="left" width="9%"><b>Pay Period</b></td>
                        <td align="left" width="30.5%" style="text-align: justify">:  <?php //echo $student_details[0]['GENERATION_DATE'];?></td>
                        <td align="left" width="20%"><b></b></td>
                        <td align="right" width="20.5%" style="text-align: justify"> </td>
                        
                    </tr> -->
                                      
                </table>
            </div>
            <div id="footerpreview">
                <div id="">
                    <table style="width: 100%; margin-left: 0px;padding: 8px;">
                        <tr>
                            <td align="left" width="50%"><b>Student Signature</b></td>
                           
                            <td align="left" width="11%"><b>Director Signature</b></td>
                            
                        </tr>
                    </table>
                </div>
                <hr size="1px" style="background-color: black;margin-left: 6px;margin-right: 30px;">
                <div class="detailTable">
                    <table class="pageBottom" width="120%">
                        <tbody>
                            <tr class="" style="font-family: Helvetica;">
                                <td style="font-size: 12px;width:21%" align:"right">Prepared By:&nbsp;  <span style="font-weight: normal;">Admin</span></td>
                                <td  style="font-size: 12px;width:20%">Approved By: &nbsp; <span style="font-weight: normal;">Admin</span> </td>
                                <td style="font-size: 12px;width:25%">Run Date: &nbsp; <span style="font-weight: normal;"><?php echo date('d-m-Y');?></span></td>
                                <td align="left" style="width:18%">Page No :&nbsp;  <span style="font-weight: normal !important;margin-top: 200px;"><script type="text/php">
                                    if ( isset($pdf) ) {
                                        $font = Font_Metrics::get_font("helvetica","regular");
                                        $pdf->page_text(552, 767, "{PAGE_NUM} of {PAGE_COUNT}", $font, 9.5, array(0,0,0));
                                    }
                                </script></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="content" style="margin-top: -85px;">
                <table class="lineTable">
                    <thead>
                        <tr>
                            <th  style="background-color: #E5E2D5; padding: 5px 5px 5px 5px;text-align: left; ">S.No</th>
                            <th style="background-color: #E5E2D5; padding: 5px 0px 5px 5px;text-align: right;">Fee Item &nbsp;</th>
                            <th  style="background-color: #E5E2D5; padding: 5px 0px 5px 5px;text-align: right;">Amount &nbsp;</th>
                            <th style="background-color: #E5E2D5; padding: 5px 0px 5px 5px;text-align: right;">Paid Amount &nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $pageCount=0;
                        // print_r($student_details);
                        foreach ($student_details[0]['feelist'] as $value) { $pageCount++;?>
                            <tr>
                                <td style="border: 1px solid black;"><?php echo $pageCount;?></td>   
                                <td style="border: 1px solid black;"><?php echo $value['ITEM_NAME'];?></td> 
                                <td style="border: 1px solid black;"><?php echo $value['AMOUNT'];?></td> 
                                <td style="border: 1px solid black;"><?php echo $value['PAID_AMOUNT'];?></td> 
                            </tr>
                       <?php } ?>
                    	
                    </tbody>                    
                </table>
            </div>
        </div>
    </body>
</html>
