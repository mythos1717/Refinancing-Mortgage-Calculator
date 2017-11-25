<script src="/js/jquery.colorbox-min.js"></script>
<script src="/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/css/validationEngine.jquery.css" type="text/css"/>	
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
jQuery(document).ready(function(){
			// binds form submission and fields to the validation engine
			jQuery("#Form").validationEngine();
		});
</script>
<script type="text/javascript">

//Requires Wordpress  Print O Matic Plugin

   function PrintPartOfPage(dvprintid)
 {
      var prtContent = document.getElementById(dvprintid);
      var WinPrint = window.open('', '', 'letf=100,top=100,width=600,height=600');
      WinPrint.document.write(prtContent.innerHTML);
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      //WinPrint.close()   
 }

</script>



<!-- layout -->
<div id="layout" class="clearfix pagewidth">

	<?php themify_content_before(); // hook ?>
	<!-- content -->
	<div id="content" class="clearfix">

<?php
if (isset($_POST['submit'])) {
$OriginationDate = $_POST['date'];
$DaysToFirstPayment = $_POST['days'];
$FinanceAmount = $_POST['amount'];
$Rate = $_POST['rate'];
$Term = $_POST['term'];
$MonthlyLastPayment = $Term - 1;
$CalcMethod = $_POST['frequency'];
$ExtraPrincipal = $_POST['turbo'];
$ServiceCharge = 399;
$String_post = array(
   "OriginationDate"  => $OriginationDate,
   "DaysToFirstPayment"  => $DaysToFirstPayment,
   "FinanceAmount"  => $FinanceAmount,
   "Rate"  => $Rate,
   "Term"  => $Term,
   "CalcMethod"  => $CalcMethod,
   "DebitFee"  => 1.95,
   "ServiceCharge"  => 399,
   "ExtraPrincipal"  => $ExtraPrincipal,
   "DebitDateShift"  => 0
);
$String_post = '{"Calc":{"OriginationDate":"'.$OriginationDate.'","DaysToFirstPayment":"'.$DaysToFirstPayment.'","FinanceAmount":"'.$FinanceAmount.'","Rate":"'.$Rate.'","Term":"'.$Term.'","CalcMethod":"'.$CalcMethod.'","DebitFee":"1.95","ServiceCharge":"399","ExtraPrincipal":"'.$ExtraPrincipal.'","DebitDateShift":"0"}}';

//echo $String_post["OriginationDate"];

  $post = '';
  $post .= '&ApiKey=xxxxxxxxxxxxxxxxxxxxx';
  //$post .= '&Data={"Calc":{"OriginationDate":"10/5/2015","DaysToFirstPayment":"45","FinanceAmount":30000,"Rate":10,"Term":60,"CalcMethod":1,"DebitFee":1.95,"ServiceCharge":399,"ExtraPrincipal":25,"DebitDateShift":0}}';
  //$post .= '&Data=' . json_encode($String_post);
  $post .= '&Data=' . $String_post;
  
  
  //$test_working = '&Data={"Calc":{"OriginationDate":"10/5/2015","DaysToFirstPayment":"45","FinanceAmount":30000,"Rate":10,"Term":60,"CalcMethod":1,"DebitFee":1.95,"ServiceCharge":399,"ExtraPrincipal":25,"DebitDateShift":0}}';
  //$test_blank = '&Data=' . $String_post; 

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'xxxxxxxxxxxxx');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //curl_setopt($ch, CURLOPT_INTERFACE, 'xxxxxxxxx'); // insert your registered ip address
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
  curl_setopt($ch, CURLOPT_TIMEOUT, 90);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  $response = curl_exec ($ch);
  
	if(!curl_errno($ch)) {
		$result = json_decode($response,true);
		
		/**** Pretty View of Array Response ****/
		//print('<pre>');
		//print_r($result);
		//print('</pre>');
		//echo 'RESULT:'.$result['Response']['Payment'];
		//echo '<br />RESULT:'.$result['Response']['Schedule'][0]['monthly']['start_balance'];
		
		$BiweeklyLastPayment = $result['Response']['Payoff'] - 1;
		end($result['Response']['DebitDates']);
		$DebitDateNum = $key = key($result['Response']['DebitDates']) + 1;
		$BiweeklyFees = $DebitDateNum*1.95 + 399;
		$BiweeklyTotalFees = $BiweeklyFees + $result['Response']['Schedule'][$BiweeklyLastPayment]['biweekly']['total_interest'];
		$PaymentDate = $result['Response']['PaymentDate'];
		$PaymentDate = date("m/d/Y", strtotime($PaymentDate));
		$OrginationDate = date("m/d/Y", strtotime($OrginationDate));
		$FirstFullDebitDate = $result['Response']['DebitDate1'];
		$FirstFullDebitDate = date("m/d/Y", strtotime($FirstFullDebitDate));
		$FirstBiweeklyRecurring = $result['Response']['DebitDate2'];
		$FirstBiweeklyRecurring = date("m/d/Y", strtotime($FirstBiweeklyRecurring));
		$DebitDateKey = key($result['Response']['DebitDates']);
		
		
	} else {
		echo 'cURL Error No.'.curl_errno($ch).': '.curl_errno($ch).'<br />';
	}
	  curl_close ($ch);
	}

