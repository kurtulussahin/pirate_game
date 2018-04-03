<?php

$OverboardHTML;
$FiftyPercentHTML;
$ProposerVoteHTML;

if($_POST['Overboard'] == "true")
{
	$OverboardHTML = true;
}else
{
	$OverboardHTML= false;
}

if($_POST['FiftyPercent'] == "true")
{
	$FiftyPercentHTML = true;
}else
{
	$FiftyPercentHTML = false;
}

if($_POST['ProposerVote'] == "true")
{
	$ProposerVoteHTML = true;
}else
{
	$ProposerVoteHTML= false;
}



	 
	
 $oyun = new Ship($OverboardHTML, $FiftyPercentHTML, $ProposerVoteHTML );
 $oyun->makeAProposal();
 
 
 
 
 //////////////////////////////////////////////////////////////////////////////////
 /////////////////////////         CLASS Ship     ////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////
 
class Ship {
	
	public $numberOfPrates;
	public $numberOfGolds;
	public $proposal = array();
	public $Overboard;
	public $isFiftyPercentEnough;
	public $isProposerVote;
	public $previousProposal= array();
	public $previousProposalAccepted;
		
	
	function __construct($Overboard, $FiftyPercent, $ProposerVote)
	{
		
		$this->numberOfPrates = 5;
		$this->numberOfGolds = 100;		
		$this->Overboard =  $Overboard;
		$this->isFiftyPercentEnough =  $FiftyPercent ;
		$this->isProposerVote = $ProposerVote;
		$this->previousProposal[1]= 100;
		
		
		
		
	}
	
	///////////////////////////////// FUNCTION makeAProposal /////////////////////////
	
	
	function makeAProposal()
	{
		
		
		$numberOfTurn = 1;
		
		$controlDeath = 0;
		$controlDeathRepetition = 0;
		
		
		/// FOR    to evaulate each prate ///
		
		for ($k = 1; $k <= ($this->numberOfPrates); $k++)
		
		{
			
			$purposeIncrease = 0;
			
			
			
			/////////////////Proposer wants to take 100///////////////////////
			for ($i = 1; $i <= $numberOfTurn ; $i++) 
		
			{
				$this->proposal[$i] = 0;
			
			}
		
			$this->proposal[$numberOfTurn] = $this->numberOfGolds;			
			///////////////////////////////////////////////////////////////
			
			
			$enoughVote = 0 ;
			$numberOfYesVote = 1;
			
			
			
			/////calculation of number of enough vote -  based on "proposer vote" and "fifty percent" options /////////////////
			if(  $this->isProposerVote == false)
			{
				$numberOfYesVote --;
				
				if($this->isFiftyPercentEnough == true)
				{
				$enoughVote = floor((($numberOfTurn - 1)+1)/2);
				
					  
				}else
				{
				$enoughVote = ((floor(($numberOfTurn - 1)/2)) +1);
				}
			}else
			{
			if($this->isFiftyPercentEnough == true)
			{
				$enoughVote = floor(($numberOfTurn+1)/2);
				
					  
			}else
			{
				$enoughVote = ((floor($numberOfTurn/2)) +1);
			}
			}
			///// END    calculation of number of enough vote /////////////////	
			
			
							  
							  
			$control =true; //if number of yes votes enough at first this turns false
			$checkPrateNumber = 0;  //it controls prate number for each turn 
			
			
			/////////// WHILE to find a proposal ///////////////////////
			while($control == true and $checkPrateNumber <= $numberOfTurn ) 
			{
			  
			  /////////////////////////////////////
			  if($checkPrateNumber == $numberOfTurn)
				{
					
					$this->proposal = $this->previousProposal;
					$this->proposal[$numberOfTurn] = $this->numberOfGolds;
					$numberOfYesVote ++ ;
					
					if($numberOfTurn > 1 and ($this->Overboard==true) )
					{
					$controlDeath ++;
					
					}
				   
				}else
			  	{				
				}
			//////////////////////////////////////
			
			
			
			  if($enoughVote == $numberOfYesVote)
			  {
				 
				  $control=false;
				  
				  for($i = 1; $i < (count($this->proposal)); $i++)
				 {
					 
				   $this->proposal[$numberOfTurn] = $this->proposal[$numberOfTurn] - $this->proposal[$i];
				   
				  }
					  
				  
				  
				  $this->previousProposal = $this->proposal;
				  
			  }
			  else
			  {
				  
				  
					  if ($this->Overboard == true) // each pirate would  prefer to throw another overboard, if all other results would otherwise be equal?
					  {
						  
						  
						   for($j = 1; $j <(count($this->proposal )); $j++)
						  {
							  
							  
							 if($this->previousProposal[$j] == $purposeIncrease)
							 {
								 
								$controlDeathRepetition ++;
								if( $controlDeath > 0)
								{
									
									$this->proposal[$j] = $this->previousProposal[$j];
									if($controlDeathRepetition == 2)
									{
										$controlDeath ++;
										$controlDeathRepetition == 2;
									}
									$controlDeath --;
									
									
									
								}else
								{
								$this->proposal[$j] = $this->previousProposal[$j] + 1;
								$controlDeathRepetition --;;
								}
								
								$numberOfYesVote ++ ;
								
							   
								
							   
								if($enoughVote == $numberOfYesVote)
								{
									
									$j = count($this->proposal);
									$this->previousProposal = $this->proposal;
								   
								}
							 }
							
						  }
						  
						  
							  $purposeIncrease ++;
							  
							  
					  }  else // each pirate would NOT  prefer to throw another overboard, if all other results would otherwise be equal?
					  {
						  
						   for($k = 1; $k <(count($this->proposal)); $k++)
						  { 
						      
							  
							  
							 if($this->previousProposal[$k] == $purposeIncrease)
							 {
								$this->proposal[$k] = $this->previousProposal[$k] ;
								
								$numberOfYesVote ++ ;
								
								
								
								
								if($enoughVote == $numberOfYesVote)
								{
									
									$k = count($this->proposal);
									
									$this->previousProposal = $this->proposal;
									
								}
								
								
							 }
							
						  }
					  }
					  					 
			  }
			  $checkPrateNumber ++ ;
			 
			
			}/////////// END WHILE to find a proposal ///////////////////////

			
				
							
			$numberOfTurn++;
			$purposeIncrease --;
			
					
			
			
			
		} /// END FOR    to evaulate each prate /// 
		
		echo "<table align=center>   <tr>";
		for ($i = $numberOfTurn-1; $i >= 1 ; $i--) 
	  	{
		  
		  echo "<td>" .$this->proposal[$i].  "</td> " ;
	  	}
				  
				  
		 echo "</tr> </table>";
		
	}
	///////////////////////////////// FUNCTION makeAProposal /////////////////////////
	
	 
}

//////////////////////////////////////////////////////////////////////////////////
 /////////////////////////  END    CLASS Ship    ////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////

?>
