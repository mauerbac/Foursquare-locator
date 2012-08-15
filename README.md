#Foursquare Locator 

This app uses [Foursquare](https://developer.foursquare.com/) to locate your users and then plot their locations on Google Maps.We are using foursquare to retrieve user's last checkin. Then we can retrieve the longitude and latitude. This app then plots these coordinates on google maps. In this app, I have customized the map by changing the marker image as well as many design aspects. When you click on the marker it shows the users, location, last check in and their twitter handle (as a name link). View Example [Here](http://www.mattsauerbach.com/four/main2.php) 


## Usage 

Company members call the conference line or call in via the web browser. 

![Example of it
working](https://raw.github.com/mauerbac/Foursquare-locator/master/screenshot.png)


## Installation

Step-by-step on how to deploy, configure and develop this app.

### Create Credentials

1) Create a Google Maps Developer Key from your [Google Oauth Dashboard](https://code.google.com/apis/console).

2) Create a Foursquare Developer Account (or login to your account). Create an App client key and client secret. 

### Setup MySQL Database

1) Create new MySQL Database

2) Use table.sql for setup 


###Configuration 

1) Reg.php (Allows users to register for the app. Assigns them an auth token. Users only need to do once). Add your foursquare credentials and database info. 

2) Main.php Add your foursquare credentials and database info. 

3) Main.php Add Google API Developer Key 
<pre>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=xxxxxAPI KEY HERExxxxx&sensor=false">
</pre>

4) Main.php Configure any style settings for the google map (lines 88-110).