//echo $OriginationDate;


//{"Calc":{"OriginationDate":"","DaysToFirstPayment":"45","FinanceAmount":30000,"Rate":10,"Term":60,"CalcMethod":1,"DebitFee":1.95,"ServiceCharge":399,"ExtraPrincipal":25,"DebitDateShift":0}}
 
  
?> 

<div class="calculator">
    <form id="Form" name="Form" Method="Post">
	<label for="amount">Loan Amount:</label>
	<p>What is the total principal amount of the loan? <b>Example: 30000.00 </b></p>
        <input type="text" class="validate[required,custom[number]]" id="amount" name="amount" value="<?php echo $FinanceAmount; ?>">
		<label for="rate">Interest Rate:</label>
		<p>What is the annual interest rate for the loan? <b>Example: 5.00 </b></p>
        <input type="text" class="validate[required,custom[number]]" id="rate" name="rate" value="<?php echo $Rate; ?>">
		<label for="term">Loan Term:</label>
		<p>How many months do you have to pay off the loan? <b>Example: 72 </b></p>
        <input type="text" class="validate[required,custom[integer]]" id="term" name="term" value="<?php echo $Term; ?>">
		<label for="date">Origination Date:</label>
		<p>What is your purchase date/first day of loan? <b>Example: YYYY-MM-DD</b></p>
        <input type="text" class="validate[custom[date]] text-input" id="date" name="date" value="<?php if(isset($OriginationDate)){echo $OriginationDate;}else{echo $today = date("Y-m-d");} ?>">
		<label for="days">Days to First Payment:</label>
		<p>How many days do you have to make the first payment? <b>Example: 30</b></p>
        <input type="text" class="validate[required,custom[integer]]" id="days" name="days" value="<?php if(isset($DaysToFirstPayment)){echo $DaysToFirstPayment;}else{echo '45';} ?>">
		<label for="frequency">Debit Frequency:</label>
		<p>Please select your preferred debit frequency.</p>
        <select id="frequency" name="frequency">
            <option value="2" selected="selected">Biweekly Starting with Full Payment</option>
            <option value="1">Biweekly Debits Only</option>
            <option value="3">Semi-Monthly</option>
        </select>
		<label for="turbo">Turbo Pay:</label>
		<p>Extra amount added to each debit for faster loan payoff. <b>Example: 10.00</b></p>
        <input class="validate[custom[number]]" type="text" id="turbo" name="turbo" value="<?php if(isset($ExtraPrincipal)){echo $ExtraPrincipal;}else{echo '';} ?>">
        <input class="submit "type="submit" name="submit">
		<p>Scroll down to see the results.</p>
    </form>
	<div id="iePrint">
    <div id="results" class="resultsPrint">
	<h2 id="Title">
	Equity-4-U Comparison Chart for Equity-4-U<br>
	Turning the Dream of Ownership Into Reality... Sooner
	</h2>
	<div class="loan-info">
	<div id="left">
	<h4>Monthly</h4>
	<p id="MonthlyLoanAmount">$<?php echo $FinanceAmount; ?></p>
	<p id="MonthlyPayment">$<?php echo $result['Response']['Payment']; ?></p>
	<p id="MonthlyInterestRate"><?php echo $Rate; ?>%</p>
	<p id="MonthlyFinance">$<?php echo $result['Response']['Schedule'][$MonthlyLastPayment]['monthly']['total_interest']; ?></p>
	<p id="MonthlyFees">$0</p>
	<p id="MonthlyTotalFees">$<?php echo $result['Response']['Schedule'][$MonthlyLastPayment]['monthly']['total_interest']; ?></p>
	<p id="MonthlyEquity">$0</p>
	<p id="MonthlyTerms"><?php echo $Term; ?>&nbsp;</p>
	</div>
	<div id="middle">
	<h4>&nbsp;    </h4>
	<p>Loan Amount</p>
	<p>Payment</p>
	<p>Interest Rate</p>
	<p>Interest</p>
	<p>Fees (Enrollment Fee + $1.95 per debit)</p>
	<p>Total Finance Charge and Fees</p>
	<p>Equity Acceleration</p>
	<p>Term (months)</p>
	</div>
	<div id="right">
	<h4>Biweekly</h4>
		<p id="BiweeklyLoanAmount">$<?php echo $FinanceAmount; ?></p>
	<p id="BiweeklyPayment">$<?php echo $result['Response']['DebitAmount2']; ?>*</p>
	<p id="BiweeklyInterestRate"><?php echo $Rate; ?>%</p>
	<p id="BiweeklyFinance">$<?php echo $result['Response']['Schedule'][$BiweeklyLastPayment]['biweekly']['total_interest']; ?></p>
	<p id="BiweeklyFees">$<?php echo $BiweeklyFees; ?></p>
	<p id="BiweeklyTotalFees">$<?php echo $BiweeklyTotalFees; ?></p>
	<p id="BiweeklyEquity">$<?php echo $result['Response']['EquityBenefit']; ?></p>
	<p id="BiweeklyTerms"><?php echo $result['Response']['Payoff']; ?>&nbsp;</p>
	</div>
	</div>
	<div id="dates">
	<p id="LoanOrigin"><b>Loan Origination Date: </b><?php echo $OriginationDate; ?></p>
	<p id="LenderPayment"><b>Lender Payment Date: </b><?php echo $PaymentDate; ?></p>
	<p id="FirstFullDebit"><b>First Debit Date: </b><?php echo $FirstFullDebitDate; ?></p>
	<p id="FirstBiweeklyRecurring"><b>First Biweekly Recurring Debit Date: </b><?php echo $FirstBiweeklyRecurring; ?></p>
	</div>
	</div>
	<button type="button" class="btn btn-info btn-lg view-modal" style="margin-left:25px;" data-toggle="modal" data-target="#schedule-calendar">View Amortization Schedule and Debit Dates</button>
	<div id="info" class="resultsPrint" style="margin-left:25px;">
	<p>
	*Includes $1.95 ACH Fee</br>
	<!--**Program begins with one full monthly payment</br>-->
	</br>
	<b><u>Important Notes</b></u></br>
	All figures are for demonstration purposes only and shows <u>ESTIMATED</u> benefits.</br>
