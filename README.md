Hello, Frank! Thank you for considering joining the Lawline team. Below is a coding exercise that will allow you
to highlight your skills.

You have up to 48 hours to submit, however, we respect your time and expect this to only take a few hours. Please make
commits reguarly so we can track your progress.

### Getting Started

1. Fork this repository on Github
2. Update the first line of this README with your name (so that it reads "Hello, YOUR NAME!"). Commit this change. This
will serve as a starting timestamp
3. Complete the exercise below
4. Commit progress regularly
5. When you're done, commit all your code except any dependencies
6. Email Oi [oiwomark@furthered.com](mailto:oiwomark@furthered.com) with any questions/issues

### Requirements

- You have up to 48 hours to submit
- Create a simple RESTful API written in Laravel (latest version)
    - All responses should be JSON
    - All requests should be JSON
- Simple UI Interface implemented in a JS Framework (plus if Vue)

## The Exercise

The exercise consists of **users**, **subscriptions** and **products**. A user will have the ability to add & remove
products within their account. A user must have an active subscription to add a product to their account.

#### Users

Each user must have, but is not limited to:

- ID
- First Name
- Last Name
- Email (unique)

**Please note:**

- These users are the only users that are able to make requests via the API.
- User creation/maintenance is not done through the API (see Database section below).
- Users can own many products

#### Products

Each product must have, but is not limited to:

- ID
- Name
- Description
- Price
- Image

#### Database

- MySQL
- All tables in the database must be created programatically
- The user table should be seeded with at least five users

#### Authentication

You must implement an authentication system so that the API knows which of the users is making the request. All requests should ensure that an authorized user is making the request. In the event of an unauthorized user, an error should be thrown.

#### Requests

The following requests should be implemented:

- Add product
    - All fields required except ID and image
- Update product
    - All fields required except image
- Delete product
- Get product
- Upload product image
- Get list of all products
- Attach product to requesting user
- Remove product from requesting user
- List products attached to requesting user

### UI

Create a simple ui interface, written in your favorite JS Framework (Preferred: VueJS) for a user to

- View all avaliable products

**Bonus*
- Authenticate
- Add/Remove products
- Writing tests (both backend and frontend) to backup your code is a huge plus

## Completion

When you are finished, you will push up the application to a personal git repo. Then please notify Oi via email
[oiwomark@furthered.com](mailto:oiwomark@furthered.com?subject=Lawline%20Code%20Challenge) with the subject line Lawline Coding Excerise. Please include:

- The link to the github repo
- Instructions on how to setup the local site
- Instructions on how to create and seed database tables
- Instructions on how authentication works
- Instructions on how to compile assets (if any)
- Anything else you think is worth mentioning to run the application

Email us for any questions regarding this coding excerise at oiwomark@furthered.com.

![Good Luck](http://www.reactiongifs.us/wp-content/uploads/2014/01/good_luck_morgan_freeman.gif)
