# FE21-CR11-Philip
Requirements
You love animals and think it is time to adopt one. You like all sorts of animals: small animals, large animals, you may even like reptiles and birds and may be open to adopting animals of any age. 

Let's then create an animal adoption platform to connect users (people interested in adopting pets) and animals (pets interested in being adopted). 

All users must introduce their first_name and last_name, email, phone_number, address, picture and password in order to register on the platform.

All animals must have a name, a photo and live at a specific location(a single line like “Praterstrasse 23” is enough). They also have a description, size, age, hobbies and belong to a breed.

For the regular points of this CodeReview, you need to create a structure using PHP and MySQL (apart from HTML, CSS, JS, etc) that will display the relevant data of the animals.

Display all animals on a single web page (home.php). Make sure a nice GUI is presented here(backenders exempt)
- Display all senior animals. Here you can decide whether to create a filter on the home page or create a new page senior.php
- Create a show more/show details button that will lead to a new page with only the information from that specific record/animal.
- Create a registration and login system.
- Create separate sessions for normal users and administrators.
Normal Users:
*They will be able to ONLY see(read) and access all data. No action buttons (create, edit or delete) should be available.

Admin:
*Only the admin is able to create, update and delete data about animals (not users) within the admin panel, therefore an Admin Panel/Dashboard  should be created.

Bonus points
- Pet Adoption
In order to accomplish this task, a new table pet_adoption will need to be created. This table should hold the userId and the petId (as foreign keys) plus other information that you may think is relevant i.e: adoption_date. 

Each Pet information/card should have a button "Take me home" that when clicked, will "adopt" the pet. When it does, a new record should be created in the table pet_adoption.

Hint: if you use the POST method to create the adoption, you will need a form. Get method won't need it. You can expand on it creating a status for the pet and it only shows to be adopted according to its status. Not required though.

