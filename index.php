<html>
<style>
/* Yeah I copy pasted this from w3schools. Wutcha gonna do about it? */
#SearchResults {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#SearchResults td, #customers th {
  border: 1px solid #ddd;s
  padding: 8px;
}

#SearchResults tr:nth-child(even){background-color: #f2f2f2;}

#SearchResults th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>

<script>
// LOGIN & REGISTRATION STUFF
function HandleLoginResponse(response)
{
	var text = JSON.parse(response);	
	document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
}

function SendLoginRequest()
{
	username = document.getElementById("usr").value;
	password = document.getElementById("psw").value;
	var request = new XMLHttpRequest();
	request.open("POST","login.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange= function ()
	{
		if ((this.readyState == 4)&&(this.status == 200))
		{
			HandleLoginResponse(this.responseText);
		}		
	}
	request.send("type=login&uname="+username+"&pword="+password); // request we're sending thru rabbit?
}


// API Search stuff
function SendRequest(url) // Send the request to the API
{
	var request = new XMLHttpRequest();
	request.open("GET", url, true);
	request.onreadystatechange = function()
	{
		if(this.readyState==4 && this.status==200)
		{
			HandleResponse(this.responseText)
		}
	}
	request.send();
}


function HandleResponse(response) // Handle the data we got from the API
{
	var data = JSON.parse(response); 	// parse the response into json array: data
	console.log(data);			// print to console for testing
	var txt = "";				// The text variable we'll be manipulating to insert our data into the page
	
	txt += "<table id='SearchResults' border='1'>"
	
	if (data.drinks)
	{
		for (drink in data.drinks)
		{	
			txt += "<tr><th>"+drink+"</th></tr>";	// The obj # we're on, usually a drink
			for (obj in data.drinks[drink]) 
			{	
				if (data.drinks[drink][String(obj)] != null) // If there's no info, don't include it
				{
					txt += "<tr><td>"+obj+"</td><td>"+data.drinks[drink][String(obj)]+"</td></tr>"; // Insert keys & values for obj #
				}
			}
		}
	}
	else if (data.ingredients)
	{
		for (ingredient in data.ingredients)
		{
			for (obj in data.ingredients[ingredient])
			{
				txt += "<tr><td>"+obj+"</td><td>"+data.ingredients[ingredient][String(obj)]+"</td></tr>"; // Insert keys & values for
			}
		}
	}
	
	
	txt += "</table>"    
      	document.getElementById("searchResponse").innerHTML = txt;
}

// SEARCH FUNCTIONS
// Do a search - Get info from HTML then send request
function DoSearch() 
{
	var type = document.getElementById("searchType").value;		// the type of search
	var input = document.getElementById("mySearch").value;		// what the user typed in the search input
	var url = "https://www.thecocktaildb.com/api/json/v2/9973533/search.php?" + type + "=" + input; // the url to use to for the request, using input
	SendRequest(url);
}

function DoFilter()
{
	var type = document.getElementById("filterType").value;		// the type of search
	var input = document.getElementById("myFilter").value;		// what the user typed in the search input
	var url = "https://www.thecocktaildb.com/api/json/v2/9973533/filter.php?" + type + "=" + input; // the url to use to for the request, using input
	SendRequest(url);
}

function DoList()
{
	var type = document.getElementById("listType").value;		// the type of search
	var url = "https://www.thecocktaildb.com/api/json/v2/9973533/" + type; // the url to use to for the request, using input
	SendRequest(url);
}

function DoLookup()
{
	var type = document.getElementById("lookupType").value;		// the type of search
	var input = document.getElementById("myLookup").value;		// what the user typed in the search input
	var url = "https://www.thecocktaildb.com/api/json/v2/9973533/lookup.php?" + type + "=" + input; // the url to use to for the request, using input
	SendRequest(url);
}

// Clear search results from the page - Not super necessary, but tidy
function ClearResults()
{
	document.getElementById("searchResponse").innerHTML = "";
}
</script>


<body>
<h3>Search</h3>
<p>Lookup cocktails or ingredients</p>
<select name="search type" id="searchType">
<option value="s">Cocktail</option>
<option value="f">First letter</option>
<option value="i">Ingredient</option>
</select>
<input type="text" placeholder="Enter your search keywords" id="mySearch" />
<button onclick="DoSearch()">Search</button>

<hr>

<!--
Filter search can mainly be used for multi-ingredient searches... 
Might substitute the ingredient search for search.php with this?

Filter by multi-ingredient (only available to $2+ Patreon supporters)
https://www.thecocktaildb.com/api/json/v1/1/filter.php?i=Dry_Vermouth,Gin,Anis

Filter by alcoholic
https://www.thecocktaildb.com/api/json/v1/1/filter.php?a=
Alcoholic, Non_Alocoholic, Optional_alcohol

Filter by Category
https://www.thecocktaildb.com/api/json/v1/1/filter.php?c=
Ordinary Drink, Cocktail, Milk / Float / Shake, Other/Unknown, Cocoa, Shot, Coffe/Tea, Homemade Liqueur, Punch / Party Drink, Beer, Soft Drink / Soda

Filter by Glass
https://www.thecocktaildb.com/api/json/v1/1/filter.php?g=Cocktail_glass
https://www.thecocktaildb.com/api/json/v1/1/filter.php?g=Champagne_flute
-->
<h3>Filter</h3>
<p>Separate ingredients with commas</p>
<select name="filter type" id="filterType"><option value="i">Multi/Ingredient</option></select>

<select name="alcoholicFilter" id="alcoholicFilter">
<option value="filter.php?a=Alcoholic">Alcoholic</option>
<option value="filter.php?a=Non_Alcoholic">Non-Alcoholic</option>
</select>

<input type="text" placeholder="Enter your search keywords" id="myFilter" />
<button onclick="DoFilter()">Search</button>

<hr>

<!--
List will basically load up some results determined by the API
Gonna shove some other stuff in here as well.
I'll have to explore the categories a bit more to set up proper filter searches
-->
<h3>List</h3>
<select name="list type" id="listType">
<option value="random.php">1 Random</option>
<option value="randomselection.php">10 Random</option>
<option value="popular.php">Popular</option>
<option value="latest.php">Latest</option>
<option value="list.php?c=list">Categories</option>
<option value="list.php?g=list">Glasses</option>
<option value="list.php?i=list">Ingredients</option>
<option value="list.php?a=list">Alcoholic Filters</option>
</select>
<button onclick="DoList()">List</button>

<hr>

<!--
ID Lookup does exactly what it says; the API has IDs for each cocktail & ingredient
Note that they're searched for with different prefixes (i vs iid)
-->
<h3>ID Lookup</h3>
<select name="lookup type" id="lookupType">
<option value="i">Drink ID</option>
<option value="iid">Ingredient ID</option>
</select>
<input type="text" placeholder="Enter your search keywords" id="myLookup" />
<button onclick="DoSearch()">Search</button>

<hr>

<!--
Search results get inserted here
-->
<button onclick="ClearResults()">Clear Results</button>
<div id="searchResponse"></div>

<hr>

<!--
Login & Registration is here
Might be wise to incorporate some sort of static siderbar\navbar for this.
-->
<h3>Register</h3>
it ain't done
<h3>Login</h3>
<div class="log-form">
	<input type="text" id="usr" placeholder="username" />
	<input type="password" id="psw" placeholder="password" />
	<button onclick="SendLoginRequest()">Login</button>
</div>

<br>

<div id="textResponse"> awaiting response </div>

</body>
</html>
