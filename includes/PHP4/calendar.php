<?

/*	CSTruter PHP Calendar Control version 1.0
	Author: Christoff Truter

	Date Created: 3 November 2006
	Last Update: 29 December 2008

	e-Mail: christoff@cstruter.com
	Website: www.cstruter.com
	Copyright 2006 CSTruter				*/


include_once("controls.php");

class Calendar extends Controls
{
	/*private*/ var $year;		// Current Selected Year
	/*private*/ var $month;		// Current Selected Month
	/*private*/ var $day;		// Current Selected Day
	/*private*/ var $output;	// Contains the Rendered Calendar
	/*private*/ var $date;		// Contains the date preset via Constructor
	/*public*/  var $redirect;  	// Page to redirect to when a specific date is selected
	/*public*/  var $inForm;    	// Use Object in a form

	// Styles used - referenced from CSS - with their default values assigned

	/*public*/ var $currentDateStyle = "currentDate";		// Style for current date
	/*public*/ var $selectedDateStyle = "selectedDate";		// Style for selected dates
	/*public*/ var $normalDateStyle = "normalDate";			// Style for unselected dates
	/*public*/ var $navigateStyle = "navigateYear";			// Style used in navigation "buttons"
	/*public*/ var $monthStyle = "month";				// Style used to display month
	/*public*/ var $daysOfTheWeekStyle = "daysOfTheWeek";		// Styles used to display sun-mon
	

	// Constructor - Assign an unique ID to your instantiated object, if needed. Date Format = YYYY-MM-DD

	/*public*/ function Calendar($ID, $Date = NULL)
	{
 		$this->ID = $ID."_";
		$this->date = isset($Date) ? $Date: NULL;

		if (isset($_REQUEST[$this->UID('year')]))
		{
			$this->year = $_REQUEST[$this->UID('year')];
			$this->month = $_REQUEST[$this->UID('month')];
			$this->day = $_REQUEST[$this->UID('Day')];
		}
		else
		{
			if (isset($Date))
			{
				$DateComponents = explode("-",$Date);
				$this->year = $DateComponents[0];
				$this->month = $DateComponents[1];
				$this->day = isset($_REQUEST[$this->UID('Day')]) ? $_REQUEST[$this->UID('Day')] : $DateComponents[2];
			}
			else
			{
				$this->year = date("Y");
				$this->month = date("n");
				$this->day = date("j");
			}
		}
	}

	/*public*/ function Value()
	{
		$returnValue="";

		if (isset($_REQUEST[$this->UID('Day')]))
		{
			$returnValue = isset($this->day) ? $this->year.'-'.$this->month.'-'.$_REQUEST[$this->UID('Day')]: '';
		}
		else if (isset($_REQUEST[$this->UID('calendar')]))
		{
			$returnValue = $_REQUEST[$this->UID('calendar')];
		}
		else if (isset($this->date))
		{
			$returnValue = isset($this->day) ? $this->year.'-'.$this->month.'-'.$this->day: '';
		}
	
		return $returnValue;
	}

	// Render the calendar, and add it to a variable - needed for placing the object in a specific area in our output buffer