</br>
	Company Name will need your loan account number, once you receive this number from your lender please contact Company Name. If Company Name does not hear from you they will make at least two attempts to contact you. If you would like to view your account activity please contact Company Name for your personal ID and password.</br>
</br>
	YOU MUST CONTACT Company Name TO STOP DEBIT ACTIVITY!</br>
</br>
	I understand there is a fee of $399.00 for the program, plus a $1.95 ACH transaction fee for each debit.
	</p>
	</div>
	<div id="signature" class="resultsPrint" style="padding-left:25px;"><p>Customer Signature:</p>
	</div>
	<div id="datesigned" class="resultsPrint" ><p>Date:</p>
	</div>
	<p id="bizinfo" class="resultsPrint" style="margin-left:25px;">Company Name, 1234 Test Lane, AnyTown, STATE 00000</br>
(555) 555-5555 :: info@email.com</p>
</div>
	<div id="schedule-calendar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
	 <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <div id="schedule">
	<h3>Amoritization Schedule</h3>
	<h4 class="monthly-header">Paid Monthly
	</h4>
	<h4 class="biweekly-header">Paid Biweekly</h4>
	<h5>Month</h5>
	<h5 class="monthly-column-headings">Balance</h5>
	<h5 class="monthly-column-headings">Equity</h5>
	<h5 class="biweekly-column-headings">Balance</h5>
	<h5 class="biweekly-column-headings">Equity</h5>
		<?php 
