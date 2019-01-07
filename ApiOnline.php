<?php
	//Change Bellow >>>
	$apicustom   = 	'RGAPI-968b2ee2-46f1-4df1-8b35-746ef7670c7f'; //Put your Api Key
	$region      =  'br1';	// Select your region
    $nick 		 = 	'maziiinho'; // Select your Nick
	$patch       =  '8.24.1'; // Change for Current patch
	
	//Getting User Information from Riot Api
	$conexaoriot   =	"https://$region.api.riotgames.com";
    $profile 	   = 	json_decode(file_get_contents("$conexaoriot/lol/summoner/v3/summoners/by-name/$nick?api_key=$apicustom"));
    $positions 	   = 	json_decode(file_get_contents("$conexaoriot/lol/league/v3/positions/by-summoner/$profile->id?api_key=$apicustom"));
    $masteryl 	   = 	json_decode(file_get_contents("$conexaoriot/lol/champion-mastery/v3/scores/by-summoner/$profile->id?api_key=$apicustom"));
    $ranked 	   = 	json_decode(file_get_contents("$conexaoriot/lol/league/v3/positions/by-summoner/$profile->id?api_key=$apicustom"));
    $mastery 	   =	json_decode(file_get_contents("$conexaoriot/lol/champion-mastery/v3/champion-masteries/by-summoner/$profile->id?api_key=$apicustom"));
	$serverimagens =    "https://cdn.communitydragon.org/$patch/";

	//Exchange Champions ID for Name
	$champsname = 	json_decode(file_get_contents('champs.json'), true);
	foreach($champsname['data'] as $champion){ $championIdToName[$champion['key']] = $champion['name']; }
	
	//Summoner Information
	echo "<img src='",$serverimagens,"profile-icon/",$profile->profileIconId,"' width='150px' height='150px'><br>";
	echo "Nick: $profile->name<br>"; 
    echo "level: $profile->summonerLevel<br>";
    echo "id:", $profile ->accountId,"<br>";
    echo "ID: $profile->id<br>";
	echo "Mastery level: $masteryl<br>";
	
	//Leagues Information
    if ($ranked[1]->queueType =='RANKED_SOLO_5x5'){
        echo "<table border='1'><tr><th>Solo:";
	}else{
		echo"<table border='1'><tr><th>Flex:";
}
    echo $ranked[1]->tier,$ranked[1]->rank,"</th></tr>";


    echo "<tr><th>Wins",$ranked[1]->wins,"</th>";
    echo "<th>Loses",$ranked[1]->losses,"</th></tr></table>";
    if ($ranked[0]->queueType =='RANKED_SOLO_5x5'){
		echo "<table border='1'><tr><th>Solo:";
    }else{
		echo"<table border='1'><tr><th>Flex:";
}
    echo $ranked[0]->tier,$ranked[0]->rank,"</th></tr>";
    echo "<tr><th>Wins",$ranked[0]->wins,"</th>";
    echo "<th>Loses",$ranked[0]->losses,"</th></tr></table>";
    
	echo "<table border='1'><tr><th>Mastery:</th></tr>";
	for($x=0;$x<3;$x++)
    echo "<tr><th><img src='",$serverimagens,"champion/".$mastery[$x]->championId."/square'></th><th>",$mastery[$x]->championPoints,"</th></tr>";
 
	echo "</table><br>";
	
	

	//Match History
	for($m=0;$m<5;$m++){
		$partrecent  = 	json_decode(file_get_contents("$conexaoriot/lol/match/v3/matchlists/by-account/$profile->accountId/?api_key=$apicustom"),true);
		$part 		 = 	json_decode(file_get_contents("$conexaoriot/lol/match/v3/matches/".$partrecent['matches'][$m]['gameId']."?api_key=$apicustom"));
		echo "<table class='tablebans' border='1'><tr><td>";
		for($t=0;$t<=1;$t++){
			for($b=0;$b<5;$b++){
				echo "<img class='bans' src='",$serverimagens,"champion/",$championIdToName[$part->teams[$t]->bans[$b]->championId],"/square'>";}
			echo "</td><td>";}
		echo "</tr><table class='picks' border='1'>";
			
		$t2 = 5;
		for($t1=0;$t1<5;$t1++){
		echo "<tr><td><img src='",$serverimagens,"champion/",$championIdToName[$part->participants[$t1]->championId],"/square'></td>
		<td>",$part->participantIdentities[$t1]->player->summonerName,"</td>
		<td>",$part->participants[$t1]->stats->kills,"/",$part->participants[$t1]->stats->deaths,"/",$part->participants[$t1]->stats->assists,"</td>
		
		<td>",$part->participants[$t2]->stats->kills,"/",$part->participants[$t2]->stats->deaths,"/",$part->participants[$t2]->stats->assists,"</td>
		<td>",$part->participantIdentities[$t2]->player->summonerName,"</td>	
		<td><img src='",$serverimagens,"champion/",$championIdToName[$part->participants[$t2]->championId],"/square'></td></tr>";
		
		$t2++;}}

	?>