	/*public*/ function Output()
	{
		$days = 0;
		$this->redirect = isset($this->redirect) ? $this->redirect: $_SERVER['PHP_SELF'] ;

		if ($this->year > 2037) 
			$this->year = 2037;
		else if ($this->year < 1971) 
			$this->year = 1971;
		
		$total_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$first_spaces = date("w", mktime(0, 0, 0, $this->month, 1, $this->year));
		$currentday = $this->UID('Day');

		if (isset($this->inForm))
		{
			$CObjID = $this->UID('calendar');
			$DateString = ($this->Value()) ? '","'.$this->Value() : '';
			$this->output = '<script language="javascript">'."\n".'var '.$CObjID.' = new Calendar("'.$this->ID.$DateString.'");'."\n"
			.$CObjID.'.currentDateStyle = "'.$this->currentDateStyle.'";'."\n"
			.$CObjID.'.selectedDateStyle = "'.$this->selectedDateStyle.'";'."\n"
			.$CObjID.'.normalDateStyle = "'.$this->normalDateStyle.'";'."\n"
			.$CObjID.'.setStyles();'."\n"
			.'</script>'."\n"
			.'<input type="hidden" id="'.$CObjID.'" name="'.$CObjID.'" value="'.$this->Value().'"/>'."\n";
		}
		else $this->output = '';		

		$NavUrls = $this->UrlParams($this->UID('year').','.$this->UID('month').','.$this->UID('Day'));

		if (($this->month+1) > 12)
		{
			$nextMonth = 1;
			$prevMonth = $this->month -1;
			$nextYear = $this->year + 1;
			$prevYear = $this->year;
		}
		else
		{
			$nextMonth = $this->month+1;
			$prevMonth = $this->month-1;
			
			if ($prevMonth == 0)
			{
				$prevMonth = 12;
				$prevYear = $this->year - 1;
			}
			else
				$prevYear = $this->year;
				
			$nextYear = $this->year;
		}
		
		$this->output.= '<table class="calendar"><tr><td class="'.$this->navigateStyle.'"><a id="'.$this->UID('navigateback').'" class="'.$this->navigateStyle.'" href="'.$_SERVER['PHP_SELF'].
			'?'.$this->UID('month').'='.$prevMonth.'&'.$this->UID('year').'='.$prevYear.$NavUrls.'"><</a>
		    </td><td id="'.$this->UID('Month').'" colspan="5" class="'.$this->monthStyle.'">'.date("F", mktime(0, 0, 0, $this->month, 1, $this->year)).'&nbsp;'.$this->year.'
		    </td><td class="'.$this->navigateStyle.'"><a id="'.$this->UID('navigatenext').'" class="'.$this->navigateStyle.'" href="'.$_SERVER['PHP_SELF'].'?'.$this->UID('month').'='.$nextMonth.'&'.$this->UID('year').'='.$nextYear.$NavUrls.'">></a>
		    </td></tr><tr class="'.$this->daysOfTheWeekStyle.'"><td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td></tr>';
	
        	for ($Week=0;$Week<6;$Week++)
        	{
            	$this->output.= '<tr>';
		        
				for ($Day=0;$Day<7;$Day++)
            	{      
					$days++;
					$dDay = $days - $first_spaces;

					$CellID = $this->UID('item['.$days.']');

					if ($days > $first_spaces && ($dDay) < $total_days  + 1) 
					{
						$LinkID = $this->UID('hlink['.$days.']');
						$currentSelectedDay = '<td id="'.$CellID.'" class="'.$this->selectedDateStyle.'"><a id="'.$LinkID.'" class="'.$this->selectedDateStyle.'"';
						$CurrentDate = isset($_REQUEST[$currentday]) ? $_REQUEST[$currentday]: '';

						if ($CurrentDate == $dDay)	
						{
							$this->output.= $currentSelectedDay;
						}
						else
						{
							$this->output.='<td id="'.$CellID.'" class=';
							$this->output.= ($dDay==date("j") && $this->year==date("Y") && $this->month==date("n")) ? 
								'"'.$this->currentDateStyle.'"><a id="'.$LinkID.'" class="'.$this->currentDateStyle.'"' : 
								'"'.$this->normalDateStyle.'"><a id="'.$LinkID.'" class="'.$this->normalDateStyle.'"';						
						}

						$this->output.= 'href="'.$this->redirect.'?'.$currentday.'='.$dDay.$this->UrlParams($currentday).'">'.$dDay.'</a></td>';

					}
					else
					{
						$this->output.='<td id="'.$CellID.'" class="'.$this->normalDateStyle.'"></td>'."\n";
					}
				}

				$this->output.="</tr>";
        	}

		$this->output.= '</table>';

		return $this->output;
	}
}

?>