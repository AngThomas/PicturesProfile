Simple project based on Symfony 5.4 allowing to register user with pictures upload.

/api/users/register POST
allows to register user 

/api/users/login POST 
allows to login registered user, gives jwt in response

/api/users/me GET
allows to retrieve user details (need to have jwt in Authorization header)
