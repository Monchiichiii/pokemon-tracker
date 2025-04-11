# Pokémon Tracker Website

This project is a web-based Pokémon Trading Card Game collection tracker. It allows users to browse, search, and manage their personal collection of Pokémon cards using live data from the Pokémon TCG API.

We used ChatGPT in order to get the skeletal structure using the MVC pattern which we later filled in with the appropriate code for each file. This was the structure it gave us:

```bash
pokemontracker_website/
├── app/                        # MVC application core
│   ├── controllers/            # Handles requests and logic
│   │   ├── CardController.php
│   │   └── CollectionController.php
│   ├── models/                 # Database interaction logic
│   │   ├── Card.php
│   │   ├── Collection.php
│   │   └── User.php
│   └── views/                  # UI templates for each page
│       ├── browse.php
│       ├── home.php
│       └── myCollection.php
├── assets/                     # Designs
│   └── css/
│       └── style.css           # Main stylesheet
├── config/                     # Configuration files
│   ├── config.php              # Database credentials
│   └── google_config.php       # Google OAuth setup
├── includes/                   # Shared page elements
│   ├── header.php              
│   └── footer.php             
├── index.php                   # Main entry and routing
├── oauth.php                   # Handles Google OAuth login
├── logout.php                  # Ends session and logs user out
```
- We also had an error where GET[set_id]: none was showing up on our Collection page. Put this error into ChatGPT in order to find out where it could be coming from and it was able to tell us it was either on myCollection.php or CollectionController.php. We were able to then pinpoint that the error came from myCollection.php where a block of code forgot to get removed while we were debugging.



- Front-end framework: HTML with PHP files
- Back-end framework: PHP with MySQLi
- Database: MySQL
- External API: Pokemon TCG API
- Authentication Method: OAuth

# Website Run Time
Due to the website being locally hosted it would just require the PC to be on so it can be live. Therefore the website will be live from 10am for users to interact until midnight everyday. Enjoy tracking your collection!! :)
http://pokemontracker.org/pokemontracker_website/