for ($i = 0; $i <= $Term; $i++) {
	$m = $i+1;
	
	 if($i % 2 == 0 && $i != 0){
	   echo"<div class='colored'>
   <p class='month-column'>".$m.
   "</p>
   <p class='monthly-balance-column'>$".$result[Response][Schedule][$i][monthly][new_balance].
   "</p>
   <p class='monthly-equity-column'>$".$result[Response][Schedule][$i][monthly][total_equity]."</p>
   <p class='biweekly-balance-column'>$".$result[Response][Schedule][$i][biweekly][new_balance]."</p>
   <p class='biweekly-equity-column'>$".$result[Response][Schedule][$i][biweekly][total_equity]."</p></div>";
   }
   else{
   echo "<div class='row'>
    <p class='month-column'>".$m.
   "</p>
   <p class='monthly-balance-column'>$".$result[Response][Schedule][$i][monthly][new_balance].
   "</p>
   <p class='monthly-equity-column'>$".$result[Response][Schedule][$i][monthly][total_equity]."</p>
   <p class='biweekly-balance-column'>$".$result[Response][Schedule][$i][biweekly][new_balance]."</p>
   <p class='biweekly-equity-column'>$".$result[Response][Schedule][$i][biweekly][total_equity]."</p></div>";
   }
   
} 
?>
	</div>
	 <div id="calendar">
	 <h3>Debit Dates</h3>
	<h4 class="monthly-header">Debit Number</h4><h4 class="monthly-header">Date</h4>
	 <?php 
		for ($j = 0; $j <= $DebitDateKey; $j++){
			$n = $j+1;
			if($j % 2 == 0 && $j != 0){
			echo"<div class='colored'><p>".$n."</p>
			<p class='monthly-balance-column'>".date("m/d/Y", strtotime($result['Response']['DebitDates'][$j]))."</p></div>";
			}
			else
			{
				echo"<div class='row'><p>".$n."</p>
			<p class='monthly-balance-column'>".date("m/d/Y", strtotime($result['Response']['DebitDates'][$j]))."</p></div>";
				
			}
				
		}?>
	 </tbody>
	 </table> 
	 </div>
      </div>
    </div>
	
	 </div>
	 </div>
<div class="print-area">
<div>
<h5>Print Calculator Results</h5>
<?php echo do_shortcode( '[print-me target="#iePrint"]' ); ?>
</div>
<div>
<h5>Print Amortization Schedule</h5>
<?php echo do_shortcode( '[print-me target="#schedule"]' ); ?>
</div>
<div>
<h5>Print Debit Dates</h5>
<?php echo do_shortcode( '[print-me target="#calendar"]' ); ?>
</div>
</div>
</div>