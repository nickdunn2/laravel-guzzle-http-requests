# Zillow HTTP Requests

This small project uses Laravel (with [Guzzle 6](http://docs.guzzlephp.org/en/latest/overview.html)) [Zillow's GetDeepSearchResults API](http://www.zillow.com/howto/api/GetDeepSearchResults.htm) to add info for houses to a database that could then be used on a website. Follow [Zillow's API instructions](http://docs.guzzlephp.org/en/latest/overview.html) to make sure you're set up with an account and Zillow Web Services ID (zws-id) before getting started.

Most of the logic is in HouseController and ClientController, using simple GET routes in the routes.php file. If you have a spreadsheet or csv of houses instead of a database, just rename it to houses.csv and import it into a database like SQLite. Then add that houses.csv file in the public directory. The houses will need to have an id, street address and zip code. If it's a city/state combo instead of zip code, some minor adjustments would need to be made in the ClientController's obtainDetails() method to send the urlencoded city/state combo in the GET request instead of the zip.

Once you sign up and get a zws-id, you'll need to provide it in the first line of the obtainDetails() method in the ClientController.

Add empty fields in the database for zestimate, yearBuilt, bathrooms and bedrooms (as well as updated_at if it's not in there already). Any other fields returned in the API call could also be added easily.

To perform the requests, start up the local Laravel server using `php artisan serve` and then hit either...

  * localhost:8000/houses/adddetails/{house_id} to add a single property's zestimate, yearBuilt, bathrooms and bedrooms
  * localhost:8000/houses/addalldetails to add the data for all houses in the database.
  * (Addresses that have errors or unknown data will be filled in with "N/A".)

**Important:** Zillow enforces a 1000-call daily limit for its APIs, and make sure to provide a Web-based user interface directly to consumers. Read their [API documentation](http://www.zillow.com/howto/api/APIOverview.htm) and [Terms of Use](http://www.zillow.com/howto/api/APITerms.htm) for more information.